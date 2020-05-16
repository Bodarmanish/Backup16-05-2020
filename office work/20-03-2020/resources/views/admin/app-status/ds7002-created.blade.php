@php  
    $upload_file_size = config('common.upload_file_size');
    $allow_file_ext = collect(config('common.allow_doc_ext'))->implode(', ');
    $step_status = @$step_verified_data['step_status'];
    $document_data = @$step_verified_data['document_data'];
    $document_status = @$document_data->document_status; 
@endphp
<div class="row">
    <div class="col-sm-12">
    <h3>DS7002 Created</h3> 
    @if(empty($step_status)) 
        <p>This step is disable until user will reach to step.</p>  
    @else
        @if(!empty($document_data))
            <p>Document name: <a href="javascript:void(0);">{{ @$document_data->document_filename }}</a></p>
            <p>Document Status: 
                <span class="label label-table label-success">Available to user</span>
           </p>
            <hr/>
            <div class="clearfix"></div>
            <div class="text-center response"></div> 
            <div class="clearfix"></div>
            <a href="{{ @$document_data->document_download_link }}" class="btn btn-info m-r-15" title="View Resume">View</a>
            <button type="button" class="btn btn-danger m-r-15" title="Delete Document" onclick="return deleteDocument({{@$document_data->id}},function(){ setTimeout(function(){ navigateStages('{{ $active_stage }}','{{ $active_step_key }}'); }, 3000);})">Delete</button>
        @else
            <form name="ds72002_upload" id="ds72002_upload" enctype="multipart/form-data" action="{{ route('visa.stage') }}"  method="post">
                <p class="text-muted">DS7002 Training Plan should be a {{ $allow_file_ext }} and doesn't exceed {{ $upload_file_size }} MB.</p><br>
                {{ csrf_field() }}
                <input type="hidden" name="user_id" value="{{encrypt($user_id)}}">
                <input type="hidden" name="action" value="ds7002_create">
                <div class="form-group m-b-10 uploadresume">
                    <div class="row">
                        <div class="col-xs-12">
                            <label>Select a file: <span class="text-danger">*</span></label> 
                            <input type="file" name="ds72002_file" class="di"id="ds72002_file" onchange="return ds7002Upload();"/>
                            <div class="clearfix"></div>
                            <div class="help-block with-errors"></div>  
                        </div>
                    </div> 
                </div>  
                <div class="text-center response"></div>  
            </form>
        @endif  
     @endif
    </div>
</div> 
<script>  
function ds7002Upload(){
    var user_id = $('meta[name="user_token"]').attr('content');
    
    showLoader("#full-overlay"); 
    var fd = new FormData();
    var files = $('#ds72002_file')[0].files[0];
    fd.append('ds72002_file',files);
    fd.append('user_id',user_id);
    fd.append('action','ds7002_create');
    
    var url = "{{ route('visa.stage') }}";
    
    $.ajax({
        url: url,
        type: 'post',
        data: fd,
        dataType: 'json',
        contentType: false,
        processData: false, 
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response){ 
            $("#ds72002_upload").trigger("reset");
            if(response.type == "success"){

                var Html = '<div class="alert swl-alert-success"><ul><li>'+response.message+'</li></ul></div>'; 
                notifyResponseTimerAlert(Html,"success","Success");
                setTimeout(function(){
                    navigateStages('{{ $active_stage }}',"{{$active_step_key}}");
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