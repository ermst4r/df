var _token =  $('meta[name="csrf-token"]').attr('content');
/**
 * Change the store
 * @param id
 */



(function($){

    $('#select2').select2({
    });
    $('.select2').select2();


    var $section = $('section');
    if($section.is('.logging')) {
        //Date picker
        $('#start_date').datepicker({
            autoclose: true,
            dateFormat: 'yy-mm-dd'
        });

        $('#end_date').datepicker({
            autoclose: true,
            dateFormat: 'yy-mm-dd'
        });



        $('#logging').dataTable( {
            "order": [[ 0, "desc" ]],
            "pageLength": 50,
            "bPaginate": true,
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": false,
            "bAutoWidth": false
        } );


    }




})(jQuery);
function change_store(id)
{
    swal({
        title: "Do you want to change store?",
        text: "The store will be changed.",
        type: "info",
        showCancelButton: true,
        confirmButtonText: "Yes",
        closeOnConfirm: false
    },
    function(){
        window.location= "/setdefaultstore."+id;
    });
}


function toggle_id(id)
{
    $("#"+id).toggle();
}

/**
 * Generate delete on confirm dialog
 * @param title
 * @param text
 * @param location
 */
function deleteconfirm(title,text,location)
{
    swal({
            title: title,
            text: text,
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "OK",
            closeOnConfirm: false,
            confirmButtonColor: "#DD6B55"
        },
        function(){
            window.location = location
        });
}



/**
 * Open the feed dialog when importing
 * @param type
 */
function openFeedDialog(type) {
    $(".url_parser").show();
    $(".format_selector").hide();
    $(".import_type").text(type);
    $('input[name=feed_type]').val(type);

    if(type == 'xml') {
        $('.xml_advanced_settings').show();
        $( ".xml_advanced_settings a" ).click(function() {
        $('.xml_advanced_settings_field').toggle();
        });
    }


}


/**
 *
 * @param selected_condition
 * @param item_number
 * @param selected_field
 * @param id
 * @param phrase_selector
 */
$.fn.autocomplete = function (selected_condition,selected_field,id,phrase_selector,item_number) {


    if(selected_condition == conditionValueRules.is_equal_to || selected_condition == conditionValueRules.is_not_equal_to) {

        var options = {
            url: function () {
                return "/teaser.ajax.autosuggest/"+id;
            },
            getValue: function (element) {
                return element.name;
            },
            list: {
                onClickEvent: function() {
                    $("#eac-container-phrase_"+item_number+" ul").hide();
                }
            },

            ajaxSettings: {
                dataType: "json",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': _token
                },
                data: {
                    dataType: "json"
                }
            },
            preparePostData: function (data) {
                data.phrase = encodeURIComponent($(phrase_selector).val());
                data.id = id;
                data.field = selected_field;
                return data;
            },
            requestDelay: 100
        };

        $(phrase_selector).easyAutocomplete(options);

    }
};

/**
 *
 * @param selected_condition
 * @param phrase_selector
 * @param selected_field
 * @param id
 * @param callback
 */
$.fn.ajax_teaser = function (selected_condition,phrase_selector,selected_field,id,callback) {


        $.ajax({
            url:"/teaser.ajax_categorize_teaser/"+id,
            method:"POST",
            data:"selected_condition="+selected_condition+"&phrase="+encodeURIComponent($(phrase_selector).val())+"&field="+selected_field,
            headers: {
                'X-CSRF-TOKEN': _token
            }
        }).done(function (data) {
            callback(data);
        }).fail(function () {
            console.log("fail");
            callback(false);
        });


};


/**
 *
 * @param feed_id
 * @param callback
 */
$.fn.ajax_getrule_esfields = function (feed_id,callback) {

    $.ajax({
        url:"/rules.ajax_getrule_esfields/"+feed_id,
        method:"GET",
        headers: {
            'X-CSRF-TOKEN': _token
        }
    }).done(function (data) {
        callback(data);
    }).fail(function () {
        callback(false);
    });

};

