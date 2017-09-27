(function($){
    var _token = $('input[name=_token]').val();
    var id = $('input[name=id]').val();



    /**
     * Mapping section
     */
    if($('section').is('.mapping')){



        var file_saved = $('input[name=file_saved]').val();
        $(".select2").select2();
        var type = $('input[name=type]').val();
        if(file_saved == 0 ) {
            $.ajax({
                method:'POST',
                url:'/import.download_file',
                data:'id='+id+'&type='+type,
                headers: {
                    'X-CSRF-TOKEN':_token
                }
            }).done(function () {
                location.reload();
            })
        }


        $(document).on('click','.remove_custom_mapping',function (e) {
            var id = $(this).attr('data-id');
            $("#remove_custom_field_"+id).remove();

        });
        $(document).on('click','#add_extra_map_field',function (e) {
            var html = '<tr>\n' +
                '        <td> Extra veld</td>\n' +
                '        <td >\n' +
                '            <input type="text" class="form-control" name="extra_mapping_field[]">\n' +
                '        </td>\n' +
                '    </tr>';
            $("#map_table tr:last").after(html);

        });


    }





    /**
     * Let perform a mapvalidator on the form
     */
    jQuery("#mapForm").submit(function(e) {


        var number_of_fields = $('input[name=number_of_fields]').val();
        var has_composite_key  =  parseInt($('input[name=has_composite_key]').val());
        var selected_items = [];
        var product_id_found = false;
        for(var i = 0; i < number_of_fields; i++) {
            $('.map_field_'+i+'').css('color', 'black');
            var selected_item =  $( "select[name=mapped_field"+i+"] :selected").val();
            if(selected_item !='') {
                selected_items.push({name:selected_item,position:i});
            }
            if(selected_item == 'product_id' || has_composite_key == 1 ) {
                product_id_found = true;
            }
        }


        if(!product_id_found) {
            sweetAlert("Product id is mandatory", "A product id is mandatory to identify the products while updating...", "error");
            return false;
        }
        var duplicate_index = [];
        for(var z =0; z < selected_items.length; z++) {
            var occ = 0;
            for(var x = 0; x<selected_items.length; x++) {
                if(selected_items[z].name == selected_items[x].name) {
                    occ++;
                }
                if(occ > 1) {
                    duplicate_index.push(selected_items[z]);
                }
            }
        }
        if(duplicate_index.length >0) {
            for(var g = 0; g < duplicate_index.length; g++) {
                console.log('.map_field_'+duplicate_index[g].position+'');
                $('.map_field_'+duplicate_index[g].position+'').css('color', 'red');
            }
            e.preventDefault();
            sweetAlert("Oops...", "It look likes you made a mistake with the mapping. You cannot map duplicate fields", "error");

            return false;
        } else {
            return true;
        }
    });



})(jQuery);







