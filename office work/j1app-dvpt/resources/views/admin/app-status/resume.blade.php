@php  
    $upload_file_size = config('common.upload_file_size');
    $allow_file_ext = collect(config('common.allow_doc_ext'))->implode(', ');
    $upload_img_size = config('common.upload_img_size');
    $allow_image_ext = collect(config('common.allow_image_ext'))->implode(', ');
@endphp
<div class="row">
    <div class="col-sm-12">
    <h3>Resume Upload</h3> 
    @if(empty($step_status)) 
        <p>This step is disable until user will reach to step.</p>  
    @else
        @if($is_step_success == 0)
            @if(!empty(@$step_verified_data['type']))
                <div class="text-center response alert alert-{{ @$step_verified_data['type'] }}">{{ @$step_verified_data['message'] }}</div> 
            @endif
            <form name="resume_upload" id="resume_upload" enctype="multipart/form-data" action=""> 
                <p class="text-muted">Resume should be a {{ $allow_file_ext }} and doesn't exceed {{ $upload_file_size }} MB.</p><br>
                <div class="form-group m-b-10 uploadresume">
                    <div class="row">
                        <div class="col-xs-12">
                            <label>Select a file: <span class="text-danger">*</span></label> 
                            <input type="file" name="resume_file" class="di"id="resume_file" onchange="return resumeUpload('{{ $active_step_key }}');"/>
                            <div class="clearfix"></div>
                            <div class="help-block with-errors"></div>  
                        </div>
                    </div> 
                </div>  
                <div class="text-center response"></div>  
            </form>
        @elseif($is_step_success == 1 || $is_step_success == 2) 
            <div class="text-center response alert alert-{{ @$step_verified_data['type'] }}">{{ @$step_verified_data['message'] }}</div>
        @endif
     @endif
    </div>
</div> 
<script>  
function resumeUpload(active_step_key){
    var user_id = $('meta[name="user_token"]').attr('content');
    
    showLoader("#full-overlay"); 
    var fd = new FormData();
    var files = $('#resume_file')[0].files[0];
        fd.append('resume', files);
        fd.append('user_id', user_id);

    $.ajax({
        url:  "{{ route('uploadresume') }}",
        type: 'post',
        data: fd,
        dataType: 'json',
        contentType: false,
        processData: false, 
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response){ 
            $("#resume_upload").trigger("reset");
            if(response.type == "success"){

                var Html = '<div class="alert swl-alert-success"><ul><li>'+response.message+'</li></ul></div>'; 
                notifyResponseTimerAlert(Html,"success","Success");
                setTimeout(function(){
                    navigateStages(1,active_step_key);
                }, 3000); 
            } 
            else{
                var Html = '<div class="alert swl-alert-danger">'+response.message+'</div>'; 
                notifyResponseTimerAlert(Html,"error","Error");
            }
            hideLoader("#full-overlay");
        },
    });
}
</script>