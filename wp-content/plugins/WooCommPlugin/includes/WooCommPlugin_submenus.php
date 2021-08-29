<?php
namespace WooCommPlugin;


if ( ! defined( 'ABSPATH' ) ) {
	die; // Exit if accessed directly
}

if ( !class_exists( 'Submenus' ) ) :

class Submenus 
{
    public $options_page_hook;
    public $options;
	
	protected static $_instance = null;
	public $TnC;
	public $Refund;

	public function instance() 
	{
		echo('instance');
		if ( is_null( self::$_instance ) ) 
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
    public function __construct()
    {
		// include settings classes
		$this->TnC = include( 'WooCommPlugin_TnC_submenu.php' );
		$this->Refund = include( 'WooCommPlugin_Refund_Policy_submenu.php');

		// T&C menu item
		add_action( 'load_menus', array( $this, 'store_policies' ), 999 ); // Add menu\
		add_action( 'load_menus', array( $this, 'woocommplugin_tax_menu' ), 999 ); // Add menu\

		// $this->general_settings	= get_option('woocommplugin_store_policies_TnC');
		
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
            array($this,'store_policies_callback')
		);
	}
    
    public function store_policies_callback() 
	{
		$settings_tabs = apply_filters( 'woocommplugin_store_policies_tabs', array (
				'TnC'	=> __('Terms and Conditions', 'woocommplugin' ),
				'Refund_Policy'	=> __('Refund Policy', 'woocommplugin' ),
			)
		);
		
		$active_tab = isset( $_GET[ 'tab' ] ) ? sanitize_text_field( $_GET[ 'tab' ] ) : 'TnC';
		$active_section = isset( $_GET[ 'section' ] ) ? sanitize_text_field( $_GET[ 'section' ] ) : '';

		include('views/Store_Policies.php');
    }

	public function woocommplugin_tax_menu()
	{
		$parent_slug = 'edit.php?post_type=product';

		$this->options_page_hook = add_submenu_page(
			$parent_slug,
			'Tax',
			'Tax',
			'manage_woocommerce',
			'woocommplugin_tax_submenu',
            array($this,'woocommplugin_tax_callback')
		);
	}

	public function woocommplugin_tax_callback()
	{
		$settings_tabs = apply_filters( 'woocommplugin_tax_tabs', array (
				'Tax_Sample'	=> __('Tax Samples', 'woocommplugin' ),
			)
		);
		
		$active_tab1 = isset( $_GET[ 'tab' ] ) ? sanitize_text_field( $_GET[ 'tab' ] ) : 'TnC';
		$active_section1 = isset( $_GET[ 'section' ] ) ? sanitize_text_field( $_GET[ 'section' ] ) : '';

		include('views/Tax_Sample.php');
	}
}


endif;  // calss_exists

return new Submenus();