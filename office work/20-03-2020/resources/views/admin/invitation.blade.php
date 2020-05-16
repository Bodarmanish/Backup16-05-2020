@extends('admin.layouts.app')
@php
    $action = route('user.invite');
@endphp
@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Users</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('user.list') }}">User</a></li>
                <li class="active">Invite User</li>
            </ol>
        </div>
    </div>
    <div class="row"> 
        <div class="col-md-12 col-xs-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">Invite User</h3>
                        <!--<p class="text-muted m-b-30">Add New Admin</p>-->
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        @include('admin.includes.status')
                        <form method="post" action="{{ $action }}" class="form-horizontal form-validator" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                <label class="col-md-12">Email <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="email" name="email" id="email" placeholder="Email Address" class="form-control " required value="{{ old('email') }}" >
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('first_name')){{ $errors->first('email') }}@endif
                                    </div>
                                </div>
                            </div>
                           <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info">Send Invitation</button>
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
