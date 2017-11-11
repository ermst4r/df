<?php

$equal = (isset($then_field_values[0]) ? $then_field_values[0] : '');
?>

<div class="col-lg-8">
    <div class="form-group">

        <select class="form-control select2"
                name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_COMMON_STRING_ACTIONS !!}_{!! $conditional_identifier !!}_0"
                data-then_identifier="{!! $conditional_identifier !!}"
                style="width: 100%;">

            <option {{ $equal == \App\DfCore\DfBs\Enum\CommonRulesEnum::FIRST_CHARACTER_UPPERCASE ? 'selected' : '' }} value="{!! \App\DfCore\DfBs\Enum\CommonRulesEnum::FIRST_CHARACTER_UPPERCASE !!}">{!! trans('messages.rules_then_conditon_37') !!}  </option>
            <option {{ $equal == \App\DfCore\DfBs\Enum\CommonRulesEnum::WORD_UPPER_CASE ? 'selected' : '' }} value="{!! \App\DfCore\DfBs\Enum\CommonRulesEnum::WORD_UPPER_CASE !!}"> {!! trans('messages.rules_then_conditon_38') !!}  </option>
            <option {{ $equal == \App\DfCore\DfBs\Enum\CommonRulesEnum::LOWERCASE_ALL ? 'selected' : '' }} value="{!! \App\DfCore\DfBs\Enum\CommonRulesEnum::LOWERCASE_ALL !!}"> {!! trans('messages.rules_then_conditon_39') !!}   </option>
            <option {{ $equal == \App\DfCore\DfBs\Enum\CommonRulesEnum::UPPERCASE_ALL ? 'selected' : '' }} value="{!! \App\DfCore\DfBs\Enum\CommonRulesEnum::UPPERCASE_ALL !!}"> {!! trans('messages.rules_then_conditon_40') !!} </option>
            <option {{ $equal == \App\DfCore\DfBs\Enum\CommonRulesEnum::REMOVE_NON_NUMERIC_CHARACTERS ? 'selected' : '' }} value="{!! \App\DfCore\DfBs\Enum\CommonRulesEnum::REMOVE_NON_NUMERIC_CHARACTERS !!}"> {!! trans('messages.rules_then_conditon_41') !!} </option>
            <option {{ $equal == \App\DfCore\DfBs\Enum\CommonRulesEnum::REMOVE_LINE_BREAKS ? 'selected' : '' }} value="{!! \App\DfCore\DfBs\Enum\CommonRulesEnum::REMOVE_LINE_BREAKS !!}"> {!! trans('messages.rules_then_conditon_42') !!} </option>
            <option {{ $equal == \App\DfCore\DfBs\Enum\CommonRulesEnum::REMOVE_HTML ? 'selected' : '' }} value="{!! \App\DfCore\DfBs\Enum\CommonRulesEnum::REMOVE_HTML !!}"> {!! trans('messages.rules_then_conditon_43') !!} </option>
        </select>
    </div>



</div>

<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_COMMON_STRING_ACTIONS !!}_{!! $conditional_identifier !!}_0">