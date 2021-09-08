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
	public $TnC;
	public $Refund;
	public $Refund1;


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
		$this->Refund1 = include( 'WooCommPlugin_Terms_and_Conditions_post_type.php');

		// T&C menu item
		add_action( 'load_menus', array( $this, 'invoice_settings' ), 999 ); // Add menu\
		//Tax menu
		add_action( 'load_menus', array( $this, 'woocommplugin_tax_menu' ), 999 ); // Add menu\
		
		//Product hsn code
		add_action('woocommerce_product_options_general_product_data', array( $this , 'add_product_custom_meta_box_hsn_code') );
		add_action( 'woocommerce_process_product_meta', array($this,'save_hsn_code_field' ));
		
		add_filter( 'woocommerce_tax_settings', array($this, 'tax_setting_for_gst') );
		// add_filter( 'woocommerce_cart_taxes_total', array($this, 'order_taxes'), 10, 4 );
		add_filter( 'woocommerce_calculated_total', array($this,'change_calculated_total'), 10, 2 );
    }
	
	
	public function change_calculated_total( $total, $cart ) {
	    return $total + 300;
	}

	public function invoice_settings() 
    {
		$parent_slug = 'woocommerce';

		$this->options_page_hook = add_submenu_page(
			$parent_slug,
			'Invoice Settings',
			'Invoice Settings',
			'manage_woocommerce',
			'woocommplugin_invoice_settings_submenu',
            array($this,'invoice_settings_callback')
		);
	}
    
    public function invoice_settings_callback() 
	{
		$settings_tabs = apply_filters( 'woocommplugin_invoice_settings_tabs', array (
				'Invoice'	=> __('Invoice', 'woocommplugin' ),
				// 'Refund_Policy'	=> __('Refund Policy', 'woocommplugin' ),
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

		include('views/check1.php');
		
	}
	
    public function add_product_custom_meta_box_hsn_code() {
        woocommerce_wp_text_input( 
            array( 
                'id'            => 'hsn_prod_id', 
                'label'         => __('HSN Code', 'woocommerce' ), 
                'description'   => __( 'HSN Code is mandatory for GST.', 'woocommerce' ),
                'custom_attributes' => array( 'required' => 'required' ),
                'value'         => get_post_meta( get_the_ID(), 'hsn_prod_id', true )
                )
            );
    }

	public function save_hsn_code_field( $post_id ) {
        $value = ( $_POST['hsn_prod_id'] )? sanitize_text_field( $_POST['hsn_prod_id'] ) : '' ;
        update_post_meta( $post_id, 'hsn_prod_id', $value );
    }

	public function tax_setting_for_gst($settings)
	{
		array_push($settings, array(
			'title'   => __( 'Use default GST', 'woocommerce' ),
			'desc'    => __( 'Use in-built GST data for tax calculation', 'woocommerce' ),
			'id'      => 'woocommplugin_use_default_gst',
			'default' => 'yes',
			'type'    => 'checkbox',
		));
		
		return $settings;
	}
}


endif;  // calss_exists

return new Submenus();