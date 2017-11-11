(function($){
    var _token = $('input[name=_token]').val();
    var id = $('input[name=id]').val();

    $('#manage_feeds').dataTable( {
        "order": [[ 0, "desc" ]],
        "pageLength": 50,
        "bPaginate": true,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false
    } );


    /**
     * Get the feed
     * @param feed_id
     * @param job
     * @param callback
     */
    $.fn.get_feed = function (feed_id,job,callback) {

        $.ajax({
            url:"/import.ajax_update_feed/"+feed_id+"/"+job,
            method:"GET",
            headers: {
                'X-CSRF-TOKEN': _token
            }
        }).done(function (data) {
            callback(data);
        }).fail(function () {
            console.log("failed");
        });
    };

    var pusher = new Pusher(config.pusher_key);
    var channel = pusher.subscribe('feed_imported');
    channel.bind('App\\Events\\FeedImported', function(data) {

        $.fn.get_feed(data.feed_id,false,function (feed_data) {
            if(data.success === true) {
                $("#updated_at_"+data.feed_id).html(feed_data.feed_updated);
                $('#import_state_'+data.feed_id).html('<span class="label label-info"> <i class="fa fa-check"></i> GEIMPORTEERD </span>');
            } else {
                $('#import_state_'+data.feed_id).html('<span class="label label-danger"> <i class="fa fa-error"></i> GEFAALD </span>');

            }

        })


    });

    $(document).on('click','.update_feed',function () {
        var feed_id = $(this).attr('data-feed_id');

        $.fn.get_feed(feed_id,true,function (data) {
            $('#import_state_'+feed_id).html('<span class="label label-primary"> <i class="fa fa-refresh fa-spin"></i> Importeren...</span>');
        })
    });


})(jQuery);





