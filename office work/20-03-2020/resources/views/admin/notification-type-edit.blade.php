@extends('admin.layouts.app')
@php
if(!empty($id)){
    $mode = "Edit";
    $action = route('notification.type.edit',$id);
}
else 
{
    $id = 0;
}

@endphp
@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Notification Type</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                @if(check_route_access('notification.type.list'))
                    <li><a href="{{ route('notification.type.list') }}">Notification Type Manager</a></li>
                @endif
                <li class="active">{{ $mode }} Notification Type</li>
            </ol>
        </div>
    </div>
    <div class="row"> 
        <div class="col-md-12 col-xs-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">{{ $mode }} Notification Type</h3>
                        <!--<p class="text-muted m-b-30">Add New Admin</p>-->
                    </div>
                    @if(check_route_access('user.list'))
                    <div class="col-md-6 col-xs-12">
                        <div class="pull-right">
                            @if(check_route_access('notification.type.list'))
                            <a href="{{ route('notification.type.list') }}" class="btn btn-block btn-info"><i class="fa fa-list"></i> Notification Type</a>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @include('admin.includes.status')
                        <form method="post" action="{{ $action }}" class="form-horizontal form-validator" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="user_id" value="{{ ($mode == "Edit") ? $id : ''  }}" />
                            <div class="form-group {{ $errors->has('notification_key') ? 'has-error' : '' }}">
                                <label class="col-md-12">Notification Key <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="notification_key" id="notification_key" placeholder="Notification Key" class="form-control " required value="{{ ($mode == "Edit") ? $notification->notification_key : old('notification_key') }}" >
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('notification_key')){{ $errors->first('notification_key') }}@endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('notification_name') ? 'has-error' : '' }}">
                                <label class="col-md-12">Notification Name <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="notification_name" id="notification_name" placeholder="Notification Name" class="form-control " required value="{{ ($mode == "Edit") ? $notification->notification_name : old('notification_name') }}" >
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('notification_name')){{ $errors->first('notification_name') }}@endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('visible_to_user') ? 'has-error' : '' }}">
                                <label class="col-md-12">Visible To User </label>
                                <div class="col-md-12">
                                    <select name="visible_to_user" id="visible_to_user"  class="form-control ">
                                        <option value="0" @if($mode == "Edit"){{ is_selected($notification->visible_to_user,0) }} @endif {{ is_selected(old('visible_to_user'),0) }}>Hidden for user</option>
                                        <option value="1" @if($mode == "Edit") {{ is_selected($notification->visible_to_user,1) }} @endif {{ is_selected(old('visible_to_user'),1) }}>Display to user</option>
                                        <option value="2" @if($mode == "Edit") {{ is_selected($notification->visible_to_user,2) }} @endif {{ is_selected(old('visible_to_user'),2) }}>Display in disable mode</option>
                                    </select>
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('visible_to_user')){{ $errors->first('visible_to_user') }}@endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('notification_mode') ? 'has-error' : '' }}">
                                <label class="col-md-12">Notification Mode </label>
                                <div class="col-md-12">
                                    <select name="notification_mode" id="notification_mode"  class="form-control ">
                                        <option value="1" @if($mode == "Edit"){{ is_selected($notification->notification_mode,1) }} @endif {{ is_selected(old('notification_mode'),1) }}>Send email notification</option>
                                        <option value="0" @if($mode == "Edit"){{ is_selected($notification->notification_mode,0) }} @endif {{ is_selected(old('notification_mode'),0) }}>Only log no email send</option>
                                    </select>
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('notification_mode')){{ $errors->first('notification_mode') }}@endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info">Save Notification Type</button>
                                    <button type="reset" class="btn btn-danger">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
</script>
@endsection
