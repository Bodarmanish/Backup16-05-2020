@extends('admin.layouts.app')
@php
if(!empty($id)){
    $mode = "Edit";
    $action = route('email.notification.edit',$id);
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
            <h4 class="page-title">Email Notification</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                @if(check_route_access('email.notification.list'))
                    <li><a href="{{ route('email.notification.list') }}">Email Notification Manager</a></li>
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
                        <h3 class="box-title m-b-0">{{ $mode }} Email Notification</h3>
                        <!--<p class="text-muted m-b-30">Add New Admin</p>-->
                    </div>
                    @if(check_route_access('user.list'))
                    <div class="col-md-6 col-xs-12">
                        <div class="pull-right">
                            @if(check_route_access('email.notification.list'))
                            <a href="{{ route('email.notification.list') }}" class="btn btn-block btn-info"><i class="fa fa-list"></i> Email Notification</a>
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
                            <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
                                <label class="col-md-12">Email Subject <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="subject" id="subject" placeholder="Subject" class="form-control " required value="{{ ($mode == "Edit") ? $notification->subject : old('subject') }}" >
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('subject')){{ $errors->first('subject') }}@endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('send_cc') ? 'has-error' : '' }}">
                                <label class="col-md-12">Email Send Cc <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="send_cc" id="send_cc" placeholder="Email ID" class="form-control " required value="{{ ($mode == "Edit") ? $notification->send_cc : old('send_cc') }}" >
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('send_cc')){{ $errors->first('send_cc') }}@endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('send_to') ? 'has-error' : '' }}">
                                <label class="col-md-12">Email Send To <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <select name="send_to" id="send_to" required class="form-control">
                                        <option value="1" {{ is_selected($notification->send_to,1) }}  {{ is_selected(old('send_to'),1) }}>Candidate</option>
                                        <option value="2" {{ is_selected($notification->send_to,2) }}  {{ is_selected(old('send_to'),2) }}>Admin</option>
                                        <option value="3" {{ is_selected($notification->send_to,3) }}  {{ is_selected(old('send_to'),3) }}>Both</option>
                                    </select>
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('send_to')){{ $errors->first('send_to') }}@endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('text') ? 'has-error' : '' }}">
                                <label class="col-md-12">Email Text <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <textarea name="text" id="text" placeholder="Text" class="form-control" required="required" rows="4">{!! ($mode == "Edit") ? $notification->text : old('text') !!}</textarea>
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('text')){{ $errors->first('text') }}@endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info">Save Email Notification</button>
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
$(document).ready(function () {
    $('#send_cc').tokenfield();
});
CKEDITOR.replace( 'text' ); 
</script>
@endsection
