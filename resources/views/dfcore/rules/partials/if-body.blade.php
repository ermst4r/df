<?php
if($if_id == 0) {
    $box_type = 'info';
    $box_msg = '<i class="fa fa-fw fa-rocket"></i>'.trans('messages.rules_if_lbl39');
}


if( $if_rules['if_operator'][$if_id] === \App\DfCore\DfBs\Enum\RuleConditions::OR_OPERATOR) {
    $box_msg =  '<i class="fa fa-fw fa-rocket"></i> '.trans('messages.rules_if_lbl38');
    $box_type = 'success';
}

if( $if_rules['if_operator'][$if_id] === \App\DfCore\DfBs\Enum\RuleConditions::AND_OPERATOR) {
    $box_msg =  '<i class="fa fa-fw fa-rocket"></i> '.trans('messages.rules_if_lbl24');
    $box_type = 'warning';
}



?>


<div class="box box-{!! $box_type !!} box-solid rules-if-field-container-{!! $if_id !!} ">
    <div class="box-header with-border">
    <h3 class="box-title">{!! $box_msg !!}</h3>



        <!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body " style="display: block;">
        <div class="row">
            <div class="col-lg-4">


                <label>1.0 Als veld</label>

                {!! $if_rules['if_field'][$if_id] !!}

                {!! Form::hidden('if_operator['.$if_id.']', $if_rules['if_operator'][$if_id]  ) !!}
                <select class="form-control if_field select2" name="if_field[{!! $if_id !!}]" data-if_identifier="{!! $if_id !!}">
                    <option value=""> {!! trans('messages.rules_if_lbl3') !!}</option>
                    <option value="all" {{ 'all' == $if_rules['if_field'][$if_id] ? 'selected' : '' }}> All</option>
                    @foreach($field_names as $fieldname)
                        <option value="{!! $fieldname !!}" {{ $fieldname == $if_rules['if_field'][$if_id] ? 'selected' : '' }}>{!! $fieldname !!}</option>

                    @endforeach

                </select>
            </div>

            <div class="col-lg-4" >
                <label>2.0 Uit</label>
                <select class="form-control if_condition_select select2" name="exists_of_field[{!! $if_id !!}]" data-if_identifier="{!! $if_id !!}">
                    <option value=""> {!! trans('messages.rules_if_lbl3') !!}</option>



                    @foreach(\App\DfCore\DfBs\Rules\ConditionToHtmlFormType::ifFormOptions() as $rule)
                        @if($rule['type'] == 'optgroup')
                            <optgroup label="{!! $rule['text'] !!}">{!! $rule['text'] !!}</optgroup>
                        @else
                            <option  {{ $if_rules['exists_of_field'][$if_id] ==  $rule['value']  ? 'selected' : '' }} value="{!! $rule['value'] !!}">{!! $rule['text'] !!}</option>
                        @endif
                    @endforeach


                </select>
            </div>
        </div>

        <?php
        $rule_type = \App\DfCore\DfBs\Rules\ConditionToHtmlFormType::ifToFormType($if_rules['exists_of_field'][$if_id]);

        ?>
        <div class="row" style="margin-top: 20px; margin-bottom: 30px;">


            <div class="col-lg-8 append_if_container_{!! $if_id !!}" >
                <label>{!! trans('messages.rules_if_lbl21') !!}</label>

                <div class="append_if_form_{!! $if_id !!}">
                    <?php
                    switch ($rule_type['type']) {
                        case 'text':
                        case 'text no_listener':
                            echo '<input data-toggle="tooltip" id="phrase_'.$if_id.'" value="'.$if_rules['if_condition_field'][$if_id].'" name="if_condition_field['.$if_id.']" data-placement="bottom" title="" type="text" placeholder="'.$rule_type['placeholder'].'" data-if_identifier="'.$if_id.'" class="form-control if_field_txt_field text_if_'.$if_id.'"  data-original-title="'.$rule_type['tooltip'].'">';
                            break;
                        case 'textarea':
                            echo '<textarea tabindex="1"  name="if_condition_textarea['.$if_id.']" class="form-control textarea_if_'.$if_id.'" data-toggle="tooltip" rows="5" data-placement="bottom" title=""  data-if_identifier="'.$if_id.'" data-original-title="'.$rule_type['tooltip'].'">'.$if_rules['if_condition_textarea'][$if_id].'</textarea>';
                            break;
                        case 'empty':
                            echo '<span class="badge bg-green">'.trans('messages.rules_if_lbl22').'</span>';
                            break;
                        case null:
                            echo '<span class="badge bg-orange">'.trans('messages.rules_if_lbl23').'</span>';
                            break;
                    } ?>

                </div>

            </div>



            <div class="col-lg-8 if-button" style="margin-top:20px;">

                @if($if_id > 0)

                    <button type="button" class="btn btn-warning btn-xs delete-if-field" data-container="{!! $if_id !!}">
                        <i class="fa fa-trash"></i>
                    </button>
                @endif
            </div>
        </div>

    </div>
    <!-- /.box-body -->
</div>





