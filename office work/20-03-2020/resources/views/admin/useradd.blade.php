@extends('admin.layouts.app')
@php
$mode = "Add";
$action = route('user.add');
 
if(!empty($id)){
    $mode = "Edit";
    $action = "";
}

$allow_image_ext = collect(config('common.allow_image_ext'))->implode(', ');
$upload_image_ext = collect(config('common.allow_image_ext'))->implode('|');
$upload_img_size = config("common.upload_img_size");
$allowed_img_size = config("common.upload_img_size")*1024;
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
                @if(check_route_access('user.list'))
                    <li><a href="{{ route('user.list') }}">User</a></li>
                @endif
                <li class="active">{{ $mode }} User</li>
            </ol>
        </div>
    </div>
    <div class="row"> 
        <div class="col-md-12 col-xs-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">{{ $mode }} User</h3>
                        <!--<p class="text-muted m-b-30">Add New Admin</p>-->
                    </div>
                    @if(check_route_access('user.list'))
                    <div class="col-md-6 col-xs-12">
                        <div class="row">
                            <div class="col-md-3 pull-right">
                                <a href="{{ route('user.list') }}" class="btn btn-block btn-info"><i class="fa fa-arrow-left"></i>  Back</a>
                            </div>
                            <div class="col-md-3 pull-right">
                                <a href="{{ route('user.list') }}" class="btn btn-block btn-info"><i class="fa fa-list"></i> Users</a>
                            </div> 
                        </div>
                    </div>
                    @endif
                </div>

                <div class="row">
                    <div class="{{ ($mode == "Edit")? 'col-md-6' : 'col-md-12'}}">
                        @include('admin.includes.status')
                        <form method="post" action="" class="form-horizontal form-validator" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
                                <label class="col-md-12">First Name <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="first_name" id="first_name" placeholder="First Name" class="form-control " required value="{{ ($mode == "Edit") ? $users->first_name : old('first_name') }}" >
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('first_name')){{ $errors->first('first_name') }}@endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Middle Name</label>
                                <div class="col-md-12">
                                    <input type="text" name="middle_name" id="middle_name" placeholder="Middle Name" class="form-control " value="{{ ($mode == "Edit") ? $users->middle_name : old('middle_name') }}">
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
                                <label class="col-md-12">Last Name <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="last_name" id="last_name" placeholder="Last Name" class="form-control " required value="{{ ($mode == "Edit") ? $users->last_name : old('last_name') }}">
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('last_name')){{ $errors->first('last_name') }}@endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                <label class="col-md-12">Email <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="email"  name="email" id="email" placeholder="Email Address" class="form-control " required value="{{ ($mode == "Edit") ? $users->email : old('email') }}" @if($mode == "Edit") disabled="disabled" @endif>
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('email')){{ $errors->first('email') }}@endif
                                    </div>
                                </div>
                            </div>
                            @if($mode == "Add")
                            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                <label class="col-md-12">Password <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="password" name="password" id="password" placeholder="Password" class="form-control " required value="{{ old('password') }}">
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('password')){{ $errors->first('password') }}@endif
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="form-group {{ $errors->has('phone_number') ? 'has-error' : '' }}">
                                <label class="col-md-12">Phone Number <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="phone_number" id="phone_number" placeholder="Phone Number" class="form-control " required value="{{ ($mode == "Edit") ? $users->phone_number : old('phone_number') }}" >
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('phone_number')){{ $errors->first('phone_number') }}@endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Secondary Email</label>
                                <div class="col-md-12">
                                    <input type="email" name="secondary_email" id="secondary_email" placeholder="Secondary Email" class="form-control "value="{{ ($mode == "Edit") ? $users->secondary_email : old('secondary_email') }}" >
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('timezone') ? 'has-error' : '' }}">
                                <label class="col-md-12">Timezone <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <select name="timezone" id="timezone" class="form-control" required="">
                                        <option value="">-- Select Timezone --</option>
                                            @foreach($timezones as $zone)
                                                <option value="{{ $zone->zone_id }}" @if($zone->zone_id == $users->timezone || $zone->zone_id == old('timezone')) selected="selected" @endif >{{ $zone->zone_label }}</option>                                                                     
                                            @endforeach
                                    </select> 
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('timezone')){{ $errors->first('timezone') }}@endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('street') ? 'has-error' : '' }}">
                                <label class="col-md-12">Street <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="street" id="street" placeholder="Street" class="form-control" required value="{{ ($mode == "Edit") ? $users->street : old('street') }}" >
                                <div class="clearfix"></div>
                                <div class="help-block with-errors">
                                    @if ($errors->has('street')){{ $errors->first('street') }}@endif
                                </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('city') ? 'has-error' : '' }}">
                                <label class="col-md-12">City <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="city" id="city" placeholder="City" class="form-control" required value="{{ ($mode == "Edit") ? $users->city : old('city') }}" >
                                <div class="clearfix"></div>
                                <div class="help-block with-errors">
                                    @if ($errors->has('city')){{ $errors->first('city') }}@endif
                                </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('zip_code') ? 'has-error' : '' }}">
                                <label class="col-md-12">Zip Code <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="zip_code" id="zip_code" placeholder="Zip Code" class="form-control" required value="{{ ($mode == "Edit") ? $users->zip_code : old('zip_code') }}" >
                                <div class="clearfix"></div>
                                <div class="help-block with-errors">
                                    @if ($errors->has('zip_code')){{ $errors->first('zip_code') }}@endif
                                </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('country') ? 'has-error' : '' }}">
                                <label class="col-md-12">Country <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <select name="country" id="country" class="form-control" required="" >
                                        <option value="">-- Select --</option>
                                        @foreach($countries as $country)
                                            <option @if($country->country_id == $users->country || $country->country_id == old('country')) selected="selected" @endif value="{{ $country->country_id }}" >{{ $country->country_name }}</option>
                                        @endforeach
                                    </select> 
                                <div class="clearfix"></div>
                                <div class="help-block with-errors">
                                    @if ($errors->has('country')){{ $errors->first('country') }}@endif
                                </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('skype_id') ? 'has-error' : '' }}">
                                <label class="col-md-12">Skype ID <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="skype_id" id="skype_id" placeholder="Skype ID" class="form-control" required value="{{ ($mode == "Edit") ? $users->skype_id : old('skype_id') }}" >
                                <div class="clearfix"></div>
                                <div class="help-block with-errors">
                                    @if ($errors->has('skype_id')){{ $errors->first('skype_id') }}@endif
                                </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('facebook_url') ? 'has-error' : '' }}">
                                <label class="col-md-12">Facebook URL </label>
                                <div class="col-md-12">
                                    <input type="text" name="facebook_url" id="facebook_url" placeholder="Facebook URL" class="form-control" value="{{ ($mode == "Edit") ? $users->facebook_url : old('facebook_url') }}" >
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('twitter_url') ? 'has-error' : '' }}">
                                <label class="col-md-12">Twitter URL </label>
                                <div class="col-md-12">
                                    <input type="text" name="twitter_url" id="twitter_url" placeholder="Twitter URL" class="form-control" value="{{ ($mode == "Edit") ? $users->twitter_url : old('twitter_url') }}" >
                                </div>
                            </div>
                            @if($mode == "Add")
                            <div class="form-group">
                                <label class="col-md-12">Is Email Verified? <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <div class="radio-list">
                                        <label><input type="radio" name="email_verified" value="1" required checked {{ is_checked(old('email_verified'),'1') }}> Yes </label>
                                        <label><input type="radio" name="email_verified" value="0" required {{ is_checked(old('email_verified'),'0') }}> No </label>
                                        <div class="clearfix"></div>
                                        <div class="help-block with-errors">
                                            @if ($errors->has('email_verified')){{ $errors->first('email_verified') }}@endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info">Save User</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @if($mode == "Edit")
                        <div class="col-md-6">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="dpprofileimg">
                                                @php
                                                if($users->profile_photo != "")
                                                    $img_url = get_url("user-avatar/".$id."/crop/".$users->profile_photo);
                                                else
                                                    $img_url = url("assets/images/noimage.png");
                                                @endphp
                                                <img src="{{  $img_url  }}" class="img-responsive thumbnail editprofilepicture avatar-md"> 
                                            </div> 
                                            <div id="fileHelp" class="form-text text-muted">The image file should be JPG, PNG or JPEG format. Image size must be under 2MB.</div>
                                                <button type="button" id="editTestimonialImage" class="btn btn-info">Upload Image</button>
                                        </div>
                                    </div>
                                <div id="crop-container" style="display:none"></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
