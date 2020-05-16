@extends('admin.layouts.app')

@php
$roles = @$roles;
@endphp

@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Access Control</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="active">Roles</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">Roles</h3>
                        <p class="text-muted m-b-30">List of Admin Roles</p>
                    </div>
                    <div class="col-md-6 col-xs-12"></div>
                </div>
                @include('admin.includes.status')
                <div class="table-responsive">
                    <table id="role_list" class="table table-bordered m-t-10">
                        <thead>
                            <tr>
                                <th>Role Name</th>
                                <th>Role Key</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($roles))
                                @foreach($roles as $role)
                                    @php
                                        $status = ($role->status == 1) ? "Active" : "De-Active";
                                        $status_class = ($role->status == 1) ? "success" : "danger";
                                    @endphp
                                    <tr>
                                        <td>{{ $role->display_name }}</td>
                                        <td>{{ $role->role_name }}</td>
                                        <td>{{ $role->description }}</td>
                                        <td>
                                            <span class="label label-{{ $status_class }}">{{ $status }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('role.permissions',$role->role_name) }}" data-toggle="tooltip" data-original-title="Permissions"> <i class="fa fa-list-ul"></i> </a>
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
<script type="text/javascript">
    $(document).ready(function() {
        $('#role_list').DataTable();
    });
    
    function deleteRole(route){
        showLoader("#full-overlay");
        confirmAlert("On confirm role will be deleted.","warning","Are you sure?","Confirm",function(r,i){
            if(i){
                window.location.href = r;
            }
            else{
                hideLoader("#full-overlay");
            }
        },route);
    }
</script>
@endsection