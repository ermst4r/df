<div class="col-lg-8">
    <label>
        {!! trans('messages.rules_then_conditon_73') !!}
    </label>
    <br>
    <input   data-placement="bottom" title="" type="text"
           id="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_ALTER_FIELD_VALUE !!}_{!! $conditional_identifier !!}_0"
           value="{{ isset($then_field_values[0]) ? $then_field_values[0] : '' }}"
           name="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_ALTER_FIELD_VALUE !!}_{!! $conditional_identifier !!}_0"
           data-then_identifier="{!! $conditional_identifier !!}" class="form-control ">
</div>
<input type="hidden" name="then_ids[]" value="{!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_ALTER_FIELD_VALUE !!}_{!! $conditional_identifier !!}_0">

@if(!isset($preloaded))
    {!! Form::hidden('feed_id',$feed_id) !!}
    <script>
        (function($){
            var feed_id = parseInt($('input[name=feed_id]').val());
            var $_current_object_0 = $('input[name={!! \App\DfCore\DfBs\Enum\RuleConditions::THEN_ALTER_FIELD_VALUE.'_'.$conditional_identifier.'_0' !!}]');
            $.fn.ajax_getrule_esfields(feed_id, function (data) {
                $('#' + $_current_object_0.attr('id')).customSelectIT({'availableTags': data});
            });
        })(jQuery);
    </script>
@endif


