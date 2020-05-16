@php
    $documents = @$step_verified_data['documents']; 
    $step_key = @$step_verified_data['step_key']; 
    $document_section_id = @$step_verified_data['document_section_id']; 
    $approved_documents = @$step_verified_data['approved_documents'];
    $req_doc = @$step_verified_data['req_doc'];
    $req_document_count = $req_doc['req_count']; 
@endphp 
<div class="row m-b-20"> 
    <div class="col-sm-6">
        <h3>{{ $step_title }}</h3>
        <div id="{{ $notify_id }}"></div>
        <p>As part of the U.S. Department of State regulations, we need the following documents for completing your visa file... </p>
    </div>
    <div class="col-sm-6 text-right">
        @if(!empty($step_status) && check_route_access('user.document'))
            <a href="{{ route('user.document',encrypt($user_id)) }}" onclick="event.preventDefault(); document.getElementById('document_section').submit();" data-toggle="tooltip" data-original-title="Approve/Reject documents" class="btn btn-info">Approve/Reject Document</a>
            <form id="document_section" action="{{ route('user.document',encrypt($user_id)) }}" method="get" style="display: none;">
                {{ csrf_field() }}
                <input type="hidden" name="section_id" value="{{$document_section_id}}" />
            </form>
        @endif
    </div>
    <div class="col-sm-12">
        @if(empty($step_status))
            <p>This step is disable until user will reach to step.</p>  
        @else 
            <div class="row docs">
            @if(!empty($documents))
                @foreach($documents as $doc)
                    <div class="col-sm-4 col-md-3 col-xs-6 m-b-10 text-center">
                        <div class="upload-title" title="{{ $doc->document_label }}">
                            @php
                            $required_class = "text-warning";
                            $doc_desc = $doc->doc_desc;
                            if($doc->requirement_type == 1)
                            {
                                $required_class = "text-danger";
                                $doc_desc .= "<br> (required)";
                            }
                            @endphp
                            @if(!empty($doc_desc))
                            <a data-html="true" data-toggle="tooltip" title="{{ $doc_desc }}" data-placement="left" data-container="body"><i class="fa fa-exclamation-circle {{ $required_class }}"></i></a> 
                            @endif
                            {{ $doc->document_label }}
                        </div>
                        <div class="upload-wrapper" title="{{ $doc->document_label }}">
                            @if(!empty($doc->document_id))
                                @if(!empty($doc->document_download_link) && $doc->document_status != 2)
                                    <div class="doc-tag">
                                        <a href="{{ $doc->document_download_link }}" target="_blank"><img class="file-download" src="{{ url('assets/images/file-download.png') }}" alt="file-download" /></a>
                                    </div>
                                @else
                                    <div class="doc-tag">
                                        @if(@$is_step_locked == 1)
                                            <img class="file-download" src="{{ url('assets/images/file-upload-lock.png') }}" alt="file-upload-lock" title="File Upload Locked" />
                                        @else
                                            <form id="frm_upload_{{ $doc->document_type_id }}" class="frm_file_upload" method="post" enctype="multipart/form-data">
                                                <input type="hidden" name="document_type" value="{{ $doc->document_type_id }}"/>
                                                <input type="file" name="document_file" onchange="return uploadDocument('{{ $doc->document_type_id }}', function(){ setTimeout(function(){ loadStepContent('{{ $active_step_key }}'); }, 3000); });" />
                                                <img class="file-download" src="{{ url('assets/images/file-upload.png') }}" alt="file-upload" />
                                            </form>
                                        @endif
                                    </div>
                                @endif
                                <div class="doc-tag">
                                    <div class="doc-status">Uploaded On</div>
                                    @if(!empty($doc->document_uploaded))
                                        <div>{{ dateformat($doc->document_uploaded,DISPLAY_DATE) }}</div>
                                    @endif
                                </div>
                                <div class="doc-tag">
                                    <div class="doc-status doc-{{ $doc->document_status_name }}">{{ ucfirst($doc->document_status_name) }}</div>
                                    @if(!empty($doc->document_status_date))
                                        <div>{{ dateformat($doc->document_status_date,DISPLAY_DATE) }}</div>
                                    @endif
                                </div>
                            @else
                                <div class="file-upload-block">
                                    @if(@$is_step_locked == 1)
                                    <img class="file-upload" src="{{ url('assets/images/file-upload-lock.png') }}" alt="file-upload-lock" title="File Upload Locked" />
                                    @else
                                    <form id="frm_upload_{{ $doc->document_type_id }}" class="frm_file_upload" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="document_type" value="{{ $doc->document_type_id }}"/>
                                        <input type="file" name="document_file" onchange="return uploadDocument('{{ $doc->document_type_id }}', function(){ setTimeout(function(){ loadStepContent('{{ $active_step_key }}'); }, 3000); });" />
                                        <img class="file-upload" src="{{ url('assets/images/file-upload.png') }}" alt="file-upload" />
                                    </form>
                                    @endif
                                </div>
                                <div>
                                    <a href="javascript:void(0);" onclick="return showUploadInstruction('{{ $doc->document_requirement_id }}');">Upload Instructions</a>
                                </div>
                            @endif
                        </div>
                        @if(!empty($doc->document_id))
                        <a href="javascript:void(0);" onclick="return viewUploadHistory('{{ $doc->document_type_id }}');">View upload history</a>
                        @else
                        &nbsp;
                        @endif
                    </div>
                @endforeach
            @endif
            </div>
            @if(($req_document_count == $approved_documents) && (@$current_step_key == @$step_key))
                <div class="col-sm-12">
                    <button type="button" class="btn btn-info" onclick="return completeRequiredDocument('{{@$step_key}}','{{@$active_stage}}')">Complete Step</button>
                </div>
            @endif
        @endif
    </div>
</div>
<script> 
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});

function completeRequiredDocument(active_step_key,active_stage){
    var user_id = $('meta[name="user_token"]').attr('content');
    showLoader("#full-overlay");
    $.ajax({
        url: "{{ route('doc.uploaded') }}",
        type: 'post',
        data: { "active_step_key" : active_step_key, "user_id": user_id },
        dataType: 'json',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response){
            if(response.type == "success"){
                stageComplete(active_stage,"You have successfully completed this stage. Now it's the time to find you a {{ __('application_term.employer') }}...");
            }
            else
            {
                navigateStages(active_stage,active_step_key);
            }
            hideLoader("#full-overlay");
        },
    });
}

function deleteDocCallBack(){
    $('.close').click();
    loadStepContent('{{@$active_step_key}}');
}
</script>