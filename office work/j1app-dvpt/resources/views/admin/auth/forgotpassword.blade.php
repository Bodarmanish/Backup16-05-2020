@extends('admin.layouts.applogin')

@section('content')
<section id="wrapper" class="new-login-register bg-theme-dark">
    <div class="new-login-box">
        <div class="white-box">
            <h3>Forgot Password</h3>
            <h5 class="m-b-5">Enter your Email and instructions will be sent to you! </h5>
            @include('admin.includes.status')
            <form class="form-horizontal form-validator" method="POST" action="{{ route('password.email') }}">
                {{ csrf_field() }}
                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                    <div class="col-xs-12">
                        <label>Email Address <span class="text-danger">*</span></label>
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email Address" required autofocus> 
                        <div class="help-block with-errors">
                            @if ($errors->has('email')){{ $errors->first('email') }}@endif
                        </div>
                         <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}"> 
                    <div class="col-md-12">
                        {!! NoCaptcha::renderJs() !!}
                        {!! NoCaptcha::display(['data-theme' => 'light']) !!} 
                        <div class="help-block with-errors">
                            @if ($errors->has('g-recaptcha-response')) {{ $errors->first('g-recaptcha-response') }} @endif
                        </div> 
                    </div>
                </div>                   
                <div class="form-group text-center m-t-20">
                    <div class="col-xs-12">
                        <button class="btn btn-info btn-lg btn-rounded text-uppercase waves-effect waves-light pull-left" type="submit">Continue to Reset Password</button>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12 tex-left">
                        <small>Don't remember your email address,<a href="mailto:{{config('common.contact_email')}}" class="text-info m-l-5"><b>contact us</b></a></small>
                    </div>
                    <div class="col-xs-12 tex-left">
                        <small>If remember your email address, Please<a href="{{ route('login') }}" class="text-info m-l-5"><b>click here</b></a> to login.</small>
                    </div>
                </div>
            </form> 
        </div>
    </div>
</section>
@endsection
