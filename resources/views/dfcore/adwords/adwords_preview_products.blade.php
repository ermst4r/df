@extends('layouts.layout')
@section('backend-content')

    {!! Form::hidden('feed_id',$feed_id) !!}
    {!! Form::hidden('fk_adwords_feed_id',$fk_adwords_feed_id) !!}
    {!! Form::hidden('fk_adgroup_preview_id',$fk_adgroup_preview_id) !!}
    {!! Form::hidden('fk_campaigns_preview_id',$fk_campaigns_preview_id) !!}


    <section class="content adwords_preview_products">



        @include('dfcore.global_partials.feed_wizard',compact('wizard','route_name'))

<a  class="btn btn-block btn-default" style="width: 100px; margin-top:-20px;" href="{!! route('adwords.adwords_preview',['feed_id'=>$feed_id,'fk_adwords_feed_id'=>$fk_adwords_feed_id]) !!}">
    <i class="fa fa-arrow-left"></i>
    {!! trans('messages.adwords_preview_lbl8') !!}
</a>
        <br>
        @if($adwords_feed->updating && $adwords_configuration->live)
            <br>
            <div align="center">
                <img src="/img/wheel.gif" height="64"><br>
                {!! trans('messages.adwords_preview_lbl36') !!}
            </div>
        @endif

        @if($adwords_feed->updating && !$adwords_configuration->live)
            <br>
            <div align="center">
                <img src="/img/wheel.gif" height="64"><br>
                {!! trans('messages.adwords_preview_lbl37') !!}
            </div>
        @endif

        <div class="nav-tabs-custom">


            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">{!! trans('messages.adwords_preview_lbl16') !!}</a></li>
                <li><a href="#tab_2" data-toggle="tab">{!! trans('messages.adwords_preview_lbl17') !!}</a></li>
                <li><a href="#tab_3" data-toggle="tab">{!! trans('messages.adwords_preview_lbl18') !!}</a></li>
                <li><a href="#tab_4" data-toggle="tab">{!! trans('messages.adwords_preview_lbl38') !!}

                        @if(count($ads_errors) > 0)

                            <span class="label label-danger">{!! count($ads_errors) !!} api error(s)</span>
                        @endif


                    </a></li>

            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-info"></i> {!! trans('messages.adwords_preview_lbl2') !!}</h4>
                        {!! trans('messages.adwords_preview_lbl3') !!}
                    </div>


                    <div  id="dfbuilder-adwords-spreadsheet" class="hot-container" style=" margin-top:20px;">
                    </div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">

                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-info"></i> {!! trans('messages.adwords_preview_lbl4') !!}</h4>
                        {!! trans('messages.adwords_preview_lbl5') !!}
                    </div>


                    @include('dfcore.adwords.partial.preview_products',compact('ads'))


                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_3">

                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-info"></i> {!! trans('messages.adwords_preview_lbl6') !!}</h4>
                        {!! trans('messages.adwords_preview_lbl7') !!}
                    </div>

                    @include('dfcore.adwords.partial.preview_ads',compact('ads'))
                </div>
                <!-- /.tab-pane -->


                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_4">

                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-info"></i> {!! trans('messages.adwords_preview_lbl40') !!}</h4>
                        {!! trans('messages.adwords_preview_lbl39') !!}

                    </div>

                    @include('dfcore.adwords.partial.api_error_ads',compact('ads_errors'))
                </div>
                <!-- /.tab-pane -->


            </div>
            <!-- /.tab-content -->
        </div>



    </section>


@stop