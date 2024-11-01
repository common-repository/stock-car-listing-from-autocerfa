<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}

class AutocerfaMisc
{
    const BLUE = '#2959ad';
    const YELLOW = '#f4c23d';
    const BLACK = '#1e1e1e';
    const WHITE = '#fff';

    public static function url($lead)
    {
        $url = get_the_permalink($lead);
        $selected_single_page = (int)get_option('autocerfa_single_page');

        if ($selected_single_page > 0) {
            $page = get_post($selected_single_page);
            $url = get_the_permalink($page) . "?lead_id={$lead->lead_id}";
        }

        return $url;
    }

    public static function flushPermalink()
    {
        flush_rewrite_rules(false);
        //        global $wp_rewrite;
//        $wp_rewrite->flush_rules();
    }

    public static function carListUrl()
    {
        $selected_car_list_page = (int)get_option('autocerfa_car_list_page');

        if ($selected_car_list_page > 0) {
            $page = get_post($selected_car_list_page);
            return get_the_permalink($page);
        }

        return get_post_type_archive_link('autocerfa_car');
    }

    public static function FilterOptionVisibility()
    {
        return get_option('filter_option') === 'yes' || get_option('filter_option') === false;
    }

    public static function debug()
    {
        return get_option('autocerfa_debug') === 'yes';
    }

    public  static function color1()
    {
        // Yellow
        return empty(get_option('autocerfa_theme_color_1')) ? '#f4c23d' : sanitize_hex_color(get_option('autocerfa_theme_color_1'));
    }

    public  static function color2()
    {
        // blue
        return empty(get_option('autocerfa_theme_color_2')) ? '#2959ad' : sanitize_hex_color(get_option('autocerfa_theme_color_2'));
    }

    public  static function color3()
    {
        // black
        return empty(get_option('autocerfa_theme_color_3')) ? '#1e1e1e' : sanitize_hex_color(get_option('autocerfa_theme_color_3'));
    }

    public static function carPerPage()
    {
        $default_page = (int)get_option('car_per_page');

        return empty($default_page) ? 20 : $default_page;
    }

    public static function singlePageSlug()
    {
        $default_slug = sanitize_title_with_dashes(get_option('single_page_slug'));

        return empty($default_slug) ? 'car' : $default_slug;
    }

    public static function croppingImageAsAspectRatio()
    {
        return get_option('cropping_image_as_aspect_ration') === 'yes';
    }

    public static function isPaid()
    {
        return get_option('opcodespace_subscription');
    }

    public static function preventFreeUser()
    {
        if(!self::isPaid()){
            wp_send_json_error(['message' => __('You are running a free plugin. Please upgrade to get this pro feature.', 'autocerfa-connector')]);
        }
    }
}
