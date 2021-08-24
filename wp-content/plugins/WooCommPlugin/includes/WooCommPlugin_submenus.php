<?php
namespace WooCommPlugin;

if ( ! defined( 'ABSPATH' ) ) {
	die; // Exit if accessed directly
}

if ( !class_exists( 'Submenus' ) ) :

class Submenus 
{
    public $options_page_hook;
    
	protected static $_instance = null;

	public static function instance() 
	{
		if ( is_null( self::$_instance ) ) 
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

    function _construct()
    {
		// include settings classes
		$this->TnC = include( 'WooCommPlugin_TnC_submenu.php' );
		
		// T&C menu item
		add_action( 'admin_menu', array( $this, 'store_policies' ) ); // Add menu

		$this->general_settings	= get_option('woocommplugin_store_policies_TnC');
		
    }
    
	public function store_policies() 
    {
		$parent_slug = 'woocommerce';
		
		$this->options_page_hook = add_submenu_page(
			$parent_slug,
			'Store Policies',
			'Store Policies',
			'manage_woocommerce',
			'woocommplugin_store_policies_submenu',
            array($this,'submenu_page_callback')
		);
	}
    
    public function submenu_page_callback() {
		$settings_tabs = apply_filters( 'wpo_wcpdf_settings_tabs', array (
				'TnC'	=> __('Trems and Conditions', 'woocommplugin' ),
				'Refund Policy'	=> __('Refund Policy', 'woocommplugin' ),
			)
		);
		
		$active_tab = isset( $_GET[ 'tab' ] ) ? sanitize_text_field( $_GET[ 'tab' ] ) : 'TnC';
		$active_section = isset( $_GET[ 'section' ] ) ? sanitize_text_field( $_GET[ 'section' ] ) : '';

		include('views/Store_Policies.php');
    }
}


endif;  // calss_exists

return new Submenus();