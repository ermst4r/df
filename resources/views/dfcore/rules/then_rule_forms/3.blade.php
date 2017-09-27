<div class="col-lg-8">


    <div class="form-group">
<?php
$search_array = (isset($then_field_values[0]) ? $then_field_values[0] : []);
$replace_array = (isset($then_field_values[1]) ? $then_field_values[1] : []);
?>





        <select class="form-control select2" multiple="multiple"
                name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_AND_REPLACE_FIELD_NAME !!}_{!! $conditional_identifier !!}_0[]"
                data-then_identifier="{!! $conditional_identifier !!}"
                data-placeholder="{!! trans('messages.rules_then_conditon_6') !!}" style="width: 100%;">
            @foreach($field_names as $field)
                <option {{ is_integer(array_search($field,$search_array)) ? 'selected' : ''   }}> {!! $field !!}</option>
            @endforeach

        </select>

    </div>


    <div class="form-group">

        <select class="form-control select2" multiple="multiple"
                name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_AND_REPLACE_FIELD_NAME !!}_{!! $conditional_identifier !!}_1[]"
                data-then_identifier="{!! $conditional_identifier !!}"
                data-placeholder="{!! trans('messages.rules_then_conditon_7') !!}" style="width: 100%;">

            @foreach($field_names as $field)
                <option {{ is_integer(array_search($field,$replace_array)) ? 'selected' : ''   }}> {!! $field !!}</option>
            @endforeach



        </select>
    </div>

</div>




<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_AND_REPLACE_FIELD_NAME !!}_{!! $conditional_identifier !!}_0">
<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_AND_REPLACE_FIELD_NAME !!}_{!! $conditional_identifier !!}_1">