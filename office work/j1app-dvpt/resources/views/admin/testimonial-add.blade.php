@extends('admin.layouts.app')
@php

$mode = (!empty($id)) ? "Edit" : "Add";
$action = route('testimonials.add');

if(!empty($id)){
    $mode = "Edit";
    $action = route('testimonials.edit',$id);
    $imgurl = empty(get_url('testimonial/'.$testimonial->id.'/crop/'.$testimonial->image)) 
                ? url("assets/images/noimage.png") 
                : get_url('testimonial/'.$testimonial->id.'/crop/'.$testimonial->image);
}else{
    $id = 0;
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
            <h4 class="page-title">TESTIMONIAL Manager</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('role.list') }}">Testimonial Manager</a></li>
                <li class="active">Add New Testimonial</li>
            </ol>
        </div>
    </div>
    <div class="row"> 
        <div class="col-md-12 col-xs-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">{{ $mode }} Testimonial</h3>
                        <!--<p class="text-muted m-b-30">Add New Admin</p>-->
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="pull-right">
                            <a href="{{ route('testimonials.list') }}" class="btn btn-block btn-info"><i class="fa fa-list"></i> Testimonials</a>
                        </div>
                    </div>
                </div>
                @include('admin.includes.status')
                <div class="row">
                    <div class="{{ ($mode == "Edit")? 'col-md-6' : 'col-md-12'}}">
                        <form method="post" action="{{ $action }}" class="form-horizontal form-validator">
                            {{ csrf_field() }}
                            <input type="hidden" name="{{ $testimonial->id }}" />
                            <div class="form-group">
                                <label class="col-md-12">Title <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="title" id="title" placeholder="Title" class="form-control" required value="{{ ($mode == "Edit") ? $testimonial->title : old('title') }}">
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Client Name </label>
                                <div class="col-md-12">
                                    <input type="text" name="client_name" id="client_name" placeholder="Client" class="form-control" value="{{ ($mode == "Edit") ? $testimonial->client_name : old('client_name') }}">
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Client Country</label>
                                <div class="col-md-12">
                                    <select name="client_country" id="client_country" class="form-control">
                                        <option value="">-- Select Client Country --</option>
                                        @if (!empty($countries))
                                            @foreach($countries as $country)
                                                <option  value="{{$country->country_id}}" {{ ( $testimonial->client_country == $country->country_id || old('client_country') ==  $country->country_id) ? 'selected' : '' }} >{{ $country->country_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Description</label>
                                <div class="col-md-12">
                                    <textarea rows="3" name="description" id="description" placeholder="Testimonial Decription" class="form-control">{!! ($mode == "Edit") ? $testimonial->description : old('description') !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Status</label>
                                <div class="col-md-12">
                                    <div class="radio-list">
                                        <label><input type="radio" name="status" value="1" checked {{ is_checked($testimonial->status,'1') }}> Active </label>
                                        <label><input type="radio" name="status" value="0" {{ is_checked($testimonial->status,'0') }}> De-Active </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info">Save Testimonial</button>
                                    <button type="reset" class="btn btn-danger">Reset</button>
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
                                                <img src="{{$imgurl}}" class="img-responsive thumbnail editprofilepicture avatar-md"> 
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
 <script>
    $(document).ready(function() {
        
      
        /** Profile picture cropping and update IT **/
    var eyeCandy = $('#crop-container');
    var croppedOptions = {
        rotateControls: false,
        cropUrl: '{{ route("testimonials.upload.image") }}', 
        cropData: { 
            'width' : eyeCandy.width(),
            'height': eyeCandy.height(),
            "_token": "{{ csrf_token() }}",
            "id" : "{{$id}}"
        },
        modal: true,
        customUploadButtonId : 'editTestimonialImage',
        loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div>',
        processInline:true,
        onBeforeImgUpload: function (){
            var that = this;
            if ( /\.({{$upload_image_ext}})$/i.test(that.form.find("input[type=file]")[0].files[0].name) === false ) { 
                
                $('html, body').css('overflowY', 'hidden'); 
                notifyResponseTimerAlert("Image must be a file of type: {{$allow_image_ext}}.","error","Error"); 
                $("#editTestimonialImage .loader").remove();
                setTimeout( function(){ that.reset(); },100);
                return;
            } 
            var FileSize = Math.round(that.form.find("input[type=file]")[0].files[0].size / 1024);
            if (FileSize > {{$allowed_img_size}}) {
                
                $('html, body').css('overflowY', 'hidden'); 
                notifyResponseTimerAlert("Image must be below {{$upload_img_size}} MB.","error","Error"); 
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
            var Html = '<div class="alert swl-alert-success"><ul><li>'+ Response.message+ '</li></ul></div>'; 
            notifyResponseTimerAlert(Html,"success","Success");

        } 
};
var cropperBox = new Croppic('crop-container', croppedOptions);

        });
 </script>
@endsection

