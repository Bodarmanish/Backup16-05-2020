@extends('user.layouts.app')

@section('content')
@php 
    $allow_image_ext = collect(config('common.allow_image_ext'))->implode(', ');
    $upload_image_ext = collect(config('common.allow_image_ext'))->implode('|');
    $upload_img_size = config("common.upload_img_size");
    $allowed_img_size = config("common.upload_img_size")*1024;
    $active_tab = $data['active_tab'];
    $social_auth_info = $data['social_auto_info'];
    $profile_info = $data['profile_info'];
@endphp
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}" class="text-info">Home</a></li>
            <li class="active capitalize">Edit Profile</li>
        </ol>
    </div>
</div> 
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default m-b-10"> 
            @if(!empty($profile_info))
                <div class="panel-body p-t-10 popup-body">
                <ul class="nav customtab2 nav-tabs popup-nav m-b-30">
                    <li class="{{ $active_tab=="" ||  $active_tab=="details" ? 'active' : '' }}">
                        <a href="#tb-details" data-toggle="tab" aria-expanded="true" title="Details">
                            <span class="visible-xs"><i class="ti-user"></i></span>
                            <span class="hidden-xs">Details</span>
                        </a>
                    </li>
                    <li class="{{ $active_tab=="password" ? 'active' : '' }}">
                        <a href="#tb-password" data-toggle="tab" aria-expanded="false" title="Password">
                            <span class="visible-xs"><i class="ti-home"></i></span>
                            <span class="hidden-xs">Password</span>
                        </a>
                    </li>
                    <li class="{{ $active_tab=="notification" ? 'active' : '' }}">
                        <a href="#tb-notification" data-toggle="tab" aria-expanded="false" title="Notification">
                            <span class="visible-xs"><i class="ti-bell"></i></span>
                            <span class="hidden-xs">Notification</span>
                        </a>
                    </li>
                    <li class="{{ $active_tab=="social" ? 'active' : '' }}">
                        <a href="#tb-social" data-toggle="tab" aria-expanded="false" title="Social">
                            <span class="visible-xs"><i class="ti-settings"></i></span>
                            <span class="hidden-xs">Social</span>
                        </a> 
                    </li>
                    <li class="{{ $active_tab=="address" ? 'active' : '' }}">
                        <a href="#tb-address" data-toggle="tab" aria-expanded="false" title="Address">
                            <span class="visible-xs"><i class="ti-email"></i></span>
                            <span class="hidden-xs">Address</span>
                        </a>
                    </li>
                    <li class="{{ $active_tab=="social_details" ? 'active' : '' }}">
                        <a href="#tb-social-details" data-toggle="tab" aria-expanded="false" title="Social Details">
                            <span class="visible-xs"><i class="ti-settings"></i></span>
                            <span class="hidden-xs">Social Details</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content br-n pn"> 
                    <div id="tb-details" class="tab-pane {{ $active_tab=="" ||  $active_tab=="details" ? 'active' : '' }}"> 
                        <div class="showResponseMesssage"></div>
                        <form id="editProfileFrm" name="editProfileFrm" class="" autocomplete="off">
                            <input type="hidden" name="action" value="editProfile"/>
                            <div class="row">
                                <div class="col-lg-9 col-md-7 col-sm-7 col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
                                                <label class="font-light font-13">First Name <span class="text-danger">*</span></label>
                                                <input type="text" name="first_name" class="form-control" placeholder="First Name" data-notempty="notempty" value="{{$profile_info->first_name}}" required />
                                                <div class="help-block with-errors">@if ($errors->has('first_name')){{ $errors->first('first_name') }}@endif</div>
                                                <div class="form-control-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
                                                <label class="font-light font-13">Last Name <span class="text-danger">*</span></label>
                                                <input type="text" name="last_name" class="form-control" placeholder="Last Name" data-notempty="notempty" value="{{$profile_info->last_name}}" required />
                                                <div class="help-block with-errors"></div>
                                                <div class="form-control-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group {{ $errors->has('phone_number') ? 'has-error' : '' }}">
                                                <label class="font-light font-13">Phone Number <span class="text-danger">*</span></label> 
                                                    <input type="text" name="phone_number" class="form-control phone_number" data-nowhitespace="nowhitespace" placeholder="Default Phone Number" value="{{$profile_info->phone_number}}" required />
                                                <div class="help-block with-errors"></div>
                                                <div class="form-control-feedback"></div>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                                <label class="font-light font-13">Email Address <span class="text-danger">*</span></label>
                                                <input type="email" name="email" class="form-control" placeholder="Email Address" value="{{$profile_info->email}}" disabled="" required />
                                                <div class="help-block with-errors"></div>
                                                <div class="form-control-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label class="font-light font-13">Secondary Email Address (optional)</label>
                                                <input type="email" name="secondary_email" class="form-control" placeholder="Secondary Email Address" value="{{$profile_info->secondary_email}}"  > 
                                                <div class="help-block with-errors"></div>
                                                <div class="form-control-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group m-b-20 {{ $errors->has('timezone') ? 'has-error' : '' }}">
                                                <label class="font-light font-13">Timezone <span class="text-danger">*</span></label>
                                                <select name="timezone" class="form-control" required="">
                                                    <option value="">-- Select Timezone --</option>
                                                        @foreach($timezones as $zone)
                                                            <option value="{{ $zone->zone_id }}" @if($zone->zone_id == $profile_info->timezone) selected="selected" @endif>{{ $zone->zone_label }}</option>                                                                     @endforeach
                                                </select> 
                                                <div class="help-block with-errors">
                                                    @if ($errors->has('timezone')){{ $errors->first('timezone') }}@endif
                                                </div>
                                                <div class="form-control-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="dpprofileimg">
                                                <a href="javascript:;" id="editProfilePhoto">
                                                    <img src="{{ $profile_photo_200x }}" class="img-responsive thumbnail editprofilepicture avatar-md"> 
                                                    <div class="overlay-pro-pic">
                                                        <i class="fa fa-fw fa-camera"></i>
                                                    </div>
                                                </a> 
                                            </div> 
                                            <div id="fileHelp" class="form-text text-muted">The image file should be JPG, PNG or JPEG format. Image size must be under 2MB.</div>
                                        </div>
                                    </div>
                                    <div id="crop-container" style="display:none"></div>
                                </div>
                                <div class="col-xs-12 m-t-10">
                                    <div class="form-group">
                                        <button type="submit" name="editProfile-btn" id="editProfile-btn" class="btn btn-info btn-lg font-size-14">Save Details</button>
                                    </div>
                                </div> 
                            </div>
                        </form>
                    </div>
                    <div id="tb-password" class="tab-pane {{ $active_tab == "password" ? 'active' : '' }}">
                        <div class="showResponseMesssage"></div>
                        <div class="row"> 
                            <form id="changePasswordFrm" name="changePasswordFrm" class="">
                                <input type="hidden" name="action" value="changePassword"/> 
                                <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group m-b-20 {{ $errors->has('current_password') ? 'has-error' : '' }}">
                                                <label class="font-light font-13">Current Password <span class="text-danger">*</span></label>
                                                <input type="password" name="current_password" class="form-control" placeholder="Current Password" data-nowhitespace="nowhitespace" required />
                                                <div class="help-block with-errors"></div>
                                                <div class="form-control-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group m-b-20 {{ $errors->has('new_password') ? 'has-error' : '' }}">
                                                <label class="font-light font-13">New Password <span class="text-danger">*</span></label>
                                                <input type="password" name="new_password" id="new_password" class="form-control" placeholder="New Password" data-nowhitespace="nowhitespace" required />
                                                <div class="help-block with-errors"></div>
                                                <div class="form-control-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group m-b-20 {{ $errors->has('new_confirm_password') ? 'has-error' : '' }}">
                                                <label class="font-light font-13">Confirm New Password <span class="text-danger">*</span></label>
                                                <input type="password" name="new_confirm_password" class="form-control" data-match="#new_password" data-nowhitespace="nowhitespace" data-match-error="Whoops, these don't match" placeholder="Confirm New Password" required />
                                                <div class="help-block with-errors"> 
                                                </div>
                                                <div class="form-control-feedback"></div>
                                            </div>
                                        </div>
                                    </div>   
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                                    <h5><b>Password Hint</b></h5> 
                                    @if(isset($data['password_setting']))
                                        {!! $data['password_setting'] !!}
                                    @endif                                         
                                </div>
                                <div class="col-xs-12 m-t-10">
                                    <div class="form-group"> 
                                        <button type="submit" id="changePassword-btn" name="changePassword-btn" class="btn btn-info btn-lg font-size-14 m-t-10">Change Password</button>
                                    </div>
                                </div> 
                            </form>
                        </div>
                    </div>
                    <div id="tb-notification" class="tab-pane {{ $active_tab =="notification" ? 'active' : '' }}">
                        <div class="showResponseMesssage"></div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form_profile_notifications">
                                    <div class="row m-b-10">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="col-lg-7 col-md-7 col-sm-6 col-xs-7"></div>
                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-3">
                                            <p class="text-center">J1 App</p>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                            <p>Email</p>
                                        </div>
                                        </div>
                                    </div>  
                                    @if(!empty($data['notificationlist'])) 
                                        @foreach($data['notificationlist'] as $key => $notifytype) 
                                            <div class="row m-b-10 m-t-10">
                                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                                    <p>{{ $notifytype->notification_name }}</p>
                                                </div>
                                                @if($notifytype->visible_to_user == 1)
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                        <input type="checkbox" id="j1app_status_{{ $notifytype->id }}" name="j1app_status" class="js-switch" data-size="small" data-color="#99d683" data-secondary-color="#f96262" value="1" onchange="store_user_notification_status(this,'j1app_status',{{ $notifytype->id }})" {{ is_checked($notifytype->j1app_status,'1') }} />
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                        <input type="checkbox" id="email_status_{{ $notifytype->id }}" name="email_status" class="js-switch" data-size="small" data-color="#99d683" data-secondary-color="#f96262" value="1" onchange="store_user_notification_status(this,'email_status',{{ $notifytype->id }})" {{ is_checked($notifytype->email_status,'1') }}  />
                                                    </div> 
                                                @else
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                        <input type="checkbox" id="j1app_status_{{ $notifytype->id }}" name="j1app_status" class="js-switch" data-disabled="true" data-size="small" data-color="#99d683" data-secondary-color="#f96262" value="1" {{ is_checked($notifytype->j1app_status,'1') }} />
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                        <input type="checkbox" id="email_status_{{ $notifytype->id }}" name="email_status" class="js-switch" data-disabled="true" data-size="small" data-color="#99d683" data-secondary-color="#f96262" value="1" {{ is_checked($notifytype->email_status,'1') }}  />
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach 
                                    @endif 
                                </div>
                                <p class="text-muted m-t-30"><b>Get Text message notification</b></p>
                                <p class="text-black">To get these notifications, you need to<a href="#" class="text-info"> activate text messaging</a></p>
                            </div>
                        </div>
                    </div>
                    <div id="tb-social" class="tab-pane {{ $active_tab=="social" ? 'active' : '' }}">
                        <div class="showResponseMesssage">@include('user.includes.status')</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form_profile_social"> 
                                    <div class="social_row row m-b-10 m-t-10">
                                        <div class="col-md-2 col-sm-2 col-xs-2">
                                            <i class="fa fa-facebook-official fa-2x text-muted"></i>
                                        </div>
                                        <div class="col-md-3 col-sm-3 hidden-xs">
                                            <p>Facebook @if(!empty($social_auth_info) && !empty($social_auth_info->facebook_email))<br/><small>({{$social_auth_info->facebook_email}})</small> @endif</p>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-4">
                                            <p class="text-muted">Not Connected</p>
                                        </div> 
                                        @if(!empty($social_auth_info) && !empty($social_auth_info->facebook_email))
                                            <div class="col-md-2 col-sm-2 col-xs-4 text-right">
                                                <a class="btn btn-rounded btn-danger btn-sm" id='facebook_id' onclick="deleteSocialID('facebook')">Delete</a>
                                                <i class="fa fa-trash-o fa-2x text-danger visible-xs"></i>
                                            </div>
                                        @else
                                            <div class="col-md-2 col-sm-2 col-xs-4 text-right">
                                                <button type="button" class="btn btn-info btn-sm btn-rounded auth_btn" onclick="window.location.href='{{ route("social.redirect",['provider'=>"facebook",'type'=>"authorize"]) }}'; $('.auth_btn').prop('disabled', true); return false;">Authorize</button>
                                            </div>
                                        @endif 
                                    </div>
                                    <div class="social_row row m-b-10 m-t-10">
                                        <div class="col-md-2 col-sm-2 col-xs-2">
                                            <i class="fa fa-google fa-2x text-muted"></i>
                                        </div>
                                        <div class="col-md-3 col-sm-3 hidden-xs">
                                            <p>Google @if(!empty($social_auth_info) && !empty($social_auth_info->google_email))<br/><small>({{$social_auth_info->google_email}})</small> @endif </p> 
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-4">
                                            <p class="text-muted">Not Connected</p>
                                        </div>
                                        @if(!empty($social_auth_info) && !empty($social_auth_info->google_email))
                                            <div class="col-md-2 col-sm-2 col-xs-4 text-right">
                                                <a class="btn btn-rounded btn-danger btn-sm" id='google_id' onclick="deleteSocialID('google')">Delete</a>
                                                <i class="fa fa-trash-o fa-2x text-danger visible-xs"></i>
                                            </div>
                                        @else
                                            <div class="col-md-2 col-sm-2 col-xs-4 text-right">
                                                <button type="button" class="btn btn-info btn-sm btn-rounded auth_btn" onclick="window.location.href='{{ route("social.redirect",['provider'=>"google",'type'=>"authorize"]) }}'; $('.auth_btn').prop('disabled', true); return false;">Authorize</button>
                                            </div> 
                                        @endif  
                                    </div>  
                                    <div class="social_row row m-b-10 m-t-10">
                                        <div class="col-md-2 col-sm-2 col-xs-2">
                                            <i class="fa fa-twitter fa-2x text-muted"></i>
                                        </div>
                                        <div class="col-md-3 col-sm-3 hidden-xs">
                                            <p>Twitter @if(!empty($social_auth_info) && !empty($social_auth_info->twitter_email)) <br/><small>({{$social_auth_info->twitter_email}})</small> @endif</p>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-4">
                                            <p class="text-muted">Not Connected</p>
                                        </div>
                                        @if(!empty($social_auth_info) && !empty($social_auth_info->twitter_email))
                                            <div class="col-md-2 col-sm-2 col-xs-4 text-right">
                                                <a class="btn btn-rounded btn-danger btn-sm" id='twitter_id' onclick="deleteSocialID('twitter')">Delete</a>
                                                <i class="fa fa-trash-o fa-2x text-danger visible-xs"></i>
                                            </div>
                                        @else
                                            <div class="col-md-2 col-sm-2 col-xs-4 text-right">
                                                <button type="button" class="btn btn-info btn-sm btn-rounded auth_btn" onclick="window.location.href='{{ route("social.redirect",['provider'=>"twitter",'type'=>"authorize"]) }}'; $('.auth_btn').prop('disabled', true); return false;">Authorize</button>
                                            </div> 
                                        @endif 
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tb-address" class="tab-pane {{ $active_tab=="address" ? 'active' : '' }}">
                        <div class="showResponseMesssage"></div> 
                        <form id="editProfileAddressFrm" name="editProfileAddressFrm" class=""> 
                            <input type="hidden" name="action" value="editProfileAddress"/>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group m-b-20 {{ $errors->has('street') ? 'has-error' : '' }}">
                                                <label class="font-light font-13">Street <span class="text-danger">*</span></label>
                                                <input type="text" name="street" class="form-control" placeholder="Street" data-notempty="notempty" value="{{ $profile_info->street }}" required="">
                                                <div class="help-block with-errors"></div>
                                                <div class="form-control-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group m-b-20 {{ $errors->has('city') ? 'has-error' : '' }}">
                                                <label class="font-light font-13">City <span class="text-danger">*</span></label>
                                                <input type="text" name="city" class="form-control" placeholder="City" data-notempty="notempty" value="{{ $profile_info->city }}" required="" />
                                                <div class="help-block with-errors"></div>
                                                <div class="form-control-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group m-b-20 {{ $errors->has('zip_code') ? 'has-error' : '' }}">
                                                <label class="font-light font-13">Zip Code <span class="text-danger">*</span></label>
                                                <input type="text" name="zip_code" class="form-control" placeholder="Zip Code" data-nowhitespace="nowhitespace" value="{{ $profile_info->zip_code }}" required="">
                                                <div class="help-block with-errors"></div>
                                                <div class="form-control-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group m-b-20 {{ $errors->has('country') ? 'has-error' : '' }}">
                                                <label class="font-light font-13">Country <span class="text-danger">*</span></label>
                                                <select name="country" class="form-control" required="">
                                                    <option value="">-- Select --</option>
                                                    @foreach($countries as $country)
                                                        <option @if($country->country_id == $profile_info->country) selected="selected" @endif value="{{ $country->country_id }}" >{{ $country->country_name }}</option>
                                                    @endforeach
                                                </select> 
                                                <div class="help-block with-errors">
                                                    @if ($errors->has('address_country')){{ $errors->first('address_country') }}@endif
                                                </div>
                                                <div class="form-control-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 m-t-10"> 
                                    <button type="submit" name="editProfileAddress-btn" id="editProfileAddress-btn" class="btn btn-info btn-lg font-size-14">Save Address</button>
                                </div>
                            </div>
                        </form> 
                    </div>
                    <div id="tb-social-details" class="tab-pane {{ $active_tab=="social_details" ? 'active' : '' }}">
                        <div class="showResponseMesssage"></div>
                        <form id="editSocialDetailFrm" name="editSocialDetailFrm">
                            <input type="hidden" name="action" value="editSocialDetail"/> 
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group {{ $errors->has('skype_id') ? ' has-error' : '' }}">
                                                <label class="font-light font-13">Skype ID <span class="text-danger">*</span></label>
                                                <input type="text" name="skype_id" class="form-control" placeholder="Skype ID" data-nowhitespace="nowhitespace" value="{{$profile_info->skype_id}}" required>
                                                <div class="help-block with-errors"></div>
                                                <div class="form-control-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group {{ $errors->has('facebook_url') ? ' has-error' : '' }}">
                                                <label class="font-light font-13">Facebook URL</label> <a data-html="true" data-toggle="tooltip" title="Log into Facebook, click 'on your name', located in the upper right part of the screen.<br/><br/>Copy the Facebook Profile URL in the address bar of your browser." data-placement="right" data-container="body"><i class="fa fa-question-circle text-muted"></i></a>
                                                <input type="text" name="facebook_url" class="form-control" placeholder="Facebook URL" data-nowhitespace="nowhitespace" value="{{$profile_info->facebook_url}}" />
                                                <div class="help-block with-errors"></div>
                                                <div class="form-control-feedback"></div>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group {{ $errors->has('twitter_url') ? ' has-error' : '' }}">
                                                <label class="font-light font-13">Twitter URL</label> <a data-html="true" data-toggle="tooltip" title="Log in to Twitter, click 'view my profile page', located near your name and profile picture.<br/><br/>Copy the Twitter Profile URL in the address bar of your browser." data-placement="right" data-container="body"><i class="fa fa-question-circle text-muted"></i></a>
                                                <input type="text" name="twitter_url" class="form-control" placeholder="Twitter URL" data-nowhitespace="nowhitespace" value="{{$profile_info->twitter_url}}">
                                                <div class="help-block with-errors"></div>
                                                <div class="form-control-feedback"></div>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 m-t-10">
                                    <div class="form-group">
                                        <button type="submit" name="editSocialDetail-btn" id="editSocialDetail-btn" class="btn btn-info btn-lg font-size-14">Save Social Details</button>
                                    </div>
                                </div> 
                            </div>
                        </form>
                    </div>
                </div>
            </div> 
            @endif
        </div>
    </div> 
