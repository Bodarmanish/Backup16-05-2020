@extends('admin.layouts.app')

@php
$notification_messages = @$notification_messages;
@endphp

@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Notification Message Manager</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="active">Notification Messages</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">Notification Message</h3>
                        <p class="text-muted m-b-30">List of Notification Message</p>
                    </div>
                </div>
                <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">Notification Type</h3>
                        <p class="text-muted m-b-30">List of Notification Type</p>
                    </div>
                </div>
                <div class="white-box well">
                    <div class="row">
                        <form id="document_filter" method="post" action="{{ route('notification.message.search') }}">
                            {{ csrf_field() }}
                            <div class="form-group col-md-4 col-xs-12">
                                <label>Notification Text / Notification Message</label>
                                <input type="text" name="notification_text_message" id="notification_text_message" placeholder="Enter notification type text/message" value="{{request()->get('notification_text_message')}}" class="form-control">
                            </div>
                            <div class="form-group col-md-4 col-xs-12">
                                <label>Email Send To</label>
                                <select name="notification_name" id="notification_name" class="form-control">
                                    <option value="">-- Select Notification Type Name --</option>
                                    @if (!empty($notification_types))
                                        @foreach($notification_types as $value)
                                            <option value="{{$value->id}}" {{is_selected(request()->get('notification_name'),$value->id)}}>{{$value->notification_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info">Search</button>
                                    <a type="button" class="btn btn-danger" href="{{ route('notification.message.list') }}">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @include('admin.includes.status')
                <div class="table-responsive">
                    <table id="notification_message_list" class="table table-bordered m-t-10">
                        <thead>
                            <tr>
                                <th>Notification Text</th>
                                <th>Notification Message</th>
                                <th>Notification Type Name</th>
                                <th>Last Updated</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($notification_messages))
                                @foreach($notification_messages as $notification_message)
                                    <tr>
                                        <td>{{ $notification_message->notification_text }}</td>
                                        <td>{{ $notification_message->notification_message }}</td>
                                        <td>{{ $notification_message->notification_name }}</td>
                                        <td>{{ dateformat($notification_message->updated_at,DISPLAY_DATE)}}</td>
                                        <td>
                                                @if(check_route_access('notification.message.edit.form'))
                                                <a href="{{ route('notification.message.edit.form',encrypt($notification_message->id)) }}" data-toggle="tooltip" data-original-title="Edit"> <i class="fa fa-pencil text-inverse m-r-10"></i> </a>
                                                @endif
                                                @if(check_route_access('notification.message.delete'))
                                                <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Delete" onclick="return confirmDelete('{{ route('notification.message.delete',encrypt($notification_message->id)) }}');" > <i class="fa fa-close text-danger m-r-10"></i> </a>
                                                @endif
                                                @if(check_route_access('notification.message.status'))
                                                <form style="display:inline;" method="post" action="" name="dlt_apiuser" id="{{ $notification_message->id }}_api_user_dlt" >
                                            <input type="checkbox" name="notify_me_of_replies"  style="display: none;"  onchange="change_notification_status('{{ $notification_message->id }}');" id="{{ $notification_message->id }}" value="1" class="mini-switch" {{ $notification_message->status=="1" ? 'checked="checked"' : '' }}>
                                            <span class="mini-switch-replace" title="{{ $notification_message->status=="1" ? 'Inactive Notification Message Status' : 'Active Notification Message Status' }}" ></span>
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
        var url = "{{ route('notification.message.status') }}";

        $.ajax({
            url: url,
            type: 'post',
            data: { action:'change_notification_message_status', notification_id:id, is_active:is_checked },
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
        $('#notification_message_list').dataTable();
        $("#notification_message_list").on("click", ".mini-switch-replace", function(){
            $(this).prev().click();
        });

    });
</script>
@endsection
