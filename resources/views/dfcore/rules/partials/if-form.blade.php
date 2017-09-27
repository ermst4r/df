<div class="col-lg-12 if-condition-container">

    <div class="box box-warning">
        <div class="box-header with-border">
            <h3 class="box-title"> <i class="fa fa-check" aria-hidden="true"></i> {!! trans('messages.rules_if_lbl27') !!} </h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>

        </div>
        <div class="box-body " style="display: block;">

            <div class="box box-warning box-solid rules-if-field-container-0 ">
                <div class="box-header with-border">
                    <h3 class="box-title if-box-header"> <i class="fa fa-rocket"></i> {!! trans('messages.rules_if_lbl24') !!}</h3>


                    <!-- /.box-tools -->
                </div>

                <div class="box-body " style="display: block;">

                <div class="row">
                    <div class="col-lg-4">
                        <label>1.0 Als veld
                            {!! Form::hidden('if_operator[0]','start') !!}
                            {!! Form::hidden('if_parent_child[0]',0) !!}
                            {!! Form::hidden('if_main_parent[0]',0,['id'=>'main_parent']) !!}


                        </label>
                        <select class="form-control if_field select2" name="if_field[0]" data-if_identifier="0">
                            <option value=""> {!! trans('messages.rules_if_lbl3') !!}</option>
                            <option value="all"> All</option>
                            @foreach($field_names as $fieldname)
                                <option value="{!! $fieldname !!}">{!! $fieldname !!}</option>
                            @endforeach
                        </select>
                    </div>



                    <div class="col-lg-4" >
                        <label>2.0 Uit</label>
                        <select class="form-control if_condition_select select2" name="exists_of_field[0]" data-if_identifier="0">
                            <option value=""> {!! trans('messages.rules_if_lbl3') !!}</option>
                            @foreach(\App\DfCore\DfBs\Rules\ConditionToHtmlFormType::ifFormOptions() as $rule)
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
                    <div class="col-lg-8 append_if_container_0" style="display: none;">
                        <label>{!! trans('messages.rules_if_lbl21') !!}</label>
                    </div>
                    <div class="append_if_form_0 col-md-10">
                    </div>
                    <div class="col-lg-8 if-button" style="margin-top:20px;">
                        <button type="button"
                                data-condition="{!! \App\DfCore\DfBs\Enum\RuleConditions::OR_OPERATOR !!}"
                                data-parent="0"
                                id="or_button"
                                class="btn bg-blue btn-xs copy-if-field"><i class="fa fa-plus"></i>OF </button>
                    </div>
                </div>
            </div>

                <div class="append_or_0 top_rules" >
                </div>

        </div>





            <div class="append-if-field">

            </div>

            <div class="row">
            <div class="col-lg-8 if-button" style="margin-top:20px;">
                <button type="button"
                        data-and_parent="0"
                        id="add_and"
                        data-condition="{!! \App\DfCore\DfBs\Enum\RuleConditions::AND_OPERATOR !!}" class="btn bg-green btn-xs copy-if-field">
                    <i class="fa fa-plus"></i>{!! trans('messages.rules_if_lbl24') !!}
                </button>
            </div>
        </div>


    </div>


    </div>



