<?php
$checked = (isset($then_field_values[2]) ? $then_field_values[2] : '' );
?>

<div class="col-lg-8">

    <div class="form-group">
        <label> {!! trans('messages.rules_then_conditon_69') !!}</label>
        <br>

    <input  data-placement="bottom" title="" type="text"
           name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_AND_REPLACE !!}_{!! $conditional_identifier !!}_0"
           id="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_AND_REPLACE !!}_{!! $conditional_identifier !!}_0"
           data-then_identifier="{!! $conditional_identifier !!}"
            class="form-control " value="{{ isset($then_field_values[0]) ? $then_field_values[0] : '' }}">
    </div>


    <div class="form-group">
        <label> {!! trans('messages.rules_then_conditon_70') !!} </label>
        <br>

        <input
                title="" type="text"
               name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_AND_REPLACE !!}_{!! $conditional_identifier !!}_1"
               id="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_AND_REPLACE !!}_{!! $conditional_identifier !!}_1"
               data-then_identifier=""{!! $conditional_identifier !!}
               class="form-control"
               value="{{ isset($then_field_values[1]) ? $then_field_values[1] : '' }}">
    </div>

    <div class="form-group" style="margin-top:20px;">

        <label>
            <input type="checkbox"
                   {!!  $checked == 1 ? 'checked' : '' !!}
                   name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_AND_REPLACE !!}_{!! $conditional_identifier !!}_2" value="1" >
            {!! trans('messages.rules_then_conditon_76') !!}
        </label>



    </div>



</div>







<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_AND_REPLACE !!}_{!! $conditional_identifier !!}_0">
<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_AND_REPLACE !!}_{!! $conditional_identifier !!}_1">
<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_AND_REPLACE !!}_{!! $conditional_identifier !!}_2">


@if(!isset($preloaded))
    {!! Form::hidden('feed_id',$feed_id) !!}
    <script>
        (function($){
            var feed_id = parseInt($('input[name=feed_id]').val());
            var $_current_object_0 = $('input[name={!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_AND_REPLACE.'_'.$conditional_identifier.'_0' !!}]');
            var $_current_object_1 = $('input[name={!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_AND_REPLACE.'_'.$conditional_identifier.'_1' !!}]');

            $.fn.ajax_getrule_esfields(feed_id, function (data) {
                $('#' + $_current_object_0.attr('id')).customSelectIT({'availableTags': data});
                $('#' + $_current_object_1.attr('id')).customSelectIT({'availableTags': data});
            });
        })(jQuery);
    </script>
@endif
