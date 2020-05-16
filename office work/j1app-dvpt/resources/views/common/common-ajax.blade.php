@if(!empty($action))
    @if($action == "doument_instruction")
        @php
            $download_link = @$download_template_link;
            $doc_desc = @$doc_desc;
            $allowed_extensions = @$allowed_extensions;
            $upload_file_size = @$upload_file_size;
        @endphp
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Upload Instructions</h4>
            </div>
            <div class="modal-body p-20"> 
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <ul class="list-style-num">
                            @if(!empty($doc_desc))
                            <li>{{ $doc_desc }}</li>
                            @endif
                            @if(!empty($download_link))
                            <li><strong><a href="{{ $download_link }}">Click Here</a></strong> to download document template.</li>
                            <li>Print and sign documents</li>
                            <li>Save scanned documents on your storing device ("memory stick" or "computer hard drive")</li>
                            @endif
                            <li>To upload file click on this <img src="{{ url('assets/images/file-upload.png') }}" style="width: 40px;"/> icon to locate file on your hard drive</li>
                            <li class="text-danger"><strong>Note: </strong>Max File size: {{ config('common.upload_file_size') }}MB, Supported file format: please only upload files with following extensions {{ $allowed_extensions }}.</li>
                        </ul>
                    </div>
                </div>
            </div> 
        </div>
    @elseif($action == "view_document_history")
        @php
            $document_history = @$document_history;
            $doc_type_data = @$doc_type_data; 
        @endphp
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">View Document History</h4>
            </div> 
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12"> 
                        <h3 class="box-title">{{ @$doc_type_data->name }}</h3>
                        <div class="table-responsive">
                            <table class="table doc-history-table">
                                <thead>
                                    <tr>
                                        <th>Uploaded On</th>
                                        <th>Document Status</th>
                                        <th>Reject Reason</th>
                                        <th>Download</th>
                                        @if(config('common.app_interface') == 'admin')
                                        <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($document_history))
                                        @foreach($document_history as $document)
                                        <tr>
                                            <td>{{ dateformat($document->document_uploaded,'d M, Y') }}</td>
                                            <td>
                                                @if($document->document_status == 1)
                                                    <span class="label label-success">{{ $document->document_status_name }}</span>
                                                @elseif($document->document_status == 2)
                                                    <span class="label label-danger">{{ $document->document_status_name }}</span>
                                                @else
                                                    <span class="label label-info">{{ $document->document_status_name }}</span> 
                                                @endif 
                                                @if(!empty($document->document_status_date) && (!empty($document->document_status)))
                                                    <br/><br/><span class="text-muted">{{ dateformat($document->document_status_date,'d M, Y') }}</span>
                                                @endif
                                            </td>
                                            <td>{!! $document->document_reject_reason !!}</td>
                                            <td>
                                                @if(!empty($document->document_download_link))
                                                <a href="{{ $document->document_download_link }}">
                                                    <img class="file-download" src="{{ url('assets/images/file-download.png') }}" />
                                                </a>
                                                @endif
                                            </td>
                                            @if(config('common.app_interface') == 'admin')
                                            <td> 
                                                <a class="btn btn-danger" onclick="return deleteDocument({{$document->document_id}}, deleteDocCallBack)" title="Delete User Document">Delete</a>
                                            </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr class="text-center">
                                            <td colspan="4">No Records Found!</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div> 
                </div> 
            </div>
        </div>
    @endif
@else
    <div class="modal-content">
        <div class="modal-header bg-info">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title">Failed to load data</h4>
        </div> 
        <div class="modal-body">
            <div class="row">
                <div class="col-xs-12">
                    Failed to load data
                </div>
            </div> 
        </div>
    </div> 
@endif