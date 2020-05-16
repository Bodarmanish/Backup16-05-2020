@extends('admin.layouts.app')
@php

if(!empty($id)){
    $mode = "Edit";
    $action = route('comment.edit',$id);
}
@endphp
@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Admin Comment</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                @if(check_route_access('comment.list'))
                <li><a href="{{ route('comment.list',$id) }}">Comment</a></li>
                @endif
                <li class="active">Edit Topic</li>
            </ol>
        </div>
    </div>
    <div class="row"> 
        <div class="col-md-12 col-xs-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">{{ $mode }} Comment</h3>
                        <!--<p class="text-muted m-b-30">Add New Admin</p>-->
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="pull-right">
                            @if(check_route_access('comment.list'))
                            <a href="{{ route('comment.list',$topic_id) }}" class="btn btn-block btn-info"><i class="fa fa-list"></i> Comments</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        @include('admin.includes.status')
                        <form method="post" action="{{ $action }}" class="form-horizontal form-validator">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="col-md-12">Comment Posted By: </label>
                                <div class="col-md-12">
                                    {{ $comment->first_name }} {{ $comment->last_name }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Comment Text <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <textarea rows="5" name="comment_text" id="comment_text" class="form-control " placeholder="Comment Text">{{$comment->comment_text}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info">Save Comment</button>
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
