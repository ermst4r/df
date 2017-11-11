<div class="row">

    @if(count($feed_by_store) == 0 )
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green-gradient">
            <div class="inner">
                <h3>{!! trans('messages.dashboard_lbl_6') !!}</h3>

                <p>{!! trans('messages.dashboard_lbl_7') !!}</p>
            </div>
            <div class="icon">
                <i class="fa fa-rocket"></i>
            </div>
            <a href="{!! route('import.selectfeed') !!}" class="small-box-footer">
                {!! trans('messages.dashboard_lbl_5') !!} <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    @endif


    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{!! trans('messages.dasboard_lbl_1',['count'=>count($feed_by_store)]) !!}</h3>

                <p>{!! trans('messages.dasboard_lbl_2') !!}</p>
            </div>
            <div class="icon">
                <i class="fa fa-code"></i>
            </div>
            <a href="{!! route('import.selectfeed') !!}" class="small-box-footer">
                {!! trans('messages.dasboard_lbl_3') !!} <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>



    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-{!! $count_errors == 0 ? 'purple' : 'red' !!}">
            <div class="inner">
                <h3>{!! trans('messages.log_lbl_8',['count'=>$count_errors]) !!} </h3>
                @if($count_errors == 0 )
                    <p>{!! trans('messages.log_lbl_11') !!}</p>
                    @else
                    <p>{!! trans('messages.log_lbl_9') !!}</p>

                @endif

            </div>
            <div class="icon">
                <i class="fa fa-exclamation-triangle"></i>
            </div>
            <a href="{!! route('common.log_report') !!}" class="small-box-footer">
               {!! trans('messages.log_lbl_10') !!} <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>



</div>