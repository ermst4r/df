<!-- Left side column. contains the logo and sidebar -->

<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">


        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">

            <li class="{{ getCurrentRouteName() == 'store.select_store' ? 'active' : ''  }} "><a href="{!! route('store.select_store') !!}"><i class="fa fa-building"></i> <span>{!! trans('messages.menu_lbl10') !!}</span></a></li>


            <li class="header">Menu</li>
            @if($has_store_id)
            @foreach(config('menu') as $key=>$value)

                    <li class="{{ in_array(getCurrentRouteName(),$value['active_class']) ? 'active' : ''  }} ">

            @if(count($value['children']) >0 )

                    <a href="{!! route($value['route']) !!}">
                        <i class="{!! $value['fa-icon'] !!} " ></i>
                        <span>{!! trans($value['translation_key']) !!}</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                     </a>
                <ul class="treeview-menu">
                    @foreach($value['children'] as $children)
                        @foreach($children as $c_key=>$c_value)
                            <li class="{{ in_array(getCurrentRouteName(),$c_value['active_class']) ? 'active' : ''  }}"><a href="{!! route($c_value['route']) !!}"><i class="{!! $c_value['fa-icon'] !!}"></i> {!! trans($c_value['translation_key']) !!}</a></li>
                        @endforeach


                    @endforeach
                </ul>

            @else

               <a href="{!! route($value['route']) !!}">
                   <i class="{!! $value['fa-icon'] !!}"></i> {!! trans($value['translation_key']) !!}
               </a>
            @endif
            </li>

            @endforeach
            @endif




        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
