<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 *
 */
class AutocerfaAjaxAction
{

    static public function init()
    {
        $self = new self();

        add_action("wp_ajax_get_cars_from_autocerfa", array($self, 'get_cars_from_autocerfa'));
        add_action("wp_ajax_autocerfa_sync_now", array($self, 'autocerfa_sync_now'));
        add_action("wp_ajax_autocerfa_bg_process_check", array($self, 'autocerfa_bg_process_check'));
        add_action("wp_ajax_save_multi_diff", array($self, 'save_multi_diff'));
        add_action("wp_ajax_autocerfa_creating_page", array($self, 'creating_page'));
        add_action("wp_ajax_save_autocerfa_single_page", array($self, 'save_autocerfa_single_page'));
        add_action("wp_ajax_save_autocerfa_car_list_page", array($self, 'save_autocerfa_car_list_page'));
        // From v2.0.0
        add_action("wp_ajax_autocerfa_get_car_details", array($self, 'autocerfa_get_car_details'));
        add_action("wp_ajax_autocerfa_save_short_listed_cars", array($self, 'autocerfa_save_short_listed_cars'));
        add_action("wp_ajax_save_short_listed_car_settings", array($self, 'save_short_listed_car_settings'));
        add_action("wp_ajax_autocerfa_save_slider_settings", array($self, 'autocerfa_save_slider_settings'));
        add_action("wp_ajax_autocerfa_save_slider_cars", array($self, 'autocerfa_save_slider_cars'));
        add_action("wp_ajax_autocerfa_save_search_box", array($self, 'autocerfa_save_search_box'));
        add_action("wp_ajax_autocerfa_save_license_key", array($self, 'autocerfa_save_license_key'));
        add_action("wp_ajax_autocerfa_save_general_settings", array($self, 'autocerfa_save_general_settings'));
        add_action("wp_ajax_autocerfa_sold_car_by_registration", array($self, 'autocerfa_sold_car_by_registration'));
        add_action("wp_ajax_autocerfa_save_sold_car", array($self, 'autocerfa_save_sold_car'));
        add_action("wp_ajax_autocerfa_delete_car", array($self, 'autocerfa_delete_car'));
        add_action("wp_ajax_autocerfa_set_badge", array($self, 'autocerfa_set_badge'));
        add_action("wp_ajax_autocerfa_remove_badge", array($self, 'autocerfa_remove_badge'));
        add_action("wp_ajax_autocerfa_badge_save", array($self, 'autocerfa_badge_save'));
        add_action("wp_ajax_autocerfa_get_badge", array($self, 'autocerfa_get_badge'));
        add_action("wp_ajax_autocerfa_delete_badge", array($self, 'autocerfa_delete_badge'));
        add_action("wp_ajax_autocerfa_get_model_by_make", array($self, 'autocerfa_get_model_by_make'));
        add_action("admin_post_update_min_max_price", array($self, 'update_min_max_price'));
    }

    public function security($field, $nonce)
    {
        if (!isset($_REQUEST[$field]) || !wp_verify_nonce($_REQUEST[$field], $nonce)) {
            wp_send_json_error(['message' => __("You are not allowed to submit data.", 'autocerfa-connector')]);
        }
    }

    public function update_min_max_price()
    {
        $this->security('_wpnonce', 'update_min_max_price');

        (new AutocerfaStockProcess())->saveMinMaxPrice();

        wp_redirect(admin_url('/admin.php?page=autocerfa-settings'));
    }

    /*
     * Front search box
     */
    public function autocerfa_get_model_by_make()
    {
        $make = sanitize_text_field($_POST['make']);

        $models = (new AutocerfaStockPost())->getModelByMake($make);

        wp_send_json_success(['models' => $models, '_model_text' => __('Model', 'autocerfa-connector')]);
    }

    public function autocerfa_remove_badge()
    {
        $this->security('_wpnonce', 'autocerfa-dashboard');
        $id = (int) $_POST['id'];

        delete_post_meta($id, 'badge_id');

        wp_send_json_success();
    }

