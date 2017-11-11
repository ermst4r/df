<?php

$from_field = (isset($then_field_values[0]) ? $then_field_values[0] : '');
$calculate_action = (isset($then_field_values[1]) ? $then_field_values[1] : '');
$field_action = (isset($then_field_values[2]) ? $then_field_values[2] : '');
$select_action = (isset($then_field_values[3]) ? $then_field_values[3] : '');
$hidden_field_value = (isset($then_field_values[4]) ? $then_field_values[4] : '');

?>

<div class="col-lg-8">
    <div class="form-group">
        <label> {!! trans('messages.rules_then_conditon_57') !!}</label>
        <select class="form-control select2"
                name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_CALCULATE_NUMBER !!}_{!! $conditional_identifier !!}_0"
                data-then_identifier="{!! $conditional_identifier !!}"
                style="width: 100%;">
            <option></option>
            @foreach($field_names as $field)
                <option {{ $from_field == $field ? 'selected' : '' }} value="{!! $field !!}">{!! $field !!} </option>
            @endforeach


        </select>
    </div>




    <div class="form-group">

        <div class="row">
            <div class="col-md-6">
                <label> {!! trans('messages.rules_then_conditon_58') !!}</label>
                <select class="form-control select2 alter_calculate_number_rule"
                        data-full_field_name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_CALCULATE_NUMBER !!}_{!! $conditional_identifier !!}_4"
                        name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_CALCULATE_NUMBER !!}_{!! $conditional_identifier !!}_1"
                        data-then_identifier="{!! $conditional_identifier !!}"
                        style="width: 100%;">
                    <option></option>
                   <option {{ $calculate_action == \App\DfCore\DfBs\Enum\CommonRulesEnum::DIVIDE_BY ? 'selected' : '' }} value="{!! \App\DfCore\DfBs\Enum\CommonRulesEnum::DIVIDE_BY !!}" data-field_type="normal"> Delen door</option>
                   <option  {{ $calculate_action == \App\DfCore\DfBs\Enum\CommonRulesEnum::MULTIPLY ? 'selected' : '' }} value="{!! \App\DfCore\DfBs\Enum\CommonRulesEnum::MULTIPLY !!}" data-field_type="normal"> Maal </option>
                   <option {{ $calculate_action == \App\DfCore\DfBs\Enum\CommonRulesEnum::PLUS ? 'selected' : '' }}  value="{!! \App\DfCore\DfBs\Enum\CommonRulesEnum::PLUS !!}" data-field_type="normal"> Plus </option>
                   <option {{ $calculate_action == \App\DfCore\DfBs\Enum\CommonRulesEnum::MINUS ? 'selected' : '' }}  value="{!! \App\DfCore\DfBs\Enum\CommonRulesEnum::MINUS !!}" data-field_type="normal"> Minus </option>
                   <option {{ $calculate_action == \App\DfCore\DfBs\Enum\CommonRulesEnum::DIVIDE_BY_FIELD ? 'selected' : '' }} value="{!! \App\DfCore\DfBs\Enum\CommonRulesEnum::DIVIDE_BY_FIELD !!}" data-field_type="fieldname"> Deel door veld </option>
                   <option {{ $calculate_action == \App\DfCore\DfBs\Enum\CommonRulesEnum::MULTIPLY_BY_FIELD ? 'selected' : '' }} value="{!! \App\DfCore\DfBs\Enum\CommonRulesEnum::MULTIPLY_BY_FIELD !!}" data-field_type="fieldname"> Maal  veld </option>
                   <option {{ $calculate_action == \App\DfCore\DfBs\Enum\CommonRulesEnum::PLUS_FIELD ? 'selected' : '' }} value="{!! \App\DfCore\DfBs\Enum\CommonRulesEnum::PLUS_FIELD !!}" data-field_type="fieldname"> Plus  veld </option>
                   <option {{ $calculate_action == \App\DfCore\DfBs\Enum\CommonRulesEnum::MINUS_FIELD ? 'selected' : '' }} value="{!! \App\DfCore\DfBs\Enum\CommonRulesEnum::MINUS_FIELD !!}" data-field_type="fieldname"> Minus  veld </option>
                </select>
            </div>


            <input type="hidden" name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_CALCULATE_NUMBER !!}_{!! $conditional_identifier !!}_4" value="{!! $hidden_field_value !!}">



            <div class="col-md-6">

                <div class="calculate_normal_field_{!! $conditional_identifier !!}" style="display: {{ $hidden_field_value == 'normal' ? '' : 'none' }};">
                    <label> {!! trans('messages.rules_then_conditon_57') !!}</label>
                 <input type="text" value="{!! $field_action !!}" class="form-control" name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_CALCULATE_NUMBER !!}_{!! $conditional_identifier !!}_2">
                </div>


                <div class="calculate_select_field_{!! $conditional_identifier !!}" style="display: {{ $hidden_field_value == 'fieldname' ? '' : 'none' }};">
                    <label> {!! trans('messages.rules_then_conditon_57') !!}</label>
                    <select class="form-control select2"
                            name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_CALCULATE_NUMBER !!}_{!! $conditional_identifier !!}_3"
                            data-then_identifier="{!! $conditional_identifier !!}"
                            style="width: 100%;">
                        @foreach($field_names as $field)
                            <option {{ $select_action == $field ? 'selected' : '' }} value="{!! $field !!}">{!! $field !!} </option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>

    </div>



</div>
<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_CALCULATE_NUMBER !!}_{!! $conditional_identifier !!}_0">
<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_CALCULATE_NUMBER !!}_{!! $conditional_identifier !!}_1">
<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_CALCULATE_NUMBER !!}_{!! $conditional_identifier !!}_2">
<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_CALCULATE_NUMBER !!}_{!! $conditional_identifier !!}_3">
<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_CALCULATE_NUMBER !!}_{!! $conditional_identifier !!}_4">