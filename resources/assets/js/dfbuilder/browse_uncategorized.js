// (function($){
//     var _token =  $('meta[name="csrf-token"]').attr('content');
//     var id =  $('input[name="id"]').val();
//     var channel_type_id = parseInt($('input[name=channel_type_id]').val());
//     var channel_feed_id = parseInt($('input[name=channel_feed_id]').val());
//     $('#browse_uncategorized').DataTable(
//         {
//             "pageLength": 50,
//             "processing": false,
//             "serverSide": true,
//             "searchDelay": 600,
//
//             "ajax": {
//                 'beforeSend': function (request) {
//                     request.setRequestHeader("X-CSRF-TOKEN", _token);
//                 },
//                 "url": "/filter.ajax_browse_uncategorized/"+id+"?channel_feed_id="+channel_feed_id+"&channel_type_id="+channel_type_id,
//                 "type": "POST",
//                 "sType": "html"
//             },
//             "columns": [
//                 { "data": "product_name" },
//                 { "data": "price" },
//                 { "data": "image_url" },
//                 { "data": "product_url" }
//             ]
//         }
//     );
//
// })(jQuery);



(function($){
    var _token =  $('meta[name="csrf-token"]').attr('content');
    var id =  $('input[name="id"]').val();
    var channel_type_id = parseInt($('input[name=channel_type_id]').val());
    var channel_feed_id = parseInt($('input[name=channel_feed_id]').val());
    var url_key = parseInt($('input[name=url_key]').val());
    var bol_id = parseInt($('input[name=bol_id]').val());

    var json_query;
    /**
     * CHannel feed
     */
    if(url_key === 3){
        json_query = "/filter.ajax_browse_uncategorized/"+id+"?channel_feed_id="+channel_feed_id+"&channel_type_id="+channel_type_id+"&url_key="+url_key;
    }

    /**
     * BOL
     */
    if(url_key === 3){
        json_query = "/filter.ajax_browse_uncategorized/"+id+"?bol_id="+bol_id+"&url_key="+url_key;
    }

    $('#browse_uncategorized').DataTable(
        {
            "pageLength": 50,
            "processing": false,
            "serverSide": true,
            "searchDelay": 600,

            "ajax": {
                'beforeSend': function (request) {
                    request.setRequestHeader("X-CSRF-TOKEN", _token);
                },
                "url": json_query,
                "type": "POST",
                "sType": "html"
            },
            "columns": [
                { "data": "product_name" },
                { "data": "price" },
                { "data": "image_url" },
                { "data": "product_url" }
            ]
        }
    );

})(jQuery);

