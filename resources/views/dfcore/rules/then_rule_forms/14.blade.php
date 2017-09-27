<div class="col-lg-8">
    <div class="form-group">
        <?php
        $search_array = (isset($then_field_values[0]) ? $then_field_values[0] : []);
        ?>
        <label> {!! trans('messages.rules_then_conditon_60') !!}</label>
        <select class="form-control select2 space-listener" multiple="multiple"
                id="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_CALCULATE_SUM !!}_{!! $conditional_identifier !!}_0"
                name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_CALCULATE_SUM !!}_{!! $conditional_identifier !!}_0[]"
                data-then_identifier="{!! $conditional_identifier !!}"
                data-placeholder="{!! trans('messages.rules_then_conditon_61') !!}" style="width: 100%;">
            @foreach($field_names as $field)
                <option {{ is_integer(array_search($field,$search_array)) ? 'selected' : ''   }}> {!! $field !!}</option>
            @endforeach


        </select>
    </div>




</div>



<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_CALCULATE_SUM !!}_{!! $conditional_identifier !!}_0">
