<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 *
 */
class AutocerfaHook
{
    public static function init()
    {
        $self = new self;

        add_action( 'admin_menu', array($self, 'autocerfa_menus') );

//        add_action( 'init', [$self, 'autocerfa_register_setting'] );

        add_action( 'autocerfa_daily_event', [$self, 'autocerfa_do_daily_event'] );
        add_action( 'autocerfa_twicedaily_event', [$self, 'autocerfa_do_twicedaily_event'] );

//        add_action( 'update_option_single_page_slug', [$self, 'single_page_slug_after_save'], 10, 2 );
//        add_action( 'update_option_ops_token', [$self, 'ops_token_after_save'], 10, 2 );

        // From Version 2
        add_action( 'init', [$self, 'custom_post_type'] );
        add_action( 'init', [$self, 'rewrite_rule_flush'] );
        add_action( 'admin_init', [$self, 'upgrade'] );
        add_filter('single_template', [$self, 'post_type_template'], 99, 1);
        add_filter('archive_template', [$self, 'post_type_archive_template'], 99, 1);
    }

    public function post_type_archive_template($template)
    {
        if (is_post_type_archive('autocerfa_car') && file_exists(AUTOCERFA_VIEW_PATH.'front/archive-autocerfa_car.php')) {
           return AUTOCERFA_VIEW_PATH.'front/archive-autocerfa_car.php';
        }
        return $template;
    }

    public function post_type_template($single)
    {
        global $post;
        if (($post->post_type === 'autocerfa_car') && file_exists(AUTOCERFA_VIEW_PATH.'front/single-car-template.php')) {
            return AUTOCERFA_VIEW_PATH . 'front/single-car-template.php';
        }
        return $single;
    }

    public function rewrite_rule_flush()
    {
        if(get_option('autocerfa_requires_flush')){
            flush_rewrite_rules();
        }
    }

    public static function upgrade()
    {
        if(!version_compare(get_option('autocerfa_plugin_version'), '2.3.0', '>=')){
            update_option('autocerfa_requires_flush', true);

            (new AutocerfaInstallTable())->autocerfa_badges();
            $AutocerfaBadge = new AutocerfaBadge;
            $badge = $AutocerfaBadge->getRow(['badge_id' => 1]);
            if(empty($badge)){
                $AutocerfaBadge->insert((array)AutocerfaBadge::default());
            }
            (new AutocerfaStockProcess)->run();

            update_option('autocerfa_plugin_version', AUTOCERFA_PLUGIN_VERSION);
        }
    }

    public function custom_post_type()
    {

        $slug = AutocerfaMisc::singlePageSlug();

        register_post_type( 'autocerfa_car',
            array(
                'labels' => array(
                    'name' => __( 'Autocerfa Car', 'autocerfa-connector' ),
                    'singular_name' => __( 'Autocerfa Car', 'autocerfa-connector' )
                ),
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => $slug),
                'show_in_rest' => true,
                'show_in_menu'        => false,
                'show_in_nav_menus'   => false,
                'show_in_admin_bar'   => false,
            )
        );
    }


//    public function ops_token_after_save($old_value, $new_value)
//    {
//        if ( $old_value !== $new_value ) {
//            update_option('ops_token', $new_value);
//            $AutocerfaOpcodespace = new AutocerfaOpcodespace();
//            $is_paid              = $AutocerfaOpcodespace->isPaid();
//            update_option('opcodespace_subscription', $is_paid);
//        }
//    }

//    public function single_page_slug_after_save( $old_value, $new_value)
//    {
//        // if ( $old_value !== $new_value ) {
//            global $wp_rewrite;
//            $wp_rewrite->flush_rules(true);
//        // }
//    }
    

    public static function plugin_activation()
    {
        global $wp_rewrite;
        $wp_rewrite->flush_rules(true);

        $AutocerfaBadge = new AutocerfaBadge;
        $badge = $AutocerfaBadge->getRow(['badge_id' => 1]);
        if(empty($badge)){
            $AutocerfaBadge->insert((array)AutocerfaBadge::default());
        }
    }

//    public function rewrite_rule()
//    {
//        $slug = sanitize_title_with_dashes( get_option('single_page_slug') );
//        $slug = empty($slug) ? 'car' : $slug;
//        add_rewrite_rule("^{$slug}/([^/]*)/([^/]*)/?",'index.php?lead_id=$matches[1]&lead_title=$matches[2]','top');
//    }

//    public function register_query_var($query_vars)
//    {
//        $query_vars[] = 'lead_id';
//        $query_vars[] = 'lead_title';
//        return $query_vars;
//    }