    public function autocerfa_get_badge()
    {
        $badge_id = (int) $_POST['badge_id'];

        $badge = (new AutocerfaBadge())->get_by_id($badge_id);

        wp_send_json_success(['badge' => $badge]);
    }

    public function autocerfa_delete_badge()
    {
        $this->security('_wpnonce', 'autocerfa-general');
        $badge_id = (int) $_POST['badge_id'];

        (new AutocerfaBadge())->delete(['badge_id' => $badge_id]);

        wp_send_json_success(['message' => __('Successfully deleted', 'autocerfa-connector')]);
    }

    public function autocerfa_set_badge()
    {
        $this->security('_wpnonce', 'autocerfa-dashboard');
        $id       = (int) $_POST['id'];
        $badge_id = (int) $_POST['badge_id'];

        AutocerfaMisc::preventFreeUser();

        update_post_meta($id, 'badge_id', $badge_id);

        wp_send_json_success();
    }

    public function autocerfa_badge_save()
    {
        $this->security('_wpnonce', 'autocerfa-badge-modal');

        AutocerfaMisc::preventFreeUser();

        $badge_id = (int) $_POST['badge_id'];
        $id       = (int) $_POST['id'];

        $new_badge_id = (new AutocerfaBadge())->updateOrInsert([
            'label'            => sanitize_text_field($_POST['autocerfa_badge_text']),
            'background_color' => sanitize_hex_color($_POST['autocerfa_badge_bg_color']),
            'text_color'       => sanitize_hex_color($_POST['autocerfa_badge_txt_color']),
        ], [
            'badge_id' => $badge_id
        ]);

        if (empty($new_badge_id)) {
            $new_badge_id = $badge_id;
        }

        if ($id > 0) {
            update_post_meta($id, 'badge_id', $new_badge_id);
        }

        wp_send_json_success(['message' => 'Successfully saved']);

    }

    public function autocerfa_delete_car()
    {
        $this->security('_wpnonce', 'autocerfa-dashboard');
        if (!current_user_can('administrator')) {
            wp_send_json_error(['message' => __("You are not allowed to submit data.", 'autocerfa-connector')]);
        }
        $id = (int) $_POST['id'];
        wp_delete_post($id);
        wp_send_json_success(['message' => __("Successfully deleted.", 'autocerfa-connector')]);
    }

    public function autocerfa_sold_car_by_registration()
    {
        $registration = sanitize_text_field($_POST['registration']);

        if (strlen($registration) < 2) {
            wp_send_json_error(['message' => 'at least two character requires.']);
        }

        $result = (new AutocerfaStockProcess())->sold_leads_by_registration($registration);

        if (is_wp_error($result)) {
            wp_send_json_error(['message' => $result->get_error_message()]);
        }

        wp_send_json_success(['leads' => $result->data->leads]);
    }

    public function autocerfa_save_sold_car()
    {
        $this->security('_wpnonce', 'sold_lead');

        AutocerfaMisc::preventFreeUser();

        $sold_lead_id = (int) $_POST['sold_lead_id'];

        if (empty($sold_lead_id)) {
            wp_send_json_error(['message' => __('Please select sold car.', 'autocerfa-connector')]);
        }

        $result = (new AutocerfaStockProcess())->stock_car_by_sold_car($sold_lead_id);

        if (is_wp_error($result)) {
            wp_send_json_error(['message' => $result->get_error_message()]);
        }
        $lead           = $result->data->lead;
        $lead->type     = 'sold';
        $lead->badge_id = 1;

        global $autocerfa_bg_process;
        $autocerfa_bg_process->push_to_queue($lead);
        $autocerfa_bg_process->save()->dispatch();

        wp_send_json_success();
    }

