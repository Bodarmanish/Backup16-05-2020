@extends('admin.layouts.app')

@php
$admins = @$admins;
@endphp

@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Administrators</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="active">Admins</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">Admins</h3>
                        <p class="text-muted m-b-30">List of Admins</p>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="col-md-3 pull-right">
                            <a href="{{ route('admin.add.form') }}" class="btn btn-block btn-info">Add New Admin</a>
                        </div>
                    </div>
                </div>
                <div class="white-box well">
                    <div class="row">
                        <form id="admin_filter" method="post" action="{{ route('admin.search') }}">
                            {{ csrf_field() }}
                            <div class="form-group col-md-4 col-xs-12">
                                <label>Name</label>
                                <input type="text" name="admin_name" id="admin_name" placeholder="Admin Name" value="{{request()->get('admin_name')}}" class="form-control">
                            </div>
                            <div class="form-group col-md-4 col-xs-12">
                                <label>Email Address</label>
                                <input type="text" name="email_address" id="email_address" placeholder="Email Address" value="{{request()->get('email_address')}}" class="form-control">
                            </div>
                            @if($agency_type == 'root') 
                                <div class="form-group col-md-4 col-xs-12">
                                    <label>Agency Name</label>
                                    <select name="agency_id" id="agency_id" class="form-control">
                                        <option value="">-- Select Agency Name --</option>
                                        @if (!empty($agencies))
                                            @foreach($agencies as $agency)
                                                <option value="{{$agency->id}}" {{is_selected(request()->get('agency_id'),$agency->id)}}>{{$agency->agency_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            @endif
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info">Search</button>
                                    <a type="button" class="btn btn-info" href="{{ route('admin.list') }}">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @include('admin.includes.status')
                <div class="table-responsive"> 
                    <table id="admin_list" class="table table-bordered m-t-10">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th> 
                                <th>Role</th> 
                                <th>Agency</th> 
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($admins))
                                @foreach($admins as $admin)
                                    @php
                                        $display_name = $agency_name = "";
                                        $roles = collect($admin->roles)->first();
                                        if(!empty($roles)){
                                            $display_name = $roles->display_name;
                                            $role_name = $roles->role_name;
                                        } 
                                        if(!empty($admin->agency)){
                                            $agency_name = $admin->agency->agency_name;
                                        } 
                                        $status = ($admin->status == 1) ? "Active" : "De-Active";
                                        $status_class = ($admin->status == 1) ? "success" : "danger";

                                    @endphp
                                    <tr>
                                        <td>{{ $admin->first_name." ".$admin->last_name }}</td>
                                        <td>{{ $admin->email }}</td>
                                        <td>{{ $display_name }}</td>
                                        <td>{{ $agency_name }}</td>
                                        <td>
                                            <span class="label label-{{ $status_class }}">{{ $status }}</span>
                                        </td>
                                        <td>
                                            @if($role_name!="root")  
                                            <a href="{{ route('admin.edit.form',encrypt($admin->id)) }}" data-toggle="tooltip" data-original-title="Edit" onclick="return showLoader('#full-overlay');"> <i class="fa fa-pencil text-inverse m-r-10"></i> </a>
                                            <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Delete" onclick="return confirmDelete('{{ route('admin.delete',encrypt($admin->id)) }}');"> <i class="fa fa-close text-danger m-r-10"></i> </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="100%">No Records Found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
$(document).ready(function() {
    $('#admin_list').DataTable();
});
</script>
@endsection