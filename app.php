<?php
/*
Plugin Name: Autocerfa Connector
Description: Connecting with autocerfa and displaying stock cars
Plugin URI: https://www.opcodespace.com
Author: Opcodespace <mehedee@opcodespace.com>
Author URI: https://www.opcodespace.com
Version: 2.4.3
Text Domain: autocerfa-connector
*/
if ( ! defined( 'ABSPATH' ) ) {exit;}

define('AUTOCERFA_PLUGIN_VERSION', '2.4.3');
define("AUTOCERFA_PATH", wp_normalize_path(plugin_dir_path(__FILE__)));
define("AUTOCERFA_VIEW_PATH", wp_normalize_path(plugin_dir_path(__FILE__) . "view/"));
define("AUTOCERFA_ASSETSURL", wp_normalize_path(plugins_url("assets/", __FILE__)));
define("AUTOCERFA_UPLOAD_PATH", wp_normalize_path(wp_upload_dir()['basedir']));
define("AUTOCERFA_UPLOAD_URL", wp_normalize_path(wp_upload_dir()['baseurl']));
define('AUTOCERFA_PRODUCT_LINK', 'https://www.opcodespace.com/product/autocerfa-connector-pro/');
define('AUTOCERFA_DEBUG', get_option('autocerfa_debug') === 'yes');

global $autocerfa_bg_process;
global $AutocerfaImageDownloadAsync;
require 'vendor/autoload.php';
include_once 'functions.php';

add_action('plugins_loaded', array('AutocerfaShortCode', 'init'));
add_action('plugins_loaded', array('AutocerfaEnqueue', 'init'));
add_action('plugins_loaded', array('AutocerfaAjaxAction', 'init'));
add_action('plugins_loaded', array('AutocerfaHook', 'init'));

add_action('plugins_loaded', 'autocerfa_bg_plugins_loaded');

if(!function_exists('autocerfa_bg_plugins_loaded')){
    function autocerfa_bg_plugins_loaded()
    {
        global $autocerfa_bg_process;
        $autocerfa_bg_process = new AutocerfaBackgroundProcess();
        global $AutocerfaImageDownloadAsync;
        $AutocerfaImageDownloadAsync = new AutocerfaImageDownloadAsync();
    }
}

register_activation_hook(__FILE__, array('AutocerfaInstallTable', 'autocerfa_badges'));
register_activation_hook(__FILE__, array('AutocerfaHook', 'registered_action'));
register_deactivation_hook(__FILE__, array('AutocerfaHook', 'deregister_action'));
register_activation_hook(__FILE__, array('AutocerfaHook', 'plugin_activation'));

add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'autocerfa_plugin_link');

if(!function_exists('autocerfa_plugin_link')) {
    function autocerfa_plugin_link($links)
    {
        $links[] = '<a href="' .
            admin_url('admin.php?page=autocerfa&get_started&step=1') .
            '">' . __('Getting Started') . '</a>';
        $links[] = '<a href="' .
            admin_url('admin.php?page=autocerfa-settings') .
            '">' . __('Settings') . '</a>';
        return $links;
    }
}

function autocerfa_connector_textdomain() {
    load_plugin_textdomain( 'autocerfa-connector', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'autocerfa_connector_textdomain' );


add_filter( 'cron_schedules', 'autocerfa_add_every_five_minutes' );
function autocerfa_add_every_five_minutes( $schedules ) {
    $schedules['every_five_minutes'] = array(
        'interval'  => 300,
        'display'   => __( 'Every 5 Minutes', 'autocerfa-connector' )
    );
    return $schedules;
}

// Schedule an action if it's not already scheduled
if ( ! wp_next_scheduled( 'autocerfa_add_every_five_minutes' ) ) {
    wp_schedule_event( time(), 'every_five_minutes', 'autocerfa_add_every_five_minutes' );

}

// Hook into that action that'll fire every five minutes
add_action( 'autocerfa_add_every_five_minutes', 'every_five_minutes_event_func' );
function every_five_minutes_event_func() {
    AutocerfaLogger::log('Schedule Download Started');
    if(!get_option('autocerfa_processing')){
        AutocerfaLogger::log('Schedule Download Stopped');
        return;
    }
    (new AutocerfaStockProcess())->downloadImage();
}