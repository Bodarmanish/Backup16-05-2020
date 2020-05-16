@php
    $flight_data = @$step_verified_data['flight_data'];
@endphp
<div class="row"> 
    <div class="col-sm-12">
        <h3>Arrival in the United States</h3>  
        @if(!empty($flight_data))
            @if($flight_data->is_date_passed == 1)
                <div class="alert alert-success text-center">
                    <p><strong>Welcome to The USA...</strong></p>
                </div>
            @else
            <p>Your arrival date and time are recorded for <strong>{{ dateformat($flight_data->arrival_date,DISPLAY_DATETIME) }}</strong> according to following time zone <strong>@if(!empty($flight_data->zone_label)){{ $flight_data->zone_label }}@endif</strong></p>
            @endif
        @else
            <p>This will be final step of your application.</p>
        @endif

        @if(!empty($portfolio->sponsor_agency_id) && false)
            <button type="button" class="btn btn-info" onclick="navigateStages('4')">Next Stage</button>
        @endif
    </div>
</div>