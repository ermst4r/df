<div class="col-lg-15 else-condition-container">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-code"></i> {!! trans('messages.rules_if_lbl28') !!}</h3>
        </div>
        <div class=" ">
            <div class="box-body">

                <div class="rules-and-field-container-0 box box-info box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title if-box-header">{!! trans('messages.rules_if_lbl29') !!}</h3>


                        <!-- /.box-tools -->
                    </div>
                    <div class="box-body " style="display: block;">





                <div class="row">
                    <div class="col-lg-4">
                        <label> {!! trans('messages.rules_then_conditon_23') !!}</label>
                        <select class="form-control then_field select2" name="then_field[0]">
                            <option value="">{!! trans('messages.rules_if_lbl3') !!}</option>
                            @foreach($field_names as $field)
                                <option value="{!! $field !!}">{!! $field !!}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4">
                        <label> {!! trans('messages.rules_if_lbl31') !!}</label>
                       <select class="form-control then_condition_select select2" name="then_action[0]"  data-then_identifier="0">
                            <option value="">{!! trans('messages.rules_if_lbl3') !!}</option>

                           @foreach(\App\DfCore\DfBs\Rules\ConditionToHtmlFormType::thenFormOptions() as $rule)
                               @if($rule['type'] == 'optgroup')
                                   <optgroup label="{!! $rule['text'] !!}">{!! $rule['text'] !!}</optgroup>
                               @else
                                   <option value="{!! $rule['value'] !!}">{!! $rule['text'] !!}</option>
                               @endif
                           @endforeach


                        </select>
                    </div>


                </div>

                <div class="row" style="margin-top: 20px; margin-bottom: 30px;">
                    <div class="append_then_form_0">
                    </div>

                    <div class="col-lg-8 then-button" style="margin-top:20px;">

                    </div>
                </div>


            </div>



        </div>
        <div class="append-and-field">
        </div>


    </div>
    <div class="box-footer" style="margin-top:10px;">
        <button type="submit" title="{!! trans('messages.rules_if_lbl25') !!}"   data-placement="bottom" data-toggle="tooltip" class="btn  btn-xs btn-success"><i class="fa fa-save"></i></button>
        <button title="{!! trans('messages.rules_if_lbl26') !!}"  data-placement="bottom"  data-toggle="tooltip"   type="button" class="btn btn-info btn-xs copy-and-field"><i class="fa fa-plus"></i></button>

    </div>
    </div>
    </div>
</div>