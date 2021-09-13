jQuery(function($) {
    $('body').on('change', '.state_select', function() {
        var countryid = $(this).val();
        if(countryid != '') {
            var data = {
                'action': 'get_states_by_ajax',
                'country': countryid,
                'security': blog.security
            }
 
            alert(countryid);
            // $.post(blog.ajaxurl, data, function(response) {
            //      $('.load-state').html(response);
            // });
        }
    });
});