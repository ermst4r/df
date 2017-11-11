@extends('layouts.layout')
@section('backend-content')


    @component('dfcore.import.components.progress')
    <li class="active"><a href="{!! route('import.selectfeed') !!}">
            <h4 class="list-group-item-heading">{!! trans('messages.import_progress_lbl1') !!}</h4>
            <p class="list-group-item-text">{!! trans('messages.import_progress_lbl4') !!}</p>
        </a></li>
    <li class="disabled" ><a href="#">
            <h4 class="list-group-item-heading">{!! trans('messages.import_progress_lbl2') !!}</h4>
            <p class="list-group-item-text">{!! trans('messages.import_progress_lbl5') !!}</p>
        </a></li>
    <li class="disabled"><a href="#">
            <h4 class="list-group-item-heading">{!! trans('messages.import_progress_lbl3') !!}</h4>
            <p class="list-group-item-text"> {!! trans('messages.import_progress_lbl6') !!}</p>
        </a></li>
    @endcomponent

        <div class="box box-default format_selector">
            <div class="box-header with-border">
                <i class="fa fa-file"></i>

                <h3 class="box-title">{!! trans('messages.import_selectfeed_lbl1') !!} </h3>
            </div>



            <div class="row format_margin " style="margin-left:10px;" >
                <div class='list-group gallery'>
                    <div class='col-sm-4 col-xs-6 col-md-3 col-lg-3'>
                        <a class="fancybox thumbnail"  href="javascript:openFeedDialog('csv')"
                           data-placement="bottom"
                           data-original-title="{!! trans('messages.import_mapping_lbl6') !!}" data-toggle="tooltip">


                            <img class="img-responsive" alt="" src="/img/csv.png" />
                        </a>
                    </div> <!-- col-6 / end -->
                    <div class='col-sm-4 col-xs-6 col-md-3 col-lg-3'>
                        <a class="fancybox thumbnail"   href="javascript:openFeedDialog('xml')"
                           data-placement="bottom"
                           data-original-title="{!! trans('messages.import_mapping_lbl7') !!}" data-toggle="tooltip"
                        >
                            <img class="img-responsive" alt="" src="/img/xml.png" />
                        </a>
                    </div> <!-- col-6 / end -->


                    <div class='col-sm-4 col-xs-6 col-md-3 col-lg-3'>
                        <a class="fancybox thumbnail"   href="javascript:openFeedDialog('txt')"
                           data-placement="bottom"
                           data-original-title="{!! trans('messages.import_mapping_lbl8') !!}" data-toggle="tooltip"
                        >
                            <img class="img-responsive" alt="" src="/img/txt.png" />
                        </a>
                    </div> <!-- col-6 / end -->







                </div> <!-- list-group / end -->
            </div> <!-- row / end -->







        </div>
        <!-- /.box -->

        <div class="box box-info url_parser"  >
            <div class="box-header with-border">
                <h3 class="box-title">{!! trans('messages.import_selectfeed_lbl2') !!}</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

                {!! Form::open(['method'=>'POST','route'=>'import.post_feed','id'=>'importFeedForm','class'=>'form-horizontal']) !!}
                {!! csrf_field() !!}
                <div class="box-body">

                    <div class="form-group">
                        <label class="col-sm-2 control-label">{!! trans('messages.import_selectfeed_lbl6') !!} </label>

                        <div class="col-sm-10">

                            {!! Form::input('text','feed_name',null,['class'=>'form-control','placeholder'=> trans('messages.import_selectfeed_lbl7')]) !!}


                        </div>
                    </div>
                    {!!  Form::hidden('feed_type') !!}

                    <div class="form-group">
                        <label class="col-sm-2 control-label">{!! trans('messages.import_selectfeed_lbl3') !!} </label>

                        <div class="col-sm-10">

                            {!! Form::input('text','feed_url',null,['class'=>'form-control','placeholder'=> 'http://www.feed.com']) !!}
                        </div>
                    </div>




                    <div class="form-group">

                        <label class="col-sm-2 control-label">{{ trans('messages.update_key_lbl8') }}</label>
                        <div class="col-sm-10">
                        {{ Form::select('update_interval', \App\DfCore\DfBs\Enum\UpdateIntervals::updateSelectBoxArray(),null,['class'=>'form-control']) }}
                        </div>
                    </div>



                    <div class="form-group">

                        <label class="col-sm-2 control-label">{{ trans('messages.import_selectfeed_lbl12') }}</label>
                        <div class="col-sm-10">
                            {{ Form::select('active', [1=>trans('messages.import_selectfeed_lbl9'),0=>trans('messages.import_selectfeed_lbl10')],null,['class'=>'form-control']) }}
                            <p class="help-block">
                                {!! trans('messages.import_selectfeed_lbl11') !!}
                            </p>
                        </div>
                    </div>








                    <div class="form-group xml_advanced_settings" style="display: none" >
                        <label class="col-sm-2 control-label"> </label>
                        <div class="col-sm-10">
                            <a href="javascript:void(0);" > <i class="fa fa-gear"></i> {!! trans('messages.import_selectfeed_lbl17') !!}</a>
                        </div>
                    </div>

                    <div class="xml_advanced_settings_field" style="display: none">


                        <div class="form-group" >
                            <label class="col-sm-2 control-label">{!! trans('messages.import_selectfeed_lbl19') !!}</label>
                            <div class="col-sm-10">
                                {{ Form::select('feed_custom_parser', \App\DfCore\DfBs\Import\Xml\CustomXmlParser\Register::getXmlParsers(),null,['class'=>'form-control']) }}
                                <p class="help-block">
                                    {!! trans('messages.import_selectfeed_lbl18') !!}
                                </p>
                            </div>

                        </div>


                        <div class="form-group" >
                            <label class="col-sm-2 control-label">{!! trans('messages.import_selectfeed_lbl13') !!}</label>
                            <div class="col-sm-10">
                                {!! Form::input('text','xml_root_node',null,['class'=>'form-control']) !!}
                                <p class="help-block">
                                    {!! trans('messages.import_selectfeed_lbl14') !!}
                                </p>
                            </div>
                            </div>






                        <div class="form-group" >
                            <label class="col-sm-2 control-label">Node follow up</label>
                            <div class="col-sm-10">
                                {!! Form::input('text','prepend_nodes',null,['class'=>'form-control']) !!}
                                <p class="help-block">
                                    {!! trans('messages.import_selectfeed_lbl15') !!}
                                </p>
                            </div>

                        </div>


                        <div class="form-group" >
                            <label class="col-sm-2 control-label">Node identifier</label>
                            <div class="col-sm-10">
                                {!! Form::input('text','prepend_identifier',null,['class'=>'form-control']) !!}
                                <p class="help-block">
                                  {!! trans('messages.import_selectfeed_lbl16') !!}
                                </p>
                            </div>

                        </div>



                    </div>







                    <!-- /.box-body -->
                <div class="box-footer">

                    <button type="button" class="btn btn-primary pull-left btn-import-cancel">{!! trans('messages.import_selectfeed_lbl5') !!}</button>
                    <button type="submit" class="btn btn-success pull-right ">{!! trans('messages.import_selectfeed_lbl4') !!}</button>


                </div>
                <!-- /.box-footer -->
                {!! Form::close() !!}
        </div>


@stop