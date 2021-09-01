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
		$tab_link = esc_url("?page=woocommplugin_tax_sample_submenu&tab={$tab_slug}");
		printf('<a href="%1$s" class="nav-tab nav-tab-%2$s %3$s">%4$s</a>', $tab_link, $tab_slug, (($active_tab == $tab_slug) ? 'nav-tab-active' : ''), $tab_title);
	}
	?>
	</h2>
    <form id="tax_sample_form">
        <?php
			do_action( 'woocommplugin_tax_sample_page', $active_tab, $active_section );
			if ( has_action( 'woocommplugin_tax_sample_page_'.$active_tab )) {
				
				do_action( 'woocommplugin_tax_sample_page_'.$active_tab, $active_section );
				
			} else {
				
				do_action( 'woocommplugin_tax_sample_page_'.$active_tab, $active_section );
				
			}
		?>
        <label for="shipping_address">Choose Shipping state:</label><br>

        <select name="shipping_address" id="shipping_address">
            <option value="Uttar Pradesh">Uttar Pradesh</option>
            <option value="Karnataka">Karnataka</option>
            <option value="Gujrat">Gujrat</option>
            <option value="West Bengal">West Bengal</option>
        </select>  
        <br />
        <input type="file" id="csvFile" accept=".csv" />
        <br />
        <input type="submit" value="Submit" />
    </form>
</div>
<script>
    const myForm = document.getElementById("tax_sample_form");
    const csvFile = document.getElementById("csvFile");
    var tax_rate = 0.0;
    var SGST = 0.0;
    var CGST = 0.0;
    var IGST = 0.0;

    function csvToArray(str, delimiter = ",") {

        // slice from start of text to the first \n index
        // use split to create an array from string by delimiter
        const headers = str.slice(0, str.indexOf("\n")).split(delimiter);

        // slice from \n index + 1 to the end of the text
        // use split to create an array of each csv value row
        const rows = str.slice(str.indexOf("\n") + 1).split("\n");

        // Map the rows
        // split values from each row into an array
        // use headers.reduce to create an object
        // object properties derived from headers:values
        // the object passed as an element of the array
        const arr = rows.map(function (row) {
            const values = row.split(delimiter);
            const el = headers.reduce(function (object, header, index) {
                object[header] = values[index];
                return object;
            }, {});
            return el;
        });

        // return the array
        return arr;
    }

    myForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const input = csvFile.files[0];
        const reader = new FileReader();
        
        var shipping_address;

        reader.onload = function (e) {
            const text = e.target.result;
            const data = csvToArray(text);
            
            shipping_address  = document.getElementById("shipping_address").value;
            for(let i=0;i<data.length;i++)
            {
                
                if(data[i]['HSNCode']==='0403')      //code value according to cart item
                {
                    tax_rate = 2*data[i]['SGSTRate'];
                    if(shipping_address == 'Uttar Pradesh')
                    {
                        SGST = tax_rate/2;
                        CGST = tax_rate/2;
                        IGST = 0;
                    }
                    else
                    {
                        SGST = 0;
                        CGST = 0;
                        IGST = tax_rate;
                    }
                    window.alert('Tax distribution : \nSGST' + SGST + '\nCGST' + CGST + '\nIGST' + IGST);
                }
            }
        };

        reader.readAsText(input);
    });
</script>
<?php
    global $WC;
    // $items = $WC->cart->get_cart();
    global $woocommerce; 
    echo($WC->cart->get_total);
    echo('no');
    // foreach($items as $item => $values) { 
    //     $_product =  wc_get_product( $values['data']->get_id()); 
    //     echo "<b>".$_product->get_title().'</b>  <br> Quantity: '.$values['quantity'].'<br>'; 
    //     $price = get_post_meta($values['product_id'] , '_price', true);
    //     echo "  Price: ".$price."<br>";
    // } 
?>