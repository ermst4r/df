@extends('layouts.layout')
@section('backend-content')


                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            @if($id == 0 )
                            {!! trans('messages.store_lbl11') !!}
                            @else
                                {!! $get_store->store_name !!}
                            @endif

                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->

                    @if(is_null($get_store))
                        {!! Form::open(['method'=>'POST','route'=>'store.post','id'=>'storeForm']) !!}
                    @else
                        {!! Form::model($get_store,['method'=>'POST','route'=>'store.post','id'=>'storeForm']) !!}
                    @endif


                        {!!  Form::hidden('id',$id) !!}
                        {!! csrf_field() !!}
                        <div class="box-body">
                            <div class="form-group">
                                <label >{!! trans('messages.store_lbl2') !!}</label>
                                {!! Form::input('text','store_name',null,['class'=>'form-control','placeholder'=> trans('messages.store_lbl4')]) !!}

                            </div>
                            <div class="form-group">
                                <label >{!! trans('messages.store_lbl3') !!}</label>
                                {!! Form::input('text','store_url',null,['class'=>'form-control','placeholder'=> trans('messages.store_lbl5')]) !!}

                            </div>

                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">{!! trans('messages.store_lbl6') !!}</button>

                            @if($id > 0)
                            <a href="javascript:deleteconfirm('{!! trans('messages.store_lbl13') !!}','{!! trans('messages.store_lbl14') !!}','{!! route('store.delete_store',['id'=>$get_store->id]) !!}')"
                               class="btn btn-danger pull-right">{!! trans('messages.store_lbl12') !!}
                            </a>
                            @endif
                        </div>
                        {!! Form::close() !!}
                </div>


@stop