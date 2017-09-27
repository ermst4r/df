<div class="col-lg-15 else-condition-container">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-code" aria-hidden="true"></i> {!! trans('messages.rules_if_lbl28') !!}</h3>
        </div>
        <div class="box-body">

            @include('dfcore.rules.partials.then-body',['then_id'=>0,'spacer'=>'','field_names', 'preloaded'=>true,'then_rules'=>$rules_dictonary['rules']])




            <?php $counter = 0;?>
            @foreach(array_keys($rules_dictonary['rules']['then_field']) as $i)
                @if($counter > 0)
                    @include('dfcore.rules.partials.then-body',['then_id'=>$i,'spacer'=>'rules-spacer','field_names','then_rules'=>$rules_dictonary['rules'],'feed_id'=>$feed_id,'preloaded'=>true])
                @endif
                <?php $counter ++;?>
            @endforeach


            <div class="append-and-field">  </div>




            <div class="box-footer" style="margin-top:10px;">
                <button title="{!! trans('messages.rules_if_lbl25') !!}"
                        data-placement="bottom"
                        data-toggle="tooltip"
                        type="submit" class="btn btn-success btn-xs"><i class="fa fa-save"></i></button>
                <button title="{!! trans('messages.rules_if_lbl26') !!}"  data-placement="bottom"  data-toggle="tooltip"   type="button" class="btn btn-xs btn-info copy-and-field"><i class="fa fa-plus"></i></button>

            </div>
        </div>
    </div>
</div>

{!! Form::hidden('preloaded_then_counter',count($rules_dictonary['rules']['then_field']) - 1) !!}