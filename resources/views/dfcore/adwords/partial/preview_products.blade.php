
        <table class="table table-hover" id="manage_feeds">
            <thead><tr>
                <th>Headline 1</th>
                <th>Headline 2</th>
                <th>Description</th>
                <th>Path 1</th>
                <th>Path 2</th>
                <th>Final Url</th>
                <th>Errors</th>
            </tr>
            </thead>
            <tbody>
            @foreach($ads as $ad)
                <tr class="even" {!! $ad->is_valid == false ? 'style=color:red' : '' !!}>
                    <td>{!! $ad->headline_1 !!}</td>
                    <td>{!! $ad->headline_2 !!}</td>
                    <td>{!! $ad->description !!}</td>
                    <td>{!! $ad->path_1 !!}</td>
                    <td>{!! $ad->path_2 !!}</td>
                    <td>{!! $ad->final_url !!}</td>
                    <td>
                    <?php
                        if($ad->is_valid == false) {
                            $ad_errors = json_decode($ad->errors,true);
                            foreach($ad_errors as $field_name => $values) {
                                echo $field_name .' '. $values[0].'<BR><br>';
                            }
                        } else {
                            echo '<i class="fa fa-check" style="color:green"></i>';
                        }
                    ?>

                    </td>

                </tr>


            @endforeach
            </tbody>

        </table>
