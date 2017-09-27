


@if(count($adwords_ads) > 0)
    <?php $item_id = 1 ;?>
    @foreach($adwords_ads as $ad)
        @include('dfcore.adwords.partial.adwords_ad_form',compact('ad','item_id','backup'))
        <?php $item_id ++ ; ?>
    @endforeach

    @else
    @include('dfcore.adwords.partial.adwords_ad_form',compact('ad','counter','backup'))
@endif


<div class="append_ads"></div>

<div class="box-footer">
    <button class="btn btn-primary add_template" type="button">
        <i class="fa fa-plus"></i>
        @if($backup)
            {!! trans('messages.adwords_backup_lbl7') !!}
            @else
            {!! trans('messages.adwords_ad_form_lbl19') !!}
        @endif


    </button>

</div>
<!-- /.box-footer -->