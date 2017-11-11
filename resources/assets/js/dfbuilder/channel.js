
(function($){
    var _token =  $('meta[name="csrf-token"]').attr('content');

    var $section = $('section');


    /**
     * Format select2 styles.
     * @param selection
     * @returns {*}
     */
    function selectProductStyles(selection) {
        if (!selection.id) { return selection.text; }
        var thumb = $(selection.element).data('thumb');
        if(!thumb){
            return selection.text;
        } else {
            var $selection = $(
                '<img src="' + thumb + '" alt="" style="height: 32px; width: 32px;"><span class="img-changer-text">' + $(selection.element).text() + '</span>'
            );
            return $selection;
        }
    }


    /**
     * Select2 functions
     */
    $('.select_country').select2({
        placeholder: "Select your country"
    });

    $('#is_active').select2({
    });

    $('#channel_type').select2({
    });

    $('.channel_finalize').select2({
    });

    $(".select_channels").select2({
        placeholder: "Select a Channel",
        templateResult: selectProductStyles,
        templateSelection: selectProductStyles
    });



    if($section.is('.channel')) {
        $('.tooltip').tooltip();
        var id = $('input[name=fk_feed_id]').val();
        var number_of_items = parseInt($('input[name=number_of_items]').val());
        var country_id = $('input[name=default_country]').val();
        var selected_channel = parseInt($('input[name=selected_channel]').val());
        var channel_feed_id = parseInt($('input[name=fk_channel_feed_id]').val());
        var channel_type_id = parseInt($('input[name=channel_type_id]').val());


        /**
         * Add extra fields..
         */
        var extra_field_counter = 0;
        $(document).on('click','#add_extra_channel_field',function (e) {

            var $_channel = $("#channel_finalize");
            $_channel.find(".channel_finalize").each(function(index)  {
                if ($(this).data('select2')) {
                    $(this).select2('destroy');
                }
            });

            extra_field_counter ++ ;

            $.ajax({
                url:"/channel.ajax_get_field/"+id+"/"+channel_feed_id+"/"+channel_type_id+"/"+extra_field_counter,
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            }).done(function (data) {
                var new_items_counter  = number_of_items + 1;
                $('input[name=number_of_items]').val(new_items_counter);
                $("#channel_finalize tr:last").after(data);
                $('.channel_finalize').select2({});
            }).fail(function () {
            });

        });


        /**
         * If an user wants to delete the extra field...
         */
        $(document).on('click','.delete_extra_field',function () {
            var id = $(this).attr('data-id');
            $("#extra_row_"+id).remove();
        });











        /**
         *
         * @param channel_id
         * @param callback
         */
        $.fn.get_type_by_channel = function (channel_id,callback) {
            $.ajax({
                url:"/channel.ajax_get_channel_type/"+id,
                beforeSend: function( xhr ) {
                    $("#channel_next_button").prop('disabled',true);
                },
                data:"channel_id="+channel_id,
                method:"POST",
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            }).done(function (data) {
                $("#channel_next_button").prop('disabled',false);
                callback(data)
            }).fail(function () {
                callback(false);
            });


        };



        /**
         * Generic function for dropdown
         * @param data
         */
        $.fn.fill_dropdown = function (data,id,thumbs,selected_item) {

            var $select_channels = $(id);
            var is_selected  = '' ;
            if(selected_item === 0) {
                is_selected =  'selected';
            }
            data.forEach(function (item) {

                if(selected_item === item.id) {

                    is_selected = 'selected';
                }

                if(thumbs === true) {

                    $select_channels
                        .append($("<option "+is_selected+"></option>")
                            .attr("value",item.id)
                            .attr('data-thumb',item.channel_image)
                            .text(item.channel_name));
                } else {

                    $select_channels
                        .append($("<option "+is_selected+"></option>")
                            .attr("value",item.id)
                            .text(item.channel_type));
                }
                is_selected = '';

            });
        };




        /**
         * Get channels by the country id
         * @param country_id
         * @param callback
         */
        $.fn.get_channel = function (country_id,callback) {
            $.ajax({
                url:"/channel.ajax_get_channel/"+id,
                beforeSend: function( xhr ) {
                    $("#channel_next_button").prop('disabled',true);
                },
                data:"country_id="+country_id,
                method:"POST",
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            }).done(function (data) {
                $("#channel_next_button").prop('disabled',false);
                callback(data)

            }).fail(function () {
                callback(false);
            });


        };



        /**
         * On load, let load the default countries and set the channels..
         * And if a channel is set lets preload the selected items
         */
        $.fn.get_channel(country_id,function (data) {
            $.fn.fill_dropdown(data,'#select_channels',true,selected_channel);

            if(selected_channel === 0 ) {
                $.fn.get_type_by_channel( parseInt($("#select_channels option:selected").val()),function (data) {
                    $.fn.fill_dropdown(data,'#channel_type',false,channel_type_id);
                });
            }


        });


        /**
         * Only preload the type if a channel has been set.
         */
        if(selected_channel !== 0) {

            $.fn.get_type_by_channel(selected_channel,function (data) {

                $.fn.fill_dropdown(data,'#channel_type',false,channel_type_id);
            });

        }





        /**
         * Select country
         */
        $(document).on('select2:select','.select_country',function (e) {
            var country_id = $(this).find(':selected').data('id');
            $("#channel_type").children().remove();
            $("#select_channels").children('option:not(:first)').remove();

            // reset channels
            // and make the type default
            $.fn.get_channel(country_id,function (data) {
              $.fn.fill_dropdown(data,"#select_channels",true,0);
                var selected_channel = parseInt($("#select_channels option:selected").val());
                $.fn.get_type_by_channel(selected_channel,function (data) {
                    $.fn.fill_dropdown(data,'#channel_type',false,0);
                });
            });

        });


        /**
         * Select channel
         */
        $(document).on('select2:select','#select_channels',function (e) {
            var channel_id = $(this).find(':selected').val();
            $("#channel_type").children().remove();
            $.fn.get_type_by_channel(channel_id,function (data) {
                $.fn.fill_dropdown(data,'#channel_type',false,0);
            });

        });







    }





})(jQuery);




