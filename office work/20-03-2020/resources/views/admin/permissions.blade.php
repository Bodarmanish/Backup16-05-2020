@extends('admin.layouts.app')

@php
$permission_groups = @$permission_groups;
$permissions = @$permissions;
$roles = @$roles;
$pg = "";
@endphp

@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Access Control</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="active">Permissions</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <div class="row m-b-30">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">Permissions</h3>
                        <span class="text-muted">List of Permission</span>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="pull-right">
                            <a href="{{ route('role.list') }}" class="btn btn-block btn-info"><i class="fa fa-list"></i> Roles</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <form id="permission_filter" method="get" action="{{ route('role.permissions', $role_name) }}">
                        {{ csrf_field() }}
                        <div class="form-group col-md-4 col-xs-12">
                            <label>Permission Group</label>
                            <select name="permission_group" id="permission_group" class="form-control" onchange="return searchByGroup('permission_filter');">
                                <option value="">-- Select Group --</option>
                                @foreach($permission_groups as $item)
                                    <option value="{{ $item->id }}" {{ is_selected(request()->get('permission_group'),$item->id) }}>{{ $item->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                @include('admin.includes.status')
                <div class="table-responsive">
                    <table class="table table-bordered m-t-10">
                        <thead>
                            <tr>
                                <th></th>
                                @foreach($roles as $role)
                                    <th>{{ $role->display_name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($permissions))
                                @foreach($permissions as $permission)
                                    @php
                                        $role_ids = [];
                                        if(!empty($permission->roles))
                                        {
                                            $role_ids = $permission->roles->role_ids;
                                        }
                                    @endphp
                                    @if($pg != $permission->group_label)
                                        <tr>
                                            <td class="table-row-head" colspan="100%">{{ $permission->group_label }}</td>
                                        </tr>
                                    @endif
                                    @php
                                        $pg = $permission->group_label;
                                    @endphp
                                    <tr>
                                        <td><label>{{ $permission->permission_label }} <i class="fa fa-fw fa-question-circle" data-toggle="tooltip" data-placement="right" title="" data-original-title="{{ $permission->description }}"></i> </label></td>
                                        @foreach($roles as $role)
                                            @php
                                                $is_checked = "";
                                                $is_disabled = "";
                                                if(in_array($role->id,$role_ids)){
                                                    $is_checked = "checked";
                                                }
                                                if($role->role_name == 'root'){
                                                    $is_checked = "checked";
                                                    $is_disabled = "disabled";
                                                }
                                            @endphp
                                            <td>
                                                <label style="font-weight: normal;"><input type="checkbox" value="{{ $permission->id }}" {{ $is_checked }} {{ $is_disabled }} onchange="return rolePermission(this.checked,this.value,'{{ $role->id }}');" /> {{ $permission->permission_label }} </label>
                                            </td>
                                        @endforeach
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
    function rolePermission(is_checked,p_id,r_id){
        showLoader("#full-overlay");
        var is_checked = (is_checked == true) ? "1" : "0";
        var url = "{{ route('role.update.role.permissions') }}";
        $.ajax({
            url: url,
            type: 'post',
            data: { is_checked:is_checked, p_id: p_id, r_id:r_id },
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            /*statusCode: {
                404: function(jqXHR,textStatus,errorThrown) {
                    alert("Page not found");
                },
                500: function(jqXHR,textStatus,errorThrown) {
                    alert("Internal server error");
                },
            },*/
            success: function(response){
                hideLoader("#full-overlay");
            },
        });
    }
    
    function searchByGroup(form_id){
        return document.getElementById(form_id).submit();
    }
</script>
@endsection