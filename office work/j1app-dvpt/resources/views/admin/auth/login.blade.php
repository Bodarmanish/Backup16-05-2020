@extends('admin.layouts.applogin')

@section('content')
<section id="wrapper" class="new-login-register bg-theme-dark">
    <div class="new-login-box">
        <div class="white-box">
            <h3 class="box-title m-b-0">Sign In to {{config('app.name')}} Admin</h3>
            <small>Enter your details below</small>
            @include('admin.includes.status')
            <form method="post" id="loginform" action="{{ route('login') }}" class="form-horizontal new-lg-form form-validator">
                {{ csrf_field() }}
                <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                    <div class="col-xs-12">
                        <label>Email Address</label>
                        <input class="form-control" type="email" name="email" id="email" required="" placeholder="Email Address">
                        <div class="clearfix"></div>
                        <div class="help-block with-errors">
                            @if ($errors->has('email')){{ $errors->first('email') }}@endif
                        </div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                    <div class="col-xs-12">
                        <label>Password</label>
                        <input class="form-control" type="password" name="password" id="password" required="" placeholder="Password">
                        <div class="clearfix"></div>
                        <div class="help-block with-errors">
                            @if ($errors->has('password')){{ $errors->first('password') }}@endif
                        </div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="checkbox checkbox-info pull-left p-t-0">
                            <input id="checkbox-signup" type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                            <label for="checkbox-signup"> Remember me </label>
                        </div>
                        <a href="{{ route('password.request') }}" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> Lost your password?</a> </div>
                </div>
                <div class="form-group text-center m-t-20">
                    <div class="col-xs-12">
                        <button class="btn btn-info" type="submit">Log In</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
