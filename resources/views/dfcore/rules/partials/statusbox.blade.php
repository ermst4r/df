<div class="col-md-2">
    <div class="box box-default">
        <div class="box-header with-border">
            <i class="fa fa-refresh"></i>
            <h3 class="box-title">{!! trans('messages.filter_categorize_lbl26') !!}</h3>
        </div>
        <!-- /.box-header -->
            {!! Form::hidden('number_of_products',$number_of_records['count']) !!}
        <div class="loading_rule" align="center">
            <img src="/img/reload.gif" height="48">
            <br>
            {!! trans('messages.filter_categorize_lbl29') !!}
        </div>


        <div class="box-body number_of_items_mapped" align="center" style="display: none;" >




                <input type="text" class="rules_progress" value="0" data-fgColor="#3c8dbc" data-readonly="true" data-width="90"  data-height="90">
                 <p class="help-block" >
                    <span class="badge bg-green products-with-rules" >

                    </span><br>
                <input type="hidden" name="number_of_products" value="{!! $number_of_records['count'] !!}">

            </p>

        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>
<!-- /.col -->



<div class="col-md-2">
    <div class="box box-default">
        <div class="box-header with-border">
            <i class="fa fa-refresh"></i>
            <h3 class="box-title">{!! trans('messages.rules_statusbox_lbl1') !!}</h3>
        </div>
        <!-- /.box-header -->


        <div class="box-body" align="center" >
            @if(count($feed_rules) == 0 )
                <small class="label bg-gray-active">{!! trans('messages.rules_create_rule_lbl3') !!}</small>

            @endif

            <table class="table table-striped">
                @foreach($feed_rules as $rule)


                <tr data-id="{!! $rule->rule_id !!}" id="row_{!! $rule->rule_id !!}" >
                    <td> <i class="fa fa-align-justify" aria-hidden="true" style="cursor:move"></i> </td>
                    <td>
                        <?php
                                $update_array = ['id'=>$id,'rule_id'=>$rule->rule_id,'url_key'=>$url_key,'channel_type_id'=>$channel_type_id,'channel_feed_id'=>$channel_feed_id];
                                switch($url_key) {
                                    case \App\DfCore\DfBs\Enum\UrlKey::ADWORDS:
                                        $update_array = ['id'=>$id,'rule_id'=>$rule->rule_id,'url_key'=>$url_key,'adwords_feed_id'=>$adwords_feed_id];
                                    break;

                                    case \App\DfCore\DfBs\Enum\UrlKey::BOL:
                                        $update_array = ['id'=>$id,'rule_id'=>$rule->rule_id,'url_key'=>$url_key,'bol_id'=>$bol_id];
                                    break;
                                }


                            ?>

                        <a style="{{  $rule->id == $rule_id ? 'text-decoration:underline;' : ''}}" href="{!! route('rules.create_rules',$update_array) !!}">
                            {!! substr($rule->rule_name,0,100) !!}
                        </a>


                    </td>
                    <td>
                        @if($rule->id != $rule_id)
                        <a href="javascript:void(0);" class="btn btn-danger btn-xs delete_rule" data-rule_id="{!! $rule->rule_id !!}">
                            <i class="fa fa-trash"></i>
                        </a>
                        @endif
                    </td>

                </tr>

                @endforeach

                </table>

            <span class="pull-right rules_create_rule_small">
        <a href="{!! route('rules.create_rules',$create_manual_array) !!}">
               {!! trans('messages.rules_create_rule_lbl4') !!}
            </a>
            </span>

        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>
<!-- /.col -->


<div class="col-md-2">
    <div class="box box-default">
        <div class="box-header with-border">
            <i class="fa fa-feed"></i>
            <h3 class="box-title">{!! trans('messages.feed_preview_lbl5') !!}</h3>
        </div>
        <!-- /.box-header -->

        <div align="center">
            <br>
            <a class="btn btn-app" href="{!! route('import.browse_feed',['feed_id'=>$id]) !!}" target="_blank">
                <i class="fa fa-search"></i>
                {!! trans('messages.feed_preview_lbl4') !!}
            </a>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>
<!-- /.col -->



