<div class="row">
<div class="col-md-12">
    <table class="table table-bordered">
        <tbody>

        <thead>
        <tr>
            <th >{!! trans('messages.adwords_keywords_main_lbl1') !!}</th>
            <th>{!! trans('messages.adwords_keywords_main_lbl2') !!}</th>
            <th>{!! trans('messages.adwords_keywords_main_lbl3') !!}</th>

        </tr>
        </thead>
        <tbody id="append_keywords">
        @if(count($adwords_keywords) ==  0 )
        <tr id="no_results_keywords" style="display: none;">
            <td colspan="3">{!! trans('messages.adwords_keywords_main_lbl4') !!}</td>
        </tr>
            @else
            <?php $item_id = 1; ?>
            @foreach($adwords_keywords as $keywords)
                @include('dfcore.adwords.partial.keyword_add',['keywords'=>$keywords,'item_id'=>$item_id])
                <?php $item_id ++ ;?>
            @endforeach

        @endif
        </tbody>

    </table>


    <div class="box-footer">
        <button type="button" class="btn btn-info adwords_new_keyword">{!! trans('messages.adwords_keywords_main_lbl5') !!}</button>
    </div>
</div>


</div>