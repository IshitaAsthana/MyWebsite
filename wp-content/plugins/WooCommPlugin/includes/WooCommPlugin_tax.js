jQuery(function($) {
    $('body').on('change', '.state_select', function() {
        var stateid = $(this).val();
        if(stateid != '') {

            var data = {
                'action': 'get_states_by_ajax',
                'state': stateid
            }
            $.post('../wp-content/plugins/WooCommPlugin/includes/WooCommPlugin_Tax_Modifier.php', {"state": stateid}, function () {
                // alert(stateid);
             });
            
 
            // alert(stateid);

            // $.post(blog.ajaxurl, data, function(response) {
            //      $('.load-state').html(response);
            // });
        }
        
        jQuery('body').trigger('update_checkout');  
    });
    // jQuery('body').trigger('update_checkout'); 
});