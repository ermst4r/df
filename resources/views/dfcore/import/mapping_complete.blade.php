@extends('layouts.layout')
@section('backend-content')

    @component('dfcore.import.components.progress')
    <li  ><a href="{!! route('import.mapping') !!}">
            <h4 class="list-group-item-heading">{!! trans('messages.import_progress_lbl1') !!}</h4>
            <p class="list-group-item-text">{!! trans('messages.import_progress_lbl4') !!}</p>
        </a></li>
    <li  ><a href="{!! route('import.mapping',['id'=>$id,'type'=>$feed->feed_type]) !!}">
            <h4 class="list-group-item-heading">{!! trans('messages.import_progress_lbl2') !!}</h4>
            <p class="list-group-item-text">{!! trans('messages.import_progress_lbl5') !!}</p>
        </a></li>

    @endcomponent





    <section class="invoice">


        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-file"></i> Productfeed {!! $feed->feed_name !!}

                </h2>
            </div>
            <!-- /.col -->
        </div>




        <!-- this row will not appear when printing -->
        <div class="row no-print">


            <div class="box-body no-padding">
                <table class="table table-striped">
                    <tbody><tr>
                        <th>Bestandstype</th>
                        <th>Feed Naam</th>
                        <th>Status</th>
                        <th >Actie</th>
                    </tr>
                    <tr>
                        <td>
                        <i class="fa fa-file-excel-o"></i> {!! strtoupper($feed->feed_type) !!}

                        </td>
                        <td>{!! $feed->feed_name !!} </td>
                        <td><span class="label label-primary">Importeren <i class="fa fa-refresh fa-spin"></i> </span></td>
                        <td>
                            <a href="invoice-print.html"   data-placement="bottom"
                               data-original-title="{!! trans('messages.import_mapping_lbl6') !!}" data-toggle="tooltip" target="_blank" class="btn btn-default btn-xs"><i class="fa fa-download"></i></a>
                            <a href="invoice-print.html"   data-placement="bottom"
                               data-original-title="{!! trans('messages.import_mapping_lbl6') !!}" data-toggle="tooltip" target="_blank" class="btn btn-default btn-xs"><i class="fa fa-copy"></i></a>
                            <a href="invoice-print.html"    data-placement="bottom"
                               data-original-title="{!! trans('messages.import_mapping_lbl6') !!}" data-toggle="tooltip" target="_blank" class="btn btn-default btn-xs"><i class="fa fa-pencil"></i></a>
                        </td>
                    </tr>
                    <tr>

                    </tbody></table>
            </div>
        </div>
    </section>





@stop