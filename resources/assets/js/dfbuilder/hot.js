

var $section = $('section');



if($section.is('.preview_feed')) {
    var field = null;
    var term = null;
    var selected_condition = null;
    var container = document.getElementById('dfbuilder-preview_feed');
    var id =  $('input[name=id]').val();
    var row_headers =  $('input[name=row_headers]');
    var page = 1;
    /**
     * Navigation
     */
    $(document).on('click', '.spreadsheet-next', function () {
        page++;
        $.fn.loadHot(page, field, term, selected_condition);
    });

    $(document).on('click', '.spreadsheet-prev', function () {
        page--;
        $.fn.loadHot(page, field, term, selected_condition);
    });

}




if($section.is('.browse_spreadsheet')) {
    var field = null;
    var term = null;
    var selected_condition = null;
    var container = document.getElementById('dfbuilder-spreadsheet');
    var _token =  $('meta[name="csrf-token"]').attr('content');
    var id =  $('input[name=id]').val();
    var channel_feed_id =  parseInt($('input[name=channel_feed_id]').val());
    var channel_type_id =  parseInt($('input[name=channel_type_id]').val());
    var url_key =  parseInt($('input[name=url_key]').val());
    var row_headers =  $('input[name=row_headers]');
    var page = 1;



    /**
     * Get pusher details
     * @type {Pusher}
     */
    var pusher = new Pusher(config.pusher_key, {
        encrypted: true
    });
    var channel = pusher.subscribe('rules_updated');
    channel.bind('App\\Events\\RulesUpdated', function(data) {

        if(data.channel_feed_id === channel_feed_id) {
           window.location = '/spreadsheet.browse_feed/'+id+'?channel_feed_id='+channel_feed_id+'&channel_type_id='+channel_type_id+'&url_key=1';
        }
    });



    /**
     * Navigation
     */
    $(document).on('click', '.spreadsheet-next', function () {
        page++;
        $.fn.loadHot(page, field, term, selected_condition);
    });

    $(document).on('click', '.spreadsheet-prev', function () {
        page--;
        $.fn.loadHot(page, field, term, selected_condition);
    });


    /**
     * Ajax update revision..
     * @param jsonData
     * @param type
     */
    $.fn.ajax_revision = function (jsonData,type) {


        $.ajax({
            url: "/spreadsheet.ajax_revision/" + id + "?url_key="+url_key+"&channel_feed_id="+channel_feed_id+"&channel_type_id="+channel_type_id,
            method:"POST",
            data: 'data_object='+jsonData+'&type='+type,
            headers: {
                'X-CSRF-TOKEN': _token
            }
        }).done(function (data) {

        });

    };


    /**
     * Run the job
     * @param channel_feed_id
     * @param feed_id
     * @param fk_channel_type_id
     * @param callback
     */
    $.fn.ajax_run_job = function (channel_feed_id,feed_id,fk_channel_type_id,callback) {
        $.ajax({
            url: "/spreadsheet.run_job/" + id,
            method:"POST",
            data: 'channel_feed_id='+channel_feed_id+'&feed_id='+feed_id+'&channel_type_id='+fk_channel_type_id,
            headers: {
                'X-CSRF-TOKEN': _token
            }
        }).done(function (data) {
            callback(data);
        });

    };



    $(document).on('click','.open_spreadsheet_field_selector',function (e) {
        $('.open_spreadsheet_field_filters').toggle();
    });



    /**
     *
     */
    $(document).on('click','#import_feed',function (e) {
        $.fn.ajax_run_job(channel_feed_id,id,channel_type_id,function (res) {
            $('#import_feed').html(' <i class="fa fa-refresh fa-spin"></i> Importeren...');
        });

    });
    /**
     *
     */
    $(document).on('select2:select', '.selected_condition', function (e) {
        $('input[name=search_term]').attr('placeholder', 'Voor een term in');

        if (conditionValueRules.range == $(this).val()) {
            $('input[name=search_term]').attr('placeholder', 'Bijv: 50-100');
        }

    });

    /**
     * When a user searchs in the spreadsheet....
     */
    $(document).on('click', '.search_spreadsheet', function () {
        field = encodeURIComponent($('.selected_field option:selected').val());
        selected_condition = $('.selected_condition option:selected').val();
        term = encodeURIComponent($('input[name=search_term]').val());
        $.fn.loadHot(page, field, term, selected_condition);

    });


    /**
     * Remove the selecting tag from the spreadsheet.
     */
    $(document).on('click', '.search_term_spreadsheet', function () {
        $('input[name=search_term]').val('');
        page = 1;
        term = null;
        selected_condition = null;
        field = null;
        $.fn.loadHot(page);

    });


    var hot = new Handsontable(container, {
        columnSorting: true,
        rowHeaders: true,
        startRows: 1,
        stretchH: 'all',
        cells: function (row, col, prop) {
            var cellProperties = {};
            if (prop === 0) {
                cellProperties.readOnly = true;
            }
            return cellProperties;

        },

        afterChange: function (change, source) {
            if (source === 'loadData') {
                return;
            }

            var jsonData = [];
            var row_headers_array = $.map(String(row_headers.val()).split(","),function (value) {
                return value;
            });

            for (var i = 0; i < change.length; i++) {
                 jsonData.push( {
                     fk_feed_id:id,
                     fk_channel_feed_id:channel_feed_id,
                     fk_channel_type_id:channel_type_id,

                    // old_value: encodeURIComponent(change[i][2]),
                     revision_new_content: encodeURIComponent(change[i][3]),
                     generated_id: this.getDataAtCell(change[i][0], 0),
                     revision_field_name: row_headers_array[change[i][1]],
                     revision_type:1
                });
            }
            $.fn.ajax_revision(JSON.stringify(jsonData),1);
            $("#spreadsheet_refresh_button").addClass('btn-success').removeClass('btn-default').css('color','white').html('<i class="fa fa-save"> </i> Wijzigingen opslaan');
        },

        beforeRemoveRow: function (index, amount) {
            var sel = this.getSelected();
            var start_row = sel[0];
            var end_row = sel[2];
            var jsonData = [];
            if (end_row >= start_row) {
                for (var i = start_row; i <= end_row; i++) {
                    jsonData.push(this.getDataAtCell(i, 0));
                }
            } else {
                for (var x = end_row; x <= start_row; x++) {
                    jsonData.push(this.getDataAtCell(i, 0));
                }
            }

            $.fn.ajax_revision(JSON.stringify(jsonData),2);

        },
        contextMenu: ['remove_row']
    });


    $.fn.loadHot = function (page, field, term, selected_condition) {
        $.ajax({
            url: "/spreadsheet.ajax_browse_hot/" + id,
            method: "POST",
            data: "page=" + page + "&field=" + field + "&term=" + term + "&selected_condition=" + selected_condition +"&channel_feed_id="+channel_feed_id+"&channel_type_id="+channel_type_id,
            dataType: "JSON",
            headers: {
                'X-CSRF-TOKEN': _token
            },
            beforeSend: function () {
                $('.spreadsheet-navigation').hide();
                $('#dfbuilder-spreadsheet').hide();
                $('.loading-browse-feed').show();
                $('.search_term_spreadsheet').html('').hide();
                $('.number_of_spreadsheet_records').hide();

            }
        }).done(function (data) {

            var spreadsheet = $('#dfbuilder-spreadsheet');
            var preloader = $('.loading-browse-feed');
            if (term) {
                $('.search_term_spreadsheet').html('<i class="fa fa-trash"></i>' + decodeURIComponent(term)).show();
            }

            if (data.num_of_items === 0) {

                $.fn.notyMsg("Er zijn geen resultaten gevonden. Je hebt geen regels opgegeven, of je bent vergeten om stap 4 uit te voeren.", notyMessageTypes.warning, 4000, notyPositons.top);
                spreadsheet.hide();
                preloader.hide();
                return;
            }
            $('.spreadsheet-navigation').show();
            spreadsheet.show();
            preloader.hide();
            $('.number_of_spreadsheet_records').html(data.num_of_items + ' records').show();
            hot.loadData(data.data);
            hot.updateSettings({
                colHeaders: data.field_names
            });
            if(row_headers.val() ==='') {
                row_headers.val(data.field_names);
            }


            if (!data.show_prev) {
                $('.spreadsheet-prev').hide();
            } else {
                $('.spreadsheet-prev').show();
            }

            if (!data.show_next) {
                $('.spreadsheet-next').hide();
            } else {
                $('.spreadsheet-next').show();
            }


        });
    };


    $.fn.loadHot(1);
}