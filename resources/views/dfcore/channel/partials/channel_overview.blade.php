<div class="col-md-3">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Kanalen</h3>
        </div>
        <!-- /.box-header -->
        @if(count($channels_from_feed) > 0 )
        <table class="table table-bordered ">

            <tr >
                <th> {!! trans('messages.channel_create_lbl15') !!} </th>
                <th>{!! trans('messages.channel_create_lbl16') !!}</th>

            </tr>

            @foreach($channels_from_feed as $c_from_feed)
                @if($c_from_feed->id != $channel_feed_id)
            <tr>
                <td class="col-md-6">
                    <a href="{!! route('channel.channel_settings',['feed_id'=>$c_from_feed->fk_feed_id,'channel_feed_id'=>$c_from_feed->id]) !!}">
                        {!! $c_from_feed->name !!}
                    </a>

                </td>
                <td class="col-md-1">
                    <a href="javascript:deleteconfirm('{!! trans('messages.channel_create_lbl13') !!}','{!! trans('messages.channel_create_lbl14') !!}','{!! route('channel.remove_channel_feed',
                    ['channel_feed_id'=>$c_from_feed->id,'feed_id'=>$c_from_feed->fk_feed_id]) !!}')"
                       class="btn btn-danger btn-xs">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>

            </tr>
            @endif

            @endforeach


            <td colspan="2">

                <div class="pull-right">
                    <a href="{!! route('channel.channel_settings',['feed_id'=>$feed_id]) !!}">
                        <i class="fa fa-plus"></i>
                        {!! trans('messages.channel_create_lbl19') !!}
                    </a>
                </div>

            </td>


        </table>
            @else
            <p align="center" class="help-block">
            U heeft nog geen kanalen aangemaakt.<br> Maak direct een kanaal aan<br><br>
            </p>
        @endif


        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>