<?php
if (!defined('ABSPATH')) exit;

class AutocerfaStockProcess
{
    protected $leads;
    protected $AutocerfaFiles;
    protected $AutocerfaStock;
    protected $AutocerfaStockPost;

    public function __construct()
    {
        $this->AutocerfaFiles = new AutocerfaFiles();
        $this->AutocerfaStock = new AutocerfaStock();
        $this->AutocerfaStockPost = new AutocerfaStockPost();
    }

    public function downloadImage()
    {
        global $AutocerfaImageDownloadAsync;
        $leads = (new AutocerfaStockPost())->ImageReadyForDownload();

        if(empty($leads)){
            update_option('autocerfa_processing', false);
            return;
        }

        foreach ($leads as $lead) {
            if (!empty($lead->raw_images)) {
                if(!empty($lead->images)){
                    AutocerfaLogger::log('Old Image about to delete #' . $lead->lead_id);
                    $this->removeImages($lead->images);
                }

                update_post_meta($lead->ID, 'images', []);

                $images = [];

                foreach ($lead->raw_images as $key => $photo) {
                    // $AutocerfaImageDownloadAsync->data(['post_id' => $lead->ID, 'image' => $photo])->dispatch();

                    $post_id = $lead->ID;
                    $image = $photo;
                    AutocerfaLogger::log('Image download started for post #' . $post_id . ' Image: ' . $image);
                    $images[] = (new AutocerfaFiles)->download($image)->resize($key === 0);
                }
                update_post_meta($post_id, 'images', $images);
            }

            delete_post_meta($lead->ID, 'raw_images');

        }
    }


    protected function pullFromAutocefa($action = 'stock', $method = 'GET', $data = [])
    {
        $autocerfa_token = sanitize_text_field(get_option('autocerfa_token'));

        if (empty($autocerfa_token)) {
            AutocerfaLogger::log('Autocerfa authorize token is missing.');
            return new WP_Error('autocerfa_token',
                __('Autocerfa token is missing. Please authorize your autocerfa account.', 'autocerfa-connector'));
        }

        $response = wp_remote_request('https://www.autocerfa.com/all-post/?action=' . $action,
            array(
                'method' => $method,
                'headers' => ['Authorization' => $autocerfa_token],
                'timeout' => 180,
                'sslverify' => apply_filters('https_local_ssl_verify', false),
                'body' => $data
            )
        );


        if (is_wp_error($response)) {
            AutocerfaLogger::log($response->get_error_message());
            return $response;
        }

        $body = wp_remote_retrieve_body($response);
        $code = wp_remote_retrieve_response_code($response);
        $message = wp_remote_retrieve_response_message($response);

        AutocerfaLogger::log('Stock pulling from Autocerfa.');

        if ((int)$code === 401) {
            $data = json_decode($body);

            return new WP_Error('autocerfa-response', $data->data->message);
        }

        if ((int)$code !== 200) {
            return new WP_Error('autocerfa-response', $message);
        }
        AutocerfaLogger::log('Successfully stock pulled from Autocerfa.');

        return json_decode($body);
    }

    public function sold_leads_by_registration($registration)
    {
        return $this->pullFromAutocefa('sold_car_by_registration', 'POST', ['registration' => $registration]);
    }

    public function stock_car_by_sold_car($sold_lead_id)
    {
        return $this->pullFromAutocefa('stock_car_by_sold_car', 'POST', ['sold_lead_id' => $sold_lead_id]);
    }


    protected function removeImages($images)
    {
        foreach ($images as $image) {
            if (isset($image['thumbnails'])) {
                foreach ($image['thumbnails'] as $thumbnail) {
                    if (file_exists($thumbnail['file'])) {
                        AutocerfaLogger::log('deleted ---' . $thumbnail['file']);
                        wp_delete_file($thumbnail['file']);
                    }
                }
            }

            if (isset($image['image']) && file_exists($image['image'])) {
                AutocerfaLogger::log('deleted ---' . $image['image']);
                wp_delete_file($image['image']);
            }
        }
    }

