<?php
namespace WooCommPlugin;


if ( ! defined( 'ABSPATH' ) ) {
	die; // Exit if accessed directly
}

if ( !class_exists( 'Tax_Modifier' ) ) :

class Tax_Modifier 
{

    public $store_location;
    public $shipping_location;
    public $billing_location;
    public $cart_items_list;
    public $calculated_tax = array("SGST"=>"0.0","CGST"=>"0.0","IGST"=>"0.0");
    public $tax_slab;
    public $new_total;

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
        // remove taxes before checkout
        add_action( 'woocommerce_calculate_totals', array($this, 'action_cart_calculate_totals'), 10, 1 );
        //set total same as subtotal before checkout
		add_filter( 'woocommerce_calculated_total', array($this,'change_calculated_total'), 10, 2 );
        //add checkbox to use GST settings
		add_filter( 'woocommerce_tax_settings', array($this, 'tax_setting_for_gst') );
        //get address details for the order
        add_action( 'woocommerce_after_checkout_shipping_form', array($this,'get_address_details') );
        //modify checkout total
        add_filter( 'woocommerce_cart_totals_order_total_html', array($this,'order_total'),11);
        //order review
        // add_action( 'wc_cart_totals_shipping_html', array($this,'order_review') );

        // add_action( 'woocommerce_checkout_create_order', array($this,'change_total_on_checking'), 20, 1 );
        // add_filter( 'woocommerce_cart_subtotal', array($this,'order_review'),10,3);

        // add_action( 'woocommerce_review_order_after_shipping', function(){
            
        // });
		
    }

    //modify checkout total
    public function order_total($value)
    {
        $start = 117;
        $end = strlen($value) - 23;
        $val = '';
        $new_total = 0;
        for($i = 0;$i<strlen($value);$i++)
        {
            if($i>$start&&$i<$end)
                $val = $val.$value[$i];
        }
        for($j = 0;$j<strlen($val);$j++)
        {
            $new_total *= 10;
            $new_total += (int)$val[$j];
        }
        
        $new_total = 5.0 * $new_total;  //add tax here
        
        $value = str_replace($val,$new_total,$value);
        return $value;
    }

    //to remove taxes before checkout
    public function action_cart_calculate_totals( $cart_object ) {

		if ( is_admin() && ! defined( 'DOING_AJAX' ) )
		{
			return;
		}
	
		if ( !WC()->cart->is_empty() ):
			$cart_object->set_cart_contents_taxes(array(0,0));
	
		endif;
	}	

    //set total same as subtotal before checkout
	public function change_calculated_total( $total, $cart ) {
		$total  -= $cart->get_cart_contents_tax(); 
	    return $total;
	}

    //add checkbox to use GST settings
    public function tax_setting_for_gst($settings)
	{
		array_push($settings, array(
			'title'   => __( 'Use default GST', 'woocommerce' ),
			'desc'    => __( 'Use in-built GST data for tax calculation. You won\'t need to setup woocommerce tax if you tick this box.', 'woocommerce' ),
			'id'      => 'woocommplugin_use_default_gst',
			'default' => 'yes',
			'type'    => 'checkbox',
		));
		include('views/Tax_Sample');                //check its output
		return $settings;
	}

    public function get_address_details($checkout)
    {
        $this->shipping_location = $checkout->get_checkout_fields('shipping');
        $this->billing_location = $checkout->get_checkout_fields('billing');

        // echo $this->billing_location;
    }

    //checkout subtotal
    // public function order_review($subtotal,$cart_item, $cart )
    // {
    //     // if ( is_admin() && ! defined( 'DOING_AJAX' ) )
	// 	// {
	// 	// 	return;
	// 	// }
	
	// 	// if ( !WC()->cart->is_empty() ):
	// 	// 	WC()->cart->set_subtotal('2000');
	
	// 	// endif;
    //     // add_filter( 'woocommerce_calculated_total', function($total,$cart){
    //     //     $total *= 2.0;
    //     //     return $total;
    //     // }, 10, 2 );
        
    //     $subtotal = $subtotal.$cart->get_subtotal();;
    //     return $subtotal;
    // }
    // public function change_total_on_checking( $order ) {
    //     // Get order total
    //     $total = $order->get_total();
    
    //     ## -- Make your checking and calculations -- ##
    //     $new_total = $total * 1; // <== Fake calculation
    
    //     // Set the new calculated total
    //     $order->set_total( $new_total );
    // }
}

endif;

return new Tax_Modifier();