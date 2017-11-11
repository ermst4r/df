@extends('layouts.layout')
@section('backend-content')




    <section class="content">


        <div class="row">
            <a href="{!! route('import.mapping',['id'=>$id,'type'=>$feed->feed_type]) !!}" class="btn btn-default pull-left">
                <i class="fa fa-arrow-left"></i> {!! trans('messages.import_mapping_composite_5') !!}
            </a>
            <BR><BR>
        </div>

        <div class="row">
        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-info"></i>{!! trans('messages.import_mapping_composite_1') !!}</h4>
            {!! trans('messages.import_mapping_composite_2') !!}
        </div>


        <div class="box box-default">

            {!! Form::open(['method'=>'POST','route'=>'import.post_composite_key','id'=>'post_composite_key']) !!}
            {!! Form::hidden('id',$id) !!}
            <div class="box-header with-border">
                <i class="fa fa-key"></i>

                <h3 class="box-title">    {!! trans('messages.import_mapping_composite_3') !!}</h3>
            </div>

            <div class="box-body">



                <div class="form-group">

                    @foreach($mapping_info as $fields)
                    <div class="checkbox">
                        <label>
                            {{ Form::checkbox('composite_keys[]', $fields,(isset($current_composite_mappings[$fields]) ? true : false)) }}
                            {!! $fields !!}
                        </label>
                    </div>
                @endforeach

                </div>
                <!-- /.row -->
            </div>

            <div class="box-footer">

                <button type="submit" class="btn btn-success">{!! trans('messages.import_mapping_composite_4') !!}</button>
            </div>

            {!! Form::close() !!}



        </div>
        </div>
        <!-- /.box -->



    </section>

@stop