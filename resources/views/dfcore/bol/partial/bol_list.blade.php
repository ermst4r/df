<div class="col-md-3">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">{!! trans('messages.bol_lbl_9') !!}</h3>
        </div>
        <!-- /.box-header -->
        @if(count($bol_feeds) > 0 )
            <table class="table table-bordered ">

                <tr >
                    <th> {!! trans('messages.channel_create_lbl15') !!} </th>
                    <th>{!! trans('messages.channel_create_lbl16') !!}</th>

                </tr>


                @foreach($bol_feeds as $feeds)
                    @if($feeds->bol_id != $fk_bol_id)
                        <tr>
                            <td class="col-md-6">
                                <a href="{!! route('bol.bol_settings',['feed_id'=>$feeds->feed_id,'fk_bol_id'=>$feeds->bol_id]) !!}">
                                    {!! $feeds->name !!}
                                </a>

                            </td>
                            <td class="col-md-1">
                                <a href="javascript:deleteconfirm('{!! trans('messages.bol_lbl_11') !!}','{!! trans('messages.bol_lbl_12') !!}',
                                '{!! route('bol.remove_bol_feed',['id'=>$feeds->bol_id,'feed_id'=>$feeds->feed_id]) !!}')"
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
                            <a href="{!! route('bol.bol_settings',['feed_id'=>$feed_id]) !!}">
                                <i class="fa fa-plus"></i>
                                {!! trans('messages.bol_lbl_10') !!}
                            </a>
                        </div>

                    </td>
                </tr>

            </table>
        @else
            <p align="center" class="help-block">
                <br>
                {!! trans('messages.bol_lbl_15') !!}
                <br><br>
            </p>
    @endif


    <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>