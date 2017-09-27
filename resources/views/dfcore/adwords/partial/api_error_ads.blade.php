
@if(count($ads_errors) == 0 )
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-check"></i>{!! trans('messages.error_lbl_2') !!}</h4>

    </div>
@endif
<table class="table table-hover" id="manage_feeds">
    <thead><tr>
        <th>Headline 1</th>
        <th>Headline 2</th>
        <th>API MSG</th>
    </tr>
    </thead>
    <tbody>
    @foreach($ads_errors as $ad)
        <tr class="even"  style="color:red" >
            <td>{!! $ad->headline_1 !!}</td>
            <td>{!! $ad->headline_2 !!}</td>
            <td>{!! $ad->adwords_api_message !!}</td>
        </tr>
    @endforeach
    </tbody>

</table>
