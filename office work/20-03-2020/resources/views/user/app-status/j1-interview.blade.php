@php
    $is_interview_finished = @$step_verified_data['is_interview_finished'];
    $interview_data = @$step_verified_data['interview_data'];
    $is_payment_plan = @$step_verified_data['is_payment_plan'];
@endphp
<div class="row">
    <div class="col-sm-12">
        <h3>J1 Interview</h3> 
        <div id="{{ $notify_id }}"></div>
    </div>
    <div class="col-sm-12">
        <p>The purpose of our pre-screening interview is to evaluate your English level and gather additional information regarding your application.</p>

        @if($is_step_success == 1 && !empty($interview_data))
            <div class="interview_success">
                <div class="alert alert-warning p-25 m-b-20"> 
                    <p>One of our representative will call you as per the following on your Skype ID</p>
                    <ul>
                        <li>15 Minute Meeting</li>
                        <li>{{ dateformat($interview_data->dest_datetime,'H:i:a - l, F d, Y') }}
                        </li>
                        <li>{{ get_timezone_label($interview_data->dest_timezone_id) }}</li> 
                    </ul> 
                </div>
                <p>Please contact responsible person, if you are not able to attend the interview (<a href="mailto:{{ @$interview_data->contact_email }}">{{ @$interview_data->contact_email }}</a>)</p>
            </div>
        @endif
        
        @if(!empty($is_interview_finished) && !empty($next_step_key))
            <button type="button" class="btn btn-info" onclick="loadStepContent('{{ $next_step_key }}')">Next Step</button>
        @endif

        <div id="request_finance_content" class="financial_info">
            @if($is_payment_plan == 1)
                <label>Need financing? Up to <span class="text-info">60% of special financing</span></label>
                <div class="alert alert-success"><p>We took note of your financing request, We will contact you shortly.</p></div>
            @else
                <label>Need financing? Up to <span class="text-info">60% of special financing</span></label>
                <div class="finance_checkbox">
                    <div class="checkbox checkbox-success">
                        <input id="request_finance" type="checkbox" @if($is_step_locked == 1) disabled @endif />
                        <label for="request_finance">Request financing</label>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        var notify_id = "{{ $notify_id }}";
        $('#choose_date_time').on('changeDate', function(e) {
            $('.choose_date_time').addClass('hide');
            $('.financial_info').addClass('hide');
            $('.interview_success').removeClass('hide');
            //itn_interview_toast();
        }); 

        $("#request_finance").click(function(){
            show_inner_loader('.timeline_stp_desc',"#all_tab_data");
            if($(this).is(":checked") == true)
            {
                var url = "{{ route('requestfinance') }}";

                $.ajax({
                    url: url,
                    type: 'post',
                    data: { action:"request_payment_plan" },
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response){
                        if(response.type == "success")
                        {
                            notifyResponse("#request_finance_content .finance_checkbox",response.message,response.type);
                        }
                        else if(response.type == "error")
                        {
                            notifyResponse("#"+notify_id,response.message,response.type);
                        }

                        hide_inner_loader('.timeline_stp_desc',"#all_tab_data");
                    },
                });
            }
        });
    });
</script>