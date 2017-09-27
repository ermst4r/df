@extends('layouts.layout')
@section('backend-content')



    <section class="content">


        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-info"></i> {!! trans('messages.adwords_manage_lbl1') !!}</h4>
            {!! trans('messages.adwords_manage_lbl2') !!}
        </div>

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> {!! trans('messages.adwords_manage_lbl3') !!}</h3>
            </div>
            <table class="table table-hover" id="manage_feeds">
                <thead><tr>
                    <th>{!! trans('messages.adwords_manage_lbl4') !!}</th>
                    <th>{!! trans('messages.adwords_manage_lbl5') !!}</th>
                    <th>{!! trans('messages.adwords_manage_lbl6') !!}</th>
                    <th>{!! trans('messages.adwords_manage_lbl7') !!}</th>
                    <th>{!! trans('messages.adwords_manage_lbl8') !!}</th>
                </tr>
                </thead>
                <tbody>

                @foreach($feeds as $feed)
                <tr>
                    <td>{!! $feed->created_at !!}</td>
                    <td>{!! $feed->feed_name !!}</td>
                    <td>{!! $feed->adwords_name !!}</td>
                    <td>{!! $feed->adwords_account_id !!}</td>
                    <td>
                        <a class="btn btn-success"
                           href="{!! route('adwords.adwords_feed',['feed_id'=>$feed->feed_id,'channel_feed_id'=>$feed->adwords_id]) !!}">

                            {!! trans('messages.channel_manage_lbl7') !!}   </a>

                    </td>

                </tr>
                @endforeach




                </tbody></table>
        </div>


    </section>


@stop