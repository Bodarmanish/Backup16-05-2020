@extends('admin.layouts.app')

@php
$notification_types = @$notification_types;
@endphp

@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Notification Type Manager</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="active">Notification Types</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">Notification Type</h3>
                        <p class="text-muted m-b-30">List of Notification Type</p>
                    </div>
                </div>
                @include('admin.includes.status')
                <div class="table-responsive">
                    <table id="notification_type_list" class="table table-bordered m-t-10">
                        <thead>
                            <tr>
                                <th>Notification Name</th>
                                <th>Notification Key</th>
                                <th>Visible To User</th>
                                <th>Notification Mode</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($notification_types))
                                @foreach($notification_types as $notification_type)
                                    <tr>
                                        <td>{{ $notification_type->notification_name }}</td>
                                        <td>{{ $notification_type->notification_key }}</td>
                                        <td>
                                            @if($notification_type->visible_to_user == 0)
                                                Hidden for user
                                            @elseif($notification_type->visible_to_user == 1)
                                                Display to user
                                            @elseif($notification_type->visible_to_user == 2)
                                                Display in disable mode
                                            @endif
                                        </td>
                                        <td>
                                            @if($notification_type->notification_mode == 1)
                                                Send email notification
                                            @elseif($notification_type->notification_mode == 0)
                                                Only log no email send
                                            @endif
                                        </td>
                                        <td>
                                                @if(check_route_access('notification.type.edit.form'))
                                                <a href="{{ route('notification.type.edit.form',encrypt($notification_type->id)) }}" data-toggle="tooltip" data-original-title="Edit"> <i class="fa fa-pencil text-inverse m-r-10"></i> </a>
                                                @endif
                                                @if(check_route_access('notification.type.delete'))
                                                <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Delete" onclick="return confirmDelete('{{ route('notification.type.delete',encrypt($notification_type->id)) }}');" > <i class="fa fa-close text-danger m-r-10"></i> </a>
                                                @endif
                                                @if(check_route_access('notification.type.status'))
                                                <form style="display:inline;" method="post" action="" name="dlt_apiuser" id="{{ $notification_type->id }}_api_user_dlt" >
                                            <input type="checkbox" name="notify_me_of_replies"  style="display: none;"  onchange="change_notification_status('{{ $notification_type->id }}');" id="{{ $notification_type->id }}" value="1" class="mini-switch" {{ $notification_type->status=="1" ? 'checked="checked"' : '' }}>
                                            <span class="mini-switch-replace" title="{{ $notification_type->status=="1" ? 'Inactive Notification Type Status' : 'Active Notification Type Status' }}" ></span>
                                                </form>
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
    function change_notification_status(id){
        showLoader("#full-overlay");
        var is_checked = 0;
        if($("#"+id).prop("checked") == true){
            is_checked = 1;
        }else if($("#"+id).prop("checked") == false){
            is_checked = 0;
        }
        var url = "{{ route('notification.type.status') }}";

        $.ajax({
            url: url,
            type: 'post',
            data: { action:'change_notification_status', notification_id:id, is_active:is_checked },
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response){
                hideLoader("#full-overlay");
                if(response.type == "success"){
                    notifyAlert(response.message,response.type,"Success");
                }
            },
        });
    }
    $(document).ready(function() {
        $('#notification_type_list').dataTable();
        $("#notification_type_list").on("click", ".mini-switch-replace", function(){
            $(this).prev().click();
        });

    });
</script>
@endsection
