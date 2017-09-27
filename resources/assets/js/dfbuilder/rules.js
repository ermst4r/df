/*
 *  This file is part of Dfbuilder.
 *
 *     Dfbuilder is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     Dfbuilder is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with Dfbuilder.  If not, see <http://www.gnu.org/licenses/>
 */

(function($){

    var _token =  $('meta[name="csrf-token"]').attr('content');
    var id =  $('input[name=id]').val();
    var preloaded_if_counter =  parseInt($('input[name=preloaded_if_counter]').val());
    var preloaded_then_counter =  parseInt($('input[name=preloaded_then_counter]').val());
    var multiple_spacing_ids = [];
    var $section = $('section');
    var channel_type_id = parseInt($('input[name=channel_type_id]').val());
    var channel_feed_id = parseInt($('input[name=channel_feed_id]').val());
    var adwords_feed_id = parseInt($('input[name=adwords_feed_id]').val());
    var bol_id = parseInt($('input[name=bol_id]').val());
    var feed_id = parseInt($('input[name=id]').val());
    var url_key = parseInt($('input[name=url_key]').val());


    /**
     * Preload the values from the custom rule...
     */
    var custom_controls = $.map(String($('input[name=custom_control_loader]').val()).split(","),function (value) {
        return value;
    });







    if($section.is('.feed_rules')) {

        $.fn.ajax_getrule_esfields(feed_id, function (data) {
            $.each(custom_controls,function (index,custom_control_id) {
                var $_custom_control = $('#' + custom_control_id);
                if($_custom_control.prop('tagName') !== 'SELECT') {
                    $_custom_control.customSelectIT({'availableTags': data});
                }

            });

        });


        var number_of_products = parseInt($('input[name=number_of_products]').val());


        /**
         * Show us the mapped rules..
         */
        $.fn.show_mapped_rules = function () {
            var url = "/rules.ajax_calculate_rules/"+id+"?channel_feed_id="+channel_feed_id+"&channel_type_id="+channel_type_id+"&url_key=1";
            if(url_key === 2) {
                url = "/rules.ajax_calculate_rules/"+id+"?adwords_feed_id="+adwords_feed_id+"&url_key=2";
            }
            if(url_key === 3) {
                url = "/rules.ajax_calculate_rules/"+id+"?bol_id="+bol_id+"&url_key=3";
            }

            $.ajax({
                url:url,
                method:"POST",
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            }).done(function (data) {
                $('.rules_progress')
                    .val(data.percent)
                    .trigger('change');

                $('.products-with-rules').html(data.rules_mapped + " producten met regels");
                $('.number_of_items_mapped').show();
                $('.loading_rule').hide();

            });
        };

        $.fn.show_mapped_rules();

        /**
         * Get pusher details
         * @type {Pusher}
         */
        var pusher = new Pusher(config.pusher_key, {
            encrypted: true
        });
        var channel = pusher.subscribe('rule_filter');
        channel.bind('App\\Events\\RuleFilterProcessed', function(data) {
           if(data.feed_id == id) {
                $.fn.show_mapped_rules();
           }
        });





        /**
         * Update sortable
         * @param order
         * @param rule_id
         * @param callback
         */
        $.fn.update_sortable = function (positions,rule_id,callback) {
            $.ajax({
                url:"/rules.ajax_save_draggable/"+id,
                method:"POST",
                data:"order="+positions+"&rule_id="+rule_id,
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            }).done(function (data) {
                callback(data);
            });

        };

        /**
         *
         */
        $('tbody').sortable({
            update: function(event, ui) {
            }, stop: function(event, ui) {
                $.map($(this).find('tr'), function(el) {
                    if(typeof $(el).attr('data-id') !== 'undefined' ) {
                        $.fn.update_sortable($(el).index(),$(el).attr('data-id'));
                    }

                });

            }
        });



        $(".rules_progress").knob({
            'format' : function (value) {
                return value + '%';
            }
        });

        /**
         * When a user selects a field, an
         * @type {Array}
         */
        var field_instantiated = [];
        $(document).on('click','.if_field_txt_field',function (e) {
            $(this).select();
            var data_if_identifier = $(this).attr('data-if_identifier');
            var selected_field =  $('select[name=if_field\\['+data_if_identifier+'\\]] option:selected').val();
            var selected_condition = parseInt($('select[name=exists_of_field\\['+data_if_identifier+'\\]] option:selected').val());
            if(selected_condition === conditionValueRules.is_equal_to || selected_condition === conditionValueRules.is_not_equal_to) {
                if(typeof field_instantiated[data_if_identifier] === 'undefined') {
                    $.fn.autocomplete(selected_condition,selected_field,id, '#phrase_'+data_if_identifier,data_if_identifier);
                    field_instantiated[data_if_identifier] = true;
                }
            }
        });


        $(document).on('click','.delete_rule',function (e) {
            var rule_id = parseInt($(this).attr('data-rule_id'));
            var url_key = parseInt($('input[name=url_key]').val());
            swal({
                    title: "Weet je het zeker?",
                    text: "Wilt u de regel verwijderen?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "OK",
                    closeOnConfirm: true,
                    confirmButtonColor: "#DD6B55"
                },
                function(){
                $('.number_of_items_mapped').hide();
                $('.loading_rule').show();
                var data = "rule_id="+rule_id+"&url_key="+url_key+"&channel_feed_id="+channel_feed_id+"&channel_type_id="+channel_type_id;

                if(url_key == 2) {
                    data = "rule_id="+rule_id+"&url_key="+url_key+"&adwords_feed_id="+adwords_feed_id;
                }

                if(url_key == 3) {
                    data = "rule_id="+rule_id+"&url_key="+url_key+"&bol_id="+bol_id;
                }

                    $.ajax({
                        url:"/rules.ajax_delete_rule/"+id,
                        method:"POST",
                        data:data,
                        headers: {
                            'X-CSRF-TOKEN': _token
                        }
                    }).done(function (data) {
                        $("#row_"+rule_id).hide();
                    });

                });

        });

        /**
         * Calculate number select value
         */

        $(document).on('change','.alter_calculate_number_rule',function (e) {
            var then_identifier = $(this).attr('data-then_identifier');
            var full_field_name = $(this).attr('data-full_field_name');

            var $_select_field =  $(".calculate_select_field_"+then_identifier);
            var $_normal_field =  $(".calculate_normal_field_"+then_identifier);
            var $_full_fieldname =  $('input[name='+full_field_name+']');

            switch($('option:selected', this).attr('data-field_type')) {

                case 'fieldname':
                    $_full_fieldname.val('fieldname');
                    $_select_field.show();
                    $_normal_field.hide();
                    break;

                case 'normal':
                    $_select_field.hide();
                    $_full_fieldname.val('normal');
                    $_normal_field.show();
                    break;
            }

        });


        /**
         * A global tease field helper for the rule section
         * @param num_of_items
         * @param input_field
         */
        $.fn.tease_field = function (num_of_items,input_field) {

            if(num_of_items === 0 ) {
                $(input_field).css('background-color','orange').attr('data-toggle','tooltip').attr('toggle','').attr('title','Let op. Met deze criteria zijn geen records gevonden').tooltip('fixTitle').tooltip('show');
            } else if(num_of_items === false) {
                $(input_field).removeClass('loading_txt_field');
            } else {
                $(input_field).css('background-color','#d5ffc7').attr('data-toggle','tooltip').attr('toggle','').attr('title','In de gehele feed zijn ' + num_of_items + " records gevonden. " ).tooltip('fixTitle').tooltip('show');
            }

        };



    }

    if($section.is('.create_rules')){

        $(".select2").select2();




        if(isNaN(preloaded_if_counter) || isNaN(preloaded_then_counter)) {
            preloaded_if_counter = 0;
            preloaded_then_counter = 0;
        }
        var if_counter = preloaded_if_counter;
        var then_counter = preloaded_then_counter;

        /**
         * What form type can we load?
         * @param value
         * @param type
         * @param conditional_identifier
         * @param callback
         */
        $.fn.ajax_form_type = function (value,type, conditional_identifier,callback) {
            $.ajax({
                url:"/rules.ajax_get_form_type/"+id,
                method:"POST",
                data:"rule_type="+value+"&type="+type+"&conditional_identifier="+conditional_identifier,
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            }).done(function (data) {
                callback(data);
            });
        };




        /**
         * Copy the if field
         */
        var new_parent;
        $(document).on('click','.copy-if-field',function (e) {
            $('.tooltip').tooltip("destroy");
            /**
             * While cloning..
             * recreate select2, otherwise err..
             */
            $(".if-condition-container").find(".select2").each(function(index)
            {
                if ($(this).data('select2')) {
                    $(this).select2('destroy');
                }
            });


            var $clone = $('.rules-if-field-container-'+if_counter).clone(true);

            $clone.find('.top_rules').remove();
            var inc_counter = if_counter + 1 ;
            var operator = $(this).attr('data-condition');
            var parent = $(this).attr('data-parent');


            if(operator === 'and') {
                var $_and_parent = $("#add_and");
                new_parent =  parseInt($_and_parent.attr('data-and_parent')) + 1;
                $_and_parent.attr('data-and_parent',new_parent);
                $clone.find('.append_or_'+if_counter).remove();
                $clone.removeClass('rules-if-field-container-'+if_counter).removeClass('box-success')
                    .addClass('rules-if-field-container-'+inc_counter +' box-warning').append('<div class="append_or_'+inc_counter+' top_rules"> </div>');
                $clone.find('h3').html('<i class="fa fa-fw fa-rocket"></i> EN conditie');
                $clone.find('input[name=if_operator\\['+if_counter+'\\]]').attr('name','if_operator\['+inc_counter+'\]').val(operator);
                parent = inc_counter;
                $clone.find('input[name=if_parent_child\\['+if_counter+'\\]]').attr('name','if_parent_child\['+inc_counter+'\]').val(new_parent);
                $clone.find('input[name=if_main_parent\\['+if_counter+'\\]]').attr('name','if_main_parent\['+inc_counter+'\]').val(new_parent);



            } else if (operator === 'or') {
                $clone.find('.append_or_'+parent).remove();
                $clone.removeClass('rules-if-field-container-'+if_counter).addClass('rules-if-field-container-'+inc_counter+ ' box-success');
                $clone.find('h3').html('<i class="fa fa-fw fa-dot-circle-o"></i> OF conditie');
                $clone.find('input[name=if_operator\\['+if_counter+'\\]]').attr('name','if_operator\['+inc_counter+'\]').val(operator);
                $clone.find('input[name=if_parent_child\\['+if_counter+'\\]]').attr('name','if_parent_child\['+inc_counter+'\]').val(parent);
                $clone.find('input[name=if_main_parent\\['+if_counter+'\\]]').attr('name','if_main_parent\['+inc_counter+'\]').val('');


            } else {
                $clone.removeClass('rules-if-field-container-'+if_counter).addClass('rules-if-field-container-'+inc_counter +' box-success');
                $clone.find('.box-title').html('<i class="fa fa-fw fa-dot-circle-o"></i> OF conditie');
            }

            $clone.find('.append_if_form_'+if_counter).toggleClass('append_if_form_'+if_counter+' append_if_form_'+inc_counter);
            $clone.find('.append_if_container_'+if_counter).toggleClass('append_if_container_'+if_counter+' append_if_container_'+inc_counter);
            $clone.find('.if-button').html(' ' +
                '<button type="button" class="btn btn-warning delete-if-field btn-xs" data-container="'+inc_counter+'" data-operator="'+operator+'" data-parent="'+parent+'">' +
                '<i class="fa fa-trash"></i> </button>  <button type="button" data-parent="'+parent+'" data-condition="or" class="btn bg-blue btn-xs copy-if-field">' +
                '<i class="fa fa-plus"></i>OF </button> <hr></div>');





            $clone.find('delete-if-field').attr('data-container',inc_counter);
            $clone.find('.if_field_'+if_counter).addClass('if_field_'+inc_counter).removeClass('if_field_'+if_counter);
            $clone.find('.if_condition_select').attr('data-if_identifier',inc_counter).val($('.if_condition_select option:first').val());
            $clone.find('.if_field').attr('data-if_identifier',inc_counter).val($('.if_field option:first').val());

            $clone.find('.append_if_form_'+if_counter).attr('data-if_identifier',inc_counter);
            $clone.find('.append_if_form_'+inc_counter).html('');
            $clone.find('.append_if_container_'+inc_counter).hide();
            /**
             * Change name fields
             */
            $clone.find('.if_field').attr('name','if_field['+inc_counter+']');
            $clone.find('.if_condition_select').attr('name','exists_of_field['+inc_counter+']');

            if(operator === 'or') {
                console.log(".append_or_"+parent);
                $clone.appendTo( ".append_or_"+parent);

            } else {
                $clone.appendTo( ".append-if-field" );
            }


            if_counter++;
            $('.select2').select2({
                placeholder: "Maak uw keuze"
            });

        });

        /**
         * Delete if field
         */
        $(document).on('click','.delete-if-field',function (e) {
            var data_container = $(this).attr('data-container');
            var data_operator = $(this).attr('data-operator');
            $('.append_or_'+data_container).remove();
            $('.rules-if-field-container-'+data_container).remove();
            if_counter--;

            if(data_operator === 'and') {
                if(isNaN(new_parent)) {
                    new_parent = parseInt($(this).attr('data-parent'));
                }
                new_parent --;
                if_counter = new_parent;
                $('.copy-if-field').attr('data-and_parent',new_parent);
            }


        });


        /**
         *
         */
        $(document).on('click','.delete-then-field',function (e) {
            var data_container = $(this).attr('data-container');
            $('.rules-and-field-container-'+data_container).remove();
            then_counter--;
        });


        /**
         * Create the text field values
         * @param id
         * @returns {string}
         */
        $.fn.append_space_items = function (id) {
            var items = '';
            $.each(multiple_spacing_ids[id],function(index,value) {
                items+= value+' ';
            });
            return items;
        };


        /**
         * When a user changes the field
         * We want to relisten
         */

        $(document).on('select2:select','.if_field',function (e) {
            var if_identifier = $(this).attr('data-if_identifier');

            if($(this).val() === 'all') {
                $("#add_and").hide();
                var $_exist_of_field = $('select[name=exists_of_field\\['+if_identifier+'\\]]');
                $_exist_of_field.prop('disabled',true);
                $_exist_of_field.val('');
                $('input[name=if_condition_field\\['+if_identifier+'\\]]').val('');

                $("#or_button").hide();
            } else {
                $("#add_and").show();
                $("#or_button").show();
                $('select[name=exists_of_field\\['+if_identifier+'\\]]').prop('disabled',false);
            }
            delete field_instantiated[if_identifier];

        });


        /**
         * Handle the whitespace listeners
         */
        $(document).on('select2:unselect','.space-listener',function (e) {
            multiple_spacing_ids[this.id] = $(this).val();
            $('input[name=space_'+this.id+']').val($.fn.append_space_items(this.id));
        });

        $(document).on('select2:select','.space-listener',function (e) {

            multiple_spacing_ids[this.id] = $(this).val();
            $('input[name=space_'+this.id+']').val($.fn.append_space_items(this.id));
        });

        $(document).on('click','.open_rule_spacing_settings',function (e) {
            $(".rules-spacing-div-"+$(this).attr('data-space_item')).toggle();
        });



        /**
         *
         */
        $(document).on('change','.if_condition_select',function (e) {
            var if_identifier = $(this).attr('data-if_identifier');


            $.fn.ajax_form_type(this.value,'if','',function (data) {
                var html = '';
                $('.tooltip').tooltip("destroy");
                var $_append_form = $('.append_if_form_'+if_identifier);
                $_append_form.html('').show();

                if(data.type === 'text') {
                    html = '<input autocomplete="off" data-toggle="tooltip" name="if_condition_field['+if_identifier+']"  data-placement="bottom" title="'+data.tooltip+'" type="text"  placeholder="'+data.placeholder+'" id="phrase_'+if_identifier+'"   data-if_identifier="'+if_identifier+'" class="form-control  field_listener text_if_'+if_identifier+'"">';
                    $('.append_if_container_'+if_identifier).show();
                }

                if(data.type === 'text no_listener') {
                    html = '<input autocomplete="off" data-toggle="tooltip" name="if_condition_field['+if_identifier+']"  data-placement="bottom" title="'+data.tooltip+'" type="text"  placeholder="'+data.placeholder+'" id="phrase_'+if_identifier+'"   data-if_identifier="'+if_identifier+'" class="form-control if_field_txt_field text_if_'+if_identifier+'"">';
                    $('.append_if_container_'+if_identifier).show();
                }

                if(data.type  === 'textarea') {
                    $('.append_if_container_'+if_identifier).show();
                    html = '<textarea class="form-control  textarea_if_'+if_identifier+'" name="if_condition_textarea['+if_identifier+']"  data-toggle="tooltip"  rows="5" data-placement="bottom" title="'+data.tooltip+'"   id="comment" data-if_identifier="'+if_identifier+'"></textarea>';
                    $_append_form.show();
                }

                if(data.type  === 'empty') {
                    $_append_form.hide();
                    $_append_form.html('');
                }

                $_append_form.html(html);


                var selected_field =  $('select[name=if_field\\['+if_identifier+'\\]] option:selected').val();
                var selected_condition = parseInt($('select[name=exists_of_field\\['+if_identifier+'\\]] option:selected').val());
                var phrase_selector = '#phrase_'+if_identifier;
                $.fn.autocomplete(selected_condition,selected_field,id,phrase_selector,if_identifier);


                /**
                 *
                 */
                switch(selected_condition) {

                    case conditionValueRules.range:
                    case conditionValueRules.gt:
                    case conditionValueRules.gte:
                    case conditionValueRules.lt:
                    case conditionValueRules.lte:
                        $.fn.notyMsg("Let op: De waardes van de veld moeten uit numerieke waardes bestaan.",notyMessageTypes.warning,2000,notyPositons.topRight);
                        break;
                }


            });
        });

        /**
         * Copy and field
         */
        $('.copy-and-field').on('click', function(e){


            $(".else-condition-container").find(".select2").each(function(index)  {
                if ($(this).data('select2')) {
                    $(this).select2('destroy');
                }
            });
            var then_inc_counter = then_counter + 1 ;
            var $clone = $('.rules-and-field-container-'+then_counter).clone(true);
            $clone.removeClass('rules-and-field-container-'+then_counter).addClass('rules-and-field-container-'+then_inc_counter);
            $clone.find('.then-button').html(' <button type="button" class="btn btn-warning delete-then-field btn-xs" data-container="'+then_inc_counter+'"><i class="fa fa-trash"></i> </button>');
            $clone.find('.append_then_form_'+then_counter).html('');
            $clone.find('.then_field').attr('name','then_field['+then_inc_counter+']').val($('.then_field option:first').val());;
            $clone.find('.then_condition_select').attr('name','then_action['+then_inc_counter+']');
            $clone.find('.append_then_form_'+then_counter).removeClass('append_then_form_'+then_counter).addClass('append_then_form_'+then_inc_counter);
            $clone.find('.then_condition_select').attr('data-then_identifier',then_inc_counter).val($('.then_condition_select option:first').val());
            $clone.appendTo( ".append-and-field" );
            $('.tooltip').tooltip("destroy");
            then_counter ++;
            $('.select2').select2({
                placeholder: "Maak uw keuze"
            });
        });


        /**
         * Then condition select
         */
        $(document).on('change','.then_condition_select',function (e) {
            var then_identifier = $(this).attr('data-then_identifier');
            $.fn.ajax_form_type(this.value,'then',then_identifier,function (data) {
                $('.append_then_form_'+then_identifier).html(data.html);
                $('.select2').select2({
                    placeholder: "Maak uw keuze"
                });

            });
        });






        /**
         * Tease the user's for the then statements...
         */
        $(document).on('blur','.rule_field_listener', function (e) {
            var item_number = $(this).attr('data-then_identifier');
            var other_field = $(this).attr('data-otherfield');
            if(typeof other_field === 'undefined') {
                var selected_field = $('select[name=then_field\\['+item_number+'\\]] option:selected').val();
            } else {
                var selected_field = $('select[name='+other_field+'] option:selected').val();

            }


            var input_field = 'input[name='+this.name+']';
            $(input_field).addClass('loading_txt_field');

            $.fn.ajax_teaser(conditionValueRules.contains,input_field,selected_field,id,function (data) {
                $(input_field).removeClass('loading_txt_field');
                $.fn.tease_field(data,input_field);
            });
        });


        /**
         * Relisten on the selected field for te if statements
         */
        $(document).on('blur','.field_listener',function (e) {

            var item_number = $(this).attr('data-if_identifier');
            var select_condition_value = parseInt($('select[name=exists_of_field\\['+item_number+'\\]] option:selected').val());
            var selected_field =  $('select[name=if_field\\['+item_number+'\\]] option:selected').val();
            var input_field;


            /**
             * Decide what selecter we pass through the teaser..
             */
            switch(select_condition_value) {
                case conditionValueRules.contains_multi:
                case conditionValueRules.equals_multi:
                case conditionValueRules.not_equals_multi:
                case conditionValueRules.not_contains_multi:
                    input_field = 'textarea[name=if_condition_textarea\\['+item_number+'\\]]';
                    break;
                default:
                    input_field = 'input[name=if_condition_field\\['+item_number+'\\]]';
            }

            /**
             * Tease the user
             */

            $(input_field).addClass('loading_txt_field');
            $.fn.ajax_teaser(select_condition_value,input_field,selected_field,id,function (data) {
                $(input_field).removeClass('loading_txt_field');
                $.fn.tease_field(data,input_field);
                $(input_field).tooltip('show');
            });


        });





    }


})(jQuery);






