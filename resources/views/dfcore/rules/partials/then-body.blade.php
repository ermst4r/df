<?php

?>
<div class="box box-info box-solid rules-and-field-container-{!! $then_id !!} ">
    <div class="box-header with-border">

        <h3 class="box-title if-box-header">{!! trans('messages.rules_if_lbl29') !!}</h3>

        <!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body " style="display: block;">



        <div class="row">
            <div class="col-lg-4">
                <label>  {!! trans('messages.rules_then_conditon_23') !!}</label>
                <select class="form-control then_field select2" name="then_field[{!! $then_id !!}]">
                    <option value="">{!! trans('messages.rules_if_lbl3') !!}</option>
                    @foreach($field_names as $field)
                        <option {{ $field == $then_rules['then_field'][$then_id] ? 'selected' : '' }} value="{!! $field !!}">{!! $field !!}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-4">
                <label>  {!! trans('messages.rules_if_lbl31') !!} </label>
                <select class="form-control then_condition_select select2" name="then_action[{!! $then_id !!}]"  data-then_identifier="{!! $then_id !!}">
                    <option value="">{!! trans('messages.rules_if_lbl3') !!}</option>

                    @foreach(\App\DfCore\DfBs\Rules\ConditionToHtmlFormType::thenFormOptions() as $rule)
                        @if($rule['type'] == 'optgroup')
                            <optgroup label="{!! $rule['text'] !!}">{!! $rule['text'] !!}</optgroup>
                        @else
                            <option {{ $rule['value'] == $then_rules['then_action'][$then_id] ? 'selected' : '' }} value="{!! $rule['value'] !!}">{!! $rule['text'] !!}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>


        <div class="row" style="margin-top: 20px; margin-bottom: 30px;">
            <div class="append_then_form_{!! $then_id !!}">
                @if(!is_null($then_rules['then_action'][$then_id]))
                    @include('dfcore.rules.then_rule_forms.'.$then_rules['then_action'][$then_id],
                    [
                    'conditional_identifier'=>$then_id,
                    'then_field_values'=>$then_rules['then_field_values'][$then_id],
                    'then_spacing'=>(isset($then_rules['then_spacing'][$then_id]) ?  $then_rules['then_spacing'][$then_id] : []),
                    ]

                    )
                @endif


            </div>

            <div class="col-lg-8 then-button" style="margin-top:20px;">

                @if($then_id > 0)
                    <button type="button" class="btn btn-warning btn-xs delete-then-field" data-container="{!! $then_id !!}"><i class="fa fa-trash"></i> </button>
                @endif
            </div>

        </div>
    </div>
    <!-- /.box-body -->
</div>




