@extends('layouts.layout')
@section('backend-content')






    <section class="content create_rules feed_rules">
        {!! Form::hidden('custom_control_loader',implode(',',$custom_control_loader)) !!}

        <div class="col-md-10" >

            @if(is_null($get_rule))
                {!! Form::open(['method'=>'POST','route'=>['rules.post_rules'],'id'=>'post_rules']) !!}
            @else
                {!! Form::model($get_rule,['method'=>'POST','route'=>'rules.post_rules','id'=>'post_rules']) !!}
            @endif

            @if($url_key == \App\DfCore\DfBs\Enum\UrlKey::CHANNEL_FEED)
                {!! Form::hidden('channel_type_id',$channel_type_id) !!}
                {!! Form::hidden('channel_feed_id',$channel_feed_id) !!}
                {!! Form::hidden('url_key',\App\DfCore\DfBs\Enum\UrlKey::CHANNEL_FEED) !!}
            @endif


            @if($url_key == \App\DfCore\DfBs\Enum\UrlKey::ADWORDS)
                {!! Form::hidden('adwords_feed_id',$adwords_feed_id) !!}
                {!! Form::hidden('url_key',\App\DfCore\DfBs\Enum\UrlKey::ADWORDS) !!}
            @endif


            @if($url_key == \App\DfCore\DfBs\Enum\UrlKey::BOL)
                {!! Form::hidden('bol_id',$bol_id) !!}
                {!! Form::hidden('url_key',\App\DfCore\DfBs\Enum\UrlKey::BOL) !!}
            @endif





                <div class="col-lg-12">
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-info"></i> {!! trans('messages.rules_overview_rule_lbl12') !!}</h4>
                    {!! trans('messages.rules_overview_rule_lbl11') !!}
                </div>

                @include('dfcore.global_partials.feed_wizard',compact('wizard','route_name'))


                <button type="submit"
                        title="{!! trans('messages.rules_if_lbl25') !!}"
                        data-placement="bottom"
                        data-toggle="tooltip" class="btn btn-lg btn-success"><i class="fa fa-save"></i> {!! trans('messages.rules_if_lbl32') !!}
                </button>


                <div class="pull-right">
                    <div class="btn-group ">
                        <button type="button" class="btn btn-info btn-lg">{!! trans('messages.rules_create_rule_lbl1') !!}</button>
                        <button type="button" class="btn btn-info dropdown-toggle btn-lg" data-toggle="dropdown" aria-expanded="false">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{!! route('rules.create_rules',$create_manual_array) !!}">{!! trans('messages.rules_overview_rule_lbl1') !!} </a></li>


                            <li>
                                @if($rule_id > 0)
                                    <a href="javascript:deleteconfirm('{!! trans('messages.rules_overview_rule_lbl3') !!}','{!! trans('messages.rules_overview_rule_lbl4') !!}','{!! route('rules.remove_rule',
                                    $remove_manual_array) !!}');" >
                                        {!! trans('messages.rules_create_rule_lbl2') !!}
                                    </a>
                                @endif


                            </li>

                        </ul>
                    </div>
                </div>
                <br>
                <br>

            </div>


            <div class="col-lg-12 rules-create-rule-field">
                {!! Form::input('text','rule_name',null,['class'=>'form-control input-lg','placeholder'=> trans('messages.filter_categorize_lbl36')]) !!}
            </div>

            @if(!is_null($rules_dictonary))
                @include('dfcore.rules.partials.if-form-prefilled',compact('rules_dictonary','field_names','rule_id'))
                @include('dfcore.rules.partials.then-form-prefilled',compact('rules_dictonary','field_names','rule_id','feed_id'))

            @else
                @include('dfcore.rules.partials.if-form',compact('rules_dictonary'))
                @include('dfcore.rules.partials.then-form',compact('field_names','rule_id','feed_id'))
            @endif



            {!! Form::hidden('if_fields_counter',0) !!}
            {!! Form::hidden('then_fields_counter',0) !!}
            {!! Form::hidden('id',$id) !!}
            {!! Form::hidden('url_key',$url_key) !!}
            <button type="submit"
                    title="{!! trans('messages.rules_if_lbl25') !!}"
                    data-placement="bottom"
                    data-toggle="tooltip" class="btn btn-lg btn-success"><i class="fa fa-save"></i> {!! trans('messages.rules_if_lbl32') !!}
            </button>
            {!! Form::close() !!}


        </div>
        </div>

        @include('dfcore.rules.partials.statusbox',compact('number_of_records','feed_rules','rule_id','url_key','channel_feed_id','channel_type_id','create_manual_array','bol_id'))
    </section>
@stop