    public function autocerfa_save_general_settings()
    {
        $this->security('_wpnonce', 'autocerfa-general');

        update_option('car_per_page', (int) $_POST['car_per_page']);
        update_option('single_page_slug', sanitize_title($_POST['single_page_slug']));
        update_option('autocerfa_theme_color_1', sanitize_hex_color($_POST['autocerfa_theme_color_1']));
        update_option('autocerfa_theme_color_2', sanitize_hex_color($_POST['autocerfa_theme_color_2']));
        update_option('autocerfa_theme_color_3', sanitize_hex_color($_POST['autocerfa_theme_color_3']));
        update_option('daily_sync', sanitize_text_field($_POST['daily_sync']));
        update_option('autocerfa_view_style', sanitize_text_field($_POST['autocerfa_view_style']));
        update_option('filter_option', isset($_POST['filter_option']) && $_POST['filter_option'] === 'yes' ? 'yes' : '');
        update_option('autocerfa_debug', isset($_POST['autocerfa_debug']) && $_POST['autocerfa_debug'] === 'yes' ? 'yes' : '');
        update_option('cropping_image_as_aspect_ration', isset($_POST['cropping_image_as_aspect_ration']) && $_POST['cropping_image_as_aspect_ration'] === 'yes' ? 'yes' : '');

        if(!isset($_POST['autocerfa_debug'])){
            AutocerfaLogger::delete();
        }

        update_option('autocerfa_requires_flush', true);

        wp_send_json_success();
    }

    public function autocerfa_save_license_key()
    {
        $this->security('_wpnonce', 'autocerfa_license_key');

        $token = sanitize_text_field($_POST['ops_token']);

        if (empty($token)) {
            wp_send_json_error(['message' => __('License key is empty.', 'autocerfa-connector')]);
        }

        update_option('ops_token', $token);

        $AutocerfaOpcodespace = new AutocerfaOpcodespace($token);
        $result               = $AutocerfaOpcodespace->setSubscriptionStatus();

        if (!$result['success']) {
            wp_send_json_error(['message' => $result['message']]);
        }

        wp_send_json_success(['message' => $result['message']]);
    }

    public function autocerfa_save_search_box()
    {
        $this->security('autocerfa_shortcodes', 'autocerfa_shortcodes_nonce');

        $autocerfa_shortcodes_settings               = get_option('autocerfa_shortcodes_settings');
        $autocerfa_shortcodes_settings['search_box'] = (array) $_POST['search_box'];
        update_option('autocerfa_shortcodes_settings', $autocerfa_shortcodes_settings);

        wp_send_json_success();
    }

    public function autocerfa_save_slider_settings()
    {
        $this->security('autocerfa_shortcodes', 'autocerfa_shortcodes_nonce');

        $option     = sanitize_text_field($_POST['option']);
        $latest_car = (int) $_POST['latest_car'];

        $autocerfa_shortcodes_settings                              = get_option('autocerfa_shortcodes_settings');
        $autocerfa_shortcodes_settings['slider_cars']['option']     = $option;
        $autocerfa_shortcodes_settings['slider_cars']['latest_car'] = $latest_car;
        update_option('autocerfa_shortcodes_settings', $autocerfa_shortcodes_settings);
        wp_send_json_success();
    }

    public function save_short_listed_car_settings()
    {
        $this->security('autocerfa_shortcodes', 'autocerfa_shortcodes_nonce');
        $option     = sanitize_text_field($_POST['option']);
        $latest_car = (int) $_POST['latest_car'];

        $autocerfa_shortcodes_settings                                    = get_option('autocerfa_shortcodes_settings');
        $autocerfa_shortcodes_settings['short_listed_cars']['option']     = $option;
        $autocerfa_shortcodes_settings['short_listed_cars']['latest_car'] = $latest_car;
        update_option('autocerfa_shortcodes_settings', $autocerfa_shortcodes_settings);
        wp_send_json_success();
    }

    public function autocerfa_save_slider_cars()
    {
        $this->security('autocerfa_shortcodes', 'autocerfa_shortcodes_nonce');
        $cars = $_POST['cars'];

        $autocerfa_shortcodes_settings                               = get_option('autocerfa_shortcodes_settings');
        $autocerfa_shortcodes_settings['slider_cars']['custom_cars'] = $cars;
        update_option('autocerfa_shortcodes_settings', $autocerfa_shortcodes_settings);
        wp_send_json_success();
    }

    public function autocerfa_save_short_listed_cars()
    {
        $this->security('autocerfa_shortcodes', 'autocerfa_shortcodes_nonce');
        $cars = $_POST['cars'];

        $autocerfa_shortcodes_settings                                     = get_option('autocerfa_shortcodes_settings');
        $autocerfa_shortcodes_settings['short_listed_cars']['custom_cars'] = $cars;
        update_option('autocerfa_shortcodes_settings', $autocerfa_shortcodes_settings);
        wp_send_json_success();
    }

