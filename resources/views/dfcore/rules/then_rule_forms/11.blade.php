<?php

$source = (isset($then_field_values[0]) ? $then_field_values[0] : '');
$medium = (isset($then_field_values[1]) ? $then_field_values[1] : '');
$campaign = (isset($then_field_values[2]) ? $then_field_values[2] : '');
$term = (isset($then_field_values[3]) ? $then_field_values[3] : '');
$content = (isset($then_field_values[4]) ? $then_field_values[4] : '');

?>

<div class="col-lg-8">
    <div class="form-group">
        <label> {!! trans('messages.rules_then_conditon_45') !!}</label>
        <BR>

        <input type="text" class="form-control"
               id="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_GOOGLE_TRACKING !!}_{!! $conditional_identifier !!}_0"
               name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_GOOGLE_TRACKING !!}_{!! $conditional_identifier !!}_0"
               data-then_identifier="{!! $conditional_identifier !!}" value="{!! $source !!}" >

    </div>

    <div class="form-group">
        <label> {!! trans('messages.rules_then_conditon_46') !!}</label>
        <br>

        <input type="text" class="form-control"
               id="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_GOOGLE_TRACKING !!}_{!! $conditional_identifier !!}_1"
               name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_GOOGLE_TRACKING !!}_{!! $conditional_identifier !!}_1"
               data-then_identifier="{!! $conditional_identifier !!}" value="{!! $medium !!}" >
    </div>


    <div class="form-group">
        <label> {!! trans('messages.rules_then_conditon_47') !!}</label>
        <br>

        <input type="text" class="form-control"
               id="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_GOOGLE_TRACKING !!}_{!! $conditional_identifier !!}_2"
               name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_GOOGLE_TRACKING !!}_{!! $conditional_identifier !!}_2"
               data-then_identifier="{!! $conditional_identifier !!}" value="{!! $campaign !!}" >
    </div>


    <div class="form-group">
        <label> {!! trans('messages.rules_then_conditon_48') !!}</label>
        <br>

        <input type="text" class="form-control"
               id="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_GOOGLE_TRACKING !!}_{!! $conditional_identifier !!}_3"
               name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_GOOGLE_TRACKING !!}_{!! $conditional_identifier !!}_3"
               data-then_identifier="{!! $conditional_identifier !!}" value="{!! $term !!}" >
    </div>


    <div class="form-group">
        <label> {!! trans('messages.rules_then_conditon_49') !!}</label>
        <br>

        <input type="text" class="form-control"
               id="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_GOOGLE_TRACKING !!}_{!! $conditional_identifier !!}_4"
               name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_GOOGLE_TRACKING !!}_{!! $conditional_identifier !!}_4"
               data-then_identifier="{!! $conditional_identifier !!}" value="{!! $content !!}" >
    </div>





</div>

<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_GOOGLE_TRACKING !!}_{!! $conditional_identifier !!}_0">
<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_GOOGLE_TRACKING !!}_{!! $conditional_identifier !!}_1">
<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_GOOGLE_TRACKING !!}_{!! $conditional_identifier !!}_2">
<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_GOOGLE_TRACKING !!}_{!! $conditional_identifier !!}_3">
<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_GOOGLE_TRACKING !!}_{!! $conditional_identifier !!}_4">



@if(!isset($preloaded))
    {!! Form::hidden('feed_id',$feed_id) !!}
    <script>
        (function($){
            var feed_id = parseInt($('input[name=feed_id]').val());
            var $_current_object_0 = $('input[name={!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_GOOGLE_TRACKING.'_'.$conditional_identifier.'_0' !!}]');
            var $_current_object_1 = $('input[name={!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_GOOGLE_TRACKING.'_'.$conditional_identifier.'_1' !!}]');
            var $_current_object_2 = $('input[name={!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_GOOGLE_TRACKING.'_'.$conditional_identifier.'_2' !!}]');
            var $_current_object_3 = $('input[name={!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_GOOGLE_TRACKING.'_'.$conditional_identifier.'_3' !!}]');
            var $_current_object_4 = $('input[name={!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_GOOGLE_TRACKING.'_'.$conditional_identifier.'_4' !!}]');

            $.fn.ajax_getrule_esfields(feed_id, function (data) {
                $('#' + $_current_object_0.attr('id')).customSelectIT({'availableTags': data});
                $('#' + $_current_object_1.attr('id')).customSelectIT({'availableTags': data});
                $('#' + $_current_object_2.attr('id')).customSelectIT({'availableTags': data});
                $('#' + $_current_object_3.attr('id')).customSelectIT({'availableTags': data});
                $('#' + $_current_object_4.attr('id')).customSelectIT({'availableTags': data});
            });
        })(jQuery);
    </script>
@endif
