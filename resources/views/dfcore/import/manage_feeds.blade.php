@extends('layouts.layout')
@section('backend-content')




    <section class="manage_feeds">

        <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-tag"></i> {!! trans('messages.manafeeds_lbl1') !!}</h3>
        </div>
       @include('dfcore.import.partials.manage_feeds_partial',['feeds'=>$feeds])
    </div>
    </section>

@stop