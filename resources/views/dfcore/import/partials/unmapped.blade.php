<table class="table table-striped">
    <thead>
    <tr>
        <th>{!! trans('messages.import_mapping_lbl3') !!}</th>
        <th>{!! trans('messages.import_mapping_lbl4') !!}</th>

    </tr>
    </thead>
    <tbody id="map_table">

    @foreach($mapping as $field_value => $field_prefilled)
        <tr>
            <td class="map_field_{!! $counter !!}">{!! $field_value !!}
                <br>



                @if($field_prefilled != false)
                    <small class="label label-success"   data-placement="bottom"
                           data-original-title="{!! trans('messages.import_mapping_lbl9') !!}" data-toggle="tooltip">
                        <i class="fa fa-check"></i>

                    </small>
                @endif

            </td>
            <td>
                {!! Form::hidden('csvindex',$counter) !!}
                {!! Form::hidden('mapped_field_name'.$counter,$field_value) !!}

                <select class="form-control select2" name="mapped_field{!! $counter !!}" style="width: 100%">
                    <option value="">--SELECTEER VELD--</option>
                    @foreach($fields_to_map as $field)
                        <option {{ $field_prefilled ==$field->field ? 'selected' :''  }}
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
        <tr>
            <td> Extra veld</td>
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