<div class="col-lg-12 if-condition-container">
    <div class="box box-warning solid">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-check" aria-hidden="true"></i> {!! trans('messages.rules_if_lbl27') !!}</h3>

        </div>
        <div class="box-body">


            <div class="append-if-field">
                <?php $save_parent = 0;?>
               @foreach($rules_dictonary['rules']['if_operator'] as $key => $operator)
                   @if($operator === \App\DfCore\DfBs\Enum\RuleConditions::AND_OPERATOR || $operator === \App\DfCore\DfBs\Enum\RuleConditions::START_OPERATOR)

                        @include('dfcore.rules.partials.and',['if_id'=>$key,'spacer'=>'rules-spacer','field_names', 'is_all_operator'=>$is_all_operator, 'operator'=>$operator, 'parent'=>$key,'if_rules'=>$rules_dictonary['rules']])
                       <?php $save_parent = $key;?>
                    @endif
                @endforeach
            </div>




            <!-- /.box-body -->
        </div>


    <div class="box-footer" style="margin-top:10px;">
        <button type="button"
                data-condition="{!! \App\DfCore\DfBs\Enum\RuleConditions::AND_OPERATOR !!}"
                data-and_parent="{!! $save_parent !!}"
                id="add_and"
                style="{!! $is_all_operator == true ? 'display:none;' : '' !!}"
                class="btn bg-blue btn-xs copy-if-field">
            <i class="fa fa-plus"></i>{!! trans('messages.rules_if_lbl24') !!}
        </button>
    </div>


    </div>

{!! Form::hidden('preloaded_if_counter', max(array_keys($rules_dictonary['rules']['if_field']))) !!}
{!! Form::hidden('if_update',1) !!}
{!! Form::hidden('rule_id',$rule_id) !!}
