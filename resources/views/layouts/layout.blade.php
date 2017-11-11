@include('layouts.general.header')

<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="/" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->

            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><img src="/images/logo_admin.png" height="32">  </span>
        </a>
        @include('layouts.general.notifications')
    </header>
    @include('layouts.general.menu')



    <div class="content-wrapper">
        <section class="content">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            @if(Session::has('flash_error_message'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {!!    Session::get('flash_error_message')  !!}
                </div>
            @endif


            @if(Session::has('flash_success_message'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {!!Session::get('flash_success_message') !!}
                </div>
            @endif

            @if(Session::has('flash_warning_message'))
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {!! Session::get('flash_warning_message') !!}
                </div>
            @endif
            @if(Session::has('flash_info_message'))
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {!! Session::get('flash_info_message') !!}
                </div>
            @endif






            @yield('backend-content')
        </section>
    </div>







<!-- ./wrapper -->
@include('layouts.general.footer')
