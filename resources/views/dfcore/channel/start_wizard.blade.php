@extends('layouts.layout')
@section('backend-content')






    <section class="content">

        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-info"></i>{!! trans('messages.channel_start_lbl5') !!}</h4>
          {!! trans('messages.channel_start_lbl4') !!}
        </div>


        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">{!! trans('messages.channel_start_lbl1') !!} </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form">
                <div class="box-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">{!! trans('messages.channel_start_lbl2') !!}</label>
                        <select class="form-control select2" style="width: 100%;" name="feed_id" id="select2">
                            @foreach($feeds as $feed)
                             <option value="{!! $feed->id !!}">{!! $feed->feed_name !!}</option>
                                @endforeach

                        </select>
                    </div>

                <!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">{!! trans('messages.channel_start_lbl3') !!}</button>
                </div>
            </form>
        </div>



    </section>


@stop