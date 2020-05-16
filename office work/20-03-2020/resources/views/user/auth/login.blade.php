@extends('user.layouts.applogin')

@section('content')
 <!-- Wrapper -->
<div id="wrapper">
    <div id="page-wrapper" class="m-l-0">
        <div class="login-reg-layout">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h2 class="m-b-0">Sign In</h2>
                    <p class="text-muted">Use a social media account or an email</p>
                </div>         
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 m-b-20">
                    @include('user.includes.sociallogin') 
                </div>
                <div class="col-lg-3 col-md-3 col-sm-2 col-xs-12 m-b-20 text-center">
                    <div class="btn btn-success btn-circle btn-lg cdefault pointer_evnt_none or_btn">OR</div>
                </div>

                <div class="col-lg-6 col-md-5 col-sm-6 col-xs-12">
                    <div class="well"> 
                        @include('user.includes.status') 
                        <form id="loginform" class="form-horizontal new-lg-form form-validator" method="POST" action="{{ route('login') }}">
                            {{ csrf_field() }}
                            <div class="form-body custom-form-body m-b-10">
                                <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                    <div class="col-xs-12">
                                        <label>Email Address</label>
                                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="Email Address" required>
                                        <div class="help-block with-errors">
                                            @if ($errors->has('email')){{ $errors->first('email') }}@endif
                                        </div>
                                        <div class="form-control-feedback"></div>
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                    <div class="col-xs-12">
                                        <label>Password</label>
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" data-nowhitespace="nowhitespace" required>
                                        <span toggle="#password" class="text-muted fa fa-fw fa-eye field-icon toggle-password"></span>
                                        <div class="clearfix"></div>
                                        <div class="help-block with-errors">
                                            @if ($errors->has('password')){{ $errors->first('password') }}@endif
                                        </div>
                                        <div class="form-control-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-6">
                                    <div class="checkbox checkbox-success p-t-0">
                                        <input type="checkbox" name="remember" id="remember" value="1" {{ old('remember') ? 'checked' : '' }} /> 
                                        <label for="remember">Remember Me</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <a href="{{ route('password.request') }}"><i class="fa fa-lock m-r-5"></i><b>Lost your password?</b></a>   
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <div class="col-xs-12">
                                    <button class="btn btn-info btn-lg btn-block btn-rounded text-uppercase" type="submit">Log In</button>
                                </div>
                            </div>
                            <div class="form-group m-b-0">
                                <div class="col-sm-12 text-center">
                                    <p>Don't have an account? <a href="{{ route('register') }}"><b>Sign Up</b></a></p>
                                    <p><a href="{{ route('terms-condition') }}" target="_blank"><b>Terms & Conditions</b></a> | <a href="{{ route('privacy-notice')}}" target="_blank"><b>Privacy Notice</b></a></p>
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
@section('scripts')
<script type="text/javascript">
    $(function() {
        $("#resend_activation_email").on("click",function(){
            show_popup('modal-md');
            get_common_ajax("{{route('resend.verification.captcha')}}",'','modal-md'); 
        });
    });
</script>
@stop