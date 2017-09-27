@extends('layouts.layout')
@section('backend-content')
    {!! Form::hidden('id',$id) !!}
    {!! Form::hidden('row_headers') !!}
    {!! Form::hidden('channel_feed_id',$channel_feed_id) !!}
    {!! Form::hidden('channel_type_id',$channel_type_id) !!}
    <section class="browse_spreadsheet" id="browse_spreadsheet">

    <div class="row">

        <div class="col-md-12">
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info"></i> {!! trans('messages.spreadsheet_lbl9') !!}</h4>
                {!! trans('messages.spreadsheet_lbl10') !!}
            </div>

            @include('dfcore.global_partials.feed_wizard',compact('channel_wizard','route_name'))

            <div class="box box-default">
                <div class="box-header with-border">
                    <i class="fa fa-code"></i>

                    <h3 class="box-title">{!! trans('messages.spreadsheet_lbl8') !!}</h3>
                </div>
                <!-- /.box-header -->


                <div class="box-body ">

                    {!! Form::hidden('id',$id) !!}

                    @if($index_exists && $channel_feed->updating  == false && $force_reimport==false)
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <table class="table table-bordered ">
                            <tr>
                                <td class="col-md-2" style="font-weight: bold" align="center">Gebruik de volgende feed url</td>
                                <td colspan="3">
                                <input type="text" class="form-control" style="width: 100%" value="{!! DFBUILDER_MAIN_WEBSITE_URL !!}download/{!! DFBUILDER_CHANNEL_FOLDER !!}/{!! $generated_file_name !!}">

                                </td>

                            </tr>


                            <tr class="tr_{!! $item_number !!}">
                                <td class="col-md-2">
                                    <select class="form-control select2 selected_field" id="fields"  data-item_number="{!! $item_number !!}">
                                        @foreach($fields as $field)
                                            <option value="{!! $field->channel_field_name !!}" >{!! $field->channel_field_name !!}


                                            </option>
                                        @endforeach
                                    </select>
                                </td>



                                <td class="col-md-2">
                                    <select class="form-control select2  selected_condition" data-item_number="{!! $item_number !!}">
                                        <option  value="">{!! trans('messages.filter_categorize_lbl25') !!}</option>
                                        <option data-help="{!! trans("messages.filter_categorize_lbl6") !!}" value="{!! \App\DfCore\DfBs\Enum\ConditionSelector::CONTAIN !!}">{!! trans('messages.filter_categorize_lbl19') !!}</option>
                                        <option data-help="{!! trans("messages.filter_categorize_lbl7") !!}"  value="{!! \App\DfCore\DfBs\Enum\ConditionSelector::NOT_CONTAIN !!}">{!! trans('messages.filter_categorize_lbl20') !!}</option>
                                        <option data-help="{!! trans("messages.filter_categorize_lbl8") !!}"  value="{!! \App\DfCore\DfBs\Enum\ConditionSelector::EQUALS !!}">{!! trans('messages.filter_categorize_lbl21') !!}</option>
                                        <option data-help="{!! trans("messages.filter_categorize_lbl9") !!}"  value="{!! \App\DfCore\DfBs\Enum\ConditionSelector::NOT_EQUALS !!}">{!! trans('messages.filter_categorize_lbl22') !!}</option>


                                        <option  value="{!! \App\DfCore\DfBs\Enum\ConditionSelector::GT !!}">
                                            {!! trans('messages.rules_if_lbl14') !!}
                                        </option>

                                        <option  value="{!! \App\DfCore\DfBs\Enum\ConditionSelector::GT_EQ !!}">
                                            {!! trans('messages.rules_if_lbl15') !!}
                                        </option>

                                        <option  value="{!! \App\DfCore\DfBs\Enum\ConditionSelector::LT !!}">
                                            {!! trans('messages.rules_if_lbl16') !!}
                                        </option>

                                        <option  value="{!! \App\DfCore\DfBs\Enum\ConditionSelector::LT_EQ !!}">
                                            {!! trans('messages.rules_if_lbl17') !!}
                                        </option>



                                    </select>
                                </td>
                                <td class="col-md-2">
                                    <input type="text" value=""
                                           class="form-control  "
                                           name="search_term"
                                           placeholder="{!! trans('messages.spreadsheet_lbl4') !!}"
                                           data-item_number="{!! $item_number !!}" >


                                </td>


                                <td class="col-md-2 button_bars_{!! $item_number !!}" align="left">
                                    <a href="javascript:void(0);" class="btn bg-olive btn-md search_spreadsheet" data-item_number="{!! $item_number !!}" style="margin-top:0px;">
                                        <i class="fa fa-search"></i>
                                    </a>

                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <a href="javascript:void(0);" class="open_spreadsheet_field_selector">
                                        <i class="fa fa-fw fa-list-alt"></i>
                                        {!! trans('messages.spreadsheet_lbl5') !!}
                                    </a>


                                <div class="pull-right">
                                    <a href="{!! route('spreadsheet.browse_feed',
            [
                'feed_id'=>$id,
                'channel_feed_id'=>$channel_feed_id,
                'channel_type_id'=>$channel_type_id,
                'url_key'=>\App\DfCore\DfBs\Enum\UrlKey::CHANNEL_FEED,
                'reimport'=>true,
                'force_reimport'=>true
            ]) !!}" class="btn btn-block btn-primary" id="spreadsheet_refresh_button">
                                        <i class="fa fa-refresh"></i>
                                        {!! trans('messages.spreadsheet_lbl15') !!}
                                    </a>



                                </div>
                                </td>
                            </tr>
                            <tr class="open_spreadsheet_field_filters" style="display: none;">
                                <td colspan="4">
                                    {!! Form::open(['method'=>'POST','route'=>['spreadsheet.save_headers',$id],'id'=>'save_headers']) !!}
                                    {!! Form::hidden('url_key',$url_key) !!}
                                    <h4>{!! trans('messages.spreadsheet_lbl6') !!}</h4>
                                    <div class="form-group">
                                            @foreach($fields as $field)
                                                    <div class="checkbox">
                                                        <label>
                                                            {!! Form::checkbox('show_fields[]',$field->channel_field_name, in_array($field->channel_field_name,$spreadsheet_headers) ) !!}
                                                           {!! $field->channel_field_name !!}
                                                        </label>
                                                    </div>
                                            @endforeach
                                    </div>
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary">{!! trans('messages.spreadsheet_lbl7') !!}</button>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        {!! Form::hidden('channel_feed_id',$channel_feed_id) !!}
                        {!! Form::hidden('channel_type_id',$channel_type_id) !!}
                        {!! Form::hidden('url_key',$url_key) !!}
                        {!! Form::close() !!}

                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            <small class="label label-info search_term_spreadsheet" style="display: none;" >  </small>
            <small class="label bg-gray number_of_spreadsheet_records" style="display: none;"  >  </small>

            <div  align="center" class="loading-browse-feed">
                <img src="/img/reload.gif" height="32">
            </div>
            <nav>
                <ul class="pagination spreadsheet-navigation">
                    <li class="page-item"><a class="page-link spreadsheet-prev" href="#" style="display: none;">
                            <i class="fa fa-fw fa-arrow-left"></i> {!! trans('messages.spreadsheet_lbl3') !!} </a>
                    </li>
                    <li class="page-item"><a class="page-link spreadsheet-next" href="#" style="display: none;">
                            <i class="fa fa-fw fa-arrow-right"></i> {!! trans('messages.spreadsheet_lbl2') !!} </a>
                    </li>


                </ul>
            </nav>




            <div  id="dfbuilder-spreadsheet" class="hot-container">

            </div>



            @else
                @if($channel_feed->updating  == false && $force_reimport == false)
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-info"></i> {!! trans('messages.spreadsheet_lbl11') !!}</h4>
                    {!! trans('messages.spreadsheet_lbl12') !!}
                </div>

                <a class="btn btn-block btn-success btn-lg"  id="import_feed" href="javascript:void(0);">
                    <i class="fa fa-rocket"></i>
                    {!! trans('messages.spreadsheet_lbl13') !!}
                </a>
                    @else
                    <div  align="center" class="loading-browse-feed">
                        <img src="/img/reload.gif" >
                        <br>
                        {!! trans('messages.spreadsheet_lbl14') !!}
                    </div>

                @endif


            @endif

        </div>

    </div>
        <!-- /.col -->

    </section>




@stop