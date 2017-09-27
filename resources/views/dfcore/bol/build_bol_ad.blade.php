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



        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('messages.bol_lbl_1') }}</h3>
            </div>

            @if(is_null($bol_ad))
                {!! Form::open(['method'=>'POST','route'=>'bol.post_bol_ad','role'=>'form' ,'id' => 'bol_feed_settings']) !!}
            @else
                {!! Form::model($bol_ad,['method'=>'POST','route'=>'bol.post_bol_ad','id'=>'bol_feed_settings']) !!}
            @endif
            {!! Form::hidden('fk_feed_id',$feed_id) !!}
            {!! Form::hidden('fk_bol_id',$fk_bol_id) !!}
            <div class="box-body">
                <div class="form-group">
                    <label for="">{{ trans('messages.bol_ad_lbl1') }}  <a href="javascript:toggle_id('bol_ean_wrapper')" >
                            <i class="fa fa fa-question-circle"></i>
                        </a></label>
                    {{ Form::select('ean', $field_names,null,['class'=>'form-control select2']) }}
                    <p class="help-block" id="bol_ean_wrapper" style="display: none;">
                        {!! trans('messages.bol_ad_lbl2') !!}
                    </p>



                </div>


                <div class="form-group">
                    <label >{{ trans('messages.bol_ad_lbl3') }}
                        <a href="javascript:toggle_id('bol_condition_wrapper')" >
                            <i class="fa fa fa-question-circle"></i>
                        </a>

                    </label>
                    {{ Form::select('condition', ['NEW'=>'NEW','AS_NEW'=>'AS_NEW','GOOD'=>'GOOD','REASONABLE'=>'REASONABLE','MODERATE'=>'MODERATE'],null,['class'=>'form-control select2']) }}
                    <p class="help-block" id="bol_condition_wrapper" style="display: none;">
                        {!! trans('messages.bol_ad_lbl4') !!}
                    </p>

                </div>


                <div class="form-group">
                    <label >{{ trans('messages.bol_ad_lbl5') }}  <a href="javascript:toggle_id('bol_price_wrapper')" >
                            <i class="fa fa fa-question-circle"></i>
                        </a> </label>
                    {{ Form::select('price', $field_names,null,['class'=>'form-control select2']) }}
                    <p class="help-block" id="bol_price_wrapper" style="display: none;">
                        {!! trans('messages.bol_ad_lbl6') !!}
                    </p>
                </div>


                <div class="form-group">
                    <label >{{ trans('messages.bol_ad_lbl17') }}  <a href="javascript:toggle_id('bol_title_wrapper')" >
                            <i class="fa fa fa-question-circle"></i>
                        </a> </label>
                    {{ Form::select('title', $field_names,null,['class'=>'form-control select2']) }}
                    <p class="help-block" id="bol_title_wrapper" style="display: none;">
                        {!! trans('messages.bol_ad_lbl18') !!}
                    </p>
                </div>



                <div class="form-group">
                    <label >{{ trans('messages.bol_ad_lbl7') }}  <a href="javascript:toggle_id('bol_delivery_code_wrapper')" >
                            <i class="fa fa fa-question-circle"></i>
                        </a> </label>
                    {{ Form::select('delivery_code', [
                    '1-2d'=>'1-2d',
                    '2-3d'=>'2-3d',
                    '3-5d'=>'3-5d',
                    '4-8d'=>'4-8d',
                    '1-8d'=>'1-8d',
                    '24uurs-23'=>'24uurs-23',
                    '24uurs-22'=>'24uurs-22',
                    '24uurs-21'=>'24uurs-21',
                    '24uurs-20'=>'24uurs-20',
                    '24uurs-19'=>'24uurs-19',
                    '24uurs-18'=>'24uurs-18',
                    '24uurs-17'=>'24uurs-17',
                    '24uurs-16'=>'24uurs-16',
                    '24uurs-15'=>'24uurs-15',
                    '24uurs-14'=>'24uurs-14',
                    '24uurs-13'=>'24uurs-13',
                    '24uurs-12'=>'24uurs-12',
                    ],null,['class'=>'form-control select2']) }}
                    <p class="help-block" id="bol_delivery_code_wrapper" style="display: none;">
                        {!! trans('messages.bol_ad_lbl8') !!}
                    </p>
                </div>



                <div class="form-group">
                    <label >{{ trans('messages.bol_ad_lbl9') }}  <a href="javascript:toggle_id('bol_stock_wrapper')" >
                            <i class="fa fa fa-question-circle"></i>
                        </a> </label>
                    {{ Form::select('stock', $field_names,null,['class'=>'form-control select2']) }}
                    <p class="help-block" id="bol_stock_wrapper" style="display: none;">
                        {!! trans('messages.bol_ad_lbl10') !!}
                    </p>
                </div>


                <div class="form-group">
                    <label >{{ trans('messages.bol_ad_lbl15') }}  <a href="javascript:toggle_id('bol_description_wrapper')" >
                            <i class="fa fa fa-question-circle"></i>
                        </a> </label>
                    {{ Form::select('description', $field_names,null,['class'=>'form-control select2']) }}
                    <p class="help-block" id="bol_description_wrapper" style="display: none;">
                        {!! trans('messages.bol_ad_lbl16') !!}
                    </p>
                </div>




                <div class="form-group">
                    <label >{{ trans('messages.bol_ad_lbl19') }}  <a href="javascript:toggle_id('bol_fullfilment_wrapper')" >
                            <i class="fa fa fa-question-circle"></i>
                        </a> </label>
                    {{ Form::select('fullfilment', ['FBB'=>'FBB','FBR'=>'FBR'],null,['class'=>'form-control select2']) }}
                    <p class="help-block" id="bol_fullfilment_wrapper" style="display: none;">
                        {!! trans('messages.bol_ad_lbl20') !!}
                    </p>
                </div>


                <div class="form-group">
                    <label >{{ trans('messages.bol_ad_lbl21') }}  <a href="javascript:toggle_id('reference_code')" >
                            <i class="fa fa fa-question-circle"></i>
                        </a> </label>
                    {{ Form::select('reference_code', $field_names,null,['class'=>'form-control select2']) }}
                    <p class="help-block" id="reference_code" style="display: none;">
                        {!! trans('messages.bol_ad_lbl22') !!}
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

    </section>



@stop