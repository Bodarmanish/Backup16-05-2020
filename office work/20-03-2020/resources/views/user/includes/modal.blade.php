<!-- Large modal content -->
<div id="modal-lg" class="modal modal-effect fade" style="display: none;" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Please Wait...</h4>
            </div>
            <div class="modal-body" style="height: 100px;">
                <div class="col-md-12 text-center margin2-auto">
                    <i class="fa fa-2x fa fa-spin fa-spinner m-r-10 di"></i>
                    <h4 class="di m-t-0">Please Wait...</h4>
                </div>
                <div class="clearfix"></div> 
            </div>
            <div class="modal-footer hidden">
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Large modal content -->
<div id="modal-lg-itn-odc" class="modal modal-effect connecting_modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="modal-lg-itn-odc" aria-hidden="true" style="display: none;" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body" style="height: 100px;">
                <div class="col-md-12 text-center margin2-auto">
                    <i class="fa fa-2x fa fa-spin fa-spinner m-r-10 di"></i>
                    <h4 class="di m-t-0">Please Wait...</h4>
                </div>
                <div class="clearfix"></div> 
            </div>
            <div class="modal-footer hidden">
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Medium modal content -->
<div id="modal-md" class="modal modal-effect fade" tabindex="-1" role="dialog" aria-labelledby="modal-md" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Please Wait...</h4>
            </div>
            <div class="modal-body" style="height: 100px;">
                <div class="col-md-12 text-center margin2-auto">
                    <i class="fa fa-2x fa fa-spin fa-spinner m-r-10 di"></i>
                    <h4 class="di m-t-0">Please Wait...</h4>
                </div>
                <div class="clearfix"></div> 
            </div>
            <div class="modal-footer hidden">
                <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->

<!-- Small modal content -->
<div id="modal-sm" class="modal modal-effect fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="modal-sm" aria-hidden="true" style="display: none;" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Please Wait...</h4>
            </div>
            <div class="modal-body" style="height: 100px;">
                <div class="col-md-12 text-center margin2-auto">
                    <i class="fa fa-2x fa fa-spin fa-spinner m-r-10 di"></i>
                    <h4 class="di m-t-0">Please Wait...</h4>
                </div>
                <div class="clearfix"></div> 
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->

<!-- Small modal content -->
<div id="modal-default" class="modal modal-effect fade" tabindex="-1" role="dialog" aria-labelledby="modal-default" data-keyboard="false" data-backdrop="static" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title">Please Wait...</h4>
            </div>
            <div class="modal-body" style="height: 100px;">
                <div class="col-md-12 text-center margin2-auto">
                    <i class="fa fa-2x fa fa-spin fa-spinner m-r-10 di"></i>
                    <h4 class="di m-t-0">Please Wait...</h4>
                </div>
                <div class="clearfix"></div> 
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->

<!-- Login modal content -->
<div id="login-modal" class="modal modal-effect fade" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info m-b-20">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Sign In</h4>
            </div>
            <div class="modal-body">
                <div class="col-md-12"> 
                    <div class="col-lg-4 col-xs-12 m-b-20">
                        @include('user.includes.sociallogin')
                    </div> 
                    <div class="col-lg-2 col-xs-12 m-b-20 text-center">
                        <div class="btn btn-success btn-circle btn-lg cdefault pointer_evnt_none or_btn">OR</div>
                    </div>
                    <div class="col-lg-6 col-xs-12">
                        <div class="well"> 
                            <div class="row"> 
                                <div class="col-xs-12">
                                    <div class="alert alert-danger hide" id="login_error">
                                    </div>
                                </div> 
                            </div>
                            <form id="model_loginform" class="form-horizontal new-lg-form form-validator" method="POST" action="{{ route('login') }}">
                                {{ csrf_field() }}
                                <input type="hidden" name="action" value="ajaxlogin">
                                <div class="form-body custom-form-body m-b-10">
                                    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                        <div class="col-xs-12">
                                            <label>Email Address</label>
                                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                                            <div class="help-block with-errors">
                                                @if ($errors->has('email')){{ $errors->first('email') }}@endif
                                            </div>
                                            <div class="form-control-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                        <div class="col-xs-12">
                                            <label>Password</label>
                                            <input type="password" name="password" class="form-control" required>
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
                                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} value="1" /> 
                                            <label for="remember">Remember Me</label>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 text-right">
                                        <a href="javascript:void(0)" id="to-recover" class="text-info">
                                            <i class="fa fa-lock m-r-5"></i><a href=""><b>Lost your password?</a></b>
                                        </a>
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <div class="col-xs-12">
                                        <button class="btn btn-info btn-lg btn-block btn-rounded text-uppercase waves-effect waves-light" type="submit">Log In</button>
                                    </div>
                                </div>
                                <div class="form-group m-b-0">
                                    <div class="col-sm-12 text-center">
                                        <p>Don't have an account? <a href="{{ route('register') }}"><b>Sign Up</b></a></p>
                                    </div>
                                </div>
                            </form>  
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div> 
            </div>
            <div class="modal-footer hidden">
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->