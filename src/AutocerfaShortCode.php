<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 *
 */
class AutocerfaShortCode
{

    public static function init()
    {
        $self = new self;
        add_shortcode('autocerfa-car-lists', array($self, 'autocerfaCarLists'));
        add_shortcode('autocerfa-slider', array($self, 'autocerfaSlider'));
        add_shortcode('autocerfa-single-car', array($self, 'autocerfa_single_car'));
        add_shortcode('autocerfa-search-box', array($self, 'autocerfa_search_box'));
        add_shortcode('autocerfa-short-listed-cars', array($self, 'autocerfaShortListedCars'));
    }


    public function autocerfa_search_box()
    {
        if (empty(get_option('opcodespace_subscription'))) {
            return false;
        }

        $AutocerfaStockPost = new AutocerfaStockPost();

        $models = array_column($AutocerfaStockPost->getUniqueValue('model'), 'meta_value');
        $marks  = $AutocerfaStockPost->getMake();

        return get_view(AUTOCERFA_VIEW_PATH."front/autocerfa-search-box.php", compact('models', 'marks'));
    }

    public function autocerfa_single_car()
    {
        $lead_id = (int) $_GET['lead_id'];
        if (empty($lead_id)) {
            return __('<h3>Lead reference is required.</h3>', 'autocerfa-connector');
        }
        // $AutocerfaStock = new AutocerfaStock();
        $AutocerfaStockPost = new AutocerfaStockPost();
        $lead               = $AutocerfaStockPost->getByLeadId($lead_id);

        if (empty($lead)) {
            return __('<h3>Cannot find record.</h3>', 'autocerfa-connector');
        }

        return get_view(AUTOCERFA_VIEW_PATH."front/single-car.php", compact('lead'));
    }

    public function autocerfaShortListedCars($attr)
    {

        if (empty(get_option('opcodespace_subscription'))) {
            return false;
        }

        $short_listed_id = shortcode_atts(['id' => null], $attr)['id'];

        $autocerfa_shortcodes_settings = get_option('autocerfa_shortcodes_settings');

        $option     = isset($autocerfa_shortcodes_settings['short_listed_cars']['option']) ? $autocerfa_shortcodes_settings['short_listed_cars']['option'] : false;
        $latest_car = isset($autocerfa_shortcodes_settings['short_listed_cars']['latest_car']) ? $autocerfa_shortcodes_settings['short_listed_cars']['latest_car'] : 5;

        if ($option === 'custom') {
            $cars    = [];
            $car_ids = $autocerfa_shortcodes_settings['short_listed_cars']['custom_cars'];
            if (!empty($car_ids)) {
                foreach ($car_ids as $car_id) {
                    $car = get_post($car_id);
                    if (!empty($car)) {
                        $cars[] = $car;
                    }
                }
            }
        } else {
            $AutocerfaStockPost = new AutocerfaStockPost();
            $params                   = [];
            $params['exclude_hidden'] = true;
            $AutocerfaStockPost->params = $params;
            $AutocerfaStockPost->query($latest_car, 1);
            $cars = $AutocerfaStockPost->cars;
        }


        return get_view(AUTOCERFA_VIEW_PATH."front/short-listed-cars.php", compact('cars', 'short_listed_id'));
    }

    public function autocerfaSlider()
    {

        if (empty(get_option('opcodespace_subscription'))) {
            return false;
        }

        $autocerfa_shortcodes_settings = get_option('autocerfa_shortcodes_settings');

        $option     = isset($autocerfa_shortcodes_settings['slider_cars']['option']) ? $autocerfa_shortcodes_settings['slider_cars']['option'] : false;
        $latest_car = isset($autocerfa_shortcodes_settings['slider_cars']['latest_car']) ? $autocerfa_shortcodes_settings['slider_cars']['latest_car'] : 5;

        if ($option === 'custom') {
            $cars    = [];
            $car_ids = $autocerfa_shortcodes_settings['slider_cars']['custom_cars'];
            if (!empty($car_ids)) {
                foreach ($car_ids as $car_id) {
                    $car = get_post($car_id);
                    if (!empty($car)) {
                        $cars[] = $car;
                    }
                }
            }
        } else {
            $AutocerfaStockPost = new AutocerfaStockPost();
            $params                   = [];
            $params['exclude_hidden'] = true;
            $AutocerfaStockPost->params = $params;
            $AutocerfaStockPost->query($latest_car, 1);
            $cars = $AutocerfaStockPost->cars;
        }

        return get_view(AUTOCERFA_VIEW_PATH."front/slider.php", compact('cars'));
    }

    public function autocerfaCarLists()
    {

        $AutocerfaStockPost = new AutocerfaStockPost();
        $view_style         = get_option('autocerfa_view_style');
        $view_style         = empty($view_style) ? 'list' : $view_style;

        $template                 = $view_style === 'grid' ? 'front/cars/car-grids.php' : "front/cars/car-lists.php";
        $models                   = [];
        $marks                    = [];
        $params                   = [];
        $params['exclude_hidden'] = true;
        if (AutocerfaMisc::FilterOptionVisibility()) {
            $template = $view_style === 'grid' ? "front/cars/car-grids-with-sidebar.php" : "front/cars/car-lists-with-sidebar.php";
//            $models = array_column($AutocerfaStockPost->getUniqueValue('model'), 'meta_value');
            $marks  = $AutocerfaStockPost->getMake();
        }


        $params['min_price']        = isset($_GET['min-price']) ? (int) $_GET['min-price'] : null;
        $params['max_price']        = isset($_GET['max-price']) ? (int) $_GET['max-price'] : null;
        $params['model']            = isset($_GET['model']) ? sanitize_text_field($_GET['model']) : null;
        $params['marque']           = isset($_GET['mark']) ? sanitize_text_field($_GET['mark']) : null;
        $params['energy']           = isset($_GET['fuel']) ? sanitize_text_field($_GET['fuel']) : null;
        $params['gear_box']         = isset($_GET['transmission']) ? sanitize_text_field($_GET['transmission']) : null;
        $AutocerfaStockPost->params = $params;


        $rowPerPage  = empty(get_option('car_per_page')) ? 20 : (int) get_option('car_per_page');
        $currentPage = isset($_GET['pg']) ? (int) $_GET['pg'] : 1;
        $url_param   = $_GET;
        unset($url_param['pg']);
        $urlPattern = "?pg=(:num)&".http_build_query($url_param);
        $offset     = ($currentPage - 1) * $rowPerPage;
        $AutocerfaStockPost->query($rowPerPage, $currentPage);
        $leads      = $AutocerfaStockPost->cars;
        $totalStock = $AutocerfaStockPost->total;

        return get_view(AUTOCERFA_VIEW_PATH.$template,
            compact('leads', 'totalStock', 'rowPerPage', 'urlPattern', 'currentPage', 'offset', 'models', 'marks'));
        }
}
