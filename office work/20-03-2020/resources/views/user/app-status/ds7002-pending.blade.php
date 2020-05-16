@php
    $is_multi_placement = @$step_verified_data['is_multi_placement'];
    $step_status = @$step_verified_data['step_status'];
    $placement_data = @$step_verified_data['placement_data'];
@endphp
<div class="row">
    <div class="col-sm-12">
        <h3>Training Placement Plan Creation Pending</h3>
    </div>
    @if($step_status == 0)
        <div class="col-sm-12">
            <p>The {{ __('application_term.ds7002') }} is a Department of State form and serves as the official outline of the proposed internship.</p>
            <p>Once we managed to find a {{ __('application_term.employer') }} and you are offered a job, we will send you and your {{ __('application_term.employer') }} the {{ __('application_term.ds7002') }} Form for signature.</p>  
            <!-- <p>Have question to ask? <a href="#" class="text-info">Join the discussion</a></p> -->
        </div>
    @else
        <div class="col-sm-12">
            <div class="m-b-20">
                <p>Now that you have successfully completed the previous two stages (Registration and Placement), we have sent the {{ __('application_term.ds7002') }} to your {{ __('application_term.employer') }} for their signature. You will receive a notification <strong>on your email</strong> once your {{ __('application_term.employer') }} sign the {{ __('application_term.ds7002') }} form.</p>
                <p><strong>We will send you the notification</strong></p>
                    
            </div>
            <div>
                @if($step_status == 2 && !empty($next_step_key))
                    <button type="button" class="btn btn-info" onclick="loadStepContent('{{ $next_step_key }}')">Next Step</button>
                @endif
            </div>
        </div>
    @endif
</div>
