<?php 

defined( 'ABSPATH' ) or exit;

?>
<script type="text/javascript">
	jQuery( function( $ ) {
		$("#footer-thankyou").html("If you like <strong>WooCommPlugin</strong> please leave us a <a href='#'>★★★★★</a> rating. A huge thank you in advance!");
	});
</script>
<div class="wrap">
	<div class="icon32" id="icon-options-general"><br /></div>
	<h2><?php _e( 'Taxes', 'woocommplugin' ); ?></h2>
	<h2 class="nav-tab-wrapper">
	<?php
	foreach ($settings_tabs as $tab_slug => $tab_title ) {
		$tab_link = esc_url("?page=woocommplugin_store_policies_submenu&tab={$tab_slug}");
		printf('<a href="%1$s" class="nav-tab nav-tab-%2$s %3$s">%4$s</a>', $tab_link, $tab_slug, (($active_tab == $tab_slug) ? 'nav-tab-active' : ''), $tab_title);
	}
	?>
	</h2>

	<form method="post" action="options.php" id="woocommplugin_store_policies" class="<?php echo "{$active_tab} {$active_section}"; ?>">
   
        <label for="shipping_address">Choose Shipping state:</label><br>

        <select name="shipping_address" id="shipping_address">
          <option value="Uttar Pradesh">Uttar Pradesh</option>
          <option value="Karnataka">Karnataka</option>
          <option value="Gujrat">Gujrat</option>
          <option value="West Bengal">West Bengal</option>
        </select>   
        <br><br>

        <label for="shipping_country">Choose Shipping country:</label><br>

        <select name="shipping_country" id="shipping_country">
          <option value="India">India</option>
          <option value="US">US</option>
          <option value="England">England</option>
          <option value="West Indies">West Indies</option>
        </select>   
        <br><br>

        <label for="billing_address">Choose Billing state:</label><br>

        <select name="billing_address" id="billing_address">
          <option value="Uttar Pradesh">Uttar Pradesh</option>
          <option value="Karnataka">Karnataka</option>
          <option value="Gujrat">Gujrat</option>
          <option value="West Bengal">West Bengal</option>
        </select>   
        <br><br>

        <label for="billing_country">Choose Billing country:</label><br>

        <select name="billing_country" id="billing_country">
          <option value="India">India</option>
          <option value="US">US</option>
          <option value="England">England</option>
          <option value="West Indies">West Indies</option>
        </select>
        <br><br>

        <input type="radio" name="tax_slab" value="5">
        <label for="html">5%</label><br>
        <input type="radio" name="tax_slab" value="12">
        <label for="css">12%</label><br>
        <input type="radio" name="tax_slab" value="18">
        <label for="javascript">18%</label>
        <br><br>

        <button type = "button" class = "button-primary" id="Calculate" onclick = "calculate_tax()">Calculate</button>
				<script>
          function calculate_tax()
          {
            var shipping_address = document.getElementById("shipping_address").value;
            var shipping_country = document.getElementById("shipping_country").value;
            var billing_address = document.getElementById("billing_address").value;
            var billing_country = document.getElementById("billing_country").value;
            var store_address = "Uttar Pradesh";
            var store_country = "India";
            var tax_slab_list = document.getElementsByName("tax_slab");
            var SGST = 0.0;
            var CGST = 0.0;
            var IGST = 0.0;
            
            var tax_slab = 5;
            for(let i = 0; i < 3; i++)
            {
              if(tax_slab_list[i].checked)
              {
                tax_slab = tax_slab_list[i].value;
              }
            }

            if(shipping_country === store_country)
            {
              if(shipping_address === store_address)
              {
                SGST = tax_slab/2;
                CGST = tax_slab/2;
                IGST = 0;
              }
              else
              {
                SGST = 0;
                CGST = 0;
                IGST = tax_slab;

              }
            }
            else
            {

            }

            window.alert('Tax distribution : \nSGST' + SGST + '\nCGST' + CGST + '\nIGST' + IGST);
          }

        </script>
	</form>	
</div>
