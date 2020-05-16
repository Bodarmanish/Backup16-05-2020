@php  
    $step_status = @$step_verified_data['step_status'];
    $step_success = @$step_verified_data['is_step_success'];
@endphp
<div class="row">
    <div class="col-sm-12">
    <h3>DS7002 Pending</h3> 
    @if(empty($step_status)) 
        <p>This step is disable until user will reach to step.</p>  
    @else
        <div class="clearfix"></div>
        @if($step_success == 0)
            <a onclick="ds7002_pending()" href="javascript:void(0)" class="btn btn-info m-r-15" title="DS7002 Pending" >DS7002 Pending</a>
        @else
            <div class="text-center response alert alert-warning">User status already changed to <strong>DS7002 Pending</strong>.</div>
        @endif
    @endif
    </div>
</div> 
<script>  
function ds7002_pending(){
    showLoader("#full-overlay"); 
    var url = "{{route('visa.stage')}}";
    var user_id = $('meta[name="user_token"]').attr('content');
    
    $.ajax({
        url: url,
        type: "post",
        dataType: "json",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {'user_id':user_id,'action': 'ds7002_pending'},
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