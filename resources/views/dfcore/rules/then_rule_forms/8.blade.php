
<?php

$selected_field = (isset($then_field_values[0]) ? $then_field_values[0] : '');
$seperator = (isset($then_field_values[1]) ? $then_field_values[1] : '');
$from_rule = (isset($then_field_values[2]) ? $then_field_values[2] : '');
$to_rule = (isset($then_field_values[3]) ? $then_field_values[3] : '');
$merge_together = (isset($then_field_values[4]) ? $then_field_values[4] : '');

?>
<div class="col-lg-8">


    <div class="form-group">





        <label>{!! trans('messages.rules_then_conditon_20') !!}</label>
        <select class="form-control select2"
                name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_SPLIT_FIELD !!}_{!! $conditional_identifier !!}_0"
                data-then_identifier="{!! $conditional_identifier !!}"
                style="width: 100%;">
            @foreach($field_names as $field)
                <option {{ $selected_field == $field ? 'selected' : ''  }}> {!! $field !!}</option>
            @endforeach


        </select>

    </div>

</div>


<div class="col-md-6">
<div class="row">
    <div class="col-xs-3">
        <label> {!! trans('messages.rules_then_conditon_27') !!}</label>
        <input type="text" value="{!!  $seperator!!}" name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_SPLIT_FIELD !!}_{!! $conditional_identifier !!}_1"
               class="form-control rules-split-textfield-margin">
    </div>
    <div class="col-xs-4">
        <label> {!! trans('messages.rules_then_conditon_28') !!} </label>
        <select name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_SPLIT_FIELD !!}_{!! $conditional_identifier !!}_2" class="form-control" >
            @for($i = 1; $i < 20; $i++)
                <option {{ $i == $from_rule  ? 'selected' : ''  }} value="{!! $i !!}"> {!! trans('messages.rules_then_conditon_63') !!} {!! $i !!}</option>
            @endfor

        </select>
    </div>
    <div class="col-xs-5">
        <label> {!! trans('messages.rules_then_conditon_29') !!} </label>
        <select name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_SPLIT_FIELD !!}_{!! $conditional_identifier !!}_3" class="form-control">
            @for($i = 1; $i < 20; $i++)
                <option {{ $i == $to_rule  ? 'selected' : ''  }} value="{!! $i !!}"> {!! trans('messages.rules_then_conditon_63') !!} {!! $i !!}</option>
            @endfor
        </select>
    </div>

    <div class="col-xs-3">
        <label> {!! trans('messages.rules_then_conditon_64') !!}</label>
        <input type="text" value="{!!  $merge_together !!}" name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_SPLIT_FIELD !!}_{!! $conditional_identifier !!}_4"
               class="form-control rules-split-textfield-margin">
    </div>
</div>

</div>



<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_SPLIT_FIELD !!}_{!! $conditional_identifier !!}_0">
<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_SPLIT_FIELD !!}_{!! $conditional_identifier !!}_1">
<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_SPLIT_FIELD !!}_{!! $conditional_identifier !!}_2">
<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_SPLIT_FIELD !!}_{!! $conditional_identifier !!}_3">
<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_SPLIT_FIELD !!}_{!! $conditional_identifier !!}_4">