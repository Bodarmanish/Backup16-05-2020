@extends('admin.layouts.app')

@php
$host_companies = @$host_companies;
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
                <li class="active">Host Companies</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">Host Companies</h3>
                        <p class="text-muted m-b-30">List of Host Companies</p>
                    </div>
                    <div class="col-md-6 col-xs-12">
                    @if(check_route_access('hc.add.form'))
                        <div class="pull-right">
                            <a href="{{ route('hc.add.form') }}" class="btn btn-block btn-info">Add Host Company</a>
                        </div>
                    @endif
                    </div>
                </div>
                @include('admin.includes.status')
                <div class="table-responsive">
                    <table id="host_company_list" class="table table-bordered m-t-10">
                        <thead>
                            <tr>
                                <th>Host Company</th>
                                <th>EIN</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty(@$host_companies))
                                @foreach($host_companies as $hc)
                                    @php
                                        $hc_status = config('common.hc_status');
                                    @endphp
                                    <tr>
                                        <td>{{ $hc->hc_name }}</td>
                                        <td>{{ $hc->hc_id_number }}</td>
                                        <td>
                                            @if(!empty($hc->state_id))
                                            {{ $hc->state_name }} ({{ $hc->state_abbr }})
                                            @endif
                                        </td>
                                        <td>
                                            <span class="label label-{{ $hc_status['class'][$hc->status] }}">{{ $hc_status['name'][$hc->status] }}</span>
                                            @if(check_route_access('hc.status.form'))
                                                <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Update Status" onclick="return updateHCStatus('{{ route('hc.status.form') }}','{{ $hc->id }}');" > <i class="fa fa-edit text-danger m-r-10"></i> </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if(check_route_access('hc.edit.form'))
                                                <a href="{{ route('hc.edit.form',$hc->id) }}" data-toggle="tooltip" data-original-title="Edit"> <i class="fa fa-pencil text-inverse m-r-10"></i> </a>
                                            @endif
                                            @if(check_route_access('hc.delete'))
                                                <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Delete" onclick="return confirmDelete('{{ route('hc.delete',$hc->id) }}');" > <i class="fa fa-close text-danger m-r-10"></i> </a>
                                            @endif
                                            @if(check_route_access('hc.detail'))
                                                <a href="{{ route('hc.detail',encrypt($hc->id)) }}" data-toggle="tooltip" data-original-title="View Details"> <i class="fa fa-search text-inverse m-r-10" aria-hidden="true"></i> </a> 
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
        $('#host_company_list').DataTable();
    });
    
    function updateHCStatus(url,id){
        show_popup();
        get_common_ajax(url,
        {
            action: "update_hc_status_form", 
            id: id 
        });
    }
</script>
@endsection