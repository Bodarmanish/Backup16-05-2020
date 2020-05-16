@php
    $legal_data = @$step_verified_data['legal'];
@endphp
<div class="row">
    <div class="col-sm-12">
        <h3>Certificate of Eligibility for {{ __('application_term.exchange_visitor') }} Status ({{ __('application_term.ds2019') }})</h3>  
    </div>
    <div class="col-sm-12">
        <p> The {{ __('application_term.ds2019') }} Form is the document that allows you to apply for the {{ __('application_term.exchange_visitor') }} Visa as an Intern or Trainee in the USA. The {{ __('application_term.ds2019') }} form is also called "Certificate of Eligibility". It is issued to you by a US Department of State-designated sponsor organization.</p>   
        <!-- <p>Have question to ask? <a href="#">Join the discussion</a></p> -->
        @if($is_step_success == 1)
            <table class="table table-bordered color-table info-table m-b-20">
                <thead>
                    <tr>
                        <th colspan="2" class="text-center"><strong>{{ __('application_term.ds2019') }} Details</strong></th>
                    </tr>
                    <tr>
                        <th style="width: 200px;"><strong>DS Number</strong></th>
                        <td>{{ $legal_data->ds_number }}</td>
                    </tr>
                    <tr>
                        <th><strong>DS Start Date</strong></th>
                        <td>{{ dateformat($legal_data->ds_start_date,DISPLAY_DATETIME) }}</td>
                    </tr>
                    <tr>
                        <th><strong>DS End Date</strong></th>
                        <td>{{ dateformat($legal_data->ds_end_date,DISPLAY_DATETIME) }}</td>
                    </tr>
                    <tr>
                        <th><strong>DS Tracking Number</strong></th>
                        <td>{{ $legal_data->tracking_number }}</td>
                    </tr>
                    <tr>
                        <th><strong>DS Shipment Date</strong></th>
                        <td>{{ dateformat($legal_data->ds_shipment_date,DISPLAY_DATETIME) }}</td>
                    </tr>
                </thead>
            </table>
            
            @if(!empty($next_step_key))
                <button type="button" class="btn btn-info" onclick="loadStepContent('{{ $next_step_key }}')">Next Step</button>
            @endif
        @endif
    </div>
</div>