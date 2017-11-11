<?php
    $campaign_name = '';
    $adgroup_name = '';
    $cpc = 1.00;
    $daily_budget = 1.00;
    $existing_campaign = 1;
    $campaign_adwords_id = 0 ;
    $live = false;
    $live_option = \App\DfCore\DfBs\Enum\AdwordsOptions::PREVIEW_MODUS;
    if(!is_null($adwords_configuration)) {
        $existing_campaign = $adwords_configuration->existing_campaign;
        $campaign_adwords_id = $adwords_configuration->campaign_adwords_id;
        $campaign_name = $adwords_configuration->campaign_name;
        $adgroup_name = $adwords_configuration->adgroup_name;
        $cpc = number_format($adwords_configuration->cpc,2,'.','');
        $daily_budget = number_format($adwords_configuration->daily_budget,2,'.','');
        $live = $adwords_configuration->live;
        $live_option = $adwords_configuration->live_option;
    }
?>


{!! Form::hidden('campaign_adwords_id',$campaign_adwords_id) !!}
{!! Form::hidden('live',$live) !!}

            <div class="form-group">
                <label >{!! trans('messages.adwords_settings_lbl1') !!}</label>


                <select id="existing_campaign" class="form-control" name="existing_campaign"  {{ $live ==  true ? 'disabled' : '' }}>
                    <option value="1" {!! $existing_campaign == 1 ? 'selected' : '' !!}>{!! trans('messages.adwords_preview_lbl25') !!}</option>
                    <option value="2" {!! $existing_campaign == 2 ? 'selected' : '' !!}>{!! trans('messages.adwords_preview_lbl26') !!}</option>
                </select>

            </div>


            <div id="adwords_preloader" style="display: none;">
                <img src="/img/wheel.gif" height="16">
                {!! trans('messages.adwords_preview_lbl24') !!}
                <br><br>
            </div>
            <div class="form-group" id="select_campaign_wrapper" style="display:none;">


                <label for="exampleInputEmail1"  style="width: 100%;">{!! trans('messages.adwords_preview_lbl27') !!}</label>
                <select id="prefilled_campaigns" class="form-control" name="prefilled_campaign"  {{ $live ==  true ? 'disabled' : '' }}>
                </select>
            </div>


            <div class="form-group" id="campaign_name_wrapper">
                <label for="exampleInputEmail1"  style="width: 100%;">{!! trans('messages.adwords_settings_lbl2') !!}</label>

                @if($live)
                    <input type="text"  disabled value="{!! $campaign_name !!}" class="form-control">
                    @else
                    {!! Form::input('text','campaign_name',$campaign_name,['class'=>'form-control','id'=>'campaign_name']) !!}
                @endif

            </div>

            <div class="form-group">
                <label for="exampleInputEmail1" style="width: 100%;">{!! trans('messages.adwords_settings_lbl3') !!}</label>
                @if($live)
                    <input type="text"  disabled value="{!! $adgroup_name !!}" class="form-control">
                    @else
                    {!! Form::input('text','adgroup_name',$adgroup_name,['id'=>'adgroup_name']) !!}
                @endif

            </div>


            <div class="form-group">
                <label for="exampleInputEmail1">{!! trans('messages.adwords_settings_lbl4') !!}</label>
                @if($live)
                    {!! Form::input('text','cpc',$cpc,['class'=>'form-control','disabled'=>true,'placeholder'=>trans('messages.adwords_ad_form_lbl20')]) !!}
                    @else
                    {!! Form::input('text','cpc',$cpc,['class'=>'form-control','placeholder'=>trans('messages.adwords_ad_form_lbl20')]) !!}
                @endif
            </div>

            <div class="form-group">
                <label for="exampleInputEmail1">{!! trans('messages.adwords_settings_lbl5') !!}</label>
                @if($live)
                    {!! Form::input('text','daily_budget',$daily_budget,['class'=>'form-control','disabled'=>true,'placeholder'=>trans('messages.adwords_ad_form_lbl20')]) !!}
                    @else
                    {!! Form::input('text','daily_budget',$daily_budget,['class'=>'form-control','placeholder'=>trans('messages.adwords_ad_form_lbl20')]) !!}
                @endif
            </div>


            <div class="form-group">
                <label style="color: green;" >{!! trans('messages.adwords_settings_lbl6') !!} </label>


                <select id="live_option" class="form-control" name="live_option"  {{ $live ==  true ? 'disabled' : '' }}>
                    <option value="{!! \App\DfCore\DfBs\Enum\AdwordsOptions::PREVIEW_MODUS !!}" {!! $live_option == \App\DfCore\DfBs\Enum\AdwordsOptions::PREVIEW_MODUS ? 'selected' : '' !!}>{!! trans('messages.adwords_settings_lbl7') !!}</option>
                    <option value="{!! \App\DfCore\DfBs\Enum\AdwordsOptions::AD_PAUSED !!}" {!! $live_option == \App\DfCore\DfBs\Enum\AdwordsOptions::AD_PAUSED ? 'selected' : '' !!}> {!! trans('messages.adwords_settings_lbl8') !!}</option>
                    <option value="{!! \App\DfCore\DfBs\Enum\AdwordsOptions::CAMPAIGN_PAUSED !!}" {!! $live_option == \App\DfCore\DfBs\Enum\AdwordsOptions::CAMPAIGN_PAUSED ? 'selected' : '' !!}> {!! trans('messages.adwords_settings_lbl9') !!}</option>
                    <option value="{!! \App\DfCore\DfBs\Enum\AdwordsOptions::ALL_LIVE !!}" {!! $live_option == \App\DfCore\DfBs\Enum\AdwordsOptions::ALL_LIVE ? 'selected' : '' !!}> {!! trans('messages.adwords_settings_lbl10') !!}</option>
                </select>

            </div>

        </div>