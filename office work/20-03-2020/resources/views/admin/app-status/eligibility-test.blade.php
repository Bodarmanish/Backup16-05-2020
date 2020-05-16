@php
    $industry_selected = @$step_verified_data['industry_selected'];  
    $program_name = @$step_verified_data['program_name'];
    $eligible_industries = config('common.eligible_industries');
@endphp
<div class="row">
    <div class="col-sm-12">
        <h3>Eligibility Test</h3>
        
        @if($step_status == 1 && @$is_step_success == 1)
            <p>User has been successfully passed the eligibility test.</p>
            @if(!empty($industry_selected))
                <p><strong>Selected Industry:</strong> {{ $eligible_industries[$industry_selected] }}</p>
            @endif
            <p><strong>Program Name:</strong> {{ $program_name }}</p> 
        @else
            <p>User still not complete eligibility test.</p>
        @endif
        
    </div>
</div>