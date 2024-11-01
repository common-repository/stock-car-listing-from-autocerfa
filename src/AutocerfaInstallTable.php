<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Here should be all table and column which will create table
 */
class AutocerfaInstallTable
{

    public static function autocerfa_badges()
    {
             global $wpdb;
             $version = '1.1';
             $table_name       = $wpdb->prefix . 'autocerfa_badges';
             $charset_collate  = $wpdb->get_charset_collate();
             $sql = "CREATE TABLE $table_name (
                 badge_id mediumint(11) NOT NULL AUTO_INCREMENT,
                 label varchar(250),
                 background_color varchar(10),
                 text_color varchar(10) ,
                 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                 PRIMARY KEY  (badge_id)
             ) $charset_collate;";
             require_once ABSPATH . 'wp-admin/includes/upgrade.php';
             dbDelta($sql);

             add_option('version', $version);
    }

}
