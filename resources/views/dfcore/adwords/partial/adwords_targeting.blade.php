<?php
    $adwords_target_id = 0;
    $campaign_type = '';
    $ad_delivery = '';
    $prefilled_languages = [1010];
    $prefilled_countries = [2528];
    $live = 0;
    if(!is_null($adwords_target)) {
        $adwords_target_id = $adwords_target->id;
        $campaign_type = $adwords_target->campaign_type;
        $ad_delivery = $adwords_target->ad_delivery;
        $prefilled_languages = ( is_null($adwords_target->target_languages) || empty($adwords_target->target_languages) ? [] : json_decode($adwords_target->target_languages,true));
        $prefilled_countries = ( is_null($adwords_target->target_countries) || empty($adwords_target->target_countries) ? [] : json_decode($adwords_target->target_countries,true));
        $live = $adwords_configuration->live;

    }


?>

{!! Form::hidden('adwords_target_id',$adwords_target_id) !!}
    <div class="box-body">
        <div class="form-group">
            <label> {!! trans('messages.adwords_targeting_lbl1') !!}</label>
            <select class="form-control" name="campaign_type" {!! $live == true ? 'disabled' : '' !!}>
                <option value="{!! \App\DfCore\DfBs\Enum\AdwordsOptions::SEARCH_NETWORK !!}" {!! $campaign_type == \App\DfCore\DfBs\Enum\AdwordsOptions::SEARCH_NETWORK  ? 'selected' : ''  !!}> {!! trans('messages.adwords_preview_lbl28') !!}</option>
                <option value="{!! \App\DfCore\DfBs\Enum\AdwordsOptions::DISPLAY_NETWORK !!}" {!! $campaign_type == \App\DfCore\DfBs\Enum\AdwordsOptions::DISPLAY_NETWORK  ? 'selected' : ''  !!}> {!! trans('messages.adwords_preview_lbl29') !!}</option>

            </select>
        </div>
        <div class="form-group">
            <label>  {!! trans('messages.adwords_targeting_lbl2') !!} </label>
            <select class="form-control" name="ad_delivery" {!! $live == true ? 'disabled' : '' !!}>
                <option value="{!! \App\DfCore\DfBs\Enum\AdwordsOptions::AD_STANDARD !!}" {!! $ad_delivery == \App\DfCore\DfBs\Enum\AdwordsOptions::AD_STANDARD  ? 'selected' : ''  !!}> {!! trans('messages.adwords_preview_lbl30') !!}</option>
                <option value="{!! \App\DfCore\DfBs\Enum\AdwordsOptions::AD_ACCELERATED !!}" {!! $ad_delivery == \App\DfCore\DfBs\Enum\AdwordsOptions::AD_ACCELERATED  ? 'selected' : ''  !!}> {!! trans('messages.adwords_preview_lbl31') !!} </option>
            </select>

        </div>

        <div class="form-group">
            <label> {!! trans('messages.adwords_targeting_lbl3') !!}</label>


            <select class="form-control adwords_country_select2" multiple="multiple"  style="width: 100%;" name="target_countries[]" {!! $live == true ? 'disabled' : '' !!}>
               @foreach($target_countries as $country)
                    <option  {!! in_array($country->criteria_id,$prefilled_countries) ? 'selected' : ''!!} value="{!! $country->criteria_id !!}">
                        {!! $country->country_name !!}
                    </option>
               @endforeach
            </select>

        </div>


        <div class="form-group">
            <label> {!! trans('messages.adwords_targeting_lbl4') !!} </label>

            <select class="form-control adwords_language_select2" multiple="multiple"  style="width: 100%;" name="target_languages[]" {!! $live == true ? 'disabled' : '' !!}>

                @foreach($target_languages as $language)
                    <option  {!! in_array($language->criteria_id,$prefilled_languages) ? 'selected' : ''!!} value="{!! $language->criteria_id !!}">
                        {!! $language->language_name !!}
                    </option>
                @endforeach
            </select>


        </div>


    </div>



