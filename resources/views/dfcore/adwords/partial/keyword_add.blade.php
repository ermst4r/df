<?php
$keyword = '';
$keyword_type = [];
$id = 0;
if(isset($keywords)) {
    $keyword = $keywords->keyword;
    $keyword_type = ( is_null($keywords->keyword_type) || empty($keywords->keyword_type) ? [] : json_decode($keywords->keyword_type,true));
    $id = $keywords->id;
}



?>




<tr id="keyword_container_{!! $item_id !!}">
    <td class="col-md-5">
        @if(empty($keyword))
        {!! Form::input('text','keyword['.$item_id.']',$keyword,['class'=>'form-control','id'=>'pos_keyword_'.$item_id]) !!}
            @else
            <input type="text" disabled="disabled" value="{!! $keyword !!}" class="form-control">
        @endif
        {!! Form::hidden('keyword_option['.$item_id.']',\App\DfCore\DfBs\Enum\AdwordsOptions::NORMAL_KEYWORD) !!}
        {!! Form::hidden('keyword_id['.$item_id.']',$id) !!}

    </td>


    <td class="col-md-10">



        <label class="checkbox-inline">
            {{ Form::checkbox('keyword_type['.$item_id.'][]', \App\DfCore\DfBs\Enum\AdwordsOptions::MOD_BROAD, in_array(\App\DfCore\DfBs\Enum\AdwordsOptions::MOD_BROAD,$keyword_type),['disabled'=>!empty($keyword)] ) }}
            {!! trans('messages.adwords_keyword_add_lbl1') !!}
        </label>

        <label class="checkbox-inline">
            {{ Form::checkbox('keyword_type['.$item_id.'][]',  \App\DfCore\DfBs\Enum\AdwordsOptions::BROAD, in_array(\App\DfCore\DfBs\Enum\AdwordsOptions::BROAD,$keyword_type),['disabled'=>!empty($keyword)]) }}
            {!! trans('messages.adwords_keyword_add_lbl2') !!}
        </label>

        <label class="checkbox-inline">
            {{ Form::checkbox('keyword_type['.$item_id.'][]',   \App\DfCore\DfBs\Enum\AdwordsOptions::PHRASE, in_array(\App\DfCore\DfBs\Enum\AdwordsOptions::PHRASE,$keyword_type) ,['disabled'=>!empty($keyword)]) }}
            {!! trans('messages.adwords_keyword_add_lbl3') !!}
        </label>
        <label class="checkbox-inline">
            {{ Form::checkbox('keyword_type['.$item_id.'][]',  \App\DfCore\DfBs\Enum\AdwordsOptions::EXACT, (in_array(\App\DfCore\DfBs\Enum\AdwordsOptions::EXACT,$keyword_type) || count($keyword_type) == 0 ),['disabled'=>!empty($keyword)])  }}
            {!! trans('messages.adwords_keyword_add_lbl4') !!}
        </label>

    </td>
    <td class="col-md-3">
        <a class="btn btn-app remove_keyword" data-keyword_item_id="{!! $item_id !!}">
            <i class="fa fa-trash"></i>  {!! trans('messages.adwords_keyword_add_lbl5') !!}
        </a>
    </td>
</tr>