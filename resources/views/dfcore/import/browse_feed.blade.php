<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="/css/custom_styles.css">
<link rel="stylesheet" href="/js/jquery.js">
<link rel="stylesheet" href="/css/all.css">
<link rel="stylesheet" href="/css/after_bootstrap.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

    {!! Form::hidden('id',$feed_id) !!}
    {!! Form::hidden('row_headers') !!}
    {!! Form::hidden('prefilled_fields',$show_fields) !!}

    <section class="preview_feed" id="preview_feed">
        <h3>
            Preview Mode
        </h3>
        <div class="row">

            <div class="col-md-12">


                <div class="box box-default">
                    <div class="box-header with-border">
                        <i class="fa fa-feed"></i>

                        <h3 class="box-title">{!! trans('messages.feed_preview_lbl1') !!}</h3>
                    </div>
                    <!-- /.box-header -->


                    <div class="box-body ">

                    {!! Form::hidden('id',$feed_id) !!}

                        <!-- /.box-header -->
                            <div class="box-body no-padding">
                                <table class="table table-bordered ">



                                    <tr >
                                        <td class="col-md-2">
                                            <select class="form-control select2 selected_field" id="fields"  >
                                                @foreach($fields as $field)
                                                    <option value="{!! $field !!}" >{!! $field !!}


                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>



                                        <td class="col-md-2">
                                            <select class="form-control select2  selected_condition" >
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
                                                   >


                                        </td>


                                        <td class="col-md-2 button_bars_" align="left">
                                            <a href="javascript:void(0);" class="btn bg-olive btn-md search_spreadsheet" style="margin-top:0px;">
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

                                        </td>
                                    </tr>


                                    <tr class="open_spreadsheet_field_filters" style="display: none;" >
                                        <td colspan="4">
                                            <form method="get">
                                            <h4>{!! trans('messages.spreadsheet_lbl6') !!}</h4>
                                            <div class="form-group">
                                                @foreach($fields as $field)

                                                        <div class="checkbox">
                                                            <label>
                                                                {!! Form::checkbox('show_fields[]',$field ) !!}
                                                                {!! $field !!}
                                                            </label>
                                                        </div>

                                                @endforeach
                                            </div>
                                            <div class="box-footer">
                                                <button type="submit" class="btn btn-primary">{!! trans('messages.spreadsheet_lbl7') !!}</button>
                                            </div>
                                            </form>
                                        </td>
                                    </tr>
                                </table>


                            </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
                <small class="label label-info search_term_spreadsheet" style="display: none;" >  </small>
                <small class="label bg-gray number_of_spreadsheet_records" style="display: none;"  >  </small>
                @if($feed_exists)
                <div  align="center" class="loading-browse-feed">
                    <img src="/img/reload.gif" height="32">
                </div>
                @endif
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



                @if($feed_exists)
                    <div  id="dfbuilder-preview_feed" >

                    </div>
                    @else
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-ban"></i> {!! trans('messages.feed_preview_lbl2') !!}</h4>
                        {!! trans('messages.feed_preview_lbl3') !!}
                    </div>

                @endif






            </div>

        </div>
        <!-- /.col -->

    </section>
<script src="/js/app.js?time={!! time() !!}"></script>
<script src="/js/dfbuilder.js?time={!! time() !!}"></script>
