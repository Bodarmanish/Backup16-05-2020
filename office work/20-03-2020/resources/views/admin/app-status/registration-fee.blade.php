<div class="row">
    <div class="col-sm-12">
        <h3>Registration Fee </h3> 
        <p>$50 USD/EUR non-refundable registration fee. This is a one time, non-refundable fee for the processing of your application.</p> 
        @if(empty($step_status)) 
            <p>This step is disable until user will reach to step.</p>  
        @else 
            @if(!empty($step_verified_data['reg_fee_status']))
                <div class="paynow_success">
                    <div class="alert alert-success p-25">Thank you, the payment has been confirmed.</div>
                    <p><label>Payment Status:</label> {{ (@$step_verified_data['reg_fee_status']==1)?"($50) - Charge":"($50) - Postpone" }}</p>
                </div>
                @if($step_verified_data['reg_fee_status']==1)
                    <button type="button" class="btn btn-sm btn-info" onclick="collectRegFee('{{ $active_step_key }}')">Registration Fee Collected</button>
                @endif
            @endif
        @endif
    </div>
</div>
<script type="text/javascript">
    function collectRegFee(active_step_key){
        var user_id = $('meta[name="user_token"]').attr('content');
        
        showLoader("#full-overlay");
        $.ajax({
            url:  "{{ route('collect.reg.fee') }}",
            type: 'post',
            data: {
                'user_id': user_id,
            },
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response){  
                if(response.type == "success"){
                    var Html = '<div class="alert swl-alert-success"><ul><li>'+response.message+'</li></ul></div>'; 
                    notifyResponseTimerAlert(Html,"success","Success");
                    setTimeout(function(){
                        navigateStages(1,active_step_key);
                    }, 3000); 
                }
                else{
                    var Html = '<div class="alert swl-alert-danger"><ul><li>'+response.message+'</li></ul></div>'; 
                    notifyResponseTimerAlert(Html,"success","Success"); 
                }
                hideLoader("#full-overlay");
            },
        });
    }
</script>