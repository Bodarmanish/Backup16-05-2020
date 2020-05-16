@php  
    $agency_contract = @$step_verified_data['contract_data']; 
    $contract_status = @$step_verified_data['contract_status']; 
    $agency_data = @$step_verified_data['agency_data']; 
    $user_email = @$step_verified_data['user_email']; 
@endphp
<div class="row">
    <div class="col-sm-12">
    <h3>Contract with Sponsor Agency</h3>
    @if(empty($step_status))
        <p>This step is disable until user will reach to step.</p>  
    @else 
        @if($contract_status == 1)
            <div>
                <p>User contract started with <strong>{{ @$agency_data->agency_name }}</strong> agency.</p>
            </div>
        @elseif($contract_status == 2)
        <form method="post" id="frm_contract" action="{{ route('agency.contract.list') }}">
            {{ csrf_field() }}
            <input type="hidden" name="email" id="email" value="{{$user_email}}">
            <p><span class="label label-warning m-r-10">User sent request. <a href="javascript:{}" onclick="document.getElementById('frm_contract').submit(); return false;">Click Here</a> to accept or reject request.</span></p>
        </form>
        @elseif($contract_status == 3)
            @if(!empty($agency_contract->is_expired) && $agency_contract->request_status==2)
                <p><span class="label label-success m-r-10">Your contract signed with user.</span></p>
            @elseif(!empty($agency_contract->is_expired) && $agency_contract->request_status==3)
                <p><span class="label label-danger m-r-10">You rejected user contract request.</span></p>
            @else
                <p>Contract Request is expired.</p>
            @endif
        @else
            <p>User still not contract with any agency</p> 
        @endif
    @endif
    </div>
</div>