    public function autocerfa_get_car_details()
    {
        $this->security('autocerfa_shortcodes', 'autocerfa_shortcodes_nonce');

        $car_id = (int) $_POST['id'];
        $car    = get_post($car_id);

        if (empty($car)) {
            wp_send_json_error(['message' => __('Cannot find this record', 'autocerfa-connector')]);
        }
        //        $autocerfa_shortcodes_settings = get_option('autocerfa_shortcodes_settings');
        //
        //        $cars = isset($autocerfa_shortcodes_settings['short_listed_cars']['custom_cars']) ? $autocerfa_shortcodes_settings['short_listed_cars']['custom_cars'] : [];
        //
        //        $cars[] = $car_id;
        //
        //        $autocerfa_shortcodes_settings['short_listed_cars']['custom_cars'] = $cars;
        //        update_option('autocerfa_shortcodes_settings', $autocerfa_shortcodes_settings);
        $data = [
            'marque'   => $car->marque,
            'model'    => $car->model,
            'immat'    => $car->immat,
            'milage'   => $car->milage,
            'reg_date' => $car->reg_date,
            'price'    => $car->price,
            'id'       => $car->ID,
        ];

        wp_send_json_success(['car' => $data]);
    }

    public function save_autocerfa_single_page()
    {
        $page_id = (int) $_POST['id'];
        update_option('autocerfa_single_page', $page_id);

        wp_send_json_success();
    }

    public function save_autocerfa_car_list_page()
    {
        $page_id = (int) $_POST['id'];
        update_option('autocerfa_car_list_page', $page_id);

        wp_send_json_success();
    }

    public function creating_page()
    {
        $this->security('_wpnonce', 'autocerfa-get-started');
        $name   = sanitize_title($_POST['name']);
        $result = wp_insert_post(
            [
                'post_title'   => $name,
                'post_status'  => 'publish',
                'post_content' => '[autocerfa-car-lists]',
                'post_type'    => 'page'
            ]
        );

        if (is_wp_error($result)) {
            wp_send_json_error(['message' => $result->get_error_message()]);
        }

        update_option('autocerfa_car_list_page', $result);
        $post = get_post($result);
        update_option('single_page_slug', $post->post_title);

        global $wp_rewrite;
        $wp_rewrite->flush_rules(true);

        wp_send_json_success(['link' => get_the_permalink($result)]);
    }

    public function autocerfa_bg_process_check()
    {
        $response['reload']               = (bool) get_option('autocerfa_reload');
        $response['autocerfa_processing'] = (bool) get_option('autocerfa_processing');

        update_option('autocerfa_reload', false);

        wp_send_json_success(['status' => $response]);
    }

    public function get_cars_from_autocerfa()
    {
        $this->security('_wpnonce', 'autocerfa-dashboard');

        $AutocerfaStockProcess = new AutocerfaStockProcess;
        $response              = $AutocerfaStockProcess->run(true);

        if (!$response['success']) {
            wp_send_json_error(['message' => $response['message']]);
        }

        wp_send_json_success();
    }

    public function autocerfa_sync_now()
    {
        $this->security('_wpnonce', 'autocerfa-get-started');
        if (!empty($_POST['daily_sync'])) {
            update_option('daily_sync', sanitize_text_field($_POST['daily_sync']));
        }

        if (!empty($_POST['sending_email'])) {
            update_option('sending_email', (bool) $_POST['sending_email']);
        }

        $AutocerfaStockProcess = new AutocerfaStockProcess;
        $response              = $AutocerfaStockProcess->run();

        if (!$response['success']) {
            wp_send_json_error(['message' => $response['message']]);
        }

        wp_send_json_success();
    }


    public function save_multi_diff()
    {
        //Todo: adding nonce
        $hidden = (int) $_POST['hidden'];
        $id     = (int) $_POST['id'];

        update_post_meta($id, 'hidden', $hidden);
    }
}
