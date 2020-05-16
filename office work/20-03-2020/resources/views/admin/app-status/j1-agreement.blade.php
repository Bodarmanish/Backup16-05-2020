@php 
    $upload_file_size = config('common.upload_file_size');
    $allow_file_ext = collect(config('common.allow_doc_ext'))->implode(', ');
    $upload_img_size = config('common.upload_img_size');
    $allow_image_ext = collect(config('common.allow_image_ext'))->implode(', ');
    $document_data = @$step_verified_data['document_data'];
    $document_status = @$document_data->document_status; 
@endphp
<div class="row">
    <div class="col-sm-12">
        <h3>J1 Agreement</h3>
        <p>User will need to sign the J1 Agreement in order to continue our process. </p>
        @if(empty($step_status)) 
            <p>This step is disable until user will reach to step.</p>  
        @else
            @if(!empty(@$step_verified_data['type']))
                <div class="text-center response alert alert-{{ $step_verified_data['type'] }}">{{ $step_verified_data['message'] }}</div> 
            @endif
            @if(empty($is_step_success))
                <form name="agreement_upload" id="agreement_upload" enctype="multipart/form-data" action="">
                    <p class="text-muted">J1 Agreement should be a {{ $allow_file_ext }} and doesn't exceed {{ $upload_file_size }} MB.</p><br>
                    <div class="form-group m-b-10">
                        <div class="row">
                            <div class="col-xs-12">
                                <label>Select a file: <span class="text-danger">*</span></label> 
                                <input type="file" name="agreement_file" class="di" id="agreement_file" onchange="return agreementUpload('{{ $active_step_key }}');"/>
                                <div class="clearfix"></div>
                                <div class="help-block with-errors"></div>  
                            </div>
                        </div> 
                    </div>  
                    <div class="text-center response"></div>  
                </form>
            @elseif( !empty($is_step_success) && !empty($document_data) ) 
            <div class="clearfix"></div>
                @if(!empty(@$document_data->document_download_link))
                    <a href="{{ @$document_data->document_download_link }}" class="btn btn-info m-r-15" title="View J1 Agreement">View</a>
                @endif 
                    <button type="button" class="btn btn-danger m-r-15" title="Delete J1 Agreement" onclick="return deleteDocument({{$document_data->id}}, function(){ setTimeout(function(){ navigateStages(1,'{{ $active_step_key }}'); }, 3000);})">Delete</button>
                @if($document_status==1)
                    <button type="button" class="btn btn-danger m-r-15" title="Reject J1 Agreement" onclick="return rejectDocumentReason('{{ route("document.reject.reason.form") }}',{{$document_data->id}},'{{ $active_step_key }}', '{{ $active_stage }}')">Reject</button> 
                @elseif($document_status==2)
                    <button type="button" class="btn btn-success m-r-15" title="Approve J1 Agreement" onclick="return documentAction({{$document_data->id}}, 'approve', function(){ setTimeout(function(){ navigateStages(1,'{{ $active_step_key }}'); }, 3000);})">Approve</button>
                @else
                    <button type="button" class="btn btn-success m-r-15" title="Approve J1 Agreement" onclick="return documentAction({{$document_data->id}}, 'approve', function(){ setTimeout(function(){ navigateStages(1,'{{ $active_step_key }}'); }, 3000);})">Approve</button>
                    <button type="button" class="btn btn-danger m-r-15" title="Reject J1 Agreement" onclick="return rejectDocumentReason('{{ route("document.reject.reason.form") }}',{{$document_data->id}},'{{ $active_step_key }}', '{{ $active_stage }}')">Reject</button>
                @endif 
            @endif
        @endif
    </div> 
</div>
<script>  
function agreementUpload(active_step_key){
    var user_id = $('meta[name="user_token"]').attr('content');
    
    showLoader("#full-overlay"); 
    var fd = new FormData();
    var files = $('#agreement_file')[0].files[0];
    fd.append('agreement',files);
    fd.append('user_id',user_id);
    
    $.ajax({
        url:  "{{ route('uploadagreement') }}",
        type: 'post',
        data: fd,
        dataType: 'json',
        contentType: false,
        processData: false, 
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response){ 
            $("#agreement_upload").trigger("reset");
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