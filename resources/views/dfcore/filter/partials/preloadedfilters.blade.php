<?php $item_number = 0;?>

@foreach($category_filter as $filters)
    <tr class="filter_{!! $filters->id !!}">
        <td class="col-md-2">
            <select class="form-control select2 selected_field" id="fields" disabled >
                @foreach($fields as $field)
                    <option value="{!! $field !!}" {{ $filters->field == $field ? 'selected' : '' }}  >{!! $field !!}</option>
                @endforeach
            </select>
        </td>


        <td class="col-md-2">
            <select class="form-control select2 condition_select " data-item_number="{!! $item_number !!}" disabled>
                <option  {{ $filters->category_condition == \App\DfCore\DfBs\Enum\ConditionSelector::CONTAIN  ? 'selected' : '' }} >{!! trans('messages.filter_categorize_lbl19') !!}</option>
                <option {{ $filters->category_condition == \App\DfCore\DfBs\Enum\ConditionSelector::NOT_CONTAIN  ? 'selected' : '' }}>{!! trans('messages.filter_categorize_lbl20') !!}</option>
                <option {{ $filters->category_condition == \App\DfCore\DfBs\Enum\ConditionSelector::EQUALS  ? 'selected' : '' }}>{!! trans('messages.filter_categorize_lbl21') !!}</option>
                <option {{ $filters->category_condition == \App\DfCore\DfBs\Enum\ConditionSelector::NOT_EQUALS  ? 'selected' : '' }}>{!! trans('messages.filter_categorize_lbl22') !!}</option>
                <option {{ $filters->category_condition == \App\DfCore\DfBs\Enum\ConditionSelector::IS_REGEXP  ? 'selected' : '' }}>{!! trans('messages.filter_categorize_lbl23') !!}</option>
                <option  {{ $filters->category_condition == \App\DfCore\DfBs\Enum\ConditionSelector::NOT_REGEXP  ? 'selected' : '' }}>{!! trans('messages.filter_categorize_lbl24') !!}</option>
            </select>
        </td>
        <td class="col-md-2">
            <input type="text" name="" value="{!! $filters->phrase !!}" class="form-control" placeholder="Voer een term in"  disabled >
        </td>
        <td class="col-md-2">

            <select class="form-control select2 " disabled>
                <option  value="">{!! trans('messages.filter_categorize_lbl25') !!}</option>
                @foreach($category as $categories)
                    <option {{ $filters->fk_category_id == $categories->id ? 'selected' : '' }}>{!! $categories->category_name !!}</option>
                @endforeach


            </select>
        </td>

        <td class="col-md-2 " align="left">
            <a href="" class="btn bg-red btn-md del_categorize"  style="margin-top:0px;" data-filter_id="{!! $filters->id !!}"><i class="fa fa-trash"></i> </a>
        </td>
    </tr>

    <?php $item_number ++ ;?>

@endforeach