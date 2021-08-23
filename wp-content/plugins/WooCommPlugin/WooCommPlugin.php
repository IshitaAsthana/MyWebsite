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

		add_action( 'init' , array( $this , 'create_custom_post' ) );
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

		$labels = array(
			'name' => _x( 'type', 'taxonomy general name' ),
			'singular_name' => _x( 'type', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Subjects' ),
			'all_items' => __( 'All Subjects' ),
			'parent_item' => __( 'Parent Subject' ),
			'parent_item_colon' => __( 'Parent Subject:' ),
			'edit_item' => __( 'Edit Subject' ), 
			'update_item' => __( 'Update Subject' ),
			'add_new_item' => __( 'Add New Subject' ),
			'new_item_name' => __( 'New Subject Name' ),
			'menu_name' => __( 'Type' ),
		  );    
		 
		// Now register the taxonomy
		  register_taxonomy('type',array('tests1'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'show_in_rest' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'type' ),
		  ));
	}

	function create_custom_post()
	{
		$labels = array(
			'name' => 'Tests1',
			'singular_name' => 'Test1',
			'add_new' => 'Add new item',
			'all items' => 'All Items',
			'add_new_items' => 'Add Item',
			'edit_item' => 'Edit Item',
			'new_item' => 'New Item',
			'view_item' => 'View Item',
			'search_item' => 'Search Item',
			'not_found' => 'No Items found',
			'not_found_in_trash' => 'No Items found in trash',
			'parent_item_colon' => 'Parent Item'
		);
		$args = array(
			'labels' => $labels,
			'public' => true,
			'has_archive' => true,
			'publicly_queryable' => true,
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchial' => false,
			'supports' => array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				'revisions'
			),
			'taxonomies' => array( 'category', 'post_tag'),
			'menu_position' => 5,
			'exclude_from_search' => false
			);
		register_post_type( 'tests1', $args );
	}
}

// activation
register_activation_hook( __FILE__ , array( WooCommPlugin::instance(),'activation' ) );

// deactivation
register_deactivation_hook( __FILE__ , array( WooCommPlugin::instance(),'deactivation' ) );

endif;