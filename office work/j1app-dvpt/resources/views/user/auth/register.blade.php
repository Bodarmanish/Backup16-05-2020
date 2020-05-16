@extends('user.layouts.applogin')

@section('content')
<!-- Wrapper -->
<div id="wrapper">
    <div id="page-wrapper" class="m-l-0">
        <div class="login-reg-layout">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h2 class="m-b-0">Sign Up</h2>
                    <p class="text-muted">Use a social media account or an email</p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 m-b-20">
                    @include('user.includes.socialLogin') 
                </div>
                <div class="col-lg-3 col-md-3 col-sm-2 col-xs-12 m-b-20 text-center">
                    <div class="btn btn-success btn-circle btn-lg cdefault pointer_evnt_none or_btn">OR</div>
                </div>
                <div class="col-lg-6 col-md-5 col-sm-6 col-xs-12">
                    <div class="well">
                        @include('user.includes.status') 
                        @php
                            if (session('email')){
                                $email = session('email');
                            }else{
                                $email = old('email_address');
                            }
                        @endphp
                        <form id="registerform" class="form-horizontal new-lg-form form-validator" method="POST" action="{{ route('register') }}">
                            {{ csrf_field() }}
                            <div class="form-body custom-form-body">
                                <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                    <div class="col-xs-12">
                                        <label>First Name</label>
                                        <input id="first_name" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" data-notempty="notempty" placeholder="First Name" required>
                                        <div class="help-block with-errors">
                                            @if ($errors->has('first_name')) {{ $errors->first('first_name') }} @endif
                                        </div>
                                        <div class="form-control-feedback"></div>
                                    </div>
                                </div>
                                
                                <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                    <div class="col-xs-12">
                                        <label>Last Name</label>
                                        <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" data-notempty="notempty" placeholder="Last Name" required>
                                        <div class="help-block with-errors">
                                            @if ($errors->has('last_name')) {{ $errors->first('last_name') }} @endif
                                        </div>
                                        <div class="form-control-feedback"></div>
                                    </div>
                                </div>
                                
                                <div class="form-group{{ $errors->has('email_address') ? ' has-error' : '' }}">
                                    <div class="col-xs-12">
                                        <label>Email Address</label>
                                        <input id="email_address" type="email" class="form-control" name="email_address" value="{{ $email }}" placeholder="Email Address" required>
                                        <div class="help-block with-errors">
                                            @if ($errors->has('email_address')) {{ $errors->first('email_address') }} @endif
                                        </div>
                                        <div class="form-control-feedback"></div>
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <div class="col-xs-12">
                                        <label>Password</label> <a data-html="true" data-toggle="tooltip" title="<strong>Password Hint:</strong><br/> {{ $password_setting }}" data-placement="right" data-container="body"><i class="fa fa-question-circle text-muted"></i></a>
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" data-nowhitespace="nowhitespace" required>
                                        <div class="help-block with-errors">
                                            @if ($errors->has('password')) {{ $errors->first('password') }} @endif
                                        </div>
                                        <div class="form-control-feedback"></div>
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                    <div class="col-xs-12">
                                        <label>Confirm Password</label>
                                        <input type="password" name="password_confirmation" id="password-confirm" data-match="#password" data-match-error="Whoops, these don't match" class="form-control" placeholder="Confirm Password" required>
                                        <div class="help-block with-errors">
                                            @if ($errors->has('password_confirmation')){{ $errors->first('password_confirmation') }}@endif
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
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="checkbox checkbox-success p-t-0"> 
                                            <input type="checkbox" id="checkbox-signup" required>
                                            <label for="checkbox-signup">I agree to all <a href="{{ route('terms-condition')}}" target="_blank"><b>Terms & Conditions</b></a> and <a href="{{ route('privacy-notice') }}" target="_blank"><b>Privacy Notice</b></a> </label> 
                                        </div>
                                        <div class="help-block with-errors"></div>
                                        <div class="form-control-feedback"></div>
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <div class="col-xs-12">
                                        <button class="btn btn-info btn-lg btn-block btn-rounded text-uppercase" type="submit">Sign Up</button>
                                    </div>
                                </div>
                                <div class="form-group m-b-0">
                                    <div class="col-sm-12 text-center">
                                        <p>Already have an account? <a href="{{ route("login") }}" ><b>Sign In</b></a></p>
                                    </div>
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