@extends('layouts.layout')
@section('backend-content')


    <a class="btn btn-success btn-lg" href="{!! route('categorytable.create') !!}">
       <i class="fa fa-plus"></i> {!! trans('category::messages.category_lbl13') !!}
    </a>
    <br><br>


    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">
                {!! trans('category::messages.category_lbl8') !!}
            </h3>
        </div>

        <div class="box-body">
            <ul>
                <?php

                foreach ($category_list as $r) {
                    echo  $r;
                }
                ?>
            </ul>

        </div>





    </div>




    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">

                    {!! trans('category::messages.category_lbl1') !!}

            </h3>
        </div>


        <!-- /.box-header -->
        <!-- form start -->




        @if(is_null($category))
            {!! Form::open(['method'=>'POST','route'=>'categorytable.store']) !!}
        @else
            {!! Form::model($category,['method'=>'POST','route'=>'categorytable.store']) !!}
        @endif

        @if($parent_id >  0 )
        {!!  Form::hidden('parent',$parent_id) !!}
        @endif
        {!!  Form::hidden('id',$id) !!}
        <div class="box-body">
            <div class="form-group">
                <label >{!! trans('messages.store_lbl2') !!}</label>
                {!! Form::input('text','category',null,['class'=>'form-control']) !!}
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">{!! trans('messages.store_lbl6') !!}</button>

            </div>
            {!! Form::close() !!}
        </div>


@stop