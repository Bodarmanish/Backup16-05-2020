@extends('admin.layouts.app')
@php

$mode = "Add";
$action = route('role.add');

if(!empty($role_name)){
    $mode = "Edit";
    $action = route('role.edit',$role_name);
}
@endphp
@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Access Control</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('role.list') }}">Roles</a></li>
                <li class="active">Add New Role</li>
            </ol>
        </div>
    </div>
    <div class="row"> 
        <div class="col-md-12 col-xs-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">{{ $mode }} Role</h3>
                        <!--<p class="text-muted m-b-30">Add New Admin</p>-->
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="pull-right">
                            <a href="{{ route('role.list') }}" class="btn btn-block btn-info"><i class="fa fa-list"></i> Roles</a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        @include('admin.includes.status')
                        <form method="post" action="{{ $action }}" class="form-horizontal form-validator">
                            {{ csrf_field() }}
                            <input type="hidden" name="{{ $role->role_name }}" />
                            <div class="form-group">
                                <label class="col-md-12">Role Name</label>
                                <div class="col-md-12">
                                    <input type="text" name="role_name" id="role_name" placeholder="Role Name" class="form-control " required value="{{ ($mode == "Edit") ? $role->display_name : old('role_name') }}">
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('role_name')){{ $errors->first('role_name') }}@endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Description</label>
                                <div class="col-md-12">
                                    <textarea rows="5" name="description" id="description" class="form-control ">{!! ($mode == "Edit") ? $role->description : old('description') !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Status</label>
                                <div class="col-md-12">
                                    <div class="radio-list">
                                        <label><input type="radio" name="status" value="1" checked {{ is_checked($role->status,'1') }}> Active </label>
                                        <label><input type="radio" name="status" value="0" {{ is_checked($role->status,'0') }}> De-Active </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info">Save Role</button>
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
