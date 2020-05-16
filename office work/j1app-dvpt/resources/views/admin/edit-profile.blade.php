@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Profile page</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="active">Profile page</li>
                </ol>
            </div>
        </div>
        <div class="row"> 
            <div class="col-md-12 col-xs-12">
                <div class="white-box">
                    @include('admin.includes.status')
                    <form method="post" action="{{ route('update.profile') }}" class="form-horizontal form-validator" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-md-12">First Name <span class="text-danger">*</span></label>
                            <div class="col-md-12">
                                <input type="text" name="first_name" id="first_name" placeholder="First Name" class="form-control" required value="{{ @$data->first_name }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Last Name <span class="text-danger">*</span></label>
                            <div class="col-md-12">
                                <input type="text" name="last_name" id="last_name" placeholder="Last Name" class="form-control" required value="{{ @$data->last_name }}"> 
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="example-email" class="col-md-12">Email Address <span class="text-danger">*</span></label>
                            <div class="col-md-12">
                                <input type="email" name="email" id="email" placeholder="Email Address" class="form-control" required readonly value="{{ @$data->email }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Password <span class="alert-danger">(Keep password field blank if not want to change current password.)</span></label>
                            <div class="col-md-12">
                                <input type="password" name="password" id="password" placeholder="Password" class="form-control">  
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Profile Photo</label>
                            <div class="col-md-12">
                                <input type="file" name="profile_photo" id="profile_photo" class="form-control"> 
                            </div>  
                            <label class="col-md-12"></label>
                            <div class="col-md-12">
                                <img src="{{ $profile_photo }}" width="200" class="img-responsive thumbnail" />
                            </div> 
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-info">Update Profile</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> 
@endsection
