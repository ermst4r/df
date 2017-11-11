<div class="col-md-3">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">{!! trans('messages.adwords_feed_lbl8') !!}</h3>
        </div>
        <!-- /.box-header -->
        @if(count($adwords_feeds) > 0 )
            <table class="table table-bordered ">

                <tr >
                    <th> {!! trans('messages.channel_create_lbl15') !!} </th>
                    <th>{!! trans('messages.channel_create_lbl16') !!}</th>

                </tr>

                @foreach($adwords_feeds as $feeds)
                    @if($feeds->id != $adwords_feed_id)
                        <tr>
                            <td class="col-md-6">
                                <a href="{!! route('adwords.adwords_feed',['feed_id'=>$feeds->fk_feed_id,'channel_feed_id'=>$feeds->id]) !!}">
                                    {!! $feeds->name !!}
                                </a>

                            </td>
                            <td class="col-md-1">
                                <a href="javascript:deleteconfirm('{!! trans('messages.adwords_feed_lbl14') !!}','{!! trans('messages.adwords_feed_lbl15') !!}',
                                '{!! route('adwords.remove_adwords_feed',['id'=>$feeds->id,'feed_id'=>$feeds->fk_feed_id]) !!}')"
                                   class="btn btn-danger btn-xs">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>

                        </tr>
                    @endif

                @endforeach


            <tr>
                <td colspan="2">

                    <div class="pull-right">
                        <a href="{!! route('adwords.adwords_feed',['feed_id'=>$feed_id]) !!}">
                            <i class="fa fa-plus"></i>
                            {!! trans('messages.adwords_feed_lbl16') !!}
                        </a>
                    </div>

                </td>
            </tr>

            </table>
        @else
            <p align="center" class="help-block">
                <br>
                {!! trans('messages.adwords_feed_lbl9') !!}
                <br><br>
            </p>
    @endif


    <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>