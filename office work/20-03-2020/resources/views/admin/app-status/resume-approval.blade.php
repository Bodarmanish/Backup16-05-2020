@php
    $document_data = @$step_verified_data['document_data'];
    $document_status = @$document_data->document_status; 
@endphp
<div class="row">
    <div class="col-sm-12">
        <h3>J1 Resume Approval</h3>
        @if(empty($step_status)) 
            <p>This step is disable until user will reach to step.</p>  
        @else
            @if(!empty($document_data))
                <p>User has uploaded resume. Please review resume details</p>
                <p>Resume file name: <a href="">{{ @$document_data->document_filename }}</a></p>
                <p>Resume Status: 
                @if($document_status==1) 
                    <span class="label label-table label-success">Approved</span>
                @elseif($document_status==2)
                    <span class="label label-table label-danger">Rejected</span>
                @else 
                    <span class="label label-table label-info">Pending</span>
                @endif </p>
                <hr/>
                <div class="clearfix"></div>
                <div class="text-center response"></div> 
                <div class="clearfix"></div>
                @if(!empty(@$document_data->document_download_link))
                    <a href="{{ @$document_data->document_download_link }}" class="btn btn-info m-r-15" title="View Resume">View</a>
                @endif
                <button type="button" class="btn btn-danger m-r-15" title="Delete Resume" onclick="return deleteDocument({{@$document_data->id}},'{{ @$active_step_key }}', function(){ setTimeout(function(){ navigateStages(1,'{{ $active_step_key }}'); }, 3000);})">Delete</button>
                @if($document_status==1)
                    <button type="button" class="btn btn-danger m-r-15" title="Reject Resume" onclick="return rejectDocumentReason('{{ route("document.reject.reason.form") }}',{{@$document_data->id}},'{{ @$active_step_key }}', '{{ $active_stage }}')">Reject</button> 
                @elseif($document_status==2)
                    <button type="button" class="btn btn-success m-r-15" title="Approve Resume" onclick="return documentAction({{@$document_data->id}}, 'approve', function(){ setTimeout(function(){ navigateStages(1,'{{ $active_step_key }}'); }, 3000);})">Approve</button>
                @else
                    <button type="button" class="btn btn-success m-r-15" title="Approve Resume" onclick="return documentAction({{@$document_data->id}}, 'approve', function(){ setTimeout(function(){ navigateStages(1,'{{ $active_step_key }}'); }, 3000);})">Approve</button>
                    <button type="button" class="btn btn-danger m-r-15" title="Reject Resume" onclick="return rejectDocumentReason('{{ route("document.reject.reason.form") }}',{{@$document_data->id}},'{{ @$active_step_key }}', '{{ $active_stage }}')">Reject</button>
                @endif
            @else
                <p>Still resume not uploaded. Please upload resume again.</p>
            @endif  
        @endif
    </div>
</div>