</div> 
@endsection
@section('scripts')
<script>
$(document).ready(function() {
    var formselection = "#editProfileFrm,#changePasswordFrm,#editProfileAddressFrm,#editSocialDetailFrm";
    ajaxFormValidator(formselection,updateProfile);
    $('[data-toggle="tooltip"]').tooltip();
});
function updateProfile(element,e){
    e.preventDefault();
    var formId = $(element).attr("id"); 
    var formID = $("#"+formId).serialize();
    var btn_ele = $(element).find('button[type="submit"]');
    var btn_name = $(btn_ele).html();
    btnLoader(btn_ele);
    $.ajax({
        type: 'post',
        url: '/update-profile', 
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: formID,
        dataType: 'json',
        success: function(response) { 
            btnLoader(btn_ele,'hide',btn_name);
            /* remove success box after display success message */
            var $has_success = $("#"+formId).find('.form-group'); 
            $has_success.removeClass('has-success');

            var $right_tick = $("#"+formId).find('.form-control-feedback');
            $right_tick.removeClass('form-control-feedback fa fa-check'); 
            if(response.type=='success')
            {   
                if(formId == "changePasswordFrm"){
                    $("#"+formId).trigger("reset");
                }
                var Html = '<div class="alert swl-alert-success"><ul><li>'+ response.message+ '</li></ul></div>'; 
                notifyResponseTimerAlert(Html,"success","Success"); 
            }else{
                var Html = '<div class="alert swl-alert-danger"><ul>'; 
                $.each( response.message, function( key, value ) {
                    Html += '<li>' + value+ '</li>';  
                });
                Html += '</ul></div>';  
                notifyResponseTimerAlert(Html,"error","Error");
            }
        }
    }); 
} 
function store_user_notification_status(obj,status,ID){
    var value = 0;
    if(obj.checked) {
        value = 1;
    } 
    $.ajax({
        type: 'post',
        url: '/update-profile',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data:  { 
            value: value,
            status: status,
            notification_type_id: ID, 
            action: 'storeUserNotificationStatus'
        },
        success: function(response) { 
            /* Success */
        }
    });
}
/* Delete Social ID*/
function deleteSocialID(social_id){
    swal({   
       title: "Are you sure?",   
       text: "Delete ".social_id,   
       type: "warning",   
       closeOnConfirm: true,   
       confirmButtonColor: "#1faae6",   
       confirmButtonText: "Yes", 
       cancelButtonText: "No",
       showCancelButton: true
    }, function(){ 
        $.ajax({
            type: 'post',
            url: '/update-profile',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: { 
                'social_id': social_id, 
                'action': 'deleteSocialID'
            },
            dataType: "json",
            success: function(response) { 
                if(response.type=='success')
                {
                    var Html = '<div class="alert alert-success"><ul><li>'+ response.message+ '</li></ul></div>';  
                    notifyResponseTimerAlert(Html,"success","Success");
                    setTimeout(function(){
                        window.location.href= "{{ route('edit.profile','social') }}";
                    }, 3000);
                }else{ 
                    var Html = '<div class="alert alert-danger"><ul>'; 
                    $.each( response.message, function( key, value ) { 
                        Html += '<li>' + value+ '</li>';  
                    });
                    Html += '</ul></div>';
                    notifyResponseTimerAlert(Html,"error","Error");
                }
            }
        }); 
    });
}

