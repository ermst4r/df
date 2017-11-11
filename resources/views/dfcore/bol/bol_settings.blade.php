@extends('layouts.layout')
@section('backend-content')

<?php $active = false;?>

    <section class="content bol_settings">


        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-info"></i> {!! trans('messages.bol_lbl_1') !!}</h4>
            {!! trans('messages.bol_lbl_7') !!}
        </div>

        @if($fk_bol_id > 0)
            <?php $active = true;?>
            @include('dfcore.global_partials.feed_wizard',compact('wizard','route_name'))
        @endif


        <div class="row">
            <div class="col-md-9">


                @if(is_null($bol_feed))
                    {!! Form::open(['method'=>'POST','route'=>'bol.post_bol_feed','role'=>'form' ,'id' => 'bol_feed_settings']) !!}
                @else
                    {!! Form::model($bol_feed,['method'=>'POST','route'=>'bol.post_bol_feed','id'=>'bol_feed_settings']) !!}
                @endif

                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans('messages.bol_lbl_1') }}</h3>
                    </div>


                    {!! Form::hidden('fk_feed_id',$feed_id) !!}
                    {!! Form::hidden('id',$fk_bol_id) !!}
                    <div class="box-body">
                        <div class="form-group">
                            <label for="">{{ trans('messages.adwords_feed_lbl7') }}</label>
                            {!! Form::input('text','name',null,['class'=>'form-control','placeholder'=> trans('messages.adwords_feed_lbl2')]) !!}
                        </div>




                        <div class="form-group">
                            <label >{{ trans('messages.bol_lbl_3') }}</label>
                            {!! Form::input('text','public_key',null,['class'=>'form-control','disabled'=>$active,'placeholder'=> trans('messages.bol_lbl_13')]) !!}
                        </div>


                        <div class="form-group">
                            <label >{{ trans('messages.bol_lbl_4') }}</label>
                            {!! Form::input('text','private_key',null,['class'=>'form-control','disabled'=>$active,'placeholder'=> trans('messages.bol_lbl_14')]) !!}
                        </div>


                        <div class="form-group">
                            <label >{{ trans('messages.update_key_lbl8') }}</label>
                            {{ Form::select('update_interval', \App\DfCore\DfBs\Enum\UpdateIntervals::updateSelectBoxArray(),null,['class'=>'form-control']) }}
                        </div>



                        <div class="form-group">
                            <label >{{ trans('messages.bol_lbl_5') }}</label>
                            {{ Form::select('status', [1=>trans('messages.import_selectfeed_lbl9'),0=>trans('messages.import_selectfeed_lbl10')],null,['class'=>'form-control']) }}
                            <p class="help-block">
                                {!! trans('messages.bol_lbl_6') !!}
                            </p>
                        </div>




                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">


                        <button type="submit" class="btn btn-success" id="save_adwords_button">
                            {!! trans('messages.adwords_feed_lbl5') !!}
                        </button>




                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            @include('dfcore.bol.partial.bol_list',compact('bol_feeds','fk_bol_id','feed_id'))
        </div>

    </section>



@stop