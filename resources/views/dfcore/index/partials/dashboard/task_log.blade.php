
@foreach($task_logs as $log)
    <tr>
        <td>{!! $log->created_at !!}</td>
        <td>{!! $log->feed_name !!}</td>
        <td>{!! $log->task !!}</td>
        <td>
            @if($log->status == \App\DfCore\DfBs\Enum\TasklogEnum::BUSY)
                <span class="label label-warning">{!! trans('messages.dashboard_lbl_13') !!}</span>
            @endif

            @if($log->status == \App\DfCore\DfBs\Enum\TasklogEnum::FAILED)
                <span class="label label-danger">{!! trans('messages.dashboard_lbl_14') !!}</span>
            @endif

            @if($log->status == \App\DfCore\DfBs\Enum\TasklogEnum::FINISHED)
                <span class="label label-success">{!! trans('messages.dashboard_lbl_15') !!}</span>
            @endif

            @if($log->status == \App\DfCore\DfBs\Enum\TasklogEnum::PENDING)
                <span class="label label-info">{!! trans('messages.dashboard_lbl_16') !!}</span>
            @endif

        </td>
    </tr>
@endforeach