@extends('admin.layouts.app')

@php
$users = @$users;
$user_status = config('common.user_status');
@endphp

@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Users</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="active">User</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">User Management</h3>
                        <p class="text-muted m-b-30">List of Users</p>
                    </div>
                    @if(check_route_access('user.add.form'))
                    <div class="col-md-6 col-xs-12">
                        <div class="col-md-3 pull-right">
                            <a href="{{ route('user.add.form') }}" class="btn btn-block btn-info">Add New User</a>
                        </div>
                    </div>     
                   @endif
                </div>
                <div class="white-box well">
                    <div class="row">
                        <form id="document_filter" method="post" action="{{ route('user.search') }}">
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
                                <label>Email</label>
                                <input type="text" name="email" id="email" placeholder="Enter Email" value="{{request()->get('email')}}" class="form-control">
                            </div>
                            <div class="form-group col-md-4 col-xs-12">
                                <label>Program</label>
                                <select name="program" id="program" class="form-control">
                                    <option value="">-- Select Program --</option>
                                        @foreach($programs as $value)
                                            <option value="{{ $value->id }}" {{is_selected(request()->get('program'),$value->id)}} >{{ $value->program_name }}</option>                                                                     
                                        @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                      <!--<button type="button" class="btn btn-info" onclick="sendForm();">Search</button>-->
                                      <button type="submit" class="btn btn-info">Search</button>
                                      <a type="button" class="btn btn-danger" href="{{ route('user.list') }}">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @include('admin.includes.status')
                <div class="table-responsive">
                    <table id="user_list" class="table table-bordered m-t-10">
                        <thead>
                            <tr>
                                @if($admin_role=='root')
                                <th>User ID</th>
                                @endif
                                <th>Portfolio Number</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Program</th>
                                <th>Phone Number</th> 
                                <!--<th>City</th>-->
                                <!--<th>Zip Code</th>-->
                                <th>Country</th>
                                <!--<th>Skype ID</th>-->
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($users))
                                @foreach($users as $user)
                                    @php
                                        $phone_number = ($user->phone_number != "") ? "$user->phone_number" : "-";
                                    @endphp
                                    <tr>
                                        @if($admin_role=='root')
                                        <td>{{ $user->id }}</td>
                                        @endif
                                        <td>{{ $user->portfolio_number }}</td>
                                        <td>{{ $user->first_name }}</td>
                                        <td>{{ $user->last_name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->program_name }}</td>
                                        <td>{{ $phone_number}}</td>
                                        <!--<td>{{ $user->city }}</td>-->
                                        <!--<td>{{ $user->zip_code}}</td>-->
                                        <td>{{ $user->country_name }}</td>
                                        <!--<td>{{ $user->skype_id }}</td>-->
                                        <td>
                                            @if(check_route_access('user.ajax'))
                                                <input type="checkbox" id="user_status_{{ $user->id }}" name="user_status" class="js-switch" data-size="small" data-color="#99d683" data-secondary-color="#f96262" value="1" onchange="update_user_status(this,{{ $user->id }})" {{ is_checked($user->status,'1') }} />
                                            @endif
                                        </td>
                                        <td>
                                            @if(check_route_access('user.edit.form'))
                                                <a href="{{ route('user.edit.form',encrypt($user->id)) }}" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil text-inverse m-r-10"></i></a>
                                            @endif
                                            @if(check_route_access('user.detail'))  
                                                <a href="{{ route('user.detail',encrypt($user->id)) }}" data-toggle="tooltip" data-original-title="View Details"> <i class="fa fa-search text-inverse m-r-10" aria-hidden="true"></i> </a> 
                                            @endif
                                            @if(check_route_access('user.document'))
                                            <a href="{{ route('user.document',encrypt($user->id)) }}" data-toggle="tooltip" data-original-title="User documents"> <i class="fa fa-file-o text-inverse m-r-10" aria-hidden="true"></i> </a>
                                            @endif
                                            @if(check_route_access('user.history'))
                                            <a href="{{ route('user.history',encrypt($user->id)) }}" data-toggle="tooltip" data-original-title="User History"> <i class="fa fa-history text-inverse" aria-hidden="true"></i> </a>
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
    
    function update_user_status(obj,ID){
        var value = 0;
        if(obj.checked) {
            value = 1;
        } 
        $.ajax({
            type: 'post',
            url: '{{route("user.ajax")}}',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data:  { 
                value: value,
                id: ID, 
                action: 'update_user_status'
            },
            success: function(response) { 
                /* Success */
            }
        });
    }
    
    $(document).ready(function() {
        $('#user_list').dataTable({
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
