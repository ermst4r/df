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

var $section = $('section');



if($section.is('.preview_feed')) {


    var field = null;
    var term = null;
    var selected_condition = null;
    var container = document.getElementById('dfbuilder-preview_feed');
    var id =  $('input[name=id]').val();
    var row_headers =  $('input[name=row_headers]');
    var prefilled_fields =  $('input[name=prefilled_fields]').val();
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

    $(document).on('select2:select', '.selected_condition', function (e) {
        $('input[name=search_term]').attr('placeholder', 'Voor een term in');

        if (conditionValueRules.range == $(this).val()) {
            $('input[name=search_term]').attr('placeholder', 'Bijv: 50-100');
        }

    });

    $(document).on('click','.open_spreadsheet_field_selector',function (e) {
        $('.open_spreadsheet_field_filters').toggle();
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
        manualRowResize: true,
        startRows: 1,
        stretchH: 'all',
        cells: function (row, col, prop) {
            var cellProperties = {};
            cellProperties.colWidth = 100;

            return cellProperties;

        }
    });




    $.fn.loadHot = function (page, field, term, selected_condition) {
        $.ajax({
            url: "/ajax_preview_browse/" + id,
            method: "POST",
            data: "page=" + page + "&field=" + field + "&term=" + term + "&selected_condition=" + selected_condition+"&prefilled_fields="+prefilled_fields,
            dataType: "JSON",
            headers: {
                'X-CSRF-TOKEN': _token
            },
            beforeSend: function () {
                $('.spreadsheet-navigation').hide();
                $('#dfbuilder-preview_feed').hide();
                $('.loading-browse-feed').show();
                $('.search_term_spreadsheet').html('').hide();
                $('.number_of_spreadsheet_records').hide();

            }
        }).done(function (data) {

            var spreadsheet = $('#dfbuilder-preview_feed');
            var preloader = $('.loading-browse-feed');
            if (term) {
                $('.search_term_spreadsheet').html('<i class="fa fa-trash"></i>' + decodeURIComponent(term)).show();
            }

            if (data.num_of_items === 0) {

                $.fn.notyMsg("No Results found :(", notyMessageTypes.warning, 4000, notyPositons.top);
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

