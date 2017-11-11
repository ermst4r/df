<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <tbody>

            <thead>
            <tr>
                <th >{!! trans('messages.adwords_keyword_neg_lbl1') !!}</th>
                <th>{!! trans('messages.adwords_keyword_neg_lbl2') !!}</th>
                <th>{!! trans('messages.adwords_keyword_neg_lbl3') !!}</th>

            </tr>
            </thead>
            <tbody id="append_neg_keywords">

            @if(count($adwords_negative_keywords) ==  0 )

                <tr id="no_results_neg_keywords" >
                    <td colspan="3">{!! trans('messages.adwords_keywords_main_lbl4') !!}</td>
                </tr>
            @else
                <?php $item_id = 1; ?>
                @foreach($adwords_negative_keywords as $keywords)
                    @include('dfcore.adwords.partial.keyword_negative_add',['keywords'=>$keywords,'item_id'=>$item_id])
                    <?php $item_id ++ ;?>
                @endforeach

            @endif

            </tbody>

        </table>


        <div class="box-footer">
            <button type="button" class="btn btn-info adwords_negative_keyword">
                {!! trans('messages.adwords_keyword_neg_lbl5') !!}
            </button>
        </div>
    </div>


</div>