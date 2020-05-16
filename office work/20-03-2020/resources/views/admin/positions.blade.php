@extends('admin.layouts.app')

@php
$positions = @$positions;
@endphp

@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">HC & Position Manager</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="active">Positions</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">Positions</h3>
                        <p class="text-muted m-b-30">List of Positions</p>
                    </div>
                    @if(check_route_access('hc.pos.add.form'))
                    <div class="col-md-6 col-xs-12">
                        <div class="col-md-3 pull-right">
                            <a href="{{ route('hc.pos.add.form') }}" class="btn btn-block btn-info">Add Position</a>
                        </div>
                    </div>
                    @endif
                </div>
                @include('admin.includes.status')
                <div class="table-responsive">
                    <table id="position_list" class="table table-bordered m-t-10">
                        <thead>
                            <tr>
                                <th>Position Name</th>
                                <th>Host Company</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Agency</th>
                                <th>Created By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($positions))
                                @foreach($positions as $pos)
                                    <tr>
                                        <td>{{ $pos->pos_name }}</td>
                                        <td>{{ $pos->hc_name }}</td>
                                        <td>{{ dateformat($pos->start_date,"d M, Y") }}</td>
                                        <td>{{ dateformat($pos->end_date,"d M, Y") }}</td>
                                        <td>{{ $pos->agency_name }}</td>
                                        <td>{{ $pos->created_by }}</td>
                                        <td>
                                            @if(check_route_access('hc.pos.edit.form'))
                                            <a href="{{ route('hc.pos.edit.form',$pos->id) }}" data-toggle="tooltip" data-original-title="Edit"> <i class="fa fa-pencil text-inverse m-r-10"></i> </a>
                                            @endif
                                            @if(check_route_access('hc.pos.delete'))
                                            <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Delete" onclick="return confirmDelete('{{ route('hc.pos.delete',$pos->id) }}');" > <i class="fa fa-close text-danger m-r-10"></i> </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
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
        $('#position_list').DataTable();
    });
</script>
@endsection