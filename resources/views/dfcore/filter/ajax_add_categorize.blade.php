<tr class="tr_{!! $item_number !!}">
<td class="col-md-2">
    <select class="form-control select2 selected_field field_{!! $item_number !!}" id="fields"  data-item_number="{!! $item_number !!}">
        @foreach($fields as $field)
            <option value="{!! $field !!}">{!! $field !!}</option>
        @endforeach
    </select>
</td>



<td class="col-md-2">
    <select class="form-control select2 condition_select condition_item_{!! $item_number !!}" data-item_number="{!! $item_number !!}">
        <option  value="">{!! trans('messages.filter_categorize_lbl25') !!}</option>
        <option data-help="{!! trans("messages.filter_categorize_lbl6") !!}" value="{!! \App\DfCore\DfBs\Enum\ConditionSelector::CONTAIN !!}">{!! trans('messages.filter_categorize_lbl19') !!}</option>
        <option data-help="{!! trans("messages.filter_categorize_lbl7") !!}"  value="{!! \App\DfCore\DfBs\Enum\ConditionSelector::NOT_CONTAIN !!}">{!! trans('messages.filter_categorize_lbl20') !!}</option>
        <option data-help="{!! trans("messages.filter_categorize_lbl8") !!}"  value="{!! \App\DfCore\DfBs\Enum\ConditionSelector::EQUALS !!}">{!! trans('messages.filter_categorize_lbl21') !!}</option>
        <option data-help="{!! trans("messages.filter_categorize_lbl9") !!}"  value="{!! \App\DfCore\DfBs\Enum\ConditionSelector::NOT_EQUALS !!}">{!! trans('messages.filter_categorize_lbl22') !!}</option>
        <option data-help="{!! trans("messages.filter_categorize_lbl10") !!}"   value="{!! \App\DfCore\DfBs\Enum\ConditionSelector::IS_REGEXP !!}">{!! trans('messages.filter_categorize_lbl23') !!}</option>
        <option  data-help="{!! trans("messages.filter_categorize_lbl11") !!}"  value="{!! \App\DfCore\DfBs\Enum\ConditionSelector::NOT_REGEXP !!}">{!! trans('messages.filter_categorize_lbl24') !!}</option>
    </select>
</td>
<td class="col-md-2">
    <input type="text" value=""
           class="form-control  phrase_field tooltip_field_{!! $item_number !!} "
           name="phrase_{!! $item_number !!}"
           placeholder="Voer een term in"
           id="phrase_{!! $item_number !!}"
           data-item_number="{!! $item_number !!}" >


</td>
<td class="col-md-2">

    <select class="form-control to_category_{!! $item_number !!} to_category_field" data-item_number="{!! $item_number !!}">
        <option  value="">{!! trans('messages.filter_categorize_lbl25') !!}</option>

    </select>


</td>

<td class="col-md-2 button_bars_{!! $item_number !!}" align="left">
    <a href="" class="btn bg-olive btn-md save_categorize" data-item_number="{!! $item_number !!}" style="margin-top:0px;"><i class="fa fa-save"></i> </a>
    <a href="" class="btn bg-orange btn-md remove_unsaved_row unsaved_row_{!! $item_number !!}" data-item_number="{!! $item_number !!}" style="margin-top:0px;"><i class="fa fa-minus"></i> </a>
</td>
</tr>