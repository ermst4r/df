

<?php

$equal = (isset($then_field_values[0]) ? $then_field_values[0] : '');
$type = (isset($then_field_values[1]) ? $then_field_values[1] : '');


?>







<div class="col-md-6">


    <div class="row">
        <div class="col-xs-3">
            <label>{!! trans('messages.rules_then_conditon_65') !!}</label>
            <input type="text" value="{!! $equal !!}"
                   name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_STRING_LENGTH !!}_{!! $conditional_identifier !!}_0"
                   class="form-control rules-split-textfield-margin">
        </div>
        <div class="col-xs-5">
            <label>{!! trans('messages.rules_overview_rule_lbl9') !!}</label>
            <select class="form-control" name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_STRING_LENGTH !!}_{!! $conditional_identifier !!}_1">
                <option value="1" {{ $type == 1 ? 'selected' : '' }}> {!! trans('messages.rules_then_conditon_33') !!}</option>
            </select>
        </div>

    </div>


</div>




<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_STRING_LENGTH !!}_{!! $conditional_identifier !!}_0">
<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_STRING_LENGTH !!}_{!! $conditional_identifier !!}_1">
