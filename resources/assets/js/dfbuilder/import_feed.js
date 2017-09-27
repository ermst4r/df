(function($){
    var _token = $('input[name=_token]').val();
    var id = $('input[name=id]').val();




    /**
     * Handle the cancel when a user cancels. when importing the feed
     *
     */
    $( ".btn-import-cancel" ).click(function() {
        $('.format_selector').show();
        $('.url_parser').hide();
        $('.xml_advanced_settings_field').hide();
        $('.xml_advanced_settings').hide();
        $('input[name=feed_url]').val('');
        $('input[name=feed_name]').val('');
        $('input[name=xml_root_node]').val('');

    });

})(jQuery);





