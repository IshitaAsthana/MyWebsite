<?php defined( 'ABSPATH' ) or exit; ?>
<script type="text/javascript">
	jQuery( function( $ ) {
		$("#footer-thankyou").html("If you like <strong>WooCommPlugin</strong> please leave us a <a href='#'>★★★★★</a> rating. A huge thank you in advance!");
	});
</script>
<div class="wrap">
	<div class="icon32" id="icon-options-general"><br /></div>
	<h2><?php _e( 'Store Policies', 'woocommplugin' ); ?></h2>
	<h2 class="nav-tab-wrapper">
	<?php
	foreach ($settings_tabs as $tab_slug => $tab_title ) {
		$tab_link = esc_url("?page=woocommplugin_store_policies_submenu&tab={$tab_slug}");
		printf('<a href="%1$s" class="nav-tab nav-tab-%2$s %3$s">%4$s</a>', $tab_link, $tab_slug, (($active_tab == $tab_slug) ? 'nav-tab-active' : ''), $tab_title);
	}
	?>
	</h2>

	<?php
		do_action( 'woocommplugin_before_store_policies_page', $active_tab, $active_section );
	?>

	<form method="post" action="options.php" id="woocommplugin_store_policies" class="<?php echo "{$active_tab} {$active_section}"; ?>">
		<?php
			do_action( 'woocommplugin_before_store_policies', $active_tab, $active_section );
			if ( has_action( 'woocommplugin_store_policies_output_'.$active_tab ) ) {
				echo('Has action');
				do_action( 'woocommplugin_store_policies_output_'.$active_tab, $active_section );
			} else {
				echo('Does not have actionn'.'woocommplugin_store_policies_output_'.$active_tab.$active_section);
				// legacy settings
				settings_fields( "woocommplugin_{$active_tab}_store_policies" );
				do_settings_sections( "woocommplugin_{$active_tab}_store_policies" );

				submit_button();
			}
			do_action( 'woocommplugin_after_store_policies', $active_tab, $active_section );
		?>
	</form>	
	<?php do_action( 'woocommplugin_after_store_policies_page', $active_tab, $active_section ); ?>
</div>
