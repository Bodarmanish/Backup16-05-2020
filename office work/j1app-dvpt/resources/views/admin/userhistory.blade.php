@extends('admin.layouts.app')
@php
$logs = @$logs;
$full_name = @$full_name;
@endphp

@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">View {{$full_name}}'s details</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                @if(check_route_access('user.list'))
                <li><a href="{{ route('user.list') }}">User</a></li>
                @endif
                <li class="active">User Detail</li>
            </ol>
        </div>
    </div>
    <!-- /.row -->
    <!-- .row -->
    <div class="col-md-offset-6 col-md-6 col-xs-12 m-b-20">
        <div class="row">
            <div class="col-md-3 pull-right">
                <a href="{{ route('user.list') }}" class="btn btn-block btn-info"><i class="fa fa-arrow-left"></i>  Back</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="table-responsive">
                <table class="table color-table info-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Action</th>
                            <th>Host Company Name</th>
                            <th>Training Position</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Action Owner</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($logs))
                            @foreach($logs as $log)
                                @php
                                    if($log->action_by_type == "1"){
                                        $action = "Admin";
                                    }elseif($log->action_by_type == "2"){
                                        $action = "User";
                                    }else{
                                        $action = "Auto Admin";
                                    }
                                @endphp
                                <tr>
                                    <td>{{ dateformat($log->created_at,DISPLAY_DATE)}}</td>
                                    <td>{{ $log->status_name }}{{ $log->action_note }}</td>
                                    <td>{{ $log->hc_name }}</td>
                                    <td>{{ $log->pos_name }}</td>
                                    <td>{{ dateformat($log->start_date,DISPLAY_DATE)}}</td>
                                    <td>{{ dateformat($log->end_date,DISPLAY_DATE)}}</td>
                                    <td>{{$action}}</td>
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

