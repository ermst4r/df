@extends('layouts.layout')
@section('backend-content')






    <section class="content channel">


        @include('dfcore.global_partials.feed_wizard',compact('channel_wizard','route_name'))

        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-info"></i> {!! trans('messages.channel_finalize_lbl8') !!}</h4>
            {!! trans('messages.channel_finalize_lbl9') !!}
        </div>




        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('messages.channel_finalize_lbl7') }}</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {!! Form::open(['method'=>'POST','route'=>'channel.post_channel_map_feed','role'=>'form']) !!}
            {!! Form::hidden('fk_feed_id',$feed_id) !!}
            {!! Form::hidden('fk_channel_id',$channel_id) !!}
            {!! Form::hidden('fk_channel_feed_id',$channel_feed_id) !!}
            {!! Form::hidden('fk_channel_type_id',$channel_type_id) !!}

            <div class="box-body">
                <table class="table" id="channel_finalize">
                   <tr>
                        <th >{!! trans('messages.channel_finalize_lbl1') !!}</th>
                        <th class="col-md-7" >{!! trans('messages.channel_finalize_lbl2') !!}</th>
                        <th  class="col-md-1">Type</th>

                    </tr>

                        <?php $counter = 0 ;?>
                        @foreach($channel_fields_to_map as $channel_fields)
                        <tr>
                            <td>
                                <?php
                                $prefilled = array_search($channel_fields->channel_field_name,$suggestor);
                                ?>

                                <select class="form-control channel_finalize" name="feed_row[{!! $counter !!}]">
                                    <option value=""> Maak uw keuze </option>
                                    @foreach($fields as $field)



                                        <option value="{!! $field !!}"
                                                {!! isset($get_mapped_items[$channel_fields->channel_field_name]) &&  $get_mapped_items[$channel_fields->channel_field_name] == $field  || $prefilled == $field ? 'selected' : ''  !!} >
                                            {!! $field !!}
                                        </option>
                                    @endforeach
                                </select>






                            </td>
                            <td>

                                <button type="button" class="btn btn-block btn-default"  data-original-title="{!!$channel_fields->description  !!}"
                                        data-toggle="tooltip" style="cursor: help;">{{ $channel_fields->channel_field_name }}</button>


                            </td>
                            <td>
                                {!! Form::hidden('fk_channel_mapping_id['.$counter.']',$channel_fields->id) !!}
                                {!! Form::hidden('channel_mapping_name['.$counter.']',$channel_fields->channel_field_name) !!}
                                @if(\App\DfCore\DfBs\Enum\Channel::FIELD_MANDATORY ===  $channel_fields->channel_field_type )
                                        <a  data-original-title="{!! trans('messages.channel_finalize_lbl5') !!}" data-toggle="tooltip" href="javascript:void(0);" class="btn btn-block btn-success "

                                           style="cursor: help;">
                                            <i class="fa fa-shield"></i> {!! trans('messages.channel_finalize_lbl3') !!}

                                        </a>
                                @endif

                                    @if(\App\DfCore\DfBs\Enum\Channel::FIELD_OPTIONAL ===  $channel_fields->channel_field_type )
                                        <a  data-original-title="{!! trans('messages.channel_finalize_lbl6') !!}" data-toggle="tooltip" href="javascript:void(0);"  title="{!! trans('messages.channel_finalize_lbl12') !!}" style="cursor: help;"  class="btn btn-block btn-primary">
                                            <i class="fa fa-fw fa-circle"></i>{!! trans('messages.channel_finalize_lbl4') !!}</a>
                                    @endif


                            </td>
                        </tr>
                        <?php $counter++ ;?>
                    @endforeach


                    <?php $field_counter = 0;?>
                    @foreach($custom_field_name as $field_name => $cname)

                        <tr id="extra_row_{!! $field_counter !!}">
                            <td>

                                <select class="form-control channel_finalize" name="extra_map_to_field[]" >
                                    <option value=""> {!! trans('messages.channel_finalize_lbl13') !!} {!! $field !!}  </option>
                                    @foreach($fields as $field)
                                        <option value="{!! $field !!}" {{ $cname->field_name == $field  ? 'selected' : '' }}>
                                            {!! $field !!}
                                        </option>
                                    @endforeach
                                </select>


                            </td>
                            <td>

                                    <div class="row">
                                        <div class="col-md-10"> <input type="text" class="form-control" id="extra_field_{!! $field_counter !!}" name="extra_field_name[]" value="{!! $cname->custom_field_name !!}"></div>
                                        <div class="col-md-2"> <button  type="button"  class="btn btn-block btn-default delete_extra_field" data-id="{!! $field_counter !!}">  <i class="fa fa-trash"></i> </button> </div>

                                    </div>




                            </td>
                            <td>



                                <a  data-original-title="{!! trans('messages.channel_finalize_lbl6') !!}" data-toggle="tooltip" href="javascript:void(0);"  title="{!! trans('messages.channel_finalize_lbl12') !!}" style="cursor: help;"  class="btn btn-block btn-primary">
                                    <i class="fa fa-fw fa-circle"></i>{!! trans('messages.channel_finalize_lbl4') !!}</a>


                            </td>
                        </tr>
                        <?php $field_counter ++;?>
                    @endforeach

                    {!! Form::hidden('number_of_items',$counter + count($custom_field_name)) !!}



                    </table>
            </div>

            <div class="box-footer">


                <button type="submit" class="btn btn-success">
                    <i class="fa fa-rocket"></i>
                    {!! trans('messages.channel_finalize_lbl10') !!}
                </button>

                <button type="button" id="add_extra_channel_field" class="btn  btn-default pull-right">
                    <i class="fa fa-plus"></i>
                        {!! trans('messages.channel_finalize_lbl11') !!}
                </button>

            </div>

        </div>

        {!! Form::close() !!}
    </section>


@stop