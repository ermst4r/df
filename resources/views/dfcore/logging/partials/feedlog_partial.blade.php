<table class="table table-hover" id="logging">
    <thead>


    <tr>
        <th>{!! trans('messages.log_lbl_2') !!}</th>
        <th>{!! trans('messages.log_lbl_13') !!}</th>
        <th>{!! trans('messages.log_lbl_14') !!}</th>
        <th>{!! trans('messages.log_lbl_1') !!}</th>


    </tr>
    </thead>

    <tbody>
    <?php
    $counter = 0;?>
    @foreach($log_message as $log)
        <tr>
            <td class="col-md-2"> {!! $log->log_date !!} </td>
            <td class="col-md-2"> {!! $log->feed_name !!} </td>
            <td class="col-md-2">{!! $log->log_type !!}</td>
            <td class="col-md-5">{!! $log->log_message !!}</td>




        </tr>
        <?php $counter ++ ;?>
         @if($counter == $stop && $stop != 0)
             @break
         @endif

    @endforeach



    </tbody>


</table>