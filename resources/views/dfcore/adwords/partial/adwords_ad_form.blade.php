


<?php
        $adwords_ad_id = 0;
        $headline_1 = '';
        $headline_2 = '';
        $description = '';
        $path_1 = '';
        $path_2 = '';
        $final_url = '';
        if(isset($ad) && !is_null($ad)) {
            $adwords_ad_id = $ad->id;
            $headline_1 = $ad->headline_1;
            $headline_2 = $ad->headline_2;
            $description = $ad->description;
            $path_1 = $ad->path_1;
            $path_2 = $ad->path_2;
            $final_url = $ad->final_url;

        }

 ?>
<div class="row" id="adwords_template_{!! $item_id !!}">
    @if($item_id > 1) <hr> @endif
    <div class="col-lg-9">



            <div class="box-body">
                <div class="form-group">
                    <label for="" style="width: 100%;">{!! trans('messages.adwords_ad_form_lbl1') !!}
                        <a href="javascript:toggle_id('adwords_kop_1{!! $item_id !!}')" >
                            <i class="fa fa fa-question-circle"></i>
                        </a>

                    </label>
                    {!! Form::hidden('adwords_ad_id['.$item_id.']',$adwords_ad_id) !!}
                    {!! Form::input('text','headline_1['.$item_id.']',$headline_1,['class'=>'form-control adwords_kop_1' , 'id'=>'headline_1_'.$item_id,'data-item_id'=> $item_id ]) !!}


                    <p class="help-block" id="adwords_kop_1{!! $item_id !!}" style="display: none;">
                        {!! trans('messages.adwords_ad_form_lbl2') !!}
                    </p>
                </div>


                <div class="form-group">
                    <label for="" style="width: 100%;">{!! trans('messages.adwords_ad_form_lbl3') !!}
                        <a href="javascript:toggle_id('adwords_kop_2{!! $item_id !!}')">
                            <i class="fa fa fa-question-circle"></i>
                        </a>
                    </label>

                    {!! Form::input('text','headline_2['.$item_id.']',$headline_2,['class'=>'form-control adwords_kop_2',  'id'=>'headline_2_'.$item_id, 'data-item_id'=> $item_id ]) !!}

                    <p class="help-block" id="adwords_kop_2{!! $item_id !!}" style="display: none;">
                        {!! trans('messages.adwords_ad_form_lbl4') !!}
 .

                    </p>
                </div>

                <div class="form-group">
                    <label for="" style="width: 100%;">{!! trans('messages.adwords_ad_form_lbl5') !!}
                        <a href="javascript:toggle_id('adwords_desc_{!! $item_id !!}')">
                            <i class="fa fa fa-question-circle"></i>
                        </a>
                    </label>

                    {!! Form::input('text','description['.$item_id.']',$description,['class'=>'form-control adwords_description', 'id'=>'description_'.$item_id,'data-item_id'=> $item_id ]) !!}

                    <p class="help-block" id="adwords_desc_{!! $item_id !!}" style="display: none;">
                        {!! trans('messages.adwords_ad_form_lbl6') !!}
                    </p>
                </div>


                <div class="form-group">
                    <label for="" style="width: 100%;">{!! trans('messages.adwords_ad_form_lbl7') !!}
                        <a href="javascript:toggle_id('adwords_pad_1{!! $item_id !!}')"> <i class="fa fa fa-question-circle"></i> </a> </label>

                    {!! Form::input('text','path_1['.$item_id.']',$path_1,['class'=>'form-control ad_pad_1', 'id'=>'path_1_'.$item_id,'data-item_id'=> $item_id ]) !!}


                    <p class="help-block" id="adwords_pad_1{!! $item_id !!}" style="display: none;">
                       {!! trans('messages.adwords_ad_form_lbl8') !!}
                    </p>
                </div>

                <div class="form-group">
                    <label for="" style="width: 100%;">{!! trans('messages.adwords_ad_form_lbl9') !!}   <a href="javascript:toggle_id('adwords_pad_2{!! $item_id !!}')"> <i class="fa fa fa-question-circle"></i> </a> </label>

                    {!! Form::input('text','path_2['.$item_id.']',$path_2,['class'=>'form-control ad_pad_2', 'id'=>'path_2_'.$item_id ,'data-item_id'=> $item_id ]) !!}

                    <p class="help-block" id="adwords_pad_2{!! $item_id !!}" style="display: none;">
                        {!! trans('messages.adwords_ad_form_lbl10') !!}
                    </p>
                </div>

                <div class="form-group">
                    <label for="" style="width: 100%;">{!! trans('messages.adwords_ad_form_lbl11') !!}  <a href="javascript:toggle_id('adwords_dest_url_{!! $item_id !!}')"> <i class="fa fa fa-question-circle"></i> </a> </label>


                    {!! Form::input('text','final_url['.$item_id.']',$final_url,['class'=>'form-control', 'id'=>'final_url_'.$item_id,  'data-item_id'=> $item_id]) !!}


                    <p class="help-block" id="adwords_dest_url_{!! $item_id !!}" style="display: none;">
                     {!! trans('messages.adwords_ad_form_lbl12') !!}
                    </p>
                </div>






            </div>

    </div>

    <div class="col-xs-2">



        <div class="adwords_container">
        <a class="adwords_headline">
            <span id="ad_title_1_{!! $item_id !!}">{!! $headline_1 == '' ?  trans('messages.adwords_ad_form_lbl13') : $headline_1 !!}</span> -
            <span id="ad_title_2_{!! $item_id !!}">{!! $headline_2 == '' ?  trans('messages.adwords_ad_form_lbl14') : $headline_2 !!}</span>


        </a>
        <div class="adwords_url">{!! trans('messages.adwords_ad_form_lbl15') !!}<span id="ad_path_1{!! $item_id !!}">{!! $path_1 != '' ? '/'.$path_1.'/' : '' !!}</span><span id="ad_path_2{!! $item_id !!}">{!! $path_2 !!}</span>

        </div>
        <div class="adwords_description" id="ad_description_{!! $item_id !!}"> {!! $description == '' ?  trans('messages.adwords_ad_form_lbl16') : $description !!}</div>
        </div>

        <br>

        @if($adwords_ad_id > 0 )
            <?php
                $no_of_ads = count_backup_templates($adwords_ad_id);
                ?>
                    <div class="btn-group">
            <button type="button" class="btn btn-default"><i class="fa fa-cog"></i></button>
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li>
                    <a href="{!! route('adwords.backup_ads',['feed_id'=>$feed_id,'fk_adwords_feed_id'=>$fk_adwords_feed_id,'parent_id'=>$adwords_ad_id]) !!}">
                        <i class="fa fa-caret-right"></i>
                       Backup templates ({!! $no_of_ads !!})
                    </a>
                </li>

                <li>

                    <a href="javascript:void(0);" class="delete_ad" data-item_id="{!! $item_id !!}" >
                        <i class="fa fa-caret-right"></i>
                        Delete ad
                    </a>
                </li>
            </ul>
        </div>




        @endif




    </div>



</div>
