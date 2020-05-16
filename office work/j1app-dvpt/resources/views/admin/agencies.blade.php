@extends('admin.layouts.app')

@php
$agency_data = @$agency_data;
@endphp

@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Agency Manager</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="active">Agencies</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">Agencies</h3>
                        <p class="text-muted m-b-30">List of Agencies</p>
                    </div>
                    @if(check_route_access('agency.add.form'))
                        <div class="col-md-6 col-xs-12">
                            <div class="col-md-3 pull-right">
                                <a href="{{ route('agency.add.form') }}" class="btn btn-block btn-info">Add New Agency</a>
                            </div>
                        </div>
                    @endif
                </div>
                @include('admin.includes.status')
                <div class="table-responsive">         
                    <table id="agency_list" class="table table-bordered m-t-10">
                        <thead>
                            <tr>
                                <th>Agency Name</th>
                                <th>Agency Type</th>
                                <th>Agency Address</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                          @if (!empty($agency_data))
                            @foreach($agency_data as $ad)
                                @php
                                    $status = ($ad->status == 1) ? "Active" : "De-Active";
                                    $status_class = ($ad->status == 1) ? "success" : "danger";
                                    $agency_types = config('common.agency_type');
                                    $agency_type =  (!empty($agency_types[$ad->agency_type])) ? $agency_types[$ad->agency_type] : "";
                                @endphp
                                <tr>
                                    <td>{{ $ad->agency_name }}</td>
                                    <td>{{ $agency_type }}</td>
                                    <td>{{ $ad->agency_address }}</td>
                                    <td>{{ $ad->description }}</td>
                                    <td>
                                        <span class="label label-{{ $status_class }}">{{ $status }}</span>
                                    </td>
                                    <td>
                                        @if(check_route_access('user.list'))
                                        <a href="{{ route('user.list') }}" onclick="event.preventDefault(); document.getElementById('user-list-form-'+'{{ $ad->id }}').submit();"><i class="fa fa-users text-inverse m-r-10"></i></a>
                                        <form id="user-list-form-{{ $ad->id }}" action="{{ route('user.list') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="agency_id" value="{{ $ad->id }}" />
                                            <input type="hidden" name="agency_type" value="{{ $ad->agency_type }}" />  
                                        </form>
                                        @endif
                                        @if(check_route_access('agency.edit.form'))
                                            <a href="{{ route('agency.edit.form',$ad->id) }}" data-toggle="tooltip" data-original-title="Edit" onclick="return  showLoader('#full-overlay');"> <i class="fa fa-pencil text-inverse m-r-10"></i> </a>
                                        @endif
                                        @if(check_route_access('agency.delete'))
                                            <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Delete" onclick="return confirmDelete('{{ route('agency.delete',$ad->id) }}');" > <i class="fa fa-close text-danger m-r-10"></i> </a>
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
 <script>
    $(document).ready(function() {
          $('#agency_list').DataTable();
    });
 </script>
@endsection