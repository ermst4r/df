<table class="table table-striped">
    <thead>
    <tr>
        <th>{!! trans('messages.import_mapping_lbl3') !!}</th>
        <th>{!! trans('messages.import_mapping_lbl4') !!}</th>

    </tr>
    </thead>
    <tbody id="map_table">
    @foreach($mapping as $field_value => $field_prefilled)
        <tr id="row{!! $counter !!}">
            <td class="map_field_{!! $counter !!}">


                {!! $field_value !!}



                <br>
            </td>
            <td>
                {!! Form::hidden('csvindex',$counter) !!}
                {!! Form::hidden('mapped_field_name'.$counter,$field_value) !!}
                <select class="form-control select2" name="mapped_field{!! $counter !!}" style="width: 100%">
                    <option value="">--SELECTEER VELD--</option>
                    @foreach($fields_to_map as $field)


                        @if($has_composite_key && $field->field  == DFBUILDER_MAIN_ID_FIELD )
                            @continue
                        @endif
                            <option {{ isset($plain_mapped[\App\DfCore\DfBs\Import\Mapping\MappingValidator::formatMapping($field_value)][$field->field]) ? 'selected' :''  }}
                                    value="{!! $field->field !!}">
                                {!! $field->field !!}
                            </option>

                    @endforeach
                </select>
            </td>
        </tr>
        <?php $counter ++;?>

    @endforeach


    @foreach($custom_mappings as $custom)
        <tr id="remove_custom_field_{!! $custom->id !!}">
            <td> Extra veld <a href="javascript:void(0);" data-id="{!! $custom->id !!}" class="remove_custom_mapping"><i class="fa fa-trash"></i> </a> </td>
            <td >
                <input type="text" class="form-control" name="extra_mapping_field[]" value="{!! $custom->custom_name !!}">
            </td>
        </tr>

    @endforeach

    <tr>
        <td> Extra veld</td>
        <td >
            <input type="text" class="form-control" name="extra_mapping_field[]">
        </td>
    </tr>



    {!! Form::hidden('number_of_fields',$counter) !!}
    </tbody>
</table>


<table>
    <tr>
        <td colspan="2">
            <button type="button" id="add_extra_map_field" class="btn  btn-default pull-right">
                <i class="fa fa-plus"></i>
                {!! trans('messages.channel_finalize_lbl11') !!}
            </button>
        </td>
    </tr>

</table>