    public function save($lead)
    {
        AutocerfaLogger::log('Saving Lead #' . $lead->lead_id);
        $old_lead = $this->AutocerfaStockPost->getByLeadId($lead->lead_id);

        $data = [
            'lead_id' => $lead->lead_id,
//            'images'             => $images,
            'title' => $lead->title,
            'description' => $lead->description,
            'lead_modified_date' => $lead->modified_date,
            'active' => 1,
            'price' => $lead->price,
            'marque' => $lead->marque,
            'immat' => $lead->immat,
            'model' => $lead->model,
            'reg_date' => $lead->reg_date,
            'gear_box' => $lead->gear_box,
            'milage' => $lead->milage,
            'energy' => $lead->energy,
            'category' => $lead->category,
            'couleur' => $lead->couleur,
            'etat' => $lead->etat,
            'con_tech' => $lead->con_tech,
            'equipments' => $lead->equipments,
            'telephone' => $lead->telephone,
            'email' => $lead->email,
            'lead_updated_date' => $lead->lead_updated_date,
            'autocerfa_lead_updated_at' => $lead->autocerfa_lead_updated_at,
            'type' => $lead->type,
            'badge_id' => $lead->badge_id,
        ];

        if ($lead->reset_and_pull_all_image || $old_lead->autocerfa_lead_updated_at !== $lead->autocerfa_lead_updated_at) {
            $data['raw_images'] = $lead->photos;
        }

        if ($lead->last) {
            update_option('autocerfa_reload', true);

            if (get_option('sending_email')) {
                $no_of_cars = get_option('autocerfa_no_of_cars');
                (new AutocerfaNotification)->backgroundProcessComplete($no_of_cars - 1);
            }
        }

        $this->AutocerfaStockPost->updateOrInsert($lead->lead_id, $data);
        AutocerfaLogger::log('Saved Lead #' . $lead->lead_id);

    }

    public function run($reset_and_pull_all_image = false)
    {
        # set all inactive
        # Update database and set active
        $response = $this->pullFromAutocefa();
        update_option('autocerfa_processing', false);

        if (is_wp_error($response)) {
            return _return(false, $response->get_error_message());
        }

        global $autocerfa_bg_process;

        $cnt = 1;

        if (empty($response->data->leads)) {
            return _return(false, __('No stock car', 'autocerfa-connector'));
        }

        update_option('autocerfa_processing', true);

        $size = count($response->data->leads);

        $is_paid = get_option('opcodespace_subscription');
        $lead_updated_date = current_time('timestamp');
        update_option('lead_updated_date', $lead_updated_date);

        foreach ($response->data->leads as $lead) {
            $lead->last = false;
            $lead->lead_updated_date = $lead_updated_date;
            $lead->reset_and_pull_all_image = $reset_and_pull_all_image;

            if ($cnt === $size) {
                $lead->last = true;
            }

            if (!$is_paid && $cnt >= 10) {
                $lead->last = true;
            }
            $autocerfa_bg_process->push_to_queue($lead);

            if (!$is_paid && $cnt >= 10) {
                break;
            }
            $cnt++;
        }
        $autocerfa_bg_process->save()->dispatch();

        update_option('autocerfa_no_of_cars', $cnt);

        return _return(true);
    }

    public function deleteOldLead()
    {

        $AutocerfaStockPost = new AutocerfaStockPost();
        $AutocerfaStockPost->params = [
            'lead_updated_date' => get_option('lead_updated_date')
        ];
        $AutocerfaStockPost->query(-1, 1);

        foreach ($AutocerfaStockPost->cars as $car) {
            if ($car->type === 'sold') {
                continue;
            }
            AutocerfaLogger::log('Deleting sold cars ---' . $car->ID);
            if (!empty($car->images)) {
                $this->removeImages($car->images);
            }
            wp_delete_post($car->ID, true);
        }
    }

    public function saveMinMaxPrice()
    {
        $price = (new AutocerfaStockPost())->getMinMaxPrice();

        update_option('autocerfa_price', $price);
    }
}