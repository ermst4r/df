
    <tr id="extra_row_{!! $extra_field_counter !!}">
        <td>

            <select class="form-control channel_finalize" name="extra_map_to_field[]" >
                <option value=""> Maak uw keuze </option>
                @foreach($fields as $field)
                    <option value="{!! $field !!}">
                        {!! $field !!}
                    </option>
                @endforeach
            </select>


        </td>
        <td>



            <div class="row">
                <div class="col-md-10">  <input type="text" class="form-control" name="extra_field_name[]" id="extra_field_{!! $extra_field_counter !!}"> </div>
                <div class="col-md-2"> <button  type="button"  class="btn btn-block btn-default delete_extra_field" data-id="{!! $extra_field_counter !!}">  <i class="fa fa-trash"></i> </button> </div>

            </div>



        </td>
        <td>



                <a  data-original-title="{!! trans('messages.channel_finalize_lbl6') !!}" data-toggle="tooltip" href="javascript:void(0);"  title="Dit veld is optioneel, maar wel aangeraden" style="cursor: help;"  class="btn btn-block btn-primary">
                    <i class="fa fa-fw fa-circle"></i>{!! trans('messages.channel_finalize_lbl4') !!}</a>


        </td>
    </tr>