@extends('layouts.layout')
@section('backend-content')







    <section class="content channel">

        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-info"></i> {!! trans('messages.adwords_feed_lbl10') !!}</h4>
            {!! trans('messages.adwords_feed_lbl11') !!}
        </div>

        @if($adwords_feed_id > 0)
         @include('dfcore.global_partials.feed_wizard',compact('wizard','route_name'))
        @endif

        <div class="row">
            <div class="col-md-9">

                @if(is_null($adwords_feed))
                    {!! Form::open(['method'=>'POST','route'=>'adwords.post_adwords_feed','role'=>'form' ,'id' => 'adwords_feed_settings']) !!}
                @else
                    {!! Form::model($adwords_feed,['method'=>'POST','route'=>'adwords.post_adwords_feed','id'=>'adwords_feed_settings']) !!}
                @endif



                {!! Form::hidden('feed_id',$feed_id) !!}

                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans('messages.adwords_feed_lbl1') }}</h3>
                    </div>

                    <!-- /.box-header -->
                    <!-- form start -->
                    {!! Form::hidden('fk_feed_id',$feed_id) !!}
                    {!! Form::hidden('id',$adwords_feed_id) !!}

                    <div class="box-body">
                        <div class="form-group">
                            <label for="">{{ trans('messages.adwords_feed_lbl7') }}</label>
                            {!! Form::input('text','name',null,['class'=>'form-control','placeholder'=> trans('messages.adwords_feed_lbl2')]) !!}
                        </div>




                        <div class="form-group">
                            <label >{{ trans('messages.adwords_feed_lbl6') }}</label>
                            {!! Form::input('text','adwords_account_id',null,['class'=>'form-control','placeholder'=> trans('messages.adwords_feed_lbl3')]) !!}
                        </div>




                        <div class="form-group">
                            <label >{{ trans('messages.update_key_lbl8') }}</label>
                            {{ Form::select('update_interval', \App\DfCore\DfBs\Enum\UpdateIntervals::updateSelectBoxArray(),null,['class'=>'form-control']) }}
                        </div>




                        <div class="form-group">
                            <label >{{ trans('messages.adwords_settings_lbl12') }}</label>
                            {{ Form::select('active', [1=>trans('messages.import_selectfeed_lbl9'),0=>trans('messages.import_selectfeed_lbl10')],null,['class'=>'form-control']) }}
                            <p class="help-block">
                                {!! trans('messages.adwords_settings_lbl11') !!}
                            </p>
                        </div>




                    @if(!is_null($adwords_feed))
                            <div class="form-group">
                                <label >{{ trans('messages.update_key_lbl9') }}</label>
                                <input type="text" disabled="disabled" value="{!! $adwords_feed->next_update !!}" class="form-control">
                            </div>




                    @endif


                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">


                        <button type="submit" class="btn btn-success" id="save_adwords_button">
                          {!! trans('messages.adwords_feed_lbl5') !!}
                        </button>

                        @if(!is_null($adwords_feed))

                            <div class="pull-right">


                                <a href="javascript:deleteconfirm('{!! trans('messages.adwords_feed_lbl14') !!}','{!! trans('messages.adwords_feed_lbl15') !!}',
                                '{!! route('adwords.remove_adwords_feed',['id'=>$adwords_feed->id,'feed_id'=>$adwords_feed->fk_feed_id]) !!}')"
                                   class="btn btn-block btn-danger btn-md">
                                    <i class="fa fa-trash"></i>
                                </a>

                            </div>
                        @endif

                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
                @include('dfcore.adwords.partial.adwords_list_overview',compact('adwords_feed','adwords_feed_id','feed_id'))
        </div>

    </section>


@stop