@extends('layouts.layout')
@section('backend-content')






<section class="categorize-feed content">


    <div class="row">


        <div class="col-md-10">

            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info"></i>  {!! trans('messages.filter_categorize_lbl38') !!}</h4>
                {!! trans('messages.filter_categorize_lbl39') !!}
            </div>


            @include('dfcore.global_partials.feed_wizard',compact('wizard','route_name'))
            <div class="box box-default">
                <div class="box-header with-border">
                    <i class="fa fa-filter"></i>

                    <h3 class="box-title">{!! trans('messages.filter_categorize_lbl18') !!}</h3>
                </div>
                <!-- /.box-header -->



                <div class="box-body ">

                    {!! Form::hidden('id',$id) !!}
                    {!! Form::hidden('channel_type_id',$channel_type_id) !!}
                    {!! Form::hidden('channel_feed_id',$channel_feed_id) !!}
                    {!! Form::hidden('bol_id',$bol_id) !!}
                    {!! Form::hidden('url_key',$url_key) !!}


                        <!-- /.box-header -->
                        <div class="box-body no-padding">
                            <table class="table table-bordered ">

                                <tbody><tr id="append_filter_item">
                                    <th>{!! trans("messages.filter_categorize_lbl13") !!} </th>
                                    <th>{!! trans("messages.filter_categorize_lbl14") !!}</th>
                                    <th>{!! trans("messages.filter_categorize_lbl15") !!}</th>
                                    <th>{!! trans("messages.filter_categorize_lbl16") !!}</th>
                                    <th></th>
                                </tr>
                                @include('dfcore.filter.partials.preloadedfilters',compact('category_filter','category'))

                                <tr>
                                    <td colspan="6" align="left">
                                        <BR>
                                        <a href="javascript:void(0);" class="btn-lg btn-success addCategorizeItem " >
                                            <i class="fa fa-plus"></i> {!! trans('messages.filter_categorize_lbl17') !!}
                                        </a>
                                        <BR><BR>
                                    </td>
                                </tr>



                                </tbody>
                            </table>

                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->




        <div class="col-md-2">
            <div class="box box-default">
                <div class="box-header with-border">
                    <i class="fa fa-refresh"></i>
                    <h3 class="box-title">{!! trans('messages.filter_categorize_lbl26') !!}</h3>
                </div>
                <!-- /.box-header -->

                <div class="loading_categorize" align="center">
                    <img src="/img/reload.gif" height="48">
                    <br>
                   {!! trans('messages.filter_categorize_lbl29') !!}

                </div>
                <div class="box-body number_of_items_mapped" align="center" style="display: none;">
                    <input type="text" class="cat_progress_circle" value="0" data-fgColor="#3c8dbc" data-readonly="true" data-width="90"  data-height="90">
                <p class="help-block" >
                    <span class="badge bg-green badge-no-of-products" >
                        {!! trans('messages.filter_categorize_lbl28',['number_of_products'=>$number_of_records['count']]) !!}
                    </span><br>
                    <input type="hidden" name="number_of_products" value="{!! $number_of_records['count'] !!}">



                        <span class="badge bg-red badge-uncategorized"></span>

                </p>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->




    <div class="col-md-2">
        <div class="box box-default">
            <div class="box-header with-border">
                <i class="fa fa-feed"></i>
                <h3 class="box-title">{!! trans('messages.feed_preview_lbl5') !!}</h3>
            </div>
            <!-- /.box-header -->

            <div align="center">
                <br>
            <a class="btn btn-app" href="{!! route('import.browse_feed',['feed_id'=>$id]) !!}" target="_blank">
                <i class="fa fa-search"></i>
                {!! trans('messages.feed_preview_lbl4') !!}
            </a>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
    </div>







</section>

@stop