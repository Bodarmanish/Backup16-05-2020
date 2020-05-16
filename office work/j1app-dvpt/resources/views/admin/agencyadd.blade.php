@extends('admin.layouts.app')
@php

$mode = (!empty($id)) ? "Edit" : "Add";
$action = route('agency.add');
$agency_type = config('common.agency_type');
if(!empty($id)){
    $mode = "Edit";
    $action = route('agency.edit',$id);
}
@endphp
@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Agency</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('role.list') }}">Agency Manager</a></li>
                <li class="active">Add New Agency</li>
            </ol>
        </div>
    </div>
    <div class="row"> 
        <div class="col-md-12 col-xs-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">{{ $mode }} Agency</h3>
                        <!--<p class="text-muted m-b-30">Add New Admin</p>-->
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="pull-right">
                            <a href="{{ route('agency.list') }}" class="btn btn-block btn-info"><i class="fa fa-list"></i> Agencies</a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        @include('admin.includes.status')
                        <form method="post" action="{{ $action }}" class="form-horizontal form-validator">
                            {{ csrf_field() }}
                            <input type="hidden" name="{{ $agency->id }}" />
                            <div class="form-group">
                                <label class="col-md-12">Agency Name <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="agency_name" id="agency_name" placeholder="Agency Name" class="form-control  " required value="{{ ($mode == "Edit") ? $agency->agency_name : old('agency_name') }}">
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Agency Type <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <select name="agency_type" id="agency_type" class="form-control" required>
                                        <option value="">-- Select Agency Type --</option>
                                        @if (!empty($agency))
                                           @foreach($agency_type as $key => $value)
                                                <option value="{{$key}}" {{ is_selected($agency->agency_type,$key) }} {{ is_selected(old('agency_type'),$key) }}>{{$value}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Agency Address</label>
                                <div class="col-md-12">
                                    <textarea rows="3" name="agency_address" id="agency_address" placeholder="Agency Address" class="form-control">{!! ($mode == "Edit") ? $agency->agency_address : old('agency_address') !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Description</label>
                                <div class="col-md-12">
                                    <textarea rows="3" name="description" id="description" placeholder="Agency Decription" class="form-control">{!! ($mode == "Edit") ? $agency->description : old('description') !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Status</label>
                                <div class="col-md-12">
                                    <div class="radio-list">
                                        <label><input type="radio" name="status" value="1" checked {{ is_checked($agency->status,'1') }}> Active </label>
                                        <label><input type="radio" name="status" value="0" {{ is_checked($agency->status,'0') }}> De-Active </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info">Save Agency</button>
                                    <button type="reset" class="btn btn-danger">Reset</button>
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
