<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top">
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>

    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

            <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-bell-o"></i>
                    @if(count($task_messages) > 0)
                     <span class="label label-info">{!! count($task_messages) !!}</span>
                        @else
                        <span class="label label-info">0</span>
                    @endif
                </a>
                <ul class="dropdown-menu">
                    @if(count($task_messages) > 0)
                        <li class="header"> {!! trans('messages.general_notifications_lbl3',['notifications'=>count($task_messages)]) !!}</li>
                        @else
                        <li class="header">{!! trans('messages.general_notifications_lbl4') !!}</li>
                    @endif
                    <li>
                        <!-- inner menu: contains the actual data -->
                        <ul class="menu">

                            @foreach($task_messages as $t)
                            <li style="font-size:12px;">

                                <a href="{!! route('common.completed_process') !!}">
                                    @if($t->status==\App\DfCore\DfBs\Enum\TasklogEnum::FAILED)
                                        <i class="fa fa-warning text-red"></i>
                                    @endif

                                        @if($t->status==\App\DfCore\DfBs\Enum\TasklogEnum::FINISHED)
                                            <i class="fa fa-check text-green"></i>
                                        @endif

                                        @if($t->status==\App\DfCore\DfBs\Enum\TasklogEnum::BUSY)
                                            <i class="fa fa-refresh"></i>

                                        @endif



                                    {!! $t->task !!} ({!! $t->feed_name !!})
                                </a>
                            </li>
                            @endforeach


                        </ul>
                    </li>
                    <li class="footer"><a href="{!! route('common.completed_process') !!}">{!! trans('messages.general_notifications_lbl5') !!}</a></li>
                </ul>
            </li>


            <!-- Messages: style can be found in dropdown.less-->
            <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-exclamation-triangle"></i>
                    <span class="label label-{!! $errors == 0 ? 'success' : 'danger' !!}">{!! $errors !!}</span>
                </a>
                <ul class="dropdown-menu">


                    <li class="header">{!! trans('messages.log_lbl_21') !!}</li>
                    <li>
                        <!-- inner menu: contains the actual data -->
                        <ul class="menu">

                            @if(count($errors) == 0 )
                            <li><!-- start message -->
                                <a href="#">

                                    <h4>
                                      {!! trans('messages.log_lbl_19') !!}

                                    </h4>

                                </a>
                            </li>

                                @else
                                <?php $counter = 0;?>

                                @foreach($feedlog_errors as $e)

                                    <li>
                                        <a href="{!! route('common.all_feed_log') !!}" style="font-size: 12px;">
                                            <i class="fa fa-warning text-yellow"></i>  {!! substr($e->log_message,0,40) !!}
                                        </a>
                                    </li>

                                    <?php $counter ++ ;?>
                                    @if($counter == 5 )
                                        @break
                                    @endif

                                @endforeach

                                    <?php $counter = 0;?>
                                    @foreach($dflog_errors as $df_e)

                                        <li>
                                            <a href="{!! route('common.log_report') !!}" style="font-size: 12px;">
                                                <i class="fa fa-warning text-yellow"></i>  {!! substr($df_e->message,0,40) !!}
                                            </a>
                                        </li>

                                        <?php $counter ++ ;?>
                                        @if($counter == 5 )
                                            @break
                                        @endif

                                    @endforeach


                            @endif




                        </ul>
                    </li>
                    <li class="footer"><a href="{!! route('common.all_feed_log') !!}">{!! trans('messages.log_lbl_20') !!}</a></li>
                </ul>
            </li>




            <li class=" notifications-menu">
                @if(count($stores)  == 0 )
                    <a href="{!! route('store.create') !!}" >
                        <i class="fa fa-plus"></i> {!! trans('messages.menu_lbl8') !!}
                    </a>
                @else
                    @if(!is_null($current_store))
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-building"></i> {!! $current_store->store_name !!}
                    </a>
                @endif


                @endif
                    @if(count($stores) > 1)
                <ul class="dropdown-menu">
                    <li class="header" style="text-align: center">{!! trans('messages.menu_lbl7',['number'=>count($stores) == 0 ? trans('messages.menu_lbl6') : count($stores)]) !!} </li>
                    <li>
                        <!-- inner menu: contains the actual data -->
                        <ul class="menu">


                            @foreach($stores as $store)
                                @if(!is_null($current_store) && $current_store->id != $store->id)
                            <li>
                                <a href="javascript:change_store('{!! $store->id !!}');" >
                                    <i class="fa fa-building"></i> {!! $store->store_name !!}
                                </a>
                            </li>
                            @endif
                            @endforeach


                        </ul>
                    </li>
                    <li class="footer">
                        @if(count($stores)  == 0 )
                            <a href="{!! route('store.create')!!}">{!! trans('messages.menu_lbl8') !!}</a>
                        @else
                            <a href="{!! route('store.select_store') !!}">{!! trans('messages.menu_lbl9') !!}</a>

                        @endif


                    </li>
                </ul>
                    @endif
            </li>



            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <span class="hidden-xs"> <i class="fa fa fa-user"> </i> {!! $user->name !!}</span>
                </a>
                <ul class="dropdown-menu">
                    <!-- User image -->
                    <li class="user-header">

                        <p>
                            {!! trans('messages.menu_lbl2',['name'=>$user->name]) !!}

                        </p>
                    </li>

                    <!-- Menu Footer-->
                    <li class="user-footer">

                        <div class="pull-right">
                            <form id="logout-form"
                                  action="{{ url('/logout') }}"
                                  method="POST"
                                  style="display: none;">
                                {{ csrf_field() }}
                            </form>

                            <a href="{{ url('/logout') }}"
                               onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();"
                               class="btn btn-default btn-flat">{!! trans('messages.menu_lbl3') !!}</a>
                        </div>
                    </li>
                </ul>
            </li>

        </ul>
    </div>
</nav>