@extends('admin.layouts.app')

@php
$users = @$users;
@endphp

@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">User Manager</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="active">User Application List</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">User Application List</h3>
                        <p class="text-muted m-b-30">List of User Application</p>
                    </div>
                </div>
                <div class="white-box well">
                    <div class="row">
                        <form id="document_filter" method="POST" action="{{ route('user.app.list') }}">
                            {{ csrf_field() }}
                            <div class="form-group col-md-4 col-xs-12">
                                <label>First Name</label>
                                <input type="text" name="first_name" id="first_name" placeholder="Enter First Name" value="{{request()->get('first_name')}}" class="form-control">
                            </div>
                            <div class="form-group col-md-4 col-xs-12">
                                <label>Last Name</label>
                                <input type="text" name="last_name" id="last_name" placeholder="Enter Last Name" value="{{request()->get('last_name')}}" class="form-control">
                            </div>
                            <div class="form-group col-md-4 col-xs-12">
                                <label>Email Address</label>
                                <input type="text" name="email" id="email" placeholder="Enter Email Address" value="{{request()->get('email')}}" class="form-control">
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                      <!--<button type="button" class="btn btn-info" onclick="sendForm();">Search</button>-->
                                      <button type="submit" class="btn btn-info">Search</button>
                                      <a type="button" class="btn btn-danger" href="{{ route('user.app.list') }}">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @include('admin.includes.status')
                <div class="table-responsive">
                    <table id="application_list" class="table table-bordered m-t-10">
                        <thead>
                            <tr>
                                @if($admin_role=='root')
                                <th>User ID</th>
                                @endif
                                <th>Portfolio Number</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($users))
                                @foreach($users as $user) 
                                    <tr>
                                        @if($admin_role=='root')
                                        <td>{{ $user->id }}</td>
                                        @endif
                                        <td>{{ $user->portfolio_number }}</td>
                                        <td>{{ $user->first_name }}</td>
                                        <td>{{ $user->last_name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <a href="{{ route('user.app.progress',encrypt($user->id)) }}" data-toggle="tooltip" data-original-title="User Application Progress"><i class="fa fa-hourglass-half text-inverse m-r-10"></i> </a> 
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
        $('#application_list').dataTable({
            "bStateSave": true,
            "fnStateSave": function (oSettings, oData) {
                localStorage.setItem('offersDataTables', JSON.stringify(oData));
            },
            "fnStateLoad": function (oSettings) {
                return JSON.parse(localStorage.getItem('offersDataTables'));
            }
        });
    });
</script>
@endsection
