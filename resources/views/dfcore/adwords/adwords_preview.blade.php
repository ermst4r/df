@extends('layouts.layout')
@section('backend-content')

    {!! Form::hidden('feed_id',$feed_id) !!}
    {!! Form::hidden('fk_adwords_feed_id',$fk_adwords_feed_id) !!}


    <section class="content adwords_preview">


        @if(!is_null($adwords_feed) && !is_null($adwords_feed->adwords_api_message))
            @include('dfcore.adwords.partial.adwords_feed_error_msg',['error_msg'=>$adwords_feed->adwords_api_message])
        @endif
            @if(!is_null($adwords_feed) && is_null($adwords_feed->adwords_api_message))
                @if($adwords_configuration->live)
                    <div class="alert alert-success alert-dismissible">
                        <h4><i class="icon fa fa-check"></i>{!! trans('messages.adwords_preview_lbl32') !!}</h4>
                    </div>
                @else
                    <div class="alert alert-info alert-dismissible">
                        <h4><i class="icon fa fa-info"></i> {!! trans('messages.adwords_preview_lbl33') !!}</h4>
                    </div>
                @endif
            @endif

        @include('dfcore.global_partials.feed_wizard',compact('wizard','route_name'))







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

    @if(!$adwords_feed->updating)

           {!! Form::open(['method'=>'POST','route'=>'adwords.preview_ad','id'=>'post_adwords_settings']) !!}
           {!! Form::hidden('fk_adwords_feed_id',$fk_adwords_feed_id) !!}
           {!! Form::hidden('feed_id',$feed_id) !!}
           @if($adwords_configuration->live_option == \App\DfCore\DfBs\Enum\AdwordsOptions::ALL_LIVE
            || $adwords_configuration->live_option == \App\DfCore\DfBs\Enum\AdwordsOptions::CAMPAIGN_PAUSED
            || $adwords_configuration->live_option == \App\DfCore\DfBs\Enum\AdwordsOptions::AD_PAUSED

             )

                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-info"></i> {!! trans('messages.adwords_feed_lbl17') !!}</h4>
                        {!! trans('messages.adwords_feed_lbl18') !!}
                    </div>

               <button class="btn btn-app btn bg-green"
                       data-toggle="tooltip"
                       data-original-title="{!! trans('messages.adwords_preview_lbl35') !!}" name="save">
                   <i class="fa fa-upload"></i>{!! trans('messages.adwords_preview_lbl34') !!}
               </button>


           @else

                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-info"></i> {!! trans('messages.adwords_feed_lbl13') !!}</h4>
                        {!! trans('messages.adwords_feed_lbl12') !!}
                    </div>


                    <button class="btn btn-app btn bg-yellow"
                       data-toggle="tooltip"
                       data-original-title="{!! trans('messages.adwords_preview_lbl19') !!}" name="save">
                   <i class="fa fa-repeat"></i>{!! trans('messages.adwords_preview_lbl20') !!}
               </button>


           @endif

           <a class="btn btn-app bg-blue "  href="{!! route('adwords.adwords_spreadsheet_modus',['feed_id'=>$feed_id,'fk_adwords_feed_id'=>$fk_adwords_feed_id]) !!}" data-toggle="tooltip"
              data-original-title="{!! trans('messages.adwords_preview_lbl21') !!}">
               <i class="fa fa-excel" aria-hidden="true" title="Copy to use file-excel-o"></i>
               Spreadsheet
           </a>

           {!! Form::close() !!}
           <br>
           <div class="box box-default">
               <div class="box-header with-border">
                   <h3 class="box-title"><i class="fa fa-tag"></i> {!! trans('messages.adwords_preview_lbl41') !!}</h3>
               </div>
               <div class="box-body ">
                   <table class="table table-hover" id="campaign_preview">
                       <thead><tr>
                           <th>{!! trans('messages.adwords_preview_lbl12') !!}</th>
                           <th>{!! trans('messages.adwords_preview_lbl13') !!}</th>
                           <th>{!! trans('messages.adwords_preview_lbl14') !!}</th>
                           <th>{!! trans('messages.adwords_preview_lbl15') !!}</th>
                           <th>{!! trans('messages.adwords_preview_lbl42') !!}</th>
                       </tr>
                       </thead>
                       <tbody>
                       @foreach($ad_campaigns as $campaigns)
                       <tr  class='clickable-row' data-href='{!! route('adwords.adwords_preview_products',['fk_adwords_feed_id'=>$fk_adwords_feed_id,
                               'fk_campaigns_preview_id'=>$campaigns->campaign_preview_id,
                               'fk_adgroup_preview_id'=>$campaigns->adgroup_preview_id ]) !!}'>
                           <td>

                               {!! $campaigns->campaign_name !!}

                           </td>
                           <td>{!! $campaigns->adgroup_name !!}</td>
                           <td>{!! $campaigns->no_of_ads !!}</td>
                           <td>
                               @if($campaigns->count_errors == 0 )
                                   <small class="label  bg-green"><i class="fa fa-check"></i> </small>
                                   @else
                                   <small class="label  bg-red"><i class="fa fa-error"></i> {!! $campaigns->count_errors !!} errors </small>
                               @endif

                           </td>
                           <td>
                                <i class="fa fa-search"></i>
                           </td>
                       </tr>
                       @endforeach
                       </tbody>

                   </table>
               </div>
               <!-- /.box-body -->
           </div>
        @endif



       </section>


   @stop