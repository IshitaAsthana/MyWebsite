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
        // echo("Hey");
		// T&C menu item
		add_action( 'admin_menu', array( $this, 'tnc_menu' ) ); // Add menu
    }
    
	public function tnc_menu() 
    {
		$parent_slug = 'woocommerce';
		
		$this->options_page_hook = add_submenu_page(
			$parent_slug,
			'T&C',
			'T&C',
			'manage_woocommerce',
			'woocommplugin_tnc_submenu',
            array($this,'submenu_page_callback')
		);
	}
    
    public function submenu_page_callback() {
        echo '<input type="text" id="homepage_text" name="homepage_text" value="%s" />' ;
        echo '<div class="wrap">
	<h1>My Page Settings</h1>
	<form method="post" action="options.php">';
			
		settings_fields( 'misha_settings' ); // settings group name
		do_settings_sections( 'misha-slug' ); // just a page slug
		submit_button();

	echo '</form></div>';
    }
}


endif;  // calss_exists

return new Submenus();