//    public function single_car(&$wp)
//    {
//        if ( array_key_exists( 'lead_id', $wp->query_vars ) &&  array_key_exists( 'lead_title', $wp->query_vars )) {
//
//            $lead_id = (int) $wp->query_vars['lead_id'];
//
//            $AutocerfaStock = new AutocerfaStock();
//            $lead = $AutocerfaStock->getRow(['lead_id' => $lead_id ]);
//
//            add_filter('document_title_parts', static function ($title_parts) use ($lead) {
//
//                $title_parts['title'] = empty($lead) ? '' : $lead->title ;
//                return $title_parts;
//            }, 11, 1);
//
//            include( AUTOCERFA_PATH . 'view/single-car-template.php' );
//            exit();
//        }
//    }

    public static function registered_action()
    {
        if (! wp_next_scheduled (  'autocerfa_daily_event' )) {
            wp_schedule_event( time(), 'daily','autocerfa_daily_event');
        }

        if (! wp_next_scheduled ( 'autocerfa_twicedaily_event' )) {
            wp_schedule_event( time(), 'twicedaily', 'autocerfa_twicedaily_event');
        }
    }

    public static function deregister_action()
    {
        wp_clear_scheduled_hook( 'autocerfa_daily_event' );
        wp_clear_scheduled_hook( 'autocerfa_twicedaily_event' );
    }

    public function autocerfa_do_daily_event()
    {
        (new AutocerfaOpcodespace())->setSubscriptionStatus();
        $option = sanitize_text_field(get_option('daily_sync'));
        if(empty($option) || $option === 'Daily Once'){
            $AutocerfaStockProcess = new AutocerfaStockProcess;
            $AutocerfaStockProcess->run();
        }
    }

    public function autocerfa_do_twicedaily_event()
    {
        $option = sanitize_text_field(get_option('daily_sync'));
        if($option === 'Daily Twice'){
            $AutocerfaStockProcess = new AutocerfaStockProcess;
            $AutocerfaStockProcess->run();
        }
    }

    public function autocerfa_menus()
    {
        add_menu_page(
            __('Autocerfa', 'textdomain'),
            'Autocerfa',
            'manage_options',
            'autocerfa',
            [$this, 'autocarfa_admin_page'],
            'dashicons-sos',
            10
        );
//        add_submenu_page(
//            'autocerfa',
//            'Shortcodes',
//            'Shortcodes',
//            'manage_options',
//            'shortcode', [$this, 'admin_shortcode']
//        );
        add_submenu_page(
            'autocerfa',
            __('Autocerfa Settings', 'autocerfa-connector'),
            __('Settings', 'autocerfa-connector'),
            'manage_options',
            'autocerfa-settings',
            [$this, 'autocerfa_settings_content']
        );
        add_submenu_page(
            'autocerfa',
            __('License Key', 'autocerfa-connector'),
            __('License Key', 'autocerfa-connector'),
            'manage_options',
            'license-key',
            [$this, 'autocerfa_license_key_content']
        );
        add_submenu_page(
            'autocerfa',
            __('Support', 'autocerfa-connector'),
            __('Support', 'autocerfa-connector'),
            'manage_options',
            'support', [$this, 'admin_support']
        );

        if(empty(get_option('opcodespace_subscription'))) {
            add_submenu_page(
                'autocerfa',
                __('Upgrade to pro', 'autocerfa-connector'),
                __('Upgrade to pro', 'autocerfa-connector'),
                'manage_options',
                'upgrade-to-pro',
                [$this, 'admin_upgrade']
            );
        }
    }

    public function autocerfa_settings_content()
    {
//        echo get_view(AUTOCERFA_VIEW_PATH . 'admin/settings.php');
        echo get_view(AUTOCERFA_VIEW_PATH . 'admin/shortcodes.php');
    }

    public function autocarfa_admin_page()
    {
        echo get_view(AUTOCERFA_VIEW_PATH . 'admin/dashboard.php');
    }

    public function admin_support()
    {
        echo get_view(AUTOCERFA_VIEW_PATH . 'admin/support.php');
    }

    public function admin_shortcode()
    {
        echo get_view(AUTOCERFA_VIEW_PATH . 'admin/shortcodes.php');
    }

    public function admin_upgrade()
    {
        echo get_view(AUTOCERFA_VIEW_PATH . 'admin/upgrade.php');
    }

    public function autocerfa_license_key_content()
    {
        echo get_view(AUTOCERFA_VIEW_PATH . 'admin/license-key.php');
    }

//    public function autocerfa_register_setting()
//    {
//        register_setting( 'autocerfa_register_setting', 'ops_token' );
//        register_setting( 'autocerfa_register_setting', 'single_page_slug' );
//        register_setting( 'autocerfa_register_setting', 'autocerfa_theme_color_1' );
//        register_setting( 'autocerfa_register_setting', 'autocerfa_theme_color_2' );
//        register_setting( 'autocerfa_register_setting', 'autocerfa_theme_color_3' );
//        register_setting( 'autocerfa_register_setting', 'car_per_page' );
//        register_setting( 'autocerfa_register_setting', 'filter_option' );
//        register_setting( 'autocerfa_register_setting', 'daily_sync' );
//	}
}