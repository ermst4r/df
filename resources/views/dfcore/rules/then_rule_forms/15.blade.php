<?php

$select_field = (isset($then_field_values[0]) ? $then_field_values[0] : '');


?>

<div class="col-lg-8">
    <div class="form-group">
        <label> {!! trans('messages.rules_then_conditon_62') !!}</label>
        <select class="form-control select2"
                name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_CALCULATE_STRING_LENGTH !!}_{!! $conditional_identifier !!}_0"
                data-then_identifier="{!! $conditional_identifier !!}"
                style="width: 100%;">

            @foreach($field_names as $field)
                <option {{ $select_field == $field ? 'selected' : '' }} value="{!! $field !!}">{!! $field !!} </option>
            @endforeach


        </select>
    </div>

    </div>

</div>
<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_CALCULATE_STRING_LENGTH !!}_{!! $conditional_identifier !!}_0">
