@extends('admin.layouts.app')
@php

$mode = "Add";
$action = route('forum.cat.add');

if(!empty($slug)){
$mode = "Edit";
$action = route('forum.cat.edit',$slug);
$imgurl = empty(get_url('forum-photo/'.$forum->id.'/crop/'.$forum->banner_image)) 
                ? url("assets/images/noimage.png") 
                : get_url('forum-photo/'.$forum->id.'/crop/'.$forum->banner_image);
}else{
    $slug = 0;
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
            <h4 class="page-title">Admin Forums</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                @if(check_route_access('forum.cat.list'))
                <li><a href="{{ route('forum.cat.list') }}">Forum Category</a></li>
                @endif
                <li class="active">Add New Forum Category</li>
            </ol>
        </div>
    </div>
    <div class="row"> 
        <div class="col-md-12 col-xs-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">{{ $mode }} Forum Category</h3>
                        <!--<p class="text-muted m-b-30">Add New Admin</p>-->
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="pull-right">
                            @if(check_route_access('forum.cat.list'))
                            <a href="{{ route('forum.cat.list') }}" class="btn btn-block btn-info"><i class="fa fa-list"></i> Forum Category</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="{{ ($mode == "Edit")? 'col-md-6' : 'col-md-12'}}">
                        @include('admin.includes.status')
                        <form method="post" action="{{ $action }}" class="form-horizontal form-validator" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $forum->id }}" />
                            <div class="form-group {{ $errors->has('forum_title') ? 'has-error' : '' }}">
                                <label class="col-md-12">Title <span class="text-danger">*</span> </label>
                                <div class="col-md-12">
                                    <input type="text" name="forum_title" id="forum_title" placeholder="Forum Title" class="form-control" required value="{{ ($mode == "Edit") ? $forum->title : old('forum_title') }}">
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('forum_title')){{ $errors->first('forum_title') }}@endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                <label class="col-md-12">Description <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <textarea rows="5" name="description" id="description" placeholder="Forum Description" class="form-control" required>{!! ($mode == "Edit") ? $forum->description : old('description') !!}</textarea>
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('description')){{ $errors->first('description') }}@endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('keyword') ? 'has-error' : '' }}">
                                <label class="col-md-12">Keyword <span class="text-danger">* (Enter keyword then press enter for new keyword.)</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="keyword" id="keyword" placeholder="Forum Keyword" class="form-control" required value="{{ ($mode == "Edit") ? $forum->keyword : old('keyword') }}">
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('keyword')){{ $errors->first('keyword') }}@endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Status</label>
                                <div class="col-md-12">
                                    <div class="radio-list">
                                        <label><input type="radio" name="status" value="1" checked {{ is_checked($forum->status,'1') }}> Active </label>
                                        <label><input type="radio" name="status" value="0" {{ is_checked($forum->status,'0') }}> De-Active </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info">Save Forum</button>
                                    <button type="reset" class="btn btn-danger" id="reset_form">Reset</button>
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
                                                <button type="button" id="editCategoryImage" class="btn btn-info">Upload Image</button>
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
$(document).ready(function () {
    $('#keyword').tokenfield();
     /** Profile picture cropping and update IT **/
    var eyeCandy = $('#crop-container');
    var croppedOptions = {
        rotateControls: false,
        cropUrl: '{{ route("forum.cat.upload.image") }}', 
        cropData: { 
            'width' : eyeCandy.width(),
            'height': eyeCandy.height(),
            "_token": "{{ csrf_token() }}",
            "slug" : "{{$slug}}"
        },
        modal: true,
        customUploadButtonId : 'editCategoryImage',
        loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div>',
        processInline:true,
        onBeforeImgUpload: function (){
            var that = this;
            if ( /\.({{$upload_image_ext}})$/i.test(that.form.find("input[type=file]")[0].files[0].name) === false ) { 
                
                $('html, body').css('overflowY', 'hidden'); 
                notifyResponseTimerAlert("Image must be a file of type: {{$allow_image_ext}}.","error","Error"); 
                $("#editCategoryImage .loader").remove();
                setTimeout( function(){ that.reset(); },100);
                return;
            } 
            var FileSize = Math.round(that.form.find("input[type=file]")[0].files[0].size / 1024);
            if (FileSize > {{$allowed_img_size}}) {
                
                $('html, body').css('overflowY', 'hidden'); 
                notifyResponseTimerAlert("Image must be below {{$upload_img_size}} MB.","error","Error"); 
                $("#editCategoryImage .loader").remove();
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
            $("#editCategoryImage .loader").remove();
        },
        onError : function(){
            $("#editCategoryImage .loader").remove();
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
$("#reset_form").click(function(){
<?php if($mode == "Add"){ ?>
    $('#keyword').tokenfield('setTokens', []);
<?php } ?>
});
</script>
@endsection
