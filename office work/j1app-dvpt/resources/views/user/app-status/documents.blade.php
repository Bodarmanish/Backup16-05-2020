@php
    $documents = @$step_verified_data['documents'];
    $step_status = @$step_verified_data['step_status'];
@endphp 
<div class="row m-b-20">
    <div class="col-sm-12">
        <h3>{{ $step_title }}</h3>
        <div id="{{ $notify_id }}"></div>
        <p>As part of the U.S. Department of State regulations, we need the following documents for completing your visa file... </p>
    </div>
    <div class="col-sm-12">
        <div class="row docs">
            @if(!empty($documents))
                @foreach($documents as $doc)
                    <div class="col-sm-4 col-md-4 col-xs-6 m-b-10 text-center">
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
                                        @if($user->is_timeline_locked == 1)
                                            <img class="file-download" src="{{ url('assets/images/file-upload-lock.png') }}" alt="file-upload-lock" title="File Upload Locked" />
                                        @else
                                            <form id="frm_upload_{{ $doc->document_type_id }}" class="frm_file_upload" method="post" enctype="multipart/form-data">
                                                <input type="hidden" name="document_type" value="{{ $doc->document_type_id }}"/>
                                                <input type="file" name="document_file" onchange="return uploadDocument('{{ $doc->document_type_id }}','1','{{ $active_step_key }}');" />
                                                <img class="file-download" src="{{ url('assets/images/file-upload.png') }}" alt="file-upload" />
                                            </form>
                                        @endif
                                    </div>
                                @endif
                                <div class="doc-tag">
                                    <div class="doc-status">Uploaded On</div>
                                    @if(!empty($doc->document_uploaded))
                                        <div>{{ dateformat($doc->document_uploaded,DISPLAY_DATETIME) }}</div>
                                    @endif
                                </div>
                                <div class="doc-tag">
                                    <div class="doc-status doc-{{ strtolower($doc->document_status_name) }}">{{ ucfirst($doc->document_status_name) }}</div>
                                    @if(!empty($doc->document_status_date))
                                        <div>{{ dateformat($doc->document_status_date,DISPLAY_DATETIME) }}</div>
                                    @endif
                                </div>
                            @else
                                <div class="file-upload-block">
                                    @if($user->is_timeline_locked == 1)
                                    <img class="file-upload" src="{{ url('assets/images/file-upload-lock.png') }}" alt="file-upload-lock" title="File Upload Locked" />
                                    @else
                                    <form id="frm_upload_{{ $doc->document_type_id }}" class="frm_file_upload" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="document_type" value="{{ $doc->document_type_id }}"/>
                                        <input type="file" name="document_file" onchange="return uploadDocument('{{ $doc->document_type_id }}','1','{{ $active_step_key }}');" />
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
    </div>
    <div class="col-sm-12">
        @if(!empty($next_step_key) && $step_status == 2)
            <button type="button" class="btn btn-info" onclick="navigateStages('{{ $active_stage }}','{{ $next_step_key }}')">Next Step</button>
        @endif
    </div>
</div>
<script> 
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});
</script>