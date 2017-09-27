


@if(count($wizard ) > 0 )


                <ul class="nav nav-pills nav-justified thumbnail dfbuilder_breadcrumb" >
                    <?php $teller = 1; ?>
                    @foreach($wizard as $values)

                        @if(!is_array($route_name))
                        <li  class="{{ $route_name == $values['route_name'] ? 'active' : '' }}" ><a href="{!! $values['route'] !!}"  >
                        @endif


                                <h4 class="list-group-item-heading">Stap {!! $teller !!}</h4>
                                <p class="list-group-item-text">  {!! $values['label'] !!}</p>
                            </a></li>

                        <?php $teller ++ ;?>
                    @endforeach
                </ul>


@endif