$(document).ready(function() {
        
      
        /** Profile picture cropping and update IT **/
    var eyeCandy = $('#crop-container');
    var croppedOptions = {
        rotateControls: false,
        cropUrl: '{{ route("user.upload.image") }}', 
        cropData: { 
            'width' : eyeCandy.width(),
            'height': eyeCandy.height(),
            "_token": "{{ csrf_token() }}",
            "id" : "{{ @$id }}"
        },
        modal: true,
        customUploadButtonId : 'editTestimonialImage',
        loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div>',
        processInline:true,
        onBeforeImgUpload: function (){
            var that = this;
            if ( /\.({{$upload_image_ext}})$/i.test(that.form.find("input[type=file]")[0].files[0].name) === false ) { 
                
                $('html, body').css('overflowY', 'hidden'); 
                notifyAlert("Image must be a file of type: {{$allow_image_ext}}.","error","Error"); 
                $("#editTestimonialImage .loader").remove();
                setTimeout( function(){ that.reset(); },100);
                return;
            } 
            var FileSize = Math.round(that.form.find("input[type=file]")[0].files[0].size / 1024);
            if (FileSize > {{$allowed_img_size}}) {
                
                $('html, body').css('overflowY', 'hidden'); 
                notifyAlert("Image must be below {{$upload_img_size}} MB.","error","Error"); 
                $("#editTestimonialImage .loader").remove();
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
            $("#editTestimonialImage .loader").remove();
        },
        onError : function(){
            $("#editTestimonialImage .loader").remove();
        },
        onAfterImgCrop : function (Response) {
        
            $('html, body').css('overflowY', 'hidden'); 
            $(".avatar-md").attr("src",Response.url);
            var that = this;
            that.hideLoader();
            notifyAlert(response.message,response.type,"Success");
            setTimeout(function(){location.reload(true);}, 5000);
        } 
};
var cropperBox = new Croppic('crop-container', croppedOptions);

});
</script>
@endsection
