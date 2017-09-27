@extends('layouts.layout')
@section('backend-content')



    {!! Form::hidden('channel_type_id',$channel_type_id) !!}
    {!! Form::hidden('channel_feed_id',$channel_feed_id) !!}
    {!! Form::hidden('bol_id',$bol_id) !!}
    {!! Form::hidden('url_key',$url_key) !!}
    <section class="browse_uncategorized content">
        @include('dfcore.global_partials.feed_wizard',compact('wizard','route_name'))

        <div class="row">
            <div class="col-md-10">
            <a href="{!! route('filter.categorize_feed',['id'=>$id,'url_key'=>$url_key,'channel_feed_id'=>$channel_feed_id,'channel_type_id'=>$channel_type_id]) !!}" class="btn btn-default btn-sm ">
               <i class="fa fa-arrow-left"></i> {!! trans('messages.filter_categorize_lbl30') !!}
            </a>
            </div>
        </div>
        <BR>
        <div class="row">

            <div class="col-md-10">

                <div class="box box-default">
                    <div class="box-header with-border">
                        <i class="fa fa-filter"></i>

                        <h3 class="box-title">{!! trans('messages.filter_categorize_lbl37') !!}</h3>
                    </div>
                    <!-- /.box-header -->



                    <div class="box-body ">
                        <input name="id" value="{!! $id !!}" type="hidden">

                        <!-- /.box-header -->
                        <div class="box-body no-padding">
                            <table id="browse_uncategorized" class="display">
                                <thead>
                                <tr>
                                    <th>{!! trans('messages.filter_categorize_lbl32') !!}</th>
                                    <th>{!! trans('messages.filter_categorize_lbl33') !!}</th>
                                    <th>{!! trans('messages.filter_categorize_lbl34') !!}</th>
                                    <th>{!! trans('messages.filter_categorize_lbl35') !!}</th>
                                </tr>
                                </thead>


                            </table>



                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->








    </section>

@stop