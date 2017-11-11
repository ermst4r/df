@extends('layouts.layout')
@section('backend-content')

    <section class="content-header">
        <h1>
            Dashboard
            <small>Version {!! DFBUILDER_VERSION !!}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <section class="content">


       @include('dfcore.index.partials.dashboard.tiles',compact('count_errors','feed_by_store'))
















        <!-- Latest feeds -->
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-feed"></i> {!! trans('messages.dasboard_lbl_4') !!}</h3>
            </div>
            @include('dfcore.import.partials.manage_feeds_partial',['feeds'=>$feed_by_store])
        </div>




           <div class="row">

               <div class="col-md-6">

                   <div class="box box-info">
                       <div class="box-header with-border">
                           <h3 class="box-title"> <i class="fa fa-file-text-o"></i> {!! trans('messages.dashboard_lbl_8') !!}</h3>


                       </div>
                       <!-- /.box-header -->
                       <div class="box-body">
                           <div class="table-responsive">
                               @include('dfcore.index.partials.dashboard.completed',compact('task_logs'))
                           </div>
                           <!-- /.table-responsive -->
                       </div>
                       <!-- /.box-body -->
                       <div class="box-footer clearfix">

                           <a href="{!! route('common.completed_process') !!}" class="btn btn-sm btn-default btn-flat pull-right">Toon Voltooide processen</a>
                       </div>
                       <!-- /.box-footer -->
                   </div>
               </div>



               <div class="col-md-6">

                   <div class="box box-danger">
                       <div class="box-header with-border">
                           <h3 class="box-title"> <i class="fa fa-feed"></i> {!! trans('messages.log_lbl_17') !!}</h3>
                       </div>
                       <!-- /.box-header -->
                       <div class="box-body">
                           <div class="table-responsive">
                               @include('dfcore.logging.partials.feedlog_partial',['log_message'=>$log_message,'stop'=>10])
                           </div>
                           <!-- /.table-responsive -->
                       </div>
                       <!-- /.box-body -->
                       <div class="box-footer clearfix">
                           <a href="{!! route('common.all_feed_log') !!}" class="btn btn-sm btn-default btn-flat pull-right">{!! trans('messages.log_lbl_18') !!}</a>
                       </div>
                       <!-- /.box-footer -->
                   </div>

               </div>
           </div>
















    </section>
@stop