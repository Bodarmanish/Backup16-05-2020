@extends('user.layouts.app')

@section('content')
<!-- Wrapper -->
<div class="white-box">
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <h2 class="text-info text-uppercase main-title">Accept Invitation from {{$agency_name}}</h2>  
                <div id="invitationWizard" class="wizard">
                    <ul class="wizard-steps" role="tablist">
                        <li role="tab" class="{{ ($user_exist==1)?'active done current':'' }}"> <h4><span>1</span>Basic Details</h4> </li>
                        <li role="tab" class="disabled"> <h4><span>2</span>Contract Agreement</h4> </li>
                    </ul>
                    <div class="wizard-content"> 
                        <div class="wizard-pane" role="tabpanel">
                            @if($user_exist!=1)
                            <form id="invitation_basicdetail" class="form-horizontal new-lg-form" method="POST">
                                {{ csrf_field() }} 
                                <input type="hidden" name="contract_id" value="{{ encrypt($contract_id) }}" /> 
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
                                            <input type="text" class="form-control" value="{{ $email_address }}" disabled> 
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
                                        <div class="col-xs-2">
                                            <button class="btn btn-info btn-lg btn-block btn-rounded text-uppercase" type="submit">Next</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            @else 
                            <div class="form-group text-left">
                                <div class="text-center response alert alert-success">You already regitered with us. Please click on next button to Contract Agreement.</div>  
                                <a class="btn btn-info btn-md btn-rounded text-uppercase wizard-next" href="#invitationWizard" data-wizard="next" role="button">Next</a> 
                            </div>
                            @endif
                        </div>
                        <div class="wizard-pane" role="tabpanel">
                            <form id="invitation_agreement" class="form-horizontal new-lg-form" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="contract_id" value="{{ encrypt($contract_id) }}" />
                                <div class="form-body custom-form-body">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="checkbox checkbox-success p-t-0"> 
                                                <input type="checkbox" id="agree-invitation" required>
                                                <label for="agree-invitation">I agree to accept invitation</label> 
                                            </div>
                                            <div class="help-block with-errors"></div>
                                            <div class="form-control-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="form-group text-center">
                                        <div class="col-xs-2">
                                            <button class="btn btn-info btn-lg btn-block btn-rounded text-uppercase" type="submit" name="finalSubmit">Submit</button>
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
</div>
<!-- End Wrapper -->
@endsection
@section('scripts')
<script>
(function() { 
    $('#invitationWizard').wizard({
        onInit: function() {
            var formselection = "#invitation_basicdetail,#invitation_agreement";
            ajaxFormValidator(formselection,saveDetail);
        }       
    });
})();
function saveDetail(element,e){
    e.preventDefault();
    var formId = $(element).attr("id"); 
    var formID = $("#"+formId).serialize();
    var btn_ele = $(element).find('button[type="submit"]');
    var btn_name = $(btn_ele).html();
    btnLoader(btn_ele);
    $.ajax({
        type: 'post',
        url: "{{ route('accept.invitation') }}", 
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: formID,
        dataType: 'json',
        success: function(response) {
            btnLoader(btn_ele,'hide',btn_name);
            /* remove success box after display success message */
            var $has_success = $("#"+formId).find('.form-group'); 
            $has_success.removeClass('has-success');
            var $right_tick = $("#"+formId).find('.form-control-feedback');
            $right_tick.removeClass('form-control-feedback fa fa-check'); 
            if(response.type == 'success')
            {    
                var Html = '<div class="alert swl-alert-success"><ul><li>'+ response.message+ '</li></ul></div>'; 
                notifyResponseTimerAlert(Html,"success","Success");
                if(response.data.newuser == 1){
                    $("ul.wizard-steps li:first").css("pointer-events", "none");
                    $(".wizard-buttons a.wizard-next").click();
                }
                else{
                    window.location.href="{{ route('myportfolio') }}";
                }
            }
            else{ 
                var Html = '<div class="alert swl-alert-danger"><ul>'; 
                $.each( response.message, function( key, value ) {
                    Html += '<li>' + value+ '</li>';  
                });
                Html += '</ul></div>';  
                notifyResponseTimerAlert(Html,"error","Error");
            }
        }
    });
}
</script> 
@stop