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
    public $calculated_tax;
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
        $this->calculated_tax = array("SGST"=>0.0,"CGST"=>0.0,"IGST"=>0.0);
        // $this->billing_location = $fields['billing']['billing_phone'];

        //address of store
        $this->store_details();
        // remove taxes before checkout
        add_action( 'woocommerce_calculate_totals', array($this, 'action_cart_calculate_totals'), 10, 1 );
        //set total same as subtotal before checkout
		add_filter( 'woocommerce_calculated_total', array($this,'change_calculated_total'), 10, 2 );
        //add checkbox to use GST settings
		add_filter( 'woocommerce_tax_settings', array($this, 'tax_setting_for_gst') );
        //get address details for the order
        add_action( 'woocommerce_after_checkout_shipping_form', array($this,'get_address_details') );
        //
        add_filter( 'woocommerce_checkout_fields', array($this, 'get_form_details') );
        //calculate tax
        // $this->tax_calculate();
        //modify checkout total
        add_filter( 'woocommerce_cart_totals_order_total_html', array($this,'order_total'));

        add_filter( 'woocommerce_order_button_text', array($this,'confirm_order') );
        
        add_action( 'woocommerce_after_checkout_billing_form', array($this,'billing_state_ajax'));
        
        add_action( 'wp_enqueue_scripts', array($this,'blog_scripts') ); 
        
        add_action('wp_ajax_get_states_by_ajax', array($this,'order_total'));
        add_action('wp_ajax_nopriv_get_states_by_ajax',array($this, 'order_total'));
    }

    public function billing_state_ajax()
    {
        
    }
    public function blog_scripts() {
        // Register the script
        wp_register_script( 'custom-script', '/wp-content/plugins/WooCommPlugin/includes/WooCommPlugin_tax.js', array('jquery'), false, true );
      
        // Localize the script with new data
        $script_data_array = array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'security' => wp_create_nonce( 'load_states' ),
        );
        wp_localize_script( 'custom-script', 'blog', $script_data_array );
      
        // Enqueued script with localized data.
        wp_enqueue_script( 'custom-script' );

        // $this->tax_slab = $_POST['val'];
    }

    public function get_states_by_ajax_callback() {
        // check_ajax_referer('load_states', 'security');
        $this->tax_slab = $_POST['state'];
        // global $wpdb;
        // $aStates = $wpdb->get_results( $wpdb->prepare( "SELECT id, state_name FROM ".$wpdb->prefix."states WHERE cid = %d", $country ) );
        // if ( $aStates ) {
            
        // remove_filter( 'woocommerce_cart_totals_order_total_html', array($this,'order_total'));
        // add_filter( 'woocommerce_cart_totals_order_total_html', array($this,'order_total'));
        // $this->order_total()
         
         
        // wp_die();
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
        $sub = $cart->get_subtotal();
		$total = $sub;
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
		// include('views/Tax_Sample.php');                //check its output
		return $settings;
	}

    //address details of the order
    public function get_address_details($checkout)
    {
        $this->shipping_location = $checkout->get_checkout_fields('shipping');
        // $this->billing_location = $checkout->get_value('billing_state');

       
        
        // echo $this->billing_location;
    }

    public function get_form_details($fields)
    {
        $this->billing_location = $fields['billing']['billing_phone'];

        return $fields;
    }
    
    //store details
    public function store_details()
    {
        $this->store_location =  wc_get_base_location();
        // if($this->store_location['country']=='IN')
        // {
        //     if($this->store_location['state']=='MP')
        //         $this->calculated_tax['IGST'] = 2.0;
        // }
    }

    public function confirm_order($string)
    {
        $string = __( 'Confirm order', 'woocommerce' );
        return $string;
    }

    //modify checkout total
    public function order_total($value)
    {   $tax = 0;
        $start = 117;
        $end = strlen($value) - 23;
        $val = '';
        $new_total = 0;
        for($i = 0;$i<strlen($value);$i++)
        {
            $tax = 100;
            if($i>$start&&$i<$end)
                $val = $val.$value[$i];
        }
        for($j = 0;$j<strlen($val);$j++)
        {
            $new_total *= 10;
            $new_total += (int)$val[$j];
        }

        // $user = wp_get_current_user(); // The current user

        // $country = $user->billing_country;
        // $state = $user->billing_state;
        // $input = 'billing_country';
        // $string = WC_Checkout::get_value( $input );
        // if($this->billing_location['billing']['billing_state']=='UP')
        //     $this->calculated_tax['IGST']=500;
        // else
        //     $this->calculated_tax['IGST']=1000;

        //url check to avoid cart total change
        $url = $_SERVER['REQUEST_URI'];
        
        $uri_list = array( explode('/',$url));
        
        if($uri_list[0][count($uri_list[0])-2]=="cart")
        {
            $tax = 0;
        }
        else{
            $tax = $this->calculated_tax["IGST"] + 3000;
        }

        $new_total = $tax + $new_total;// + $this->billing_location['state'];  //add tax here
        
        //grab the selected state
        $this->tax_slab = $_POST['state'];


        $value = str_replace($val,$this->tax_slab,$value);
        return $value;
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