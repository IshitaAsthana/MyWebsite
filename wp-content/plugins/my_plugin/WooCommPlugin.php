<?php
/**
 * @package WooCommPlugin
 * @version 1.0.0
 */
 /*
Plugin Name: WooCommPlugin
Plugin URI: http://wordpress.org/plugins/my_plugin/my_plugin/
Description: This is a test sample of the Plugin to be created in future supporting woocommerce websites.
Author: Ishita Asthana
Version: 1.0.0
*/

/*
*/

if ( ! defined( 'ABSPATH') ) 
{
	die;	//Exit if accessed directly.
}

if ( !class_exists( 'WooCommPlugin') ) : 

class WooCommPlugin
{
	public $version = '2.9.3';
	public $plugin_basename;

	protected static $_instance = null;

	/**
	 * Main Plugin Instance
	 *
	 * Ensures only one instance of plugin is loaded or can be loaded.
	 */
	public static function instance() 
	{
		if ( is_null( self::$_instance ) ) 
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() 
	{
		$this->plugin_basename = plugin_basename(__FILE__);

		$this->define( 'WooCommPlugin_VERSION', $this->version );

		add_action( 'init' , array( $this , 'custom_post_type' ) );
	}

	/**
	 * Define constant if not already set
	 * @param  string $name
	 * @param  string|bool $value
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	* Function for activation.
	*/
	function activation()
	{
		//custom post type for fail-safe
		$this->custom_post_type();
		//flush rewrite rules.
		flush_rewrite_rules();
	}

	/**
	* Function for deactivation.
	*/
	function deactivation()
	{
		//flush rewrite rules.
		flush_rewrite_rules();
	}

	/**
	* Function for uninstall.
	*/
	function uninstall()
	{
		
	}

	/**
	* Function for custom post type.
	*/
	function custom_post_type()
	{
		register_post_type('book',  ['public' => true, 'label' =>'Books'] );
	}
}

// activation
register_activation_hook( __FILE__ , array( WooCommPlugin::instance(),'activation' ) );

// deactivation
register_deactivation_hook( __FILE__ , array( WooCommPlugin::instance(),'deactivation' ) );

endif;