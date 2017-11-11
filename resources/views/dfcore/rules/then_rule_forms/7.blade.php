
<?php

$selected_field = (isset($then_field_values[0]) ? $then_field_values[0] : '');
$search_value = (isset($then_field_values[1]) ? $then_field_values[1] : '');
$replace_value = (isset($then_field_values[2]) ? $then_field_values[2] : '');

?>
<div class="col-lg-8">


    <div class="form-group">





        <label>{!! trans('messages.rules_then_conditon_20') !!}</label>
        <select class="form-control select2"
                data-input_type="select"
                name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_REPLACE_OTHER_FIELD !!}_{!! $conditional_identifier !!}_0"
                id="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_REPLACE_OTHER_FIELD !!}_{!! $conditional_identifier !!}_0"
                data-then_identifier="{!! $conditional_identifier !!}"
                style="width: 100%;">

            @foreach($field_names as $field)
                <option {{ $selected_field == $field ? 'selected' : ''  }}> {!! $field !!}</option>
            @endforeach


        </select>

    </div>


    <div class="form-group">
        <label>{!! trans('messages.rules_then_conditon_24') !!}</label><BR>
        <input data-toggle="tooltip"  data-placement="bottom" title="" type="text"
               name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_REPLACE_OTHER_FIELD !!}_{!! $conditional_identifier !!}_1"
               id="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_REPLACE_OTHER_FIELD !!}_{!! $conditional_identifier !!}_1"
               data-then_identifier="{!! $conditional_identifier !!}" class="form-control " value="{{ $search_value }}"
               data-otherfield="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_REPLACE_OTHER_FIELD !!}_{!! $conditional_identifier !!}_0">
    </div>


    <div class="form-group">
        <label>{!! trans('messages.rules_then_conditon_25') !!}</label>
        <BR>
        <input data-toggle="tooltip"  data-placement="bottom" title="" type="text"
               name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_REPLACE_OTHER_FIELD !!}_{!! $conditional_identifier !!}_2"
               id="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_REPLACE_OTHER_FIELD !!}_{!! $conditional_identifier !!}_2"
               data-then_identifier="{!! $conditional_identifier !!}" class="form-control" value="{!! $replace_value !!}">
    </div>

</div>




<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_REPLACE_OTHER_FIELD !!}_{!! $conditional_identifier !!}_0">
<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_REPLACE_OTHER_FIELD !!}_{!! $conditional_identifier !!}_1">
<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_REPLACE_OTHER_FIELD !!}_{!! $conditional_identifier !!}_2">

@if(!isset($preloaded))
    {!! Form::hidden('feed_id',$feed_id) !!}
    <script>
        (function($){
            var feed_id = parseInt($('input[name=feed_id]').val());
            var $_current_object_0 = $('input[name={!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_REPLACE_OTHER_FIELD.'_'.$conditional_identifier.'_0' !!}]');
            var $_current_object_1 = $('input[name={!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_REPLACE_OTHER_FIELD.'_'.$conditional_identifier.'_1' !!}]');
            var $_current_object_2 = $('input[name={!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_REPLACE_OTHER_FIELD.'_'.$conditional_identifier.'_2' !!}]');
            $.fn.ajax_getrule_esfields(feed_id, function (data) {
                $('#' + $_current_object_0.attr('id')).customSelectIT({'availableTags': data});
                $('#' + $_current_object_1.attr('id')).customSelectIT({'availableTags': data});
                $('#' + $_current_object_2.attr('id')).customSelectIT({'availableTags': data});
            });
        })(jQuery);
    </script>
@endif
