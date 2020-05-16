@extends('admin.layouts.app')
@php

$mode = "Add";
$action = route('role.pg.add');

if(!empty($pg_id)){
    $mode = "Edit";
    $action = route('role.pg.edit',$pg_id);
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
                <li><a href="{{ route('role.pg.list') }}">Permission Groups</a></li>
                <li class="active">Add New Permission Group</li>
            </ol>
        </div>
    </div>
    <div class="row"> 
        <div class="col-md-12 col-xs-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">{{ $mode }} Permission Group</h3>
                        <!--<p class="text-muted m-b-30">Add New Admin</p>-->
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="pull-right">
                            <a href="{{ route('role.pg.list') }}" class="btn btn-block btn-info"><i class="fa fa-list"></i> Permission Groups</a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        @include('admin.includes.status')
                        <form method="post" action="{{ $action }}" class="form-horizontal form-validator">
                            {{ csrf_field() }}
                            <input type="hidden" name="{{ $pg->id }}" />
                            <div class="form-group">
                                <label class="col-md-12">Permission Group Name</label>
                                <div class="col-md-12">
                                    <input type="text" name="display_name" id="display_name" placeholder="Permission Group Name" class="form-control " required value="{{ ($mode == "Edit") ? $pg->display_name : old('display_name') }}">
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('display_name')){{ $errors->first('display_name') }}@endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Description</label>
                                <div class="col-md-12">
                                    <textarea rows="3" name="description" id="description" class="form-control ">{!! ($mode == "Edit") ? $pg->description : old('description') !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Is Menu Section</label>
                                <div class="col-md-12">
                                    <div class="radio-list">
                                        <label><input type="radio" name="is_menu_section" value="1" checked {{ is_checked($pg->is_menu_section,'1') }}> Yes </label>
                                        <label><input type="radio" name="is_menu_section" value="0" {{ is_checked($pg->is_menu_section,'0') }}> No </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Menu Description</label>
                                <div class="col-md-12">
                                    <textarea rows="3" name="menu_description" id="menu_description" class="form-control ">{!! ($mode == "Edit") ? $pg->menu_description : old('menu_description') !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Menu Icon Class <small>(Example: mdi mdi-settings fa-fw)</small></label>
                                <div class="col-md-4">
                                    <input type="text" name="icon_class" class="form-control" value="{{ ($mode == "Edit") ? $pg->icon_class : old('icon_class') }}" />
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('icon_class')){{ $errors->first('icon_class') }}@endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info">Save Permission Group</button>
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
