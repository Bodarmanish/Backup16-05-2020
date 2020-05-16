@extends('user.layouts.applogin')

@section('content')
 <!-- Wrapper -->
<div id="wrapper">
    <div id="page-wrapper" class="m-l-0">
        <div class="login-reg-layout forgot_pass"> 
            <div class="row">  
                <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12 pull-right">
                   <div class="well"> 
                        @include('user.includes.status') 
                        <form class="form-horizontal form-validator" method="POST" action="{{ route('password.email') }}">
                            {{ csrf_field() }}
                            <div class="form-body custom-form-body"> 
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <h3>Forgot Password</h3>
                                        <h5 class="m-b-5">Forgot your password? Enter your email you signed up with 'Brand'</h5>
                                        <p class="text-muted">
                                            This protects your account from unauthorized access.
                                        </p>
                                    </div>
                                </div>
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
                            </div> 
                            <div class="form-group m-b-10 text-center">
                                <div class="col-xs-6 text-left"> 
                                    <button class="btn btn-info btn-lg btn-block" type="submit">Continue to Reset Password</button>
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
            </div>   
        </div>
    </div>
</div>
<!-- End Wrapper -->
@endsection
