@extends('layouts.layout')
@section('backend-content')


    {!! Form::open(['method'=>'POST','route'=>'adwords.post_adwords_backup','id'=>'adwords_backup']) !!}
    {!! Form::hidden('feed_id',$feed_id) !!}
    {!! Form::hidden('number_of_ads',$number_of_ads) !!}
    {!! Form::hidden('fk_adwords_feed_id',$fk_adwords_feed_id) !!}
    {!! Form::hidden('parent_id',$parent_id) !!}
    {!! Form::hidden('backup_ad',1) !!}

    <section class="content adwords_settings backup_ads">

        <div class="col-md-12">

            <a href="{!! route('adwords.adwords_settings',['feed_id'=>$feed_id,'fk_adwords_feed_id'=>$fk_adwords_feed_id]) !!}" class="btn btn-default" style="margin-bottom: 20px;">
                <i class="fa fa-arrow-left"></i> {!! trans('messages.adwords_backup_lbl6') !!}

            </a>

            <div class="callout callout-info">

                <h4>  {!! trans('messages.adwords_backup_lbl4',['ad_name'=> $parent_ad->headline_1 . ' - ' . $parent_ad->headline_2]) !!} </h4>

                <p>{!! trans('messages.adwords_backup_lbl5') !!}</p>
            </div>


            <div style="margin-bottom:20px;" >



            <button  class="btn btn-success" name="save_next" value="1" >
                <i class="fa fa-save"></i>   {!! trans('messages.adwords_backup_lbl1') !!}
            </button>

            <div class="pull-right"> </div>
            <button type="submit" class="btn btn-default" name="save_stay" value="1">
                <i class="fa fa-save"></i>  {!! trans('messages.adwords_backup_lbl2') !!}
            </button>

            </div>


            <div class="box box-primary">
                <div class="box-header">
                    <i class="fa fa-edit"></i>

                    <h3 class="box-title">{!! trans('messages.adwords_backup_lbl3') !!}</h3>
                </div>



                @include('dfcore.adwords.partial.adwords_ad_templates',['item_id'=>1,'adwords_ads'=>$adwords_ads,'backup'=>true])
            </div>





        </div>







    </section>



    {!! Form::close() !!}

@stop