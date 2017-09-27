@extends('layouts.layout')
@section('backend-content')

    {!! Form::hidden('feed_id',$feed_id) !!}
    {!! Form::hidden('fk_adwords_feed_id',$fk_adwords_feed_id) !!}


    <section class="content adwords_spreadsheet">

        @include('dfcore.global_partials.feed_wizard',compact('wizard','route_name'))

        <a  class="btn btn-block btn-default" style="width: 100px; margin-left:20px;" href="{!! route('adwords.adwords_preview',['feed_id'=>$feed_id,'fk_adwords_feed_id'=>$fk_adwords_feed_id]) !!}">
            <i class="fa fa-arrow-left"></i>
            {!! trans('messages.adwords_preview_lbl8') !!}
        </a>
        <br><br>
        @if($count_preview_ads  == 0 )
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-warning"></i> {!! trans('messages.error_lbl_3') !!}</h4>
                {!! trans('messages.error_lbl_4') !!}
            </div>
        @endif





        <div class="col-lg-12">


                @if($count_preview_ads > 0 )
                <div  id="dfbuilder-adwords-spreadsheet" class="hot-container">
                </div>

                @endif






        </div>


    </section>


@stop