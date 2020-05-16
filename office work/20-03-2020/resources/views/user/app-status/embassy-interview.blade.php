@php
    $step_status = @$step_verified_data['step_status'];
    $embassy_data = @$step_verified_data['embassy_data'];

    $embassy_date = @$step_verified_data['embassy_timezone'];
    
    $embassy_timezone = @$embassy_date->zone_id;
    $embassy_date = @$step_verified_data['embassy_interview'];

    $embassy_timezone_converted = @$embassy_data->dest_timezone_id;
    $embassy_date_converted = @$embassy_data->dest_datetime;

    $timezone_list = @$step_verified_data['timezone_list'];

    if(empty($embassy_timezone))
    {
        $embassy_timezone = session('user_timezone');
    }
@endphp
<div class="row"> 
    <div class="col-sm-12">
        <h3>When is your interview at the US Embassy?</h3>
        <div id="{{ $notify_id }}"></div>
        @if($step_status == 2)
            <p>It will take place on <strong>{{ dateformat($embassy_date_converted,DISPLAY_DATETIME) }}</strong> at your local time zone <strong>{{ get_timezone_label($embassy_timezone_converted) }}</strong></p>
            <p>As soon as you are done with your interview at your local U.S. Consulate / Embassy, please inform us of the result. </p>
            @if(!empty($next_step_key) && $step_status == 2)
                <button type="button" class="btn btn-info" onclick="loadStepContent('{{ $next_step_key }}')">Next Step</button>
            @endif
        @elseif($step_status == 1)
            <p>Let us at J1 know when is your interview at the embassy will take place.</p>
            <form class="m-b-20" name="embassy_interview" id="embassy_interview">
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label class="control-label">Embassy Interview Date & Time <span class="text-danger">*</span></label>
                            <input type="text" name="embassy_interview_date" class="form-control datetimepicker" value="{{ dateformat($embassy_date,'m/d/Y H:i') }}" required="" placeholder="Select Date & Time" autocomplete="off">
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label class="control-label">Local Timezone <span class="text-danger">*</span></label>
                            <select name="embassy_timezone" id="departure_timezone" class="form-control" required="">
                                <option value="">-- Select Timezone --</option>
                                @if(!empty($timezone_list))
                                    @foreach($timezone_list as $zone)
                                        <option value="{{ $zone->zone_id }}" {{ is_selected($zone->zone_id,$embassy_timezone) }}>{{ $zone->zone_label }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-sm btn-info">Submit</button>
                </div>
            </form>
        @endif
    </div>
</div>
<script>
    $(document).ready(function()
    {
        var user_id = $('meta[name="user_token"]').attr('content');
        var form_selector = "#embassy_interview";
        
        ajaxFormValidator(form_selector,function(ele,event){
            event.preventDefault();
            showLoader("#full-overlay");
            
            var form_data = new FormData(ele);
            form_data.append('action','embassy_interview');    
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