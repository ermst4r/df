@foreach($ads as $ad)
<div class="adwords_container">
    <a class="adwords_headline">
        <span id="ad_title_1_">{!! $ad->headline_1 !!}</span> -
        <span id="ad_title_2_">{!! $ad->headline_2 !!}</span>


    </a>
    <div class="adwords_url">{!! trans('messages.adwords_ad_form_lbl15') !!}/<span id="ad_path_1">{!! $ad->path_1 !!}</span> @if(!empty($ad->path_2))/<span id="ad_path_2">{!! $ad->path_2 !!}</span> @endif

    </div>
    <div class="adwords_description" id="ad_description"> {!! $ad->description !!}</div>
</div>
@endforeach