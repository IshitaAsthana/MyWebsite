<?php

/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 *
 * @package    WooCommPlugin
 * @subpackage WooCommPlugin/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WooCommPlugin
 * @subpackage WooCommPlugin/includes
 * @author     Ishita Asthana
 */

class WooCommPlugin_Activator
{
    public static function activate() 
    {
        //flush rewrite rules.
        flush_rewrite_rules();
        global $wpdb;
 
        $table_name = $wpdb->prefix . "payment";
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
          id bigint(20) NOT NULL AUTO_INCREMENT,
          user_id bigint(20) UNSIGNED NOT NULL,
          created_at datetime NOT NULL,
          expires_at datetime NOT NULL,
          PRIMARY KEY id (id)
        ) $charset_collate;";
    
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);          
	}  
}