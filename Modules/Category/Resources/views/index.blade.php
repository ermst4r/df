@extends('layouts.layout')
@section('backend-content')


    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">
                {!! trans('category::messages.category_lbl6') !!}
            </h3>
        </div>



        <div class="box-body ">
            <ul>
                <?php

                foreach ($category_list as $r) {
                    echo  $r;
                }
                ?>
            </ul>
        </div>



    </div>
<script>
    (function($){

        alert("habibeh");




    })(jQuery);
</script>

@stop