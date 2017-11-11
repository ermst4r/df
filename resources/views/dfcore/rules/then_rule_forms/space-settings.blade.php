<a href="javascript:void(0);"  class="open_rule_spacing_settings" data-space_item="{!! $condition_field !!}">
    <i class="fa fa-scissors" aria-hidden="true"></i>
    {!! trans('messages.rules_then_conditon_18') !!}

</a>

<div style="display: none; margin-left:20px; margin-top:20px;" class="form-group rules-spacing-div-{!! $condition_field !!}">
     {!! Form::input('text','space_'.$condition_field,$then_spacing,['class'=>'form-control']) !!}
    <p class="help-block"> {!! trans('messages.rules_then_conditon_66') !!}</p>

</div>