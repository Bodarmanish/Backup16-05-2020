@extends('admin.layouts.app')
@php

$mode = "Add";
$action = route('admin.add');

if(!empty($id)){
    $mode = "Edit";
    $action = route('admin.edit',encrypt($id));
    $img_url = get_url("admin-avatar/{$admin->id}/{$admin->profile_photo}");
} 

@endphp
@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Administrators</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('admin.list') }}">Admins</a></li>
                <li class="active">{{ $mode }} Admin</li>
            </ol>
        </div>
    </div>
    <div class="row"> 
        <div class="col-md-12 col-xs-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">{{ $mode }} Admin</h3>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="pull-right">
                            <a href="{{ route('admin.list') }}" class="btn btn-block btn-info"><i class="fa fa-list"></i> Admins</a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        @include('admin.includes.status')
                        <form method="post" action="{{ $action }}" class="form-horizontal form-validator" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
                                <label class="col-md-12">First Name <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="first_name" id="first_name" placeholder="First Name" class="form-control" required value="{{ ($mode == "Edit") ? $admin->first_name : old('first_name') }}">
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('first_name')){{ $errors->first('first_name') }}@endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
                                <label class="col-md-12">Last Name <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="last_name" id="last_name" placeholder="Last Name" class="form-control" required value="{{ ($mode == "Edit") ? $admin->last_name : old('last_name') }}">
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('last_name')){{ $errors->first('last_name') }}@endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                <label class="col-md-12">Email Address <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="email" id="email" placeholder="Email Address" class="form-control" required value="{{ ($mode == "Edit") ? $admin->email : old('email') }}">
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('email')){{ $errors->first('email') }}@endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                <label class="col-md-12">Password @if($mode == "Add")<span class="text-danger">*</span>@endif @if($mode == "Edit")<span class="alert-danger">(Keep password field blank if not want to change current password.)</span>@endif</label>
                                <div class="col-md-12">
                                    <input type="password" name="password" id="password" placeholder="Password" class="form-control" value="" {{ ($mode == "Add")? 'required' : '' }} >
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('password')){{ $errors->first('password') }}@endif
                                    </div>
                                </div>
                            </div>  
                            <div class="form-group {{ $errors->has('timezone') ? 'has-error' : '' }}">
                                <label class="col-md-12">Timezone <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <select name="timezone" id="timezone" class="form-control" required="">
                                        <option value="">-- Select Timezone --</option>
                                            @foreach($timezones as $zone)
                                                <option value="{{ $zone->zone_id }}" @if($zone->zone_id == $admin->timezone || $zone->zone_id == old('timezone')) selected="selected" @endif >{{ $zone->zone_label }}</option>                                                                     
                                            @endforeach
                                    </select> 
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('timezone')){{ $errors->first('timezone') }}@endif
                                    </div>
                                </div>
                            </div>
                            @if($admin->session_role_type == 'agency-admin')
                                <input type="hidden" name="role_id" value="{{$admin->session_role_id}}" />
                                <input type="hidden" name="agency_id" value="{{$admin->session_agency_id}}" />
                            @elseif(!empty($roles))
                            <div class="form-group {{ $errors->has('role_id') ? 'has-error' : '' }}">
                                <label class="col-md-12">Select Role <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <select name="role_id" id="role_id" class="form-control" data-placeholder="Select Role" tabindex="1" required onchange="return loadAgency(this)">
                                        <option value="">Select Role</option>
                                        @foreach($roles as $role)
                                        <option {{ is_selected($admin->role_id,$role->id) }} {{ is_selected(old('role_id'),$role->id) }} value="{{ $role->id }}">{{ $role->display_name }}</option>
                                        @endforeach  
                                    </select>
                                    <input type="hidden" name="role_name" id="role_name" value="{{ $admin->role_name }}" />
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('role_id')){{ $errors->first('role_id') }}@endif
                                    </div>
                                </div>      
                            </div>
                            @endif
                            @if(!empty($agencies) && ($admin->session_role_type != 'agency-admin'))
                            <div class="form-group {{ $errors->has('agency_id') ? 'has-error' : '' }} {{ ( $admin->role_name=="agency-admin" || old('role_name') == "agency-admin")? '' : 'hide' }}" id="agency_dd">
                                <label class="col-md-12">Select Agencies <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <select name="agency_id" id="agency_id" class="form-control" data-placeholder="Select Agency" tabindex="1" required> 
                                        <option value="">Select Agency</option>
                                         @foreach($agencies as $agency)
                                            <option {{ ( $admin->agency_id==$agency->id || old('agency_id') == $agency->id) ? 'selected':'' }} value="{{ $agency->id }}">{{ $agency->agency_name }}</option>
                                        @endforeach 
                                    </select>
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('agency_id')){{ $errors->first('agency_id') }}@endif
                                    </div>
                                </div>      
                            </div>
                            @endif
                            <div class="form-group">
                                <label class="col-md-12">Status <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <div class="radio-list">
                                        <label><input type="radio" name="status" value="1" required {{ is_checked($admin->status,'1') }}> Active </label>
                                        <label><input type="radio" name="status" value="0" required {{ is_checked($admin->status,'0') }}> De-Active </label>
                                        <div class="clearfix"></div>
                                        <div class="help-block with-errors">
                                            @if ($errors->has('status')){{ $errors->first('status') }}@endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($mode == "Add")
                            <div class="form-group">
                                <label class="col-md-12">Is Email Verified? <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <div class="radio-list">
                                        <label><input type="radio" name="email_verified" value="1" required {{ is_checked($admin->email_verified,'1') }} /> Yes </label>
                                        <label><input type="radio" name="email_verified" value="0" required {{ is_checked($admin->email_verified,'0') }}  /> No </label>
                                        <div class="clearfix"></div>
                                        <div class="help-block with-errors">
                                            @if ($errors->has('email_verified')){{ $errors->first('email_verified') }}@endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info">Save Admin</button>
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
@if(!empty($roles_json))
<script> 
var pausecontent = {!! $roles_json !!};

function loadAgency(e){
    var val = $(e).val();
    
    if(pausecontent[val] == "agency-admin"){
        $("#agency_dd").removeClass('hide'); 
        $('#role_name').val("agency-admin");
    }
    else{
        $("#agency_dd").addClass('hide');
        $('#role_name').val("");
    }
}
</script>
@endif
@endsection
