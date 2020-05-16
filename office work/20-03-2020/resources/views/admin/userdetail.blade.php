@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Profile page</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                @if(check_route_access('user.list'))
                <li><a href="{{ route('user.list') }}">User</a></li>
                @endif
                <li class="active">User Detail</li>
            </ol>
        </div>
    </div>
    <!-- /.row -->
    <!-- .row -->
    <div class="row">
        <div class="col-md-3 col-xs-12">
            <div class="white-box">
                @php
                if($users->profile_photo != "")
                $img_url = get_url("user-avatar/".$id."/crop/".$users->profile_photo);
                else
                $img_url = url("assets/images/noimage.png");
                @endphp
                <div class=""> <img width="100%" alt="user" src="{{$img_url}}">
                </div>
            </div>
        </div>
        <div class="col-md-8 col-xs-12">
            <div class="white-box">
                <div class="col-md-3 pull-right">
                    <a href="{{ route('user.list') }}" class="btn btn-block btn-info"><i class="fa fa-arrow-left"></i> Back</a>
                </div> 
                <ul class="nav nav-tabs tabs customtab">
                    <li class=" active tab">
                        <a href="#profile" data-toggle="tab"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">Profile</span> </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="profile">
                        <div class="row">
                            <div class="col-md-3 col-xs-6 b-r"> <strong>First Name</strong>
                                <br>
                                <p class="text-muted">{{$users->first_name}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Middle Name</strong>
                                <br>
                                <p class="text-muted">{{$users->middle_name}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Last Name</strong>
                                <br>
                                <p class="text-muted">{{$users->last_name}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6"> <strong>Email</strong>
                                <br>
                                <p class="text-muted">{{$users->email}}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Program</strong>
                                <br>
                                <p class="text-muted">{{$users->program_name}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Phone Number</strong>
                                <br>
                                <p class="text-muted">{{$users->phone_number}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Secondary Email</strong>
                                <br>
                                <p class="text-muted">{{$users->secondary_email}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Timezone </strong>
                                <br>
                                @foreach($timezones as $zone)
                                @if($zone->zone_id == $users->timezone)
                                <p class="text-muted">{{ $zone->zone_label }}</p>
                                @endif
                                @endforeach
                            </div>
                            
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Street </strong>
                                <br>
                                <p class="text-muted">{{$users->street}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>City</strong>
                                <br>
                                <p class="text-muted">{{$users->city}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Zip Code</strong>
                                <br>
                                <p class="text-muted">{{$users->zip_code}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Country </strong>
                                <br>
                                    <p class="text-muted">{{ $users->country_name }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Skype ID </strong>
                                <br>
                                <p class="text-muted">{{$users->skype_id}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Facebook URL</strong>
                                <br>
                                <p class="text-muted" style='word-break: break-all;'>{{$users->facebook_url}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Twitter URL</strong>
                                <br>
                                <p class="text-muted" style='word-break: break-all;'>{{$users->twitter_url}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

