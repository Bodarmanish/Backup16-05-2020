@php
    $document_data = @$step_verified_data['document_data'];
    $countdown_date = @$document_data->countdown_date;
@endphp
<div class="row">
    <div class="col-sm-12">
        <h3>J1 Resume Approval</h3> 
        <p>What's now:</p>
        <ul>
            <li>One of our representative will review your resume.</li>
            <li>Get notification within 24 hours.</li>
        </ul>
        @if($is_step_success == 1)
            <div class="alert alert-success p-15 approval_success">{{ @$step_verified_data['message'] }}</div>
            @if(!empty($next_step_key))
                <button type="button" class="btn btn-info" onclick="loadStepContent('{{ $next_step_key }}')">Next Step</button>
            @endif
        @elseif($is_step_success == 2)
            <div class="alert alert-warning p-15 approval_success"> 
                <p class="m-b-10">Thank you for providing your resume. We wanted to let you know that although your resume was very competitive, we believe it needs improvement in the below areas... </p>
                <div class="alert alert-warning">@if(!empty($step_verified_data['message'])){!! $step_verified_data['message'] !!}@endif</div>
                <button type="button" class="btn btn-sm btn-info disable" id="reupload_resume">Re-upload Resume</button> 
            </div>
        @else
            <div id="resume_timer">
                <strong>One of our representative will get back to you in...</strong>
                <h2 class="timer text-muted m-t-0"></h2>
            </div>
        @endif
    </div>
</div>
<script>
    $(document).ready(function(){
        if($("#resume_timer").length > 0)
        {
            @if(!empty($countdown_date))
                var finalDate = "{{ $countdown_date }}";
                finalDate = localDateTime(finalDate);
                startCountdown('#resume_timer .timer',finalDate,function(ele){
                    $(ele).after("<div class='alert alert-warning'>{{ $countdown_message }}</div>");
                });
            @endif
        }

        $("#reupload_resume").click(function(){
            loadStepContent('1_resume_upload');
        });
    });
</script>