@php
    $step_status = @$step_status;
    $visa_denied_count = @$step_verified_data['visa_denied_count'];
    $consecutive_visa_denied_flag = @$step_verified_data['consecutive_visa_denied_flag'];
    $embassy_data = @$step_verified_data['embassy_data'];
    $allow_visa_process = @$step_verified_data['allow_visa_process'];
    $show_message = @$step_verified_data['show_message'];
   
    $embassy_timezone = @$embassy_data->embassy_timezone;
    $embassy_date = @$embassy_data->embassy_interview;

    $embassy_timezone_converted = @$embassy_data->dest_timezone_id;
    $embassy_date_converted = @$embassy_data->dest_datetime;
    
@endphp
<div class="row">
    <div class="col-sm-12 visa_status_form">
        <h3>US Visa</h3>
        <div id="{{ $notify_id }}"></div>
        @if($step_status == 2)
        <div>
            <p><strong>Congratulations! US Embassy approved your J1 Visa. </strong></p>   
            <p>It's time to do some packing...</p>
        </div>
        @else
            @if($allow_visa_process == 1)
                @if(!empty($show_message))
                    <div class="alert alert-info">
                        <p>{{ $show_message }}</p>
                    </div>
                    <button type="button" name="visa_status" id="visa_approved" class="btn btn-success" value="visa_approved" onclick="frmsubmit(this.value)">Visa Approved</button>
                @else
                    <div id="visa_status_form">
                        <form name="frm_visa_status" id="frm_visa_status">
                            <p>Were you granted your J1 visa at the US Embassy?</p>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group text-center mtop15">
                                    <button type="button" name="visa_status" id="visa_approved" class="btn btn-success" value="visa_approved" onclick="frmsubmit(this.value)">Visa Approved</button>
                                    <button type="button" name="visa_status" id="green_form" class="btn btn-info" value="green_form" onclick="frmsubmit(this.value)">221(g) Letter received (Green Form)</button>
                                    <button type="button" name="visa_status" id="admin_process" class="btn btn-info" value="admin_process" onclick="frmsubmit(this.value)">Under Administrative Processing</button>
                                    <button type="button" name="visa_status" id="visa_denied" class="btn btn-danger" value="visa_denied" onclick="frmsubmit(this.value)">Visa Denied</button>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            @elseif($allow_visa_process == 2)
            <div id="visa_status_form">
                <form name="frm_visa_status" id="frm_visa_status">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <p>We are sorry to hear that your application for your {{ __('application_term.exchange_visitor') }} Visa was denied. You have several options like below.</p>
                        <div class="form-group text-center mtop15">
                            <button type="button" name="visa_status" id="reschedule_appointment" class="btn btn-info" value="reschedule_appointment" onclick="frmsubmit(this.value)">Schedule Another J1 Visa Appointment</button>
                            <button type="button" name="visa_status" id="quit_program" class="btn btn-danger" value="quit_program" onclick="frmsubmit(this.value)">Quit Program</button>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <p>In case of you change your mind or you may denied visa by mistake then below is the option to get back to visa approve.</p>
                        <div class="form-group text-center mtop15">
                            <button type="button" name="visa_status" id="visa_denied_undo" class="btn" value="visa_denied_undo" onclick="frmsubmit(this.value)">Undo Visa Denied</button>
                        </div>
                    </div>
                </form>
            </div>
            @elseif($allow_visa_process == 3)
                @if($consecutive_visa_denied_flag == 1)
                    <div id="visa_status_form">
                        <form name="frm_visa_status" id="frm_visa_status">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <p>We are sorry to hear that your application for your {{ __('application_term.exchange_visitor') }} Visa was denied. And that you decied to cancel to your J1 program, Please click on button below to confirm your decision </p>
                                <div class="form-group text-center mtop15">
                                    <button type="button" name="visa_status" id="quit_program" class="btn btn-danger" value="quit_program" onclick="frmsubmit(this.value)">Quit Program</button>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <p>Please contact <a href='mailto:{{ config('common.odyssey_contact') }}'>{{ config('common.odyssey_contact') }}</a> to discuss your options moving forward.It maybe help you for approved next time</p>
                    </div>
                @endif
            @elseif($allow_visa_process == 4)
                <div class="alert alert-warning">
                    <p>Please contact <a href='mailto:{{ config('common.odyssey_contact') }}'>{{ config('common.odyssey_contact') }}</a> to discuss your options moving forward.It maybe help you for approved next time</p>
                </div>
            @else
                <p>Your Embassy interview will take place on <strong>{{ dateformat($embassy_date_converted,DISPLAY_DATETIME) }}</strong> at your local timezone <strong>{{ get_timezone_label($embassy_timezone_converted) }}</strong></p>
                <p>As soon as you are done with your interview at your local U.S. Consulate / Embassy, please inform us of the result. </p>
            @endif
        @endif
    </div>
</div>
<script>
    function frmsubmit(submit_btn)
    {
        if(submit_btn == "visa_denied"){
            var msg = "Are you sure you want to update your status as 'visa denied'?";
            confirmAlert(msg,"warning","Confirm","Confirm",function(r,i){
                if(i){
                    submit_visa_form(submit_btn);
                }
            });
        }
        else if(submit_btn == "quit_program"){
            var msg = "Are you sure you want to quit program?";
            confirmAlert(msg,"warning","Confirm","Confirm",function(r,i){
                if(i){
                    submit_visa_form(submit_btn);
                }
            });
        }
        else{
            submit_visa_form(submit_btn);
        }
    }
    $(document).ready(function(){
        var notify_id = "{{ $notify_id }}";
        var form_selector = "#frm_visa_status";
        
        @if(@$is_step_locked == 1)
            $(form_selector)
                .find('input, select, textarea, button[type=submit]')
                .attr("disabled",true);
        @endif
    });
    
    function submit_visa_form(submit_btn){
        showLoader("#full-overlay");
        var notify_id = "{{ $notify_id }}";
        var url = "{{route('visa.stage')}}";
        var user_id = $('meta[name="user_token"]').attr('content');
        
        var form_data = new FormData();
        form_data.append('visa_status',submit_btn);
        form_data.append('action','visa_outcome');
        form_data.append('user_id',user_id);
        
        $.ajax({
            url: url,
            type: 'post',
            data: form_data,
            dataType: 'json',
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response){
                $("#frm_visa_status").hide();
                hideLoader("#full-overlay");
                notifyResponse("#"+notify_id,response.message,response.type);
                if(response.visa_status == "reschedule_appointment" && response.denied_order == 1)
                {
                    setTimeout(function(){
                        navigateStages('{{ $active_stage }}',"3_us_embassy_interview");
                    },1000);
                }
                else
                {
                    setTimeout(function(){
                        navigateStages('{{ $active_stage }}',"3_us_visa_outcome");
                    },3000);
                }
            },
        });
    }
</script>