/** Profile picture cropping and update IT **/
var eyeCandy = $('#crop-container');
var croppedOptions = {
    rotateControls: false,
    cropUrl: '{{ route("crop") }}', 
    cropData: { 
        'width' : eyeCandy.width(),
        'height': eyeCandy.height()
    },
    modal: true,
    customUploadButtonId : 'editProfilePhoto',
    loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div>',
    processInline:true,
    onBeforeImgUpload: function (){
        var that = this;
        if ( /\.({{$upload_image_ext}})$/i.test(that.form.find("input[type=file]")[0].files[0].name) === false ) { 
            notifyResponseTimerAlert("Profile Picture must be a file of type: {{$allow_image_ext}}.","error","Error"); 
            $("#editProfilePhoto .loader").remove();
            setTimeout( function(){ that.reset(); },100);
            return;
        } 
        var FileSize = Math.round(that.form.find("input[type=file]")[0].files[0].size / 1024);
        if (FileSize > {{$allowed_img_size}}) {
            notifyResponseTimerAlert("Profile Picture must be below {{$upload_img_size}} MB.","error","Error"); 
            $("#editProfilePhoto .loader").remove();
            var is_chrome = /chrome/i.test( navigator.userAgent );
            if(is_chrome)
            {
                setTimeout( function(){ that.reset(); },50);
            }else{
                $(".cropControlReset")[0].click();
            }
            return;
        } 
    },
    onAfterImgUpload: function (){
        $("#editProfilePhoto .loader").remove();
    },
    onError : function(){
        $("#editProfilePhoto .loader").remove();
    },
    onAfterImgCrop : function () {
        var that = this;
        $(".avatar-md").attr("src","{{url('assets/images/loader.gif')}}"); 
        $.ajax({
            url: '{{ route("avatar") }}',
            type: 'get',
            dataType: 'json', 
            success: function (response) { 
                if(response.type=='success')
                {  
                    var Html = '<div class="alert swl-alert-success"><ul><li>'+ response.message+ '</li></ul></div>'; 
                    notifyResponseTimerAlert(Html,"success","Success");
                    setTimeout(function(){ 
                        $(".avatar-md").attr("src",response.imgsrc);
                    }, 100); 
                    /*$( ".showResponseMesssage" ).html(Html);*/
                }
                else{ 
                    var Html = '<div class="alert swl-alert-danger"><ul>'; 
                    $.each( response.message, function( key, value ) { 
                        Html += '<li>' + value+ '</li>';  
                    });
                    Html += '</ul></div>';
                    notifyResponseTimerAlert(Html,"error","Error");
                    /*$( ".showResponseMesssage" ).html(Html); */
                }
                that.hideLoader();
                setTimeout( function(){ that.reset(); },100)
            },
            complete: function () {
            }
        });
    } 
};
var cropperBox = new Croppic('crop-container', croppedOptions);
/** Load Notification Switch **/
</script> 
@stop