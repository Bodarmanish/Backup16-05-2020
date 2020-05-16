@extends('admin.layouts.app')
@php 
    $document_section_list = @$section_list; 
@endphp

@section('content')
@if(!empty($document_section_list))
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Document Manager</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('role.list') }}">User Document Manager</a></li>
                <li class="active">User Documents</li>
            </ol>
        </div>
    </div>
    <div class="row"> 
        <div class="col-md-12 col-xs-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">User Documents</h3>
                        <p class="text-muted m-b-30">User Document List</p>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="row">
                            <div class="col-md-3 pull-right">
                                <a href="{{ URL::previous() }}" class="btn btn-block btn-info"><i class="fa fa-arrow-left"></i> Back</a>
                            </div> 
                            <div class="col-md-3 pull-right">
                               <a href="{{ route('user.list') }}" class="btn btn-block btn-info"><i class="fa fa-list"></i> Users</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12"> 
                        <div class="user_document">
                            <nav>
                                <ul class="nav nav-pills m-b-30" role="tablist">
                                    @php ($count = 1)
                                    @foreach($document_section_list as $sec_key => $section)
                                        <li role="presentation" class="{{($default_section_id == $sec_key)?'active':''}} cursor-pointer" onclick="return loadDocumentList({{$sec_key}});"><a role="tab" data-toggle="tab">{{$section}}</a></li>
                                    @php ($count++)
                                    @endforeach
                                </ul>
                            </nav>
                            <div class="content-wrap tab-content user_doc_list">
                                {!! $document_list !!}
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
<script>

var section_id = "{{ $default_section_id }}";
    
function loadDocumentList(section_id){
    user_id = $('meta[name="user_token"]').attr('content');
    showLoader("#full-overlay");
    $.ajax({
        url: "{{ route('user.document.list') }}",
        type: 'post',
        data: { 
                section_id: section_id,
                user_id: user_id,
            },
        dataType: 'json',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response){
            if(response.type == "success")
            {
                var data = response.data;
                $(".user_doc_list").html(data);
            }
            hideLoader("#full-overlay");
        },
    });
}

function rejectUserDocument(url,doc_id,section_id){
    show_popup();
    get_common_ajax(url,{
        action: "reject_document_reason_form", 
        doc_id: doc_id,
        section_id: section_id
    });
}
</script>
@endif
@endsection
