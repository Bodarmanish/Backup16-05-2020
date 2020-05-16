@php
    $embassy_date = @$step_verified_data['embassy_interview'];
    $embassy_timezone = @$step_verified_data['embassy_timezone'];
    $embassy_timezone_id = @$step_verified_data['embassy_timezone_id'];
    $is_step_success = @$step_verified_data['is_step_success'];
    $class = $is_step_success == 1 ? "hide" : "";
@endphp
<div class="row"> 
    <div class="col-sm-12">
        @if(empty($step_status)) 
            <p>This step is disable until user will reach to step.</p>  
        @else
            <h3>When is your interview at the US Embassy?</h3>
            @if($is_step_success==1 && !empty($embassy_date) && !empty($embassy_timezone))
                <div class="panel" >
                    <div class="panel-heading" id="embassyinterview_tab" role="tab"> 
                        <a class="panel-title" data-toggle="collapse" href="#embassyinterview" data-parent="#ds2019" aria-expanded="true" aria-controls="embassyinterview">Embassy Interview Information</a>
                    </div>
                    <div class="panel-collapse collapse in" id="embassyinterview" aria-labelledby="exampleHeadingDefaultOne" role="tabpanel">
                        <div class="panel-body">
                            <p>Embassy Date: <a href="javascript:void(0);">{{ dateformat($embassy_date,DISPLAY_DATETIME)}}</a></p>
                            <p>Embassy Timezone: <a href="javascript:void(0);">{{$embassy_timezone->zone_label}}</a></p>
                            <hr/>
                            <div class="clearfix"></div>
                            <div class="text-center response"></div> 
                            <div class="clearfix"></div>
                            <a  id="div_interviewinfo" href="javascript:void(0);" class="btn btn-info m-r-15" data-toggle="tooltip" data-original-title="Edit" onclick="return  embassy_interview_frm('showfrm');">Reschedule Embassy Interview</a>
                        </div>
                    </div> 
                </div>
            @endif
            <div class="panel {{$class}}" id="div_interviewfrm">
                    <div class="panel-heading" id="interview_info_tab" role="tab"> <a class="panel-title collapsed" data-toggle="collapse" href="#interview_info" data-parent="#ds2019" aria-expanded="false" aria-controls="interview_info">Embassy Interview</a> </div>
                    <div class="panel-collapse collapse in" id="interview_info" aria-labelledby="exampleHeadingDefaultTwo" role="tabpanel">
                        <div class="panel-body"> 
                            <form name="embassy_frm" id="embassy_frm" method="post" novalidate="true" class="form-horizontal"> 
                                <input type="hidden" name="action" value="embassy_interview" />
                                <div class="interview_preview">
                                    {{ csrf_field() }} 
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group {{ $errors->has('embassy_timezone') ? 'has-error' : '' }}">
                                                <label class="col-md-12">Timezone <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <select name="embassy_timezone" id="embassy_timezone" class="form-control" required="">
                                                        <option value="">-- Select Timezone --</option>
                                                        @foreach($timezones as $zone)
                                                        <option  {{ is_selected($embassy_timezone_id,$zone->zone_id) }} value="{{ $zone->zone_id }}">{{ $zone->zone_label }}</option>
                                                        @endforeach
                                                    </select> 
                                                    <div class="help-block with-errors">
                                                        @if ($errors->has('embassy_timezone')){{ $errors->first('embassy_timezone') }}@endif
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>    
                                        <div class="col-sm-6">
                                            <div class="form-group {{ $errors->has('datetime') ? 'has-error' : '' }}">
                                                <label class="col-md-12">Select Date and Time <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <input type="text" name="embassy_interview" placeholder="Date and Time" class="form-control datetimepicker" required autocomplete="off" value="{{!empty(@$embassy_date) ? dateformat($embassy_date,'m/d/Y H:i A') : ''}}" required>
                                                    <div class="help-block with-errors"></div> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <button type="reset" class="btn btn-danger" onclick="embassy_interview_frm('embassydata')">Cancel</button>
                                                    <button type="submit" class="btn btn-info">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form> 
                        </div>
                    </div>
            </div>
        @endif
    </div>
</div>
<script>
    function embassy_interview_frm(type)
    {
        showLoader("#full-overlay");
        if(type == 'showfrm')
        {
            confirmAlert("On confirm Reschedule Embassy Interview.","warning","Are you sure?","Confirm",function(r,i){
                if(i)
                {
                    $("#div_interviewfrm").removeClass( "hide" );
                    $("#div_interviewinfo").addClass( "hide" );
                    hideLoader("#full-overlay");
                }
                else
                {
                    hideLoader("#full-overlay");
                }
            });
        }
        else{
            $(".with-errors").empty();
            $("#embassy_frm").find('.has-error').removeClass("has-error");
            
            if("{{$is_step_success}}" == 1)
            {
                $("#div_interviewfrm").addClass( "hide" );
                $("#div_interviewinfo").removeClass( "hide" );
            }
            
            hideLoader("#full-overlay");
        }
    }

    $(document).ready(function()
    {
        var user_id = $('meta[name="user_token"]').attr('content');
        var form_selector = "#embassy_frm";
        
        ajaxFormValidator(form_selector,function(ele,event){
            event.preventDefault();
            showLoader("#full-overlay");
            
            var form_data = new FormData(ele);
                form_data.append('user_id',user_id);
            
            var url = "{{ route('visa.stage') }}";
            
            $.ajax({
                type: 'post',
                url: url, 
                data: form_data,
                dataType: 'json',
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    if(response.type=='success'){
                        var Html = '<div class="alert swl-alert-success"><ul><li>'+ response.message+ '</li></ul></div>'; 
                        notifyResponseTimerAlert(Html,"success","Success");
                        setTimeout(function(){
                            navigateStages(3,'{{$active_step_key}}');
                        }, 3000); 
                    }
                    else{
                        hideLoader("#full-overlay");
                        var Html = '<div class="alert swl-alert-danger"><ul>'; 
                        $.each( response.message, function( key, value ) {
                            Html += '<li>' + value+ '</li>';  
                        });
                        Html += '</ul></div>';  
                        notifyResponseTimerAlert(Html,"error","Error");
                    } 
                }
            });
        }); 
    });

</script>