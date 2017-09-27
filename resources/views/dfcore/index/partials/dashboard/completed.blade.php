<table class="table no-margin">
    <thead>
    <tr>
        <th>{!! trans('messages.dashboard_lbl_9') !!}</th>
        <th>{!! trans('messages.dashboard_lbl_10') !!}</th>
        <th>{!! trans('messages.dashboard_lbl_11') !!}</th>
        <th>{!! trans('messages.dashboard_lbl_12') !!}</th>
    </tr>
    </thead>
    <tbody>
    @include('dfcore.index.partials.dashboard.task_log',compact('task_logs'))

    </tbody>
</table>