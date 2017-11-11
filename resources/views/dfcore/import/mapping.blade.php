@extends('layouts.layout')
@section('backend-content')


    <!-- Modal -->
    {!! Form::open(['method'=>'POST','route'=>'import.post_feed','id'=>'updateRootNode']) !!}
    <div id="open_root_node" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{!! trans('messages.import_mapping_lbl12') !!}</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">



                        <div class="form-group"  >
                            <label >{!! trans('messages.import_mapping_lbl16') !!} </label>
                            <div >
                                {!! Form::hidden('id',$id) !!}
                                {!! Form::hidden('feed_type',$type) !!}
                                {!! Form::hidden('update_root_node',1) !!}
                                {!! Form::input('text','xml_root_node',$feed->xml_root_node,['class'=>'form-control','placeholder'=>'rss']) !!}

                            </div>
                        </div>


                        <div class="form-group"  >
                            <label >Node follow up</label>
                            <div >
                                {!! Form::input('text','prepend_nodes',$feed->prepend_nodes,['class'=>'form-control','placeholder'=>'channel.item']) !!}
                                <p class="help-block">
                                    {!! trans('messages.import_selectfeed_lbl15') !!}
                                </p>
                            </div>
                        </div>


                        <div class="form-group"  >
                            <label >Node identifier</label>
                            <div >
                                {!! Form::input('text','prepend_identifier',$feed->prepend_identifier,['class'=>'form-control']) !!}
                                <p class="help-block">
                                    {!! trans('messages.import_selectfeed_lbl16') !!}
                                </p>
                            </div>
                        </div>




                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" >{!! trans('messages.import_mapping_lbl14') !!}</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{!! trans('messages.import_mapping_lbl15') !!}</button>
                </div>
            </div>

        </div>
    </div>
    {!! Form::close() !!}

    @component('dfcore.import.components.progress')
        <li class="" ><a href="javascript:void(0);">
                <h4 class="list-group-item-heading">{!! trans('messages.import_progress_lbl1') !!}</h4>
                <p class="list-group-item-text">{!! trans('messages.import_progress_lbl4') !!}</p>
            </a></li>
        <li class="active" ><a href="{!! route('import.mapping',['id'=>$id,'type'=>$type]) !!}">
                <h4 class="list-group-item-heading">{!! trans('messages.import_progress_lbl2') !!}</h4>
                <p class="list-group-item-text">{!! trans('messages.import_progress_lbl5') !!}</p>
            </a></li>
        <li class="disabled"><a href="#">
                <h4 class="list-group-item-heading">{!! trans('messages.import_progress_lbl3') !!}</h4>
                <p class="list-group-item-text"> {!! trans('messages.import_progress_lbl6') !!}</p>
            </a></li>
    @endcomponent


    <section class="mapping">

        @if($type == \App\DfCore\DfBs\Enum\ImportType::XML && empty($mapping_info['root_node']) && $file_saved)
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-warning"></i> {!! trans('messages.import_progress_lbl7') !!}</h4>
                {!! trans('messages.import_progress_lbl8') !!}
            </div>
        @endif


        <?php
        $counter = 0;
        ?>

        {!! Form::open(['method'=>'POST','route'=>'import.post_mapping','id'=>'mapForm']) !!}
        {!! Form::hidden('fk_feed_id',$id) !!}
        {!! Form::hidden('type',$type) !!}
        {!! Form::hidden('has_composite_key',$has_composite_key) !!}
        {!! Form::hidden('update_root_node',0) !!}
        @if($file_saved )
            @if($type == \App\DfCore\DfBs\Enum\ImportType::XML && !empty($mapping_info['root_node']) ||  $type == \App\DfCore\DfBs\Enum\ImportType::CSV || $type == \App\DfCore\DfBs\Enum\ImportType::TXT)
                <button type="submit" class="btn btn-success">{!! trans('messages.import_mapping_lbl10') !!}</button>

                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-default"><i class="fa fa-cog"></i>  </button>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li>  <a href="{!! route('import.composite_key',['id'=>$id]) !!}" ><i class="fa fa-cog"></i> Key settings  </a></li>
                        @if($type == \App\DfCore\DfBs\Enum\ImportType::XML)
                            <li>  <a href="" data-toggle="modal" data-target="#open_root_node" ><i class ="fa fa-cog"></i> XML settings </a></li>
                        @endif

                    </ul>
                </div>




            @endif

        @endif

        {!! Form::hidden('type',$type) !!}
        {!! Form::hidden('id',$id) !!}
        {!! Form::hidden('file_saved',$file_saved) !!}

        {!! csrf_field() !!}

        <section class="content mapping">




            <!-- left column -->
            <div class="row">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Mapping </h3>
                    </div>
                    <div class="box-body">

                        @if(!$file_saved)
                            @include('dfcore.import.partials.downloading_feed')
                        @else


                            @if(!is_null($mapping) && count($mapping) > 50 ||  !is_null($mapping) && count($mapping) == 0)
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h4><i class="icon fa fa-ban"></i> {!! trans('messages.import_progress_lbl10') !!}</h4>
                                    {!! trans('messages.import_progress_lbl9') !!}
                                </div>
                            @else

                                @if($is_mapped)
                                    @include('dfcore.import.partials.mapped',compact('mapping','counter','has_composite_key'))
                                @else
                                    @include('dfcore.import.partials.unmapped',compact('mapping','counter','has_composite_key'))
                                @endif
                            @endif



                        @endif

                    </div>
                </div>
                <!-- /.box -->
            </div>


        {!! Form::close() !!}

        <!-- /.row -->
        </section>

    </section>





@stop