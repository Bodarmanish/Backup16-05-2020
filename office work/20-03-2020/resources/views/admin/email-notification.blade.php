@extends('admin.layouts.app')

@php
$email_notification = @$email_notification;
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
                <div class="white-box well">
                    <div class="row">
                        <form id="document_filter" method="post" action="{{ route('email.notification.search') }}">
                            {{ csrf_field() }}
                            <div class="form-group col-md-4 col-xs-12">
                                <label>Keyword</label>
                                <input type="text" name="mail_text" id="mail_text" placeholder="Enter keyword to search for mail-subject/mail-text" value="{{request()->get('mail_text')}}" class="form-control">
                            </div>
                            <div class="form-group col-md-4 col-xs-12">
                                <label>Recipient Email Address</label>
                                <input type="text" name="recipient_address" id="recipient_address" placeholder="Enter recipient email address" value="{{request()->get('recipient_address')}}" class="form-control">
                            </div>
                            <div class="form-group col-md-4 col-xs-12">
                                <label>Email Send To</label>
                                <select name="send_to" id="send_to" required class="form-control">
                                    <option value="0" >-- Select mail send to --</option>
                                    <option value="1" {{is_selected(request()->get('send_to'),1)}}>Candidate</option>
                                    <option value="2" {{is_selected(request()->get('send_to'),2)}}>Admin</option>
                                    <option value="3" {{is_selected(request()->get('send_to'),3)}}>Both</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                      <button type="submit" class="btn btn-info">Search</button>
                                      <a type="button" class="btn btn-danger" href="{{ route('email.notification.list') }}">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @include('admin.includes.status')
                <div class="table-responsive">
                    <table id="notification_type_list" class="table table-bordered m-t-10">
                        <thead>
                            <tr>
                                <th width="5%">Sr. No.</th>
                                <th width="10%">Subject</th>
                                <th width="55%">Text</th>
                                <th width="5%">Mail Send To</th>
                                <th width="10%">Mail Send Cc</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($email_notification))
                                @foreach($email_notification as $value)
                                    <tr>
                                        <td>{{ $value->id }}</td>
                                        <td>{{ $value->subject }}</td>
                                        <td>{!! $value->text !!}</td>
                                        <td>
                                            @if($value->send_to == 1)
                                                Candidate
                                            @elseif($value->send_to == 2)
                                                Admin
                                            @elseif($value->send_to == 3)
                                                Both
                                            @endif
                                        </td>
                                        <td>{{ $value->send_cc }}</td>
                                        <td>
                                                @if(check_route_access('email.notification.edit.form'))
                                                <a href="{{ route('email.notification.edit.form',encrypt($value->id)) }}" data-toggle="tooltip" data-original-title="Edit"> <i class="fa fa-pencil text-inverse m-r-10"></i> </a>
                                                @endif
                                                @if(check_route_access('email.notification.delete'))
                                                <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Delete" onclick="return confirmDelete('{{ route('email.notification.delete',encrypt($value->id)) }}');" > <i class="fa fa-close text-danger m-r-10"></i> </a>
                                                @endif
                                                @if(check_route_access('email.notification.status'))
                                                <form style="display:inline;" method="post" action="" name="dlt_apiuser" id="{{ $value->id }}_api_user_dlt" >
                                                    <input type="checkbox" name="notify_me_of_replies"  style="display: none;"  onchange="change_email_notification_status('{{ $value->id }}');" id="{{ $value->id }}" value="1" class="mini-switch" {{ $value->status=="1" ? 'checked="checked"' : '' }}>
                                                    <span class="mini-switch-replace" title="{{ $value->status=="1" ? 'Inactive Email Notification Status' : 'Active Email Notification Status' }}" ></span>
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
    function change_email_notification_status(id){
        showLoader("#full-overlay");
        var is_checked = 0;
        if($("#"+id).prop("checked") == true){
            is_checked = 1;
        }else if($("#"+id).prop("checked") == false){
            is_checked = 0;
        }
        var url = "{{ route('email.notification.status') }}";

        $.ajax({
            url: url,
            type: 'post',
            data: { action:'change_email_notification_status', notification_id:id, is_active:is_checked },
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
