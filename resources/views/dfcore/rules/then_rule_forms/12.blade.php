<?php

$select_value = (isset($then_field_values[0]) ? $then_field_values[0] : '');

?>

<div class="col-lg-8">
    <div class="form-group">
        <label> {!! trans('messages.rules_then_conditon_50') !!}</label>
        <select class="form-control select2"
                name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_ROUND_NUMBER !!}_{!! $conditional_identifier !!}_0"
                data-then_identifier="{!! $conditional_identifier !!}"
                style="width: 100%;">

            <option {{ $select_value == \App\DfCore\DfBs\Enum\CommonRulesEnum::ROUND_NUMBER_BELOW ? 'selected' : '' }}
                    value="{!! \App\DfCore\DfBs\Enum\CommonRulesEnum::ROUND_NUMBER_BELOW !!}">
                {!! trans('messages.rules_then_conditon_55') !!}
            </option>


            <option {{ $select_value == \App\DfCore\DfBs\Enum\CommonRulesEnum::ROUND_NUMBER_ABOVE ? 'selected' : '' }}
                    value="{!! \App\DfCore\DfBs\Enum\CommonRulesEnum::ROUND_NUMBER_ABOVE !!}">
                {!! trans('messages.rules_then_conditon_54') !!}
            </option>

            <option {{ $select_value == \App\DfCore\DfBs\Enum\CommonRulesEnum::ROUND_NUMBER_99 ? 'selected' : '' }}
                    value="{!! \App\DfCore\DfBs\Enum\CommonRulesEnum::ROUND_NUMBER_99 !!}">
                {!! trans('messages.rules_then_conditon_52') !!}
            </option>


             <option {{ $select_value == \App\DfCore\DfBs\Enum\CommonRulesEnum::ROUND_NUMBER_95 ? 'selected' : '' }}
                    value="{!! \App\DfCore\DfBs\Enum\CommonRulesEnum::ROUND_NUMBER_95 !!}">
                {!! trans('messages.rules_then_conditon_53') !!}
            </option>



        </select>
    </div>




</div>
<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_ROUND_NUMBER !!}_{!! $conditional_identifier !!}_0">