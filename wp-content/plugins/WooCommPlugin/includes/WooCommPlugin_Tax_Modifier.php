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
    public $billing_location_set;
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
        $this->billing_location_set = 0;
        $this->calculated_tax = array("SGST"=>0.0,"CGST"=>0.0,"IGST"=>0.0);
        $this->cart_items_list = array();
        
        //address of store
        $this->store_details();
        // remove taxes before checkout
        // add_action( 'woocommerce_calculate_totals', array($this, 'action_cart_calculate_totals'), 10, 1 );
        //set total same as subtotal before checkout
		// add_filter( 'woocommerce_calculated_total', array($this,'change_calculated_total'), 10, 2 );
        //add checkbox to use GST settings
		add_filter( 'woocommerce_tax_settings', array($this, 'tax_setting_for_gst') );

        //modify checkout total
        // add_filter( 'woocommerce_cart_totals_order_total_html', array($this,'order_total'));

        
        //ajax scripts for gst state

        add_action( 'wp_enqueue_scripts', array($this,'blog_scripts') ); 
        
        add_action('wp_ajax_get_states_by_ajax', array($this,'set_billing_location'));
        add_action('wp_ajax_nopriv_get_states_by_ajax',array($this, 'set_billing_location'));
        add_filter( 'woocommerce_cart_item_product', array($this, 'cart_contents'),10,3 );

        // add_filter( 'woocommerce_calculate_item_totals_taxes', array($this,'modify_tax_totals'),10,3 );
        // add_filter( 'woocommerce_calc_tax', array($this,'modify_taxes'),10,5 );

        add_filter( 'woocommerce_cart_totals_get_item_tax_rates', array($this,'change_tax_rates'),10,3 );
        add_filter( 'woocommerce_cart_tax_totals', array($this,'update_taxes'),10,2);
        // add_filter( 'woocommerce_get_cart_contents', array($this, 'get_cart_contents'));
        // add_filter( 'woocommerce_cart_item_class', array($this, 'once_get_cart_contents'),10,3);
        add_action( 'woocommerce_review_order_after_submit',function()
        {
            // print_r(WC()->cart);
            // print_r(WC()->cart->get_tax_totals());
        } );
        // add_action( 'woocommerce_review_order_before_cart_contents', function(){
        //     //  print_r(WC()->cart->get_tax_totals());
        //     // echo $this->billing_location;
        // } );
        add_action( 'woocommerce_checkout_create_order', array($this,'change_total_on_checking'), 20, 2 );

        add_filter( 'woocommerce_countries_inc_tax_or_vat', function () 
        {
           return __( 'GST', 'woocommerce' );
        });
    }
    public function change_total_on_checking( $order,$data ) 
    {
        // print_r($data);
        // // Get order total
        // $total = $order->get_total();
    
        // ## -- Make your checking and calculations -- ##
        // $new_total = $total * 1.12; // <== Fake calculation
    
        // // Set the new calculated total
        // $order->set_total( $new_total );
        return $order;
    }

    public function set_billing_location()
    {
        $this->billing_location_set = 1;
        //grab the selected state
        if(isset($_POST["state"]))
        {
            $this->billing_location = $_POST['state'];
        }
        else
        {
            $this->billing_location = $this->store_location['state'];
        }

    }

    public function change_tax_rates($item_tax_rates, $item, $cart)
    {
        //url check to avoid cart total change
        $url = $_SERVER['REQUEST_URI'];
        
        $uri_list = array( explode('/',$url));
        
        global $wpdb;
        $hsn = $item->product->get_meta('hsn_prod_id');
        $rate = $wpdb->get_results("SELECT IGSTRate FROM wp_gst_data WHERE HSNCode = $hsn");
        // print_r($item_tax_rates);
        foreach($rate as $rates)
        {
            if($uri_list[0][count($uri_list[0])-2]=="cart")
            {
                $keys = array_keys($item_tax_rates);
                foreach($keys as $key)
                {
                    $item_tax_rates[$key]['rate'] = 0;
                }  
            }
            else
            {
                
                $this->set_billing_location();
                $keys = array_keys($item_tax_rates);
                foreach($keys as $key)
                {
                    // $this->set_billing_location();
                    if($this->billing_location == $this->store_location['state'])
                    {
                        $item_tax_rates[$key]['rate'] = $rates->IGSTRate/2;
                        $item_tax_rates[$key]['label'] = "SGST/CGST";
                    }
                    else
                    {
                        $item_tax_rates[$key]['rate'] = $rates->IGSTRate;
                        $item_tax_rates[$key]['label'] = "IGST";
                    }
                }
                
            }  
        }
        if($this->billing_location == $this->store_location['state'])
        {
            $obj_arr = $item_tax_rates;
            foreach($obj_arr as $tax_obj)
            {
                array_push($item_tax_rates,$tax_obj);
            }
        }
        // $cart_tax_totals = WC()->cart->get_tax_totals();
        // $arr_keys = array_keys($cart_tax_totals);
        // $arr_val = array_values($cart_tax_totals);
        // $new_arr_keys = array();
        // foreach($arr_keys as $key)
        // {
        //     $key_list = explode('-',$key);
        //     if($this->billing_location == $this->store_location['state'])
        //     {
        //         $key_list[3] = "SGST/CGST";
        //     }
        //     else
        //     {
        //         $key_list[3] = "IGST";
        //     }
        //     $key = implode('-',$key_list);
        //     array_push($new_arr_keys,$key);
        // }
        // array_combine($new_arr_keys,$arr_val);
        
        // print_r(WC()->cart->get_tax_totals());

        // print_r(WC()->cart);
        // echo "<br><br>";
        // print_r($item_tax_rates);
        return $item_tax_rates;
    }


    //ajax for gst state
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


    }

    //to remove taxes at cart before checkout
    public function action_cart_calculate_totals( $cart_object ) {

		if ( is_admin() && ! defined( 'DOING_AJAX' ) )
		{
			return;
		}
	
		if ( !WC()->cart->is_empty() ):
			// $cart_object->set_cart_contents_taxes(array(0,0));
	
		endif;
	}	


    //set total same as subtotal at cart before checkout
	public function change_calculated_total( $total, $cart ) {
        $sub = $cart->get_subtotal();
		$total = $sub;
        // print_r($cart);
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

    //store details
    public function store_details()
    {
        $this->store_location =  wc_get_base_location();
        // $this->set_billing_location();
        // $this->billing_location = $this->store_location['state'];
    }


    //cart contents
    public function cart_contents($product, $cart_item, $cart_item_key)
    {
        // if (wc_prices_include_tax ())
        // {
        //     echo"contains tax";
        // }
        // else
        // {
        //     echo "not contain tax";
        // }
        // print_r(WC()->cart->get_tax_totals());
        $hsn = $product->get_meta('hsn_prod_id');
        $qty = $cart_item['quantity'];
        $subtotal = $qty*$product->get_price();

        global $wpdb;
        $rate = $wpdb->get_results("SELECT IGSTRate FROM wp_gst_data WHERE HSNCode = $hsn");

        foreach($rate as $rates)
        {
            if(!array_search($hsn,$this->cart_items_list,true))
            {
                array_push($this->cart_items_list,array("hsn"=>$hsn,"quantity"=>$qty,"subtotal"=>$subtotal, "tax_rate" => $rates->IGSTRate, "product_tax" => $rates->IGSTRate*$subtotal*0.01));
            }
        }
        // print_r($this->cart_items_list);
        return $product;
    }

    public function update_taxes($tax_totals,$cart)
    {
        $tax_list = array();
        foreach($this->cart_items_list as $cart_item)
        {
            array_push($tax_list,$cart_item["product_tax"]);
        }
        $keys = array_keys($tax_totals);
        foreach($keys as $key)
        {
            if($this->billing_location == $this->store_location['state'])
            {
                $tax_totals[$key]->label = "SGST/CGST";
            }
            else
            {
                $tax_totals[$key]->label = "IGST";
            }
        }
        // $cart->set_cart_contents_taxes($tax_list);
        // print_r($tax_list);
        // print_r($cart);
        // print_r(WC()->cart->get_tax_totals());
        // echo "<br><br>";
        // print_r($cart->get_cart_tax());
        return $tax_totals;
    }

    public function modify_taxes( $taxes, $price, $rates, $price_includes_tax, $deprecated)
    {
        $new_taxes = array();
        // $rates;
        // $rates = $this->cart_items_list['tax_rate'];
        foreach($this->cart_items_list as $cart_item)
        {
            // $rates = $cart_item['tax_rate'];
            array_push($new_taxes,$cart_item['product_tax']);
        }
        // if ( $price_includes_tax ) {
		// 	$taxes = WC_Tax::calc_inclusive_tax( $price, $rates );
		// } else {
		// 	$taxes = WC_Tax::calc_exclusive_tax( $price, $rates );
		// }
        array_replace($taxes,$new_taxes);
        // $taxes = array($rates,$price);
        // print_r($taxes);
        return $taxes;
    }

    public function modify_tax_totals($taxes,$item,$cart_totals)
    {
        
        $tax_list = array();
        $i = 0;
        foreach($this->cart_items_list as $cart_item)
        {
            array_push($tax_list,$cart_item["product_tax"]);
        }
        foreach($taxes as $tax_item)
        {
            // $tax_item = $tax_list[$i];
            // $i++;
            print_r($tax_list);
        }
        return $taxes;
    }

    //modify checkout total
    public function order_total($value)
    {   
        //tax to be added
        $tax = 0;

        //find current cart value in float from formatted data
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


        //url check to avoid cart total change
        $url = $_SERVER['REQUEST_URI'];
        
        $uri_list = array( explode('/',$url));
        
        if($uri_list[0][count($uri_list[0])-2]=="cart")
        {
            $tax = 0;
        }
        
        else
        {
            $total_tax = 0.0;
            foreach($this->cart_items_list as $cart_item)
            {
                $total_tax += $cart_item["product_tax"];
            }

            //grab the selected state
            $this->billing_location = $_POST['state'];
            
            if($this->billing_location == $this->store_location['state'])
            {
                $this->calculated_tax["IGST"] = $total_tax;   
            }
            else
            {
                $this->calculated_tax["IGST"] = 2000;
            }
            
            $tax = $this->calculated_tax["IGST"];
        }

        //add the total tax
        $new_total = $tax + $new_total;

        $value = str_replace($val,$new_total,$value);
        return $value;
    }
    

}

endif;

return new Tax_Modifier();