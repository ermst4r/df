@extends('layouts.layout')
@section('backend-content')






    <div class="row">
        @foreach($stores as $store)

            <div class="col-md-4">
                <!-- Widget: user widget style 1 -->
                <div class="box box-widget widget-user-2">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-blue">
                        <div class="widget-user-image">
                            <img class="img-circle" src="/images/ic_store_mall_directory_48px-512.png" alt="User Avatar">
                        </div>
                        <!-- /.widget-user-image -->
                        <h3 class="widget-user-username">{!! $store->store_name !!}</h3>
                        <h5 class="widget-user-desc">{!! $store->store_url !!}</h5>
                    </div>
                    <div class="box-footer no-padding">
                        {{--<ul class="nav nav-stacked">--}}
                            {{--<li><a href="#">{!! trans('messages.store_lbl9') !!} <span class="pull-right badge bg-blue">2</span></a></li>--}}
                            {{--<li><a href="#">{!! trans('messages.store_lbl10') !!} <span class="pull-right badge bg-blue">1529</span></a></li>--}}

                        {{--</ul>--}}

                    </div>

                    <div align="center" style="margin-top:20px;">
                    @if($current_store == $store->id)
                            <a class="btn btn-app disabled" href="{!! route('store.defaultstore',['store_id'=>$store->id]) !!}" >
                                <i class="fa  fa-check"></i> {!! trans('messages.store_lbl16') !!}
                            </a>



                    @else
                            <a class="btn btn-app" href="{!! route('store.defaultstore',['store_id'=>$store->id]) !!}" >
                                <i class="fa  fa-mouse-pointer"></i> {!! trans('messages.store_lbl1') !!}
                            </a>
                    @endif



                        <br>
                        <a  class="btn btn-default" href="{!! route('store.create',['id'=>$store->id]) !!}"><i class="fa fa-pencil"></i> </a>
                        <br><br>
                    </div>

                </div>
                <!-- /.widget-user -->
            </div>








    @endforeach

    <!-- /.col -->
        <a href="{!! route('store.create') !!}"  >
            <div class="col-md-3 col-sm-6 col-xs-12">
                <span class="info-box-icon bg-green"  data-placement="bottom"
                      data-original-title="{!! trans('messages.store_lbl11') !!}" data-toggle="tooltip"><i class="fa fa-plus-square"></i></span>

                <!-- /.info-box -->
            </div>
        </a>

@stop

