@extends('admin.layouts.app')
@php
if(!empty($id)){
    $mode = "Edit";
    $action = route('notification.message.edit',$id);
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
            <h4 class="page-title">Notification Message</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                @if(check_route_access('notification.message.list'))
                    <li><a href="{{ route('notification.message.list') }}">Notification Message Manager</a></li>
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
                        <h3 class="box-title m-b-0">{{ $mode }} Notification Message</h3>
                        <!--<p class="text-muted m-b-30">Add New Admin</p>-->
                    </div>
                    @if(check_route_access('user.list'))
                    <div class="col-md-6 col-xs-12">
                        <div class="pull-right">
                            @if(check_route_access('notification.message.list'))
                            <a href="{{ route('notification.message.list') }}" class="btn btn-block btn-info"><i class="fa fa-list"></i> Notification Message</a>
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
                            <div class="form-group {{ $errors->has('notification_text') ? 'has-error' : '' }}">
                                <label class="col-md-12">Notification Text <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="notification_text" id="notification_text" placeholder="Notification Text" class="form-control " required value="{{ ($mode == "Edit") ? $notification->notification_text : old('notification_text') }}" >
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('notification_text')){{ $errors->first('notification_text') }}@endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('notification_msg') ? 'has-error' : '' }}">
                                <label class="col-md-12">Notification Message <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="notification_msg" id="notification_msg" placeholder="Notification Message" class="form-control " required value="{{ ($mode == "Edit") ? $notification->notification_message : old('notification_msg') }}" >
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('notification_msg')){{ $errors->first('notification_msg') }}@endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info">Save Notification Message</button>
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
