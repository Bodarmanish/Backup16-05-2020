@if(!empty($user_document))
<div role="tabpanel" class="tab-pane active">
    <div class="white-box">
        @foreach($user_document as $doc)
            <div class="row border-bottom">
                <div class="col-md-5">
                    <div class="doc_raw pull-left">
                        <span>{{ $doc->document_label }}</span>
                        @if(!empty($doc->document_id))
                            <span onclick="viewDocDetail({{ $doc->document_type_id }});" id="doc_detail_{{ $doc->document_type_id }}"><i class="mdi mdi-arrow-down-drop-circle-outline"></i></span>
                        @endif
                    </div> 
                </div>
                <div class="col-md-2"> 
                    <div class="doc_raw">
                        @if(!empty($doc->document_id))
                            @if($doc->document_count_by_type >= 1 && $doc->document_status != 2)
                                @if($doc->document_status==2)
                                    <span class="label label-danger">{{ $doc->document_status_name }}</span>
                                @elseif($doc->document_status==1)
                                    <span class="label label-success">{{ $doc->document_status_name }}</span>
                                @else
                                    <span class="label label-warning">{{ $doc->document_status_name }}</span>
                                @endif
                            @endif
                        @endif 
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="doc_raw">
                        @if(!empty($doc->download_template_link))
                        <a href="{{ $doc->download_template_link }}" title="Download Document Template">
                            <span class="label label-info">Template <i class="fa fa-download"></i></span>
                        </a>
                        @endif
                    </div>
                </div>     
                <div class="col-md-4 text-right">
                    <div class="doc_raw">
                        @if(!empty($doc->document_id))
                            @if($doc->document_count_by_type>=1)
                                <a href="javascript:void(0);" class="btn btn-info" title="View Document Upload History" onclick="return viewUploadHistory('{{ $doc->document_type_id }}');"><i class="mdi mdi-history"></i></a>
                            @endif
                            @if($doc->document_count_by_type >= 1 && $doc->document_status != 2)
                                @if(!empty($doc->document_download_link))
                                    <a href="{{ $doc->document_download_link }}" class="btn btn-success" title="View Document">View</a>
                                @endif 
                                @if(check_route_access('document.action'))
                                <a class="btn btn-danger" onclick="return deleteDocument({{$doc->document_id}}, function(){ loadDocumentList('{{$section_id}}'); })" title="Delete User Document">Delete</a> 
                                @endif
                            @endif 
                            @if(empty($doc->document_status) && $doc->document_count_by_type >= 1)
                                @if(check_route_access('document.action'))
                                    <button type="button" class="btn btn-success btn-circle" title="Approve Document" onclick="return documentAction({{$doc->document_id}}, 'approve', function(){ loadDocumentList('{{$section_id}}'); })"><i class="fa fa-thumbs-up"></i> </button> | 
                                    <button type="button" class="btn btn-danger btn-circle" title="Reject Document" onclick="return rejectUserDocument('{{ route("document.reject.reason.form") }}',{{$doc->document_id}}, '{{$section_id}}')"><i class="fa fa-thumbs-down"></i> </button>
                                @endif
                            @elseif($doc->document_status == 1 && $doc->document_count_by_type >= 1)
                                @if(check_route_access('document.action'))
                                    <button type="button" class="btn btn-danger btn-circle" title="Reject Document" onclick="return rejectUserDocument('{{ route("document.reject.reason.form") }}',{{$doc->document_id}}, '{{$section_id}}')"><i class="fa fa-thumbs-down"></i> </button>
                                @endif
                            @elseif($doc->document_status == 2)
                                @if(check_route_access('uploaddocument'))
                                    <form id="frm_upload_{{ $doc->document_type_id }}" class="frm_file_upload" method="post" enctype="multipart/form-data" style="display: inline-block;">
                                        <input type="hidden" name="document_type" value="{{ $doc->document_type_id }}"/>
                                        <div class="fileupload btn btn-info">Upload
                                            <input type="file" class="upload" name="document_file" onchange="return uploadDocument('{{ $doc->document_type_id }}', function(){ loadDocumentList('{{$section_id}}'); });">
                                        </div>
                                    </form>
                                @endif
                            @endif
                            
                        @else
                            @if(check_route_access('uploaddocument'))
                            <form id="frm_upload_{{ $doc->document_type_id }}" class="frm_file_upload" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="document_type" value="{{ $doc->document_type_id }}"/>
                                <div class="fileupload btn btn-info">Upload
                                    <input type="file" class="upload" name="document_file" onchange="return uploadDocument('{{ $doc->document_type_id }}', function(){ loadDocumentList('{{$section_id}}'); });">
                                </div>
                            </form>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            @if(!empty($doc->document_id)) 
                <div class="row border-bottom document_desc hide" id="document_desc_{{ $doc->document_type_id }}">
                    <div class="col-md-12">
                        <div class="doc_raw pull-left">
                            <p><strong>Status:</strong> {{ $doc->document_status_name }}</p>
                            <p><strong>Status Date:</strong> {{ dateformat($doc->document_status_date, DISPLAY_DATETIME) }}</p>
                            <p><strong>Uploaded Date:</strong> {{ $doc->document_uploaded }}</p>
                            <p><strong>Approved/Rejected:</strong> {{ (!empty($doc->action_by))?$doc->action_by:'' }}</p>
                            <p><strong>Uploaded By:</strong> {{ (!empty($doc->uploaded_by))?$doc->uploaded_by:'' }}</p>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
<script>
function viewDocDetail(dtid){
    if($('#doc_detail_'+ dtid +' i').hasClass('mdi-arrow-down-drop-circle-outline')){
        $('#doc_detail_'+ dtid +' i').removeClass("mdi-arrow-down-drop-circle-outline");
        $('#doc_detail_'+ dtid +' i').addClass("mdi-arrow-up-drop-circle-outline");
        $('#document_desc_'+dtid).removeClass('hide');
    }else{
        $('#doc_detail_'+ dtid +' i').addClass("mdi-arrow-down-drop-circle-outline");
        $('#doc_detail_'+ dtid +' i').removeClass("mdi-arrow-up-drop-circle-outline");
        $('#document_desc_'+dtid).addClass('hide');
    }
}
function deleteDocCallBack(){
    $('.close').click();
    loadDocumentList('{{$section_id}}');
}
</script>
@endif 