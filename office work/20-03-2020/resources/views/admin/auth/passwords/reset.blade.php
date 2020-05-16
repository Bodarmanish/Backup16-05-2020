@extends('admin.layouts.applogin')

@section('content')
<section id="wrapper" class="new-login-register bg-theme-dark">
    <div class="new-login-box">
        <div class="white-box">
            @include('user.includes.status')  
            <form class="form-horizontal form-validator" method="POST" action="{{ route('password.update') }}">
                {{ csrf_field() }}
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-body custom-form-body"> 
                    <div class="form-group">
                        <div class="col-xs-12">
                            <h3>Reset Your Password</h3> 
                        </div>
                    </div>
                    <div class="form-body custom-form-body m-b-10">

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <div class="col-xs-12">
                                <label for="email">Email Address <span class="text-danger">*</span></label> 
                                <input id="email" type="email" class="form-control" name="email" value="{{ $email }}" placeholder="Email Address" required autofocus>
                                <div class="help-block with-errors">
                                    @if ($errors->has('email')){{ $errors->first('email') }}@endif
                                </div>
                                <div class="form-control-feedback"></div> 
                            </div>
                        </div> 

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}"> 

                            <div class="col-md-12">
                                <label for="password">Password <span class="text-danger">*</span></label> <a data-html="true" data-toggle="tooltip" title="<strong>Password Hint:</strong><br/> {{ $password_setting }}" data-placement="right" data-container="body"><i class="fa fa-question-circle text-muted"></i></a>
                                <input id="password" type="password" class="form-control" name="password" placeholder="Password" data-nowhitespace="nowhitespace" required> 
                                <div class="help-block with-errors">
                                    @if ($errors->has('password')){{ $errors->first('password') }}@endif
                                </div>
                                <div class="form-control-feedback"></div>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}"> 
                            <div class="col-md-12">
                                <label for="password-confirm">Confirm Password <span class="text-danger">*</span></label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" data-match="#password" data-match-error="Whoops, these don't match" placeholder="Confirm Password" required> 
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
                            <div class="col-md-4 width-50">
                                <button type="submit" class="btn btn-info btn-lg btn-rounded text-uppercase waves-effect waves-light pull-left">
                                    Reset Password
                                </button>
                            </div>
                        </div>
                    </div> 
                </div>
            </form>  
        </div>
    </div>
</section>
@endsection
