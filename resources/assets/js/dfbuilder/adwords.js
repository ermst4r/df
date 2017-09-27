

function negativeValueRendererAdwords(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.renderers.TextRenderer.apply(this, arguments);
    var orginvalue = String(value);


    if (col === 2 || col === 1) {
        // add class "negative"
        if(orginvalue.length > 30) {
            td.style.fontWeight = 'normal';
            td.style.color = 'red';
        }

    }

    if(col === 3) {
        if(orginvalue.length > 80) {
            td.style.fontWeight = 'normal';
            td.style.color = 'red';
        }
    }


    if(col === 4 || col === 5) {
        if(orginvalue.length > 15) {
            td.style.fontWeight = 'normal';
            td.style.color = 'red';
        }
    }

}




(function($){
    var _token =  $('meta[name="csrf-token"]').attr('content');
    var $section = $('section');
    var feed_id = $('input[name=feed_id]').val();
    var fk_adwords_feed_id = parseInt($('input[name=fk_adwords_feed_id]').val());

    var fk_adgroup_preview_id = parseInt($('input[name=fk_adgroup_preview_id]').val());
    var fk_campaigns_preview_id = parseInt($('input[name=fk_campaigns_preview_id]').val());


    var number_of_ads = parseInt($('input[name=number_of_ads]').val());
    var number_of_keywords = parseInt($('input[name=number_of_keywords]').val());
    var live = parseInt($('input[name=live]').val());


    var number_of_neg_keywords = parseInt($('input[name=number_of_neg_keywords]').val());
    var backup_ad = parseInt($('input[name=backup_ad]').val());
    var container = document.getElementById('dfbuilder-adwords-spreadsheet');
    var row_headers = 'id,headline_1,headline_2,description,path_1,path_2,final_url';






    /**
     * Ajax update revision..
     * @param jsonData
     * @param type
     */
    $.fn.ajax_adwords_revision = function (jsonData,type) {

        $.ajax({
            url: "/ajax_adwords_revision",
            method:"POST",
            data: 'data_object='+JSON.stringify(jsonData)+'&type='+type+"&fk_adwords_feed_id="+fk_adwords_feed_id,
            headers: {
                'X-CSRF-TOKEN': _token
            }
        }).done(function (data) {
            console.log("done..");
        });

    };



    $.fn.ajax_get_campaigns = function (callback) {

        $.ajax({
            url: "/ajax_get_campaigns",
            method:"POST",
            data:'fk_adwords_feed_id='+fk_adwords_feed_id,
            headers: {
                'X-CSRF-TOKEN': _token
            },
            beforeSend: function () {
                $("#adwords_preloader").show();
            }

        }).done(function (data) {
            callback(data);
        }).fail(function (data) {
        });

    };



    /**
     *
     */
    $.fn.loadAdwordsHot = function () {
        $.ajax({
            url: "/ajax_adwords_spreadsheet_hot",
            method: "POST",
            data: "fk_adwords_feed_id="+fk_adwords_feed_id+"&fk_campaigns_preview_id="+fk_campaigns_preview_id+"&fk_adgroup_preview_id="+fk_adgroup_preview_id,
            dataType: "JSON",
            headers: {
                'X-CSRF-TOKEN': _token
            },
            beforeSend: function () {

            }
        }).done(function (data) {
            if (data.num_of_items === 0) {
                return;
            }
            adwords_hot.loadData(data.data);
            adwords_hot.updateSettings({
                colHeaders: data.field_names
            });


        });
    };



    /**
     * Show preview msg when job has been completed
     * @param reload
     */
    $.fn.show_preview_msg = function (reload) {
        var pusher = new Pusher(config.pusher_key, {
            encrypted: true
        });
        var channel = pusher.subscribe('adwords_preview_created');
        channel.bind('App\\Events\\AdwordsPreviewCreated', function(data) {
            if(data.fk_adwords_feed_id == fk_adwords_feed_id) {
                if(reload) {
                    $.fn.notyMsg("Succes! De taak is succesvol afgerond. De pagina wordt opnieuw geladen ", notyMessageTypes.success, 5000, notyPositons.topRight);
                    setTimeout(function () { location.reload(true); }, 2000);
                } else {
                    $.fn.notyMsg("Succes! De taak is succesvol afgerond. ", notyMessageTypes.success, 2000, notyPositons.topRight);
                }




            }
        });

    };

    if($section.is('.adwords_spreadsheet') || $section.is('.adwords_preview_products')) {

        Handsontable.renderers.registerRenderer('negativeValueRendererAdwords', negativeValueRendererAdwords);

        var adwords_hot = new Handsontable(container, {
            columnSorting: true,
            rowHeaders: true,
            startRows: 1,
            stretchH: 'all',
            cells: function (row, col, prop) {
                var cellProperties = {};
                if (prop === 0) {
                    cellProperties.readOnly = true;
                }
                cellProperties.renderer = "negativeValueRendererAdwords"; // uses lookup map
                return cellProperties;

            },

            afterChange: function (change, source) {
                var row_headers_array = $.map(String(row_headers).split(","),function (value) {
                    return value;
                });

                if (source === 'loadData') {
                    return;
                }
                var jsonData = [];



                for (var i = 0; i < change.length; i++) {
                    jsonData.push( {
                        revision_new_content: encodeURIComponent(change[i][3]),
                        fk_ads_preview_id: this.getDataAtCell(change[i][0], 0),
                        revision_field_name: row_headers_array[change[i][1]],
                        revision_type:1,
                        fk_adwords_feed_id:fk_adwords_feed_id,
                    });


                }


                $.fn.ajax_adwords_revision(jsonData,1);


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
                        jsonData.push(this.getDataAtCell(x, 0));
                    }
                }
                $.fn.ajax_adwords_revision(jsonData,2);

            },
            contextMenu: ['remove_row']
        });

        $.fn.loadAdwordsHot();




    }

    if($section.is('.adwords_preview')) {

        $.fn.show_preview_msg(true);
        $(".clickable-row").click(function() {
            window.location = $(this).data("href");
        });

        $('#campaign_preview').dataTable( {
            "order": [[ 2, "desc" ]],
            "pageLength": 50,
            "bPaginate": true,
            "bFilter": true,
            "bInfo": true,
            "bAutoWidth": false
        } );

    }








    if($section.is('.adwords_settings')) {

        $.fn.show_preview_msg();

        var existing_campaign = parseInt($('select[name=existing_campaign] option:selected').val());
        var campaign_adwords_id = parseInt($('input[name=campaign_adwords_id]').val());

        if(existing_campaign === 2) {
            $("#adwords_preloader").show();
            $("#campaign_name_wrapper").hide();
            $.fn.ajax_get_campaigns(function (data) {
                $("#prefilled_campaigns").append('<option value="">--Please select your campaign--</option>');
                $.each(data,function(index,value){
                    if(campaign_adwords_id == index ) {
                        $("#prefilled_campaigns").append('<option value="'+index+'" selected>' + value + '</option>');
                    } else {
                        $("#prefilled_campaigns").append('<option value="'+index+'">' + value + '</option>');
                    }

                });
                $("#select_campaign_wrapper").show();
                $("#adwords_preloader").hide();
            });

        } else {
            $("#campaign_name_wrapper").show();
        }



        /**
         * When user changes to an existing account
         */
        $(document).on('change','#existing_campaign',function () {

            /**
             * Existing campaign
             */

            if(parseInt($(this).val()) === 2) {
                $("#campaign_name_wrapper").hide();

                $.fn.ajax_get_campaigns(function (data) {
                    $("#prefilled_campaigns").append('<option value="">--Please select your campaign--</option>');
                    $.each(data,function(index,value){
                        $("#prefilled_campaigns").append('<option value="'+index+'">' + value + '</option>');
                    });
                    $("#select_campaign_wrapper").show();
                    $("#adwords_preloader").hide();
                });
            } else {

                /**
                 * Not an existing campaign
                 */
                $("#campaign_name_wrapper").show();
                $("#select_campaign_wrapper").hide();
                $('#prefilled_campaigns')
                    .find('option')
                    .remove()
                    .end();


            }
        });



        /**
         * Basic validator
         * @type {Validator}
         */
        if(!$section.is('.backup_ads')) {
            if(live === 0 ) {
                var frmvalidator  = new Validator("post_adwords_settings");
                frmvalidator.addValidation("adgroup_name","req","Please enter the adgroup name");
                frmvalidator.addValidation("cpc","req","Please enter the cpc");
                frmvalidator.addValidation("cpc","numeric","cpc must be a number");
                frmvalidator.addValidation("daily_budget","numeric","Daily budget must be a number");
                frmvalidator.addValidation("daily_budget","req","Please enter the bid per day");

            }

        }


        /**
         * Remove adwords options
         * @param type
         * @param id
         * @param callback
         */
        $.fn.remove_adwords_options = function (type,id,backup_ad,callback) {
            $.ajax({
                url:"/ajax_remove_adwords_items",
                method:"POST",
                data:"type="+type+"&id="+id+'&backup_ad='+backup_ad,
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            }).done(function (data) {
                callback(data);
            }).fail(function (xhr, status, error) {

            });
        };



        /**
         *
         * @param callback
         */
        $.fn.get_es_fields = function (callback) {
            $.ajax({
                url:"/adwords_es_fields",
                method:"POST",
                data:"feed_id="+feed_id,
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            }).done(function (data) {
                callback(data);
            }).fail(function (xhr, status, error) {

            });
        };


        /**
         *
         * @param data
         * @param i
         */
        $.fn.ad_incrementor = function (data,i) {
            var $headline_1 =  $('#headline_1_'+i);
            var $headline_2 =  $('#headline_2_'+i);
            var $description =  $('#description_'+i);
            var $path_1 =  $('#path_1_'+i);
            var $path_2 =  $('#path_2_'+i);
            var $final_url = $('#final_url_'+i);

            $headline_1.customSelectIT({'availableTags':data});
            $headline_2.customSelectIT({'availableTags':data});
            $description.customSelectIT({'availableTags':data});
            $path_1.customSelectIT({'availableTags':data});
            $path_2.customSelectIT({'availableTags':data});
            $final_url.customSelectIT({'availableTags':data});

            $headline_1.change(function(){
                var item_id = $(this).attr('data-item_id');
                $("#ad_title_1_"+item_id).html($(this).val());
            });

            $headline_2.change(function(){
                var item_id = $(this).attr('data-item_id');
                $("#ad_title_2_"+item_id).html($(this).val());
            });


            $description.change(function(){
                var item_id = $(this).attr('data-item_id');
                $("#ad_description_"+item_id).html($(this).val());
            });


            $path_1.change(function(){
                var item_id = $(this).attr('data-item_id');
                $("#ad_path_1"+item_id).html("/"+$(this).val());
            });


            $path_2.change(function(){
                var item_id = $(this).attr('data-item_id');
                $("#ad_path_2"+item_id).html("/"+$(this).val());
            });
        };



        /**
         * Preload our groups
         */
        $.fn.get_es_fields(function (data) {
            $('#adgroup_name').customSelectIT({'availableTags':data});
            $('#campaign_name').customSelectIT({'availableTags':data});


            for (var i = 1; i <= number_of_ads; i ++ ) {
               $.fn.ad_incrementor(data,i);

            }

            /**
             * Preload positie keywords with custom select
             */

            for(var x = 1; x <= number_of_keywords; x++) {
                $('#pos_keyword_'+x).customSelectIT({'availableTags':data});
            }
            for(var y = 1; y <= number_of_neg_keywords; y++) {

                $('#keyword_neg'+y).customSelectIT({'availableTags':data});
            }


        });


        $(".adwords_country_select2").select2({
        });

        $(".adwords_language_select2").select2({
        });

        if(number_of_keywords === 0) {
            $("#no_results_keywords").show();
        }

        if(number_of_neg_keywords === 0 ) {
            $("#no_results_neg_keywords").show();
        }
        /**
         * Keywords add
         */
        $(document).on('click','.adwords_new_keyword',function (e) {
            number_of_keywords ++;
            $("#no_results_keywords").hide();
            $.fn.ajax_get_ad_template('ajax_get_keywords',number_of_keywords,function (data) {
                $('#append_keywords').append(data);

                $.fn.get_es_fields(function (data) {
                    $('#pos_keyword_'+number_of_keywords).customSelectIT({'availableTags':data});
                });


            });
        });


        /**
         * Add negative keyword
         */
        $(document).on('click','.adwords_negative_keyword',function (e) {
            number_of_neg_keywords ++;
            $("#no_results_neg_keywords").hide();
            $.fn.ajax_get_ad_template('ajax_keywords_negative',number_of_neg_keywords,function (data) {
                $('#append_neg_keywords').append(data);

                $.fn.get_es_fields(function (data) {
                    $('#keyword_neg'+number_of_neg_keywords).customSelectIT({'availableTags':data});
                });


            });
        });


        /**
         * Remove negative keyword
         */
        $(document).on('click','.remove_negative_keyword',function (e) {
            var keyword_item_id = $(this).attr('data-keyword_item_id');
            var keyword_id = parseInt($('input[name=keyword_id_negative\\['+keyword_item_id+'\\]').val());
            $("#keyword_container_negative_"+keyword_item_id).remove();

            if(number_of_neg_keywords === 0) {
                $("#no_results_neg_keywords").show();
            }
            number_of_neg_keywords --;
            $.fn.remove_adwords_options('neg_keyword',keyword_id,backup_ad,function () {

            })

        });



        /**
         * Keyword remove
         */
        $(document).on('click','.remove_keyword',function (e) {
            var keyword_item_id = $(this).attr('data-keyword_item_id');
            var keyword_id = parseInt($('input[name=keyword_id\\['+keyword_item_id+'\\]').val());
            $("#keyword_container_"+keyword_item_id).remove();
            number_of_keywords --;
            if(number_of_keywords === 0) {
                $("#no_results_keywords").show()
            }

            $.fn.remove_adwords_options('keyword',keyword_id,backup_ad,function () {

            })

        });


        $(document).on('click','.delete_ad',function (e) {
            var item_id = $(this).attr('data-item_id');
            var adwords_id = parseInt($('input[name=adwords_ad_id\\['+item_id+'\\]').val());
            $("#adwords_template_"+item_id).remove();
            number_of_ads--;
            $.fn.remove_adwords_options('ad',adwords_id,backup_ad,function () {

            })
        });




        /**
         * add a new adwords template
         */
        $(document).on('click','.add_template',function (e) {
            number_of_ads ++;
            $.fn.ajax_get_ad_template('ajax_get_words_template',number_of_ads,function (data) {
            $('.append_ads').append(data);
                $.fn.get_es_fields(function (data) {
                    $.fn.ad_incrementor(data,number_of_ads);

                });
            });
        });


        /**
         * Adwords kop 1
         */
        $(document).on('keyup','.adwords_kop_1',function () {
           var item_id = $(this).attr('data-item_id');
           $("#ad_title_1_"+item_id).html($(this).val());

        });

        /**
         * Adwords Kop 2
         */
        $(document).on('keyup','.adwords_kop_2',function () {
            var item_id = $(this).attr('data-item_id');

            $("#ad_title_2_"+item_id).html($(this).val());

        });


        /**
         * Adwords description
         */
        $(document).on('keyup','.adwords_description',function () {
            var item_id = $(this).attr('data-item_id');
            $("#ad_description_"+item_id).html($(this).val());

        });


        /**
         * Pad 1
         */
        $(document).on('keyup','.ad_pad_1',function () {
            var item_id = $(this).attr('data-item_id');
            console.log("S");
            $("#ad_path_1"+item_id).html("/"+$(this).val());

        });



        /**
         * Ad pad 2
         */

        $(document).on('keyup','.ad_pad_2',function () {
            var item_id = $(this).attr('data-item_id');
            $("#ad_path_2"+item_id).html("/"+$(this).val());

        });




        /**
         * Get ad template
         * @param callback
         */

        $.fn.ajax_get_ad_template = function (uri,identifier,callback) {
            $.ajax({
                url:"/"+uri+"/"+feed_id,
                method:"POST",
                data:"item_id="+identifier,
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            }).done(function (data) {
                callback(data);
            }).fail(function (xhr, status, error) {

            });
        };






    }

})(jQuery);

