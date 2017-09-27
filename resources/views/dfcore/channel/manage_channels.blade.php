@extends('layouts.layout')
@section('backend-content')





    <section class="manage_feeds">

        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-info"></i> {!! trans('messages.channel_manage_lbl8') !!}</h4>
            {!! trans('messages.channel_manage_lbl9') !!}
        </div>


        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> {!! trans('messages.channel_manage_lbl6') !!}</h3>
            </div>
            <table class="table table-hover" id="manage_feeds">
                <thead><tr>
                    <th>{!! trans('messages.channel_manage_lbl4') !!}</th>
                    <th>{!! trans('messages.channel_manage_lbl1') !!}</th>
                    <th>{!! trans('messages.channel_manage_lbl2') !!}</th>
                    <th>{!! trans('messages.channel_manage_lbl3') !!}</th>
                    <th>{!! trans('messages.channel_manage_lbl5') !!}</th>

                </tr>
                </thead>
                <tbody>
                @foreach($feeds as $feed)
                    <tr>
                        <td>{!! $feed->channel_feed_created !!}</td>
                        <td>{!! $feed->feed_name !!}</td>
                        <td>{!! $feed->channel_feed_name !!}</td>
                        <td>{!! $feed->network_name !!}</td>
                        <td>
                            <a class="btn btn-success" href="{!! route('channel.channel_settings',['feed_id'=>$feed->feed_id,'channel_feed_id'=>$feed->channel_feed_id]) !!}"> {!! trans('messages.channel_manage_lbl7') !!}   </a>

                        </td>


                    </tr>

                @endforeach


                </tbody></table>
        </div>
    </section>




@stop