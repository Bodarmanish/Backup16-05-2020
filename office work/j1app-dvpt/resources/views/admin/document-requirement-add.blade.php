@extends('admin.layouts.app')
@php

$mode = (!empty($id)) ? "Edit" : "Add";
$action = route('dr.add');
$document_section = config('common.document_section');

if(!empty($agency_type) && $agency_type == 3)
{
    unset($document_section[1]);
}

if(!empty($id)){
    $mode = "Edit";
    $action = route('dr.edit',$id);
}
else{
    $id = "";
}
@endphp
@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Document Manager</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('role.list') }}">Document Manager</a></li>
                <li class="active">Add New Document Requirement</li>
            </ol>
        </div>
    </div>
    <div class="row"> 
        <div class="col-md-12 col-xs-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">{{ $mode }} Document Requirement</h3>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="pull-right">
                            <a href="{{ route('dr.list') }}" class="btn btn-block btn-info"><i class="fa fa-list"></i> Document Requirements</a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        @include('admin.includes.status')
                        <form method="post" action="{{ $action }}" class="form-horizontal form-validator" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="{{ $document->id }}" />
                            @if($agency_id == 0)
                                <div class="form-group">
                                    <label class="col-md-12">Agency Name</label>
                                    <div class="col-md-12">
                                        <select name="agency_id" id="agency_id" class="form-control"  onchange="return documentType(this.value)">
                                            <option value="0">-- Select Agency Name --</option>
                                            @if (!empty($agency))
                                                @foreach($agency as $data)
                                                    <option value="{{$data->id}}" {{ is_selected($document->agency_id,$data->id) }} {{ is_selected(old('agency_id'),$data->id) }}>{{$data->agency_name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" name="agency_id" id="agency_id" value="{{$agency_id}}" />
                            @endif
                            @if($agency_type == 2)
                                <input type="hidden" name="document_section" id="document_section" value="1" />
                            @else
                            <div class="form-group">
                                <label class="col-md-12">Document Section <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <select name="document_section" id="document_section" class="form-control" required>
                                        <option value="">-- Select Document Section --</option>
                                        @if (!empty($document_section))
                                            @foreach($document_section as $key => $value)
                                                 <option value="{{$key}}" {{ is_selected($document->document_section,$key) }} {{ is_selected(old('document_section'),$key) }}>{{$value}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            @endif
                            <div class="form-group">
                                <label class="col-md-12">Document Type <span class="text-danger">*</span></label>
                                <div class="col-md-12 drtype">
                                    <select name="document_type" id="document_type" class="form-control" required>
                                        <option value="">-- Select Document Type --</option>
                                        @if (!empty($document_types))
                                            @foreach($document_types as $data)
                                                 <option value="{{$data->id}}" {{ is_selected($document->document_type,$data->id) }} {{ is_selected(old('document_type'),$data->id) }}>{{$data->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Document Description</label>
                                <div class="col-md-12">
                                    <textarea rows="3" name="document_desc" id="document_desc" placeholder="Document Decription" class="form-control">{!! ($mode == "Edit") ? $document->document_desc : old('document_desc') !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Document Template</label>
                                <div class="col-md-12">
                                    <input type="file" name="document_template" id="document_template" class="form-control"> 
                                    @if($mode == 'Edit' && !empty($document->document_template))
                                        <a href="{{$document->download_template_link}}" class="col-md-12"><i class="fa fa-download"></i> {{$document->document_template}}</a>
                                    @endif
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Requirement Type</label>
                                <div class="col-md-12">
                                    <div class="radio-list">
                                        <label><input type="radio" name="requirement_type" value="1" checked {{ is_checked($document->requirement_type,'1') }}> Required </label>
                                        <label><input type="radio" name="requirement_type" value="2" {{ is_checked($document->requirement_type,'2') }}> Optional </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Visible For</label>
                                <div class="col-md-12">
                                    <div class="radio-list">
                                        <label><input type="radio" name="visibility" value="1" {{ is_checked($document->visibility,'1') }}> Admin </label>
                                        <label><input type="radio" name="visibility" value="2" {{ is_checked($document->visibility,'2') }}> User </label>
                                        <label><input type="radio" name="visibility" value="3" {{ is_checked($document->visibility,'3') }} {{ is_checked($document->visibility,'') }}> Both </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info">Save Document Requirement</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function documentType(agency_id)
    {
        showLoader("#full-overlay");
        $.ajax({
            url: "{{route('dr.ajax')}}",
            type: "post",
            dataType: "json",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {'agency_id':agency_id, 'action':'document_type','id':'{{$id}}'},
            success: function(response){  
                $(".drtype").html(response.data);
                hideLoader("#full-overlay");
            }
        });
    }
</script>
@endsection
