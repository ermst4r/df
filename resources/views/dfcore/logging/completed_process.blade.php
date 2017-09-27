@extends('layouts.layout')
@section('backend-content')




    <section class="content logging">
        <form method="get">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">{!! trans('messages.log_lbl_7') !!}</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{!! trans('messages.log_lbl_4') !!}</label>
                                <input type="text" class="form-control pull-right" id="start_date" name="start_date" value="{!! $start_date !!}">

                            </div>

                        </div>
                        <!-- /.col -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{!! trans('messages.log_lbl_5') !!}</label>
                                <input type="text" class="form-control pull-right" id="end_date" name="end_date" value="{!! $start_date !!}">
                            </div>

                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">{!! trans('messages.log_lbl_6') !!}</button>
                </div>
            </div>
        </form>

        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> {!! trans('messages.dashboard_lbl_17') !!}</h3>
            </div>
            <div class="box-body ">
                <table class="table table-hover" id="logging">
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
            </div>
            <!-- /.box-body -->
        </div>

    </section>

    <script>

    </script>


@stop