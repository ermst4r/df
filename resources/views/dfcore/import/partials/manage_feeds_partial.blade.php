<div class="box-body ">
    <table class="table table-hover" id="manage_feeds">
        <thead><tr>
            <th>{!! trans('messages.manafeeds_lbl2') !!}</th>
            <th>{!! trans('messages.manafeeds_lbl3') !!}</th>
            <th>{!! trans('messages.manafeeds_lbl22') !!}</th>

            <th>{!! trans('messages.manafeeds_lbl6') !!}</th>
            <th>{!! trans('messages.manafeeds_lbl7') !!}</th>
            <th>{!! trans('messages.manafeeds_lbl8') !!}</th>
            <th>{!! trans('messages.manafeeds_lbl9') !!}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($feeds as $feed)
            <tr>
                <td class="col-xs-1">{!! $feed->id !!}</td>
                <td class="col-md-2">{!! $feed->feed_name !!}</td>
                <td class="col-xs-1">{!! $feed->fetched_records !!}</td>
                <td class="col-xs-1">
                    @if($feed->feed_type === \App\DfCore\DfBs\Enum\ImportType::CSV)
                        <i class="fa fa-fw fa-file-excel-o" title="{!! \App\DfCore\DfBs\Enum\ImportType::CSV !!}"></i>
                    @endif

                    @if($feed->feed_type === \App\DfCore\DfBs\Enum\ImportType::XML)
                        <i class="fa fa-file-code-o" title="{!! \App\DfCore\DfBs\Enum\ImportType::XML !!}"></i>
                    @endif

                    @if($feed->feed_type === \App\DfCore\DfBs\Enum\ImportType::TXT)
                        <i class="fa fa-fw fa-file-text-o" title="{!! \App\DfCore\DfBs\Enum\ImportType::TXT !!}"></i>
                    @endif
                </td>




                <td class="col-md-2" id="import_state_{!! $feed->id !!}"  >
                    @if($feed->feed_status === \App\DfCore\DfBs\Enum\ImportStatus::PENDING)
                        <span class="label label-warning"> {!! trans('messages.manafeeds_lbl12') !!}</span>
                    @endif

                    @if($feed->feed_status === \App\DfCore\DfBs\Enum\ImportStatus::IMPORTED)
                        <span class="label label-info"> <i class="fa fa-check"></i> {!! trans('messages.manafeeds_lbl13') !!}</span>
                    @endif

                    @if($feed->feed_status === \App\DfCore\DfBs\Enum\ImportStatus::IMPORTING)
                        <span class="label label-primary"> <i class="fa fa-refresh fa-spin"></i> {!! trans('messages.manafeeds_lbl14') !!} </span>
                    @endif


                    @if($feed->feed_status === \App\DfCore\DfBs\Enum\ImportStatus::FAILED)
                        <span class="label label-danger"> <i class="fa fa-error"></i> {!! trans('messages.manafeeds_lbl15') !!}</span>
                    @endif


                </td>
                <td class="col-md-2" id="updated_at_{!! $feed->id !!}">
                    @if(is_null($feed->feed_updated))
                        N/A
                    @else
                        {!! $feed->feed_updated !!}
                    @endif

                </td>
                <td class="col-md-2">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default"><i class="fa fa-cog"></i>  </button>
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{!! route('import.browse_feed',['id'=>$feed->id]) !!}" target="_blank"><i class="fa fa-search" aria-hidden="true"></i>
                                    {!! trans('messages.feed_preview_lbl4') !!}</a></li>
                            <li><a href="{!! route('import.mapping',['id'=>$feed->id,'type'=>$feed->feed_type]) !!}"><i class="fa fa-tasks" aria-hidden="true"></i> {!! trans('messages.manafeeds_lbl16') !!}</a></li>
                            <li><a href="javascript:void(0)" class="update_feed" data-feed_id="{!! $feed->id !!}"><i class="fa fa-clock-o" aria-hidden="true"></i>
                                    {!! trans('messages.manafeeds_lbl17') !!}</a></li>
                            <li><a href="javascript:deleteconfirm('{!! trans('messages.manafeeds_lbl20') !!}','{!! trans('messages.manafeeds_lbl21') !!}','{!! route('import.remove_feed',['id'=>$feed->id]) !!}');"><i class="fa fa-trash-o" aria-hidden="true"></i>
                                    {!! trans('messages.manafeeds_lbl19') !!}</a></li>

                        </ul>
                    </div>



                </td>
            </tr>
        @endforeach


        </tbody></table>
</div>
