@extends('admin.layouts.app')

@php
$permission_groups = @$permission_groups;
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
                <li class="active">Permission Groups</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">Permission Groups</h3>
                        <p class="text-muted m-b-30">List of Permission Group</p>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="col-md-4 pull-right">
                            <a href="{{ route('role.pg.add.form') }}" class="btn btn-block btn-info">Add Permission Group</a>
                        </div>
                    </div>
                </div>
                @include('admin.includes.status')
                <div class="table-responsive">
                    <table id="pg_list"  class="table table-bordered m-t-10">
                        <thead>
                            <tr>
                                <th>Group Name</th>
                                <th>Description</th>
                                <th>Is Menu Section</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($permission_groups))
                                @foreach($permission_groups as $pg)
                                    @php
                                        $is_menu_section = ($pg->is_menu_section == 1) ? "Yes" : "No";
                                        $is_menu_section_class = ($pg->is_menu_section == 1) ? "success" : "danger";
                                    @endphp
                                    <tr>
                                        <td>{{ $pg->display_name }}</td>
                                        <td>{{ $pg->description }}</td>
                                        <td>
                                            <span class="label label-{{ $is_menu_section_class }}">{{ $is_menu_section }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('role.pg.edit.form',$pg->id) }}" data-toggle="tooltip" data-original-title="Edit"> <i class="fa fa-pencil text-inverse m-r-10"></i> </a>
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
        $('#pg_list').DataTable();
    });
</script>
@endsection
