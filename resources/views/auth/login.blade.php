@extends('layouts.login')
@section('login-content')

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">

        </div>
        <div class="login-box-body">

            <div align="center">
                <img src="images/full-dfbuilder.png" width="200">
            <p class="login-box-msg">{!! trans('messages.login_label_1') !!}</p>
            </div>
            @if ($errors->has('email') || $errors->has('password'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> {!! trans('messages.login_label_7') !!}</h4>

                @if ($errors->has('email'))

                    {{ $errors->first('email') }}

                @endif

                @if ($errors->has('password'))
                    <br>
                    {{ $errors->first('password') }}


                @endif

            </div>
            @endif

            <form action="{{ route('login') }}" method="post" id="loginForm">
                {{ csrf_field() }}
                <div class="form-group has-feedback">
                    <input type="email" class="form-control" placeholder="{!! trans('messages.login_label_2') !!}" name="email" value="{{ old('email') }}">



                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="{!! trans('messages.login_label_3') !!}" name="password">


                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <label style="font-weight: normal;">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            {!! trans('messages.login_label_4') !!}
                        </label>

                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">   {!! trans('messages.login_label_5') !!}</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->
</body>

@stop
