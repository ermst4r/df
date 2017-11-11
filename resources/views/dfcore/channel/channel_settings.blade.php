@extends('layouts.layout')
@section('backend-content')


<?php

$name = '';
$fk_channel_id = 0;
$channel_feed_id = 0;
$fk_country_id = DFBUILDER_DEFAULT_COUNTRY;
$active = 1;
$fk_channel_type_id = 0;
if(!is_null($channel_feed)) {
    $name = $channel_feed->name;
    $fk_country_id = $channel_feed->fk_country_id;
    $fk_channel_id = $channel_feed->fk_channel_id;
    $fk_channel_type_id = $channel_feed->fk_channel_type_id;
    $active = $channel_feed->active;
    $channel_feed_id = $channel_feed->id;

}
?>




    <section class="content channel">
        <div class="row">
            <div class="col-md-9">
        @if(!is_null($channel_feed))
            @include('dfcore.global_partials.feed_wizard',compact('channel_wizard','route_name'))
            @else
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info"></i> {!! trans('messages.channel_create_lbl11') !!}</h4>
                 {!! trans('messages.channel_create_lbl10') !!}
            </div>
        @endif



        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('messages.channel_create_lbl1') }} ( {!! $feed->feed_name !!} ) </h3>
            </div>

            <!-- /.box-header -->
            <!-- form start -->
            @if(is_null($channel_feed))
                {!! Form::open(['method'=>'POST','route'=>'channel.post_channel_setting','role'=>'form' ,'id' => 'channel_settings']) !!}
            @else
                {!! Form::model($channel_feed,['method'=>'POST','route'=>'channel.post_channel_setting','id'=>'channel_settings']) !!}
            @endif



            {!! Form::hidden('feed_id',$feed_id) !!}
            {!! Form::hidden('default_country',$fk_country_id) !!}
            {!! Form::hidden('selected_channel',$fk_channel_id) !!}
            {!! Form::hidden('channel_feed_id',$channel_feed_id) !!}
            {!! Form::hidden('channel_type_id',$fk_channel_type_id) !!}

                <div class="box-body">
                    <div class="form-group">
                        <label for="">{{ trans('messages.channel_create_lbl2') }}</label>
                        {!! Form::input('text','name',null,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">{{ trans('messages.channel_create_lbl3') }}</label>
                        <select class="select_country form-control" name="fk_country_id">
                            <option></option>
                            @foreach($channel_countries as $country)

                            <option value="{!! $country->id !!}" data-id="{!! $country->id !!}"
                                    {{  $fk_country_id == $country->id ? 'selected' : '' }}>
                              {!! $country->country !!}
                            </option>
                            @endforeach


                        </select>
                    </div>

                    <div class="form-group">
                        <label >{{ trans('messages.channel_create_lbl5') }}</label>
                        <select class="select_channels form-control" id="select_channels" name="fk_channel_id">
                            <option> </option>
                        </select>
                    </div>


                    <div class="form-group">

                        <label >{{ trans('messages.channel_create_lbl12') }}</label>
                        <select class="channel_type form-control" id="channel_type" name="fk_channel_type_id">
                        </select>
                    </div>


                    <div class="form-group">
                        <label >{{ trans('messages.channel_create_lbl6') }}</label>
                        <select class="is_active form-control" id="is_active" name="active">
                            <option value="{!! \App\DfCore\DfBs\Enum\Channel::CHANNEL_ACTIVE !!}">{!! trans('messages.channel_create_lbl7') !!}</option>
                            <option value="{!! \App\DfCore\DfBs\Enum\Channel::CHANNEL_INACTIVE !!}">{!! trans('messages.channel_create_lbl8') !!}</option>
                        </select>
                    </div>


                    <div class="form-group">
                        <label >{{ trans('messages.update_key_lbl8') }}</label>
                        {{ Form::select('update_interval', \App\DfCore\DfBs\Enum\UpdateIntervals::updateSelectBoxArray(),$update_interval,['class'=>'form-control select_channels']) }}
                    </div>

                    @if(!is_null($channel_feed))
                    <div class="form-group">
                        <label >{{ trans('messages.update_key_lbl9') }}</label>
                        <input type="text" disabled="disabled" value="{!! $channel_feed->next_update !!}" class="form-control">
                    </div>
                    @endif

                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    @if($channel_feed_id > 0 )

                        <button type="submit" class="btn btn-success" id="channel_next_button">
                         {!! trans('messages.channel_create_lbl18') !!}
                        </button>

                        <div class="pull-right">


                            <a href="javascript:deleteconfirm('{!! trans('messages.channel_create_lbl13') !!}','{!! trans('messages.channel_create_lbl14') !!}','{!! route('channel.remove_channel_feed',
                    ['channel_feed_id'=>$channel_feed_id,'feed_id'=>$feed_id]) !!}')"
                               class="btn btn-block btn-danger btn-md">
                                <i class="fa fa-trash"></i>
                            </a>

                        </div>

                        @else
                        <button type="submit" class="btn btn-primary" id="channel_next_button">
                            <i class="fa fa-save"></i>
                            {!! trans('messages.channel_create_lbl9') !!}
                        </button>



                    @endif

                </div>
            {!! Form::close() !!}
        </div>
        </div>

        @include('dfcore.channel.partials.channel_overview',compact('channels_from_feed','channel_feed_id'))

        </div>

    </section>


@stop