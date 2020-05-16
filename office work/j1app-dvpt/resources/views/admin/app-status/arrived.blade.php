@php  
    $step_status = @$step_verified_data['step_status'];
    $flight_data = @$step_verified_data['flight_data'];
    $step_success = @$step_verified_data['is_step_success'];
@endphp
<div class="row"> 
    <div class="col-sm-12">
        <h3>Arrival in the United States</h3> 
        @if(empty($step_status)) 
            <p>This step is disable until user will reach to step.</p>  
        @else
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
            <div class="clearfix"></div>
            <hr>
            @if($step_success == 0)
                <a onclick="arrived()" href="javascript:void(0)" class="btn btn-info m-r-15" title="Arrived" >Arrived</a>
            @endif
        @endif
    </div>
</div>
<script>  
function arrived(){
    showLoader("#full-overlay"); 
    var url = "{{route('visa.stage')}}";
    var user_id = $('meta[name="user_token"]').attr('content');
    
    $.ajax({
        url: url,
        type: "post",
        dataType: "json",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {'user_id':user_id,'action': 'arrived'},
        success: function(response){ 
            if(response.type == "success"){
                var Html = '<div class="alert swl-alert-success"><ul><li>'+response.message+'</li></ul></div>'; 
                notifyResponseTimerAlert(Html,"success","Success");
                setTimeout(function(){
                    navigateStages('{{ $active_stage }}',"{{$active_step_key}}");
                }, 3000); 
            } 
            else{
                var Html = '<div class="alert swl-alert-danger"><p>Failed to change status.</p></div>'; 
                notifyResponseTimerAlert(Html,"error","Error");
                return false;
            }
            hideLoader("#full-overlay");
        },
    });
}
</script>