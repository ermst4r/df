
(function($){
    var _token =  $('meta[name="csrf-token"]').attr('content');
    var id = $('input[name=id]').val();
    var categorize_click_counter = 0;
    var is_category_mapped = 0;
    var number_of_products = parseInt($('input[name=number_of_products]').val());
    var channel_type_id = parseInt($('input[name=channel_type_id]').val());
    var channel_feed_id = parseInt($('input[name=channel_feed_id]').val());
    var bol_id = parseInt($('input[name=bol_id]').val());
    var url_key = parseInt($('input[name=url_key]').val());


    $(document).on('focusout','.phrase_field',function (e) {
        var item_number = $(this).attr('data-item_number');
        $.fn.removeToolTipAttributes(item_number);


    });





    /**
     * Perform an select2 ajax call for the select field
     */
    $.fn.load_categories = function () {

        $(".to_category_field").select2({
            ajax: {
                headers: {
                    'X-CSRF-TOKEN': _token
                },
                url: "/filter.ajax_categories/"+id,
                method:"POST",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;

                    return {results:data};
                },
                cache: true
            },
            escapeMarkup: function (markup) {
                return markup;
            }, // let our custom formatter work
            minimumInputLength: 1
        });

    };

    /**
     * Remove the tooltip attributes
     * @param item_number
     */
    $.fn.removeToolTipAttributes = function (item_number) {
        $('.tooltip_field_'+item_number).tooltip("destroy");
        $("#phrase_"+item_number).css('background-color','white').
        removeAttr('data-toggle').
        removeAttr('toggle').
        removeAttr('title').
        removeAttr('data-original-title');
    };


    /**
     * Show us the tooltip
     * @param item_number
     */
    $.fn.show_tooltip = function (item_number) {

        var selected_condition = parseInt($('select.condition_item_'+item_number+'  option:selected').val());
        var selected_field = $(".field_"+item_number+" option:selected").val();
        JSON.stringify(selected_condition);
        if(selected_condition == 1 || selected_condition == 2  || selected_condition == 5 || selected_condition == 6) {
            $.fn.ajax_teaser(selected_condition,'input[name=phrase_'+item_number+']',selected_field, id, function (data) {
                var field_value = $("#phrase_"+item_number).val();
                if(data == 0 ) {
                    $("#phrase_"+item_number).css('background-color','orange').attr('data-toggle','tooltip').attr('toggle','').attr('title','Let op. Er zijn geen items gevonden in de feed voor de keyword ' + field_value ).removeClass('loading_txt_field');
                } else {
                    $("#phrase_"+item_number).css('background-color','#d5ffc7').attr('data-toggle','tooltip').attr('toggle','').attr('title','Er zijn  ' + data + " records gevonden met het criterium  " + field_value ).removeClass('loading_txt_field');
                }

                $('.tooltip_field_'+item_number).tooltip("show");
                $("#eac-container-phrase_"+item_number).show();

            });
        }
    };

    /**
     *
     */
    $(document).on('blur','.phrase_field',function (e) {
        var item_number = $(this).attr('data-item_number');
        var selected_condition = parseInt($('select.condition_item_'+item_number+'  option:selected').val());
        if($("#phrase_"+item_number).val() != '' && selected_condition == 1 || selected_condition == 2  || selected_condition == 5 || selected_condition == 6 ) {
            $("#phrase_"+item_number).addClass('loading_txt_field');
            $.fn.show_tooltip(item_number);
        }




    });


    /**
     * Save a filter
     * @param selected_condition
     * @param phrase
     * @param to_category
     * @param field
     * @param callback
     */
    $.fn.ajax_save_filter = function (selected_condition, phrase, to_category,field,callback) {

        var json_ajax;

        /**
         * Channel feed
         */
        if(url_key === 1) {
           json_ajax =  "selected_condition="+selected_condition+"&phrase="+encodeURIComponent(phrase)+'&to_category='+to_category+"" +
            "&field="+field+"&channel_feed_id="+channel_feed_id+"&channel_type_id="+channel_type_id+"&url_key="+url_key;
        }
        /**
         * Bol.com
         */
        if(url_key === 3) {
            json_ajax = "selected_condition="+selected_condition+"&phrase="+encodeURIComponent(phrase)+'&to_category='+to_category+"" +
                "&field="+field+"&bol_id="+bol_id+"&url_key="+url_key;
        }

        $.ajax({
            url:"/filter.ajax_save_category_filter/"+id,
            method:"POST",
            data:json_ajax,
            headers: {
                'X-CSRF-TOKEN': _token
            },
            beforeSend:function () {
                $('.df_preloader').show();
            },
            success:function (data) {
                $('.df_preloader').hide();
                $.fn.notyMsg("Categorie filter toegevoegd",notyMessageTypes.success,1500,notyPositons.top);
                callback(data);
            }
        });
    };



    /**
     * Calculate the unmapped
     * @param cat_mapped
     * @returns {string}
     */
    $.fn.calculate_map_percent = function (cat_mapped) {
        return parseFloat(cat_mapped / number_of_products * 100).toFixed(0);
    };



    $.fn.map_filter_progess = function () {

        var json_ajax;
        /**
         * Channel feed
         */
        if(url_key ===1) {
            json_ajax = "/filter.ajax_calculate_mapped/"+id+"?channel_feed_id="+channel_feed_id+"&channel_type_id="+channel_type_id+"&url_key="+url_key;
        }

        /**
         * Bol feed
         */
        if(url_key ===3) {
            json_ajax = "/filter.ajax_calculate_mapped/"+id+"?bol_id="+bol_id+"&url_key="+url_key;
        }


        $.ajax({
            url:json_ajax,
            method:"POST",
            headers: {
                'X-CSRF-TOKEN': _token
            }
        }).done(function (data) {
            $('.cat_progress_circle')
                .val(data.percent)
                .trigger('change');
            var uncategoriserd = Math.round(number_of_products - data.categorize_mapped);
            $('.badge-uncategorized').html(uncategoriserd + " niet gecategoriseerd");


            $('.number_of_items_mapped').show();
            $('.loading_categorize').hide();

        });

    };



    $(document).on('click','.remove_unsaved_row',function (e) {
        e.preventDefault();
        var item_number = $(this).attr('data-item_number');
        $(".tr_"+item_number).remove();
    });

    /**
     * Remove the category filter
     */

    $(document).on('click','.del_categorize',function (e) {
        e.preventDefault();
        $('.number_of_items_mapped').hide();
        $('.loading_categorize').show();
        var filter_id = $(this).attr('data-filter_id');
        $('.filter_'+filter_id).remove();
        var json_ajax;
        /**
         * Channel
         */
        if(url_key === 1) {
            json_ajax = "/filter.ajax_remove_filter/"+id+"?channel_feed_id="+channel_feed_id+"&channel_type_id="+channel_type_id+"&url_key="+url_key;
        }

        /**
         * Bol.com
         */
        if(url_key === 3) {
            json_ajax = "/filter.ajax_remove_filter/"+id+"?bol_id="+bol_id+"&url_key="+url_key;
        }

        $.ajax({
            url:json_ajax,
            method:"POST",
            data:"filter_id="+filter_id,
            headers: {
                'X-CSRF-TOKEN': _token
            }
        }).done(function () {
        });
    });



    /**
     * Save the category filter
     */
    $(document).on('click','.save_categorize',function (e) {
        e.preventDefault();
        $('.loading_categorize').show();
        $('.number_of_items_mapped').hide();

        var item_number = $(this).attr('data-item_number');
        var selected_condition = $('select.condition_item_'+item_number+' option:selected').val();
        var field = $('select.field_'+item_number+' option:selected').val();
        var phrase = $("#phrase_"+item_number).val();
        var to_category = $(".to_category_"+item_number).val();
        $('.unsaved_row_'+item_number).hide();

        if(!selected_condition || !phrase || !to_category) {
            sweetAlert({
                title:"Er is een fout opgetreden",
                text:"De veld: <B>Als veld, Conditie, Waarde en Naar Categorie</B> zijn verplichte velden!",
                type:"error",
                html:true
            });
        } else {
            $(this).hide();
            $("#eac-container-phrase_"+item_number).hide();
            $(".condition_item_"+item_number).prop('disabled',true);
            $("#phrase_"+item_number).prop('disabled',true).css('background-color','#eeeeee');
            $('.tooltip_field_'+item_number).tooltip("destroy");

            $(".to_category_"+item_number).prop('disabled',true);
            $(".field_"+item_number).prop('disabled',true);
            $.fn.ajax_save_filter(selected_condition,phrase,to_category,field,function (data) {
                $('.button_bars_'+item_number).html('<a href="javascript:void(0)"  data-filter_id="'+data.id+'" class="btn bg-red btn-md del_categorize" style="margin-top:0px;"><i class="fa fa-trash"></i> </a>');
                $('.tr_'+item_number).addClass('filter_'+data.id).removeClass('.tr_'+item_number);

                $.fn.teaser(id,item_number,function (result) {
                    is_category_mapped += result.hits.total;


                });
            });
        }
    });


    /**
     *
     */
    $(".cat_progress_circle").knob({
        'format' : function (value) {
            return value + '%';
        }
    });




    /**
     * Autocomplete teaser
     * @param item_number
     * @param callback
     */
    $.fn.autoCompleteTeaser = function (item_number,callback) {
        var selected_condition = $('select.condition_select option:selected').val();
        var selected_field = $('select.selected_field option:selected').val();
        $.fn.ajax_teaser(selected_condition,'input[name=phrase_'+item_number+']',selected_field, id,function (data) {
            callback(data);
        });
    };

    /**
     *
     * @param id
     * @param item_number
     * @param callback
     */
    $.fn.teaser = function (id,item_number,callback) {
        var selected_condition = $('select.condition_select option:selected').val();
        var selected_field = $('select.selected_field option:selected').val();

        $.fn.ajax_teaser(selected_condition,'input[name=phrase_'+item_number+']',selected_field, id,function (data) {
            callback(data);
        });


    };


    /**
     *
     * @param item_number
     * @param callback
     */
    $.fn.append_categorize_item = function (item_number,callback) {
        $.ajax({
            url:"/filter.ajax_add_categorize/"+id,
            method:"POST",
            data:"item_number="+item_number,
            headers: {
                'X-CSRF-TOKEN': _token
            }
        }).done(function (data) {
            $('.df_preloader').hide();


            callback(data);
        });
    };


    /**
     * When a user is adding a new categorize item, add the field
     */

    $(document).on('click','.addCategorizeItem',function (e) {

        categorize_click_counter ++;
        $.fn.append_categorize_item(categorize_click_counter,function (data) {
            $("#append_filter_item").after(data);
            $(".condition_select").select2();
            $(".selected_field").select2();
            $.fn.load_categories();

        });

    });

    /**
     * Categorize feed
     */
    if($('section').is('.categorize-feed')) {


        /**
         * Polling...
         * When the job processing is done, we can show the status again
         */
        var pusher = new Pusher(config.pusher_key, {
            encrypted: true
        });
        var channel = pusher.subscribe('categorize_filter');
        channel.bind('App\\Events\\CatFilterProcessed', function(data) {
            /**
             * Check if we  are on the same page :)
             */
            if(parseInt(data.feed_id) == parseInt(id)) {

                $.fn.map_filter_progess();
                setTimeout(function () {
                    $('.number_of_items_mapped').show();
                },1600);

            }
        });


        /**
         * Append categorize item
         */
        $.fn.append_categorize_item(categorize_click_counter,function (data) {
            $("#append_filter_item").after(data);
            $.fn.map_filter_progess();
            $(".condition_select").select2();
            $(".selected_field").select2();
            $.fn.load_categories();
        });


        /**
         * Relisten on condition select
         */
        $(document).on('change','.condition_select',function (event) {
            var item_number = $(this).attr('data-item_number');
            var selected_field = $( ".field_"+item_number+" option:selected" ).val();
            $.fn.autocomplete($(this).val(),selected_field,id,'input[name=phrase_'+item_number+']',item_number);
        });


        /**
         * Relisten on selected field
         */
        $(document).on('change','.selected_field',function (e) {
            var item_number = $(this).attr('data-item_number');
            var select_condition_value = $( ".condition_item_"+item_number+"  option:selected" ).val();
            var selected_field = $(this).val();
            $("input[name=phrase_"+item_number+"]").val('');
            $.fn.autocomplete(select_condition_value,selected_field,id,'input[name=phrase_'+item_number+']',item_number);
        });


        /**
         * Listen to category field
         */
        $(document).on('change','.to_category_field',function (e) {
            var item_number = $(this).attr('data-item_number');
            var select_condition_value = $( ".condition_item_"+item_number+"  option:selected" ).val();
            var selected_field = $( ".field_"+item_number+" option:selected" ).val();
            $.fn.autocomplete(select_condition_value,selected_field,id,'input[name=phrase_'+item_number+']',item_number);
        });











        /**
         * Show help message on dropdown..
         */

        $(document).on('change','.condition_select',function (e) {
            var help_text = $(this).find(':selected').attr('data-help');
            $.fn.notyMsg(help_text,notyMessageTypes.information,3000,notyPositons.topRight);

        });

    }

})(jQuery);




