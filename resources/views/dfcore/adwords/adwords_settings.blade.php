@extends('layouts.layout')
@section('backend-content')


    {!! Form::hidden('number_of_ads',$number_of_ads) !!}
    {!! Form::hidden('number_of_keywords',$number_of_keywords) !!}
    {!! Form::hidden('number_of_neg_keywords',$number_of_neg_keywords) !!}
    {!! Form::hidden('backup_ad',0) !!}

    {!! Form::open(['method'=>'POST','route'=>'adwords.post_adwords_settings','id'=>'post_adwords_settings']) !!}
    {!! Form::hidden('feed_id',$feed_id) !!}
    {!! Form::hidden('fk_adwords_feed_id',$fk_adwords_feed_id) !!}

    @if(!is_null($adwords_configuration))
        {!! Form::hidden('adwords_configuration_id',$adwords_configuration->id) !!}
    @endif

    <section class="content adwords_settings">





        <div class="col-md-12">

            @if(!is_null($adwords_feed) && !is_null($adwords_feed->adwords_api_message))
                @include('dfcore.adwords.partial.adwords_feed_error_msg',['error_msg'=>$adwords_feed->adwords_api_message])
            @endif

        @if(!is_null($adwords_feed) && is_null($adwords_feed->adwords_api_message))
            @if($adwords_configuration->live)
                <div class="alert alert-success alert-dismissible">
                    <h4><i class="icon fa fa-info"></i>{!! trans('messages.adwords_preview_lbl32') !!}</h4>
                </div>
                @else
                    <div class="alert alert-info alert-dismissible">
                        <h4><i class="icon fa fa-info"></i> {!! trans('messages.adwords_preview_lbl33') !!}</h4>
                    </div>
                @endif
            @endif



                @include('dfcore.global_partials.feed_wizard',compact('wizard','route_name'))



                        <div class="box-group" id="accordion">
                            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                            <div class="panel box box-primary">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" class="">
                                            <i class="fa fa-cog"></i>    {!! trans('messages.adwords_main_lbl1') !!}
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="true" style="">
                                    <div class="box-body">
                                        @include('dfcore.adwords.partial.adwords_settings_form',compact('adwords_configuration','target_countries'))
                                </div>
                            </div>


                            <div class="panel box box-primary">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed" aria-expanded="false">
                                            <i class=" fa fa-newspaper-o" aria-hidden="true"></i>  {!! trans('messages.adwords_main_lbl2') !!}
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse  in  " aria-expanded="false">
                                    <div class="box-body">
                                        @include('dfcore.adwords.partial.adwords_ad_templates',['item_id'=>1,'adwords_ads'=>$adwords_ads,'backup'=>false])
                                    </div>
                                </div>
                            </div>



                            <div class="panel box box-primary">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" class="collapsed" aria-expanded="false">
                                            <i class="fa fa-fw fa-header"></i>
                                            {!! trans('messages.adwords_main_lbl3') !!}
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="panel-collapse collapse " aria-expanded="false">
                                    <div class="box-body">
                                        @include('dfcore.adwords.partial.keywords',compact('adwords_keywords'))
                                    </div>
                                </div>
                            </div>




                                <div class="panel box box-primary">
                                    <div class="box-header with-border">
                                        <h4 class="box-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#four" class="collapsed" aria-expanded="false">
                                                <i class="fa fa-fw fa-header"></i>
                                                {!! trans('messages.adwords_main_lbl4') !!}
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="four" class="panel-collapse collapse " aria-expanded="false">
                                        <div class="box-body">
                                            @include('dfcore.adwords.partial.keyword_negative',compact('adwords_negative_keywords'))
                                        </div>
                                    </div>
                                </div>







                                <div class="panel box box-primary">
                                    <div class="box-header with-border">
                                        <h4 class="box-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#five" class="collapsed" aria-expanded="false">
                                                <i class="fa fa-fw fa-globe"></i>
                                                {!! trans('messages.adwords_main_lbl5') !!}
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="five" class="panel-collapse collapse " aria-expanded="false">
                                        <div class="box-body">
                                            @include('dfcore.adwords.partial.adwords_targeting',compact('adwords_target','target_countries','target_languages','adwords_configuration'))
                                        </div>
                                    </div>
                                </div>





                        </div>












            <div class="col-md-2" style="margin-left:-15px;">
                <button  class="btn btn-block btn-success btn-lg" name="save"  data-toggle="tooltip">
                    <i class="fa fa-save"></i> Save All
                </button>

            </div>
        </div>


        {!! Form::close() !!}

    </section>



@stop