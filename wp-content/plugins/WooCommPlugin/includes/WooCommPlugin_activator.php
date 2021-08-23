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
	}  
}