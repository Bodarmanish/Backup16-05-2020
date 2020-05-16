@extends('user.layouts.app')

@section('content')  
@php
    $categoryList = '';
    $mode = (!empty($data['topic_id'])) ? "Edit" : "Add";
    @$topic_id = @$data['topic_id'];
    if($mode == 'Edit')
    {
        $topic_detail = $data['topic_detail'];
    }
    
@endphp
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="#" class="text-info">Home</a></li>
            <li><a href="{{ url("categories/") }}" class="text-info">Forum</a></li>
            <li class="active capitalize"> {{$mode == 'Add' ? 'Post New Topic' : 'Edit Topic'}}</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <div class="row m-b-20">
            <div class="col-xs-12">
                <h2 class="page-title capitalize no-margin"><b>{{$mode == 'Add' ? 'Post New Topic' : 'Edit Topic'}}</b></h2>
            </div>
        </div>
        <div class="well">  
            <div id="showResponseMesssage">@include('user.includes.status')</div>
            <form id="create_topic" name="create_topic" enctype="multipart/form-data" method="post">
                <input type="hidden" name="action" value="storeTopicDetail"/>
                <input type="hidden" name="mode" value="{{$mode}}"/>
                <input type="hidden" name="topic_id" value="{{$topic_id }}"/>
                {{ csrf_field() }}
                <div class="form-body custom-form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group pos_relative {{ $errors->has('category_id') ? ' has-error' : '' }}">
                                <label for="category_id" class="control-label">This post is mostly about (Category) <span class="text-danger">*</span></label> 
                                <select id="category_id" name="category_id" class="form-control" data-placeholder="Choose a Category" tabindex="1" onchange="return selectSubCategory(this.value)">
                                    <option value="">-- Select Category --</option>
                                    @if(isset($data['category']))   
                                        @foreach($data['category'] as $cat)
                                        <option value="{{ $cat->id }}" {{ is_selected(@$topic_detail->main_cat,$cat->id) }} {{ is_selected(old('category_id'),$cat->id) }}>{{ $cat->title }}</option>
                                        @endforeach
                                    @endif 
                                </select>                      
                                <div class="help-block with-errors">
                                    @if($errors->has('category')){{ $errors->first('category') }}@endif
                                </div>
                                <div class="form-control-feedback"></div>
                            </div>
                            @if($mode == 'Edit')
                            <div class="form-group pos_relative " id="forumsubcat">
                                <label for="sub_category_id" class="control-label">Select Sub Category<span class="text-danger">*</span></label> 
                                <select id="sub_category_id" name="sub_category_id" class="form-control" data-placeholder="Choose a Sub Category" tabindex="1" onchange="return selectForumTagList(this.value)">
                                    <option value="">-- Select Sub Category --</option>
                                        @if(isset($topic_detail['sub_cat']))   
                                        @foreach($topic_detail['sub_cat'] as $cat)
                                            <option value="{{ $cat->id }}" {{ is_selected($topic_detail->forum_category_id,$cat->id) }} {{ is_selected(old('category_id'),$cat->id) }}>{{ $cat->title }}</option>
                                        @endforeach
                                    @endif 
                                </select>                      
                                <div class="help-block with-errors">
                                </div>
                                <div class="form-control-feedback"></div>
                            </div>
                            <div class="form-group pos_relative" id="forumtag">
                                <label for="forum_tag_list" class="control-label">Choose Forum Tags (You can select multiple tag using ctrl+select)</label> 
                                <select id="forum_tag_list" name="forum_tag_list[]" class="form-control" data-placeholder="Choose a Forum Tag List" tabindex="1" multiple="">
                                    <option value="" disabled="" selected>Choose your tag</option>
                                    @foreach($topic_detail['cat_tags'] as $tag)
                                        @php
                                            $selected = "";
                                            if(!empty($topic_detail['tags']))
                                            {
                                               $selected = in_array($tag['id'], custom_explode($topic_detail['tags'])) ? "selected" : "not";
                                            }
                                        @endphp
                                        <option value="{{ $tag->id }}" {{$selected}}>{{ $tag->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="form-group pos_relative {{ $errors->has('sub_category_id') ? ' has-error' : '' }}" id="forumsubcat"> 
                            </div>
                            <div class="form-group pos_relative" id="forumtag"> 
                            </div>
                            <div class="form-group pos_relative {{ $errors->has('title') ? ' has-error' : '' }}">
                                <label class="control-label">Post Title <span class="text-danger">*</span></label>
                                <input type="text" id="title" name="title" value="{{ ($mode == "Edit") ? $topic_detail->title : old('title') }}" class="form-control" placeholder="Enter title here..."> 
                                <div class="help-block with-errors">
                                    @if($errors->has('title')){{ $errors->first('title') }}@endif
                                </div>
                                <div class="form-control-feedback"></div>
                            </div>
                            <div class="form-group pos_relative {{ $errors->has('ft_desc') ? ' has-error' : '' }}">
                                <label class="control-label">Description <span class="text-danger">*</span></label>
                                <textarea id="ft_desc" name="ft_desc" class="form-control noresize" rows="10" placeholder="Enter Description...">{!! ($mode == "Edit") ? $topic_detail->description : old('ft_desc') !!}</textarea> 
                                <div class="help-block with-errors">
                                    @if($errors->has('ft_desc')){{ $errors->first('ft_desc') }}@endif
                                </div>
                                <div class="form-control-feedback"></div>
                            </div>
                            <div class="form-group pos_relative {{ $errors->has('notify_me_of_replies') ? ' has-error' : '' }}">
                                <label class="control-label">Notify Me of Replies <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="radio radio-info">
                                            <input type="radio" name="notify_me_of_replies" id="yes" value="1" {{ ($mode == "Edit") ? is_checked($topic_detail->notify_me_of_replies,'1') : "checked" }} required="" />
                                            <label for="yes"> Yes</label>
                                        </div> 
                                    </div>
                                    <div class="col-sm-4">  
                                        <div class="radio radio-info">
                                            <input type="radio" name="notify_me_of_replies" id="no" value="0" {{ ($mode == "Edit") ? is_checked($topic_detail->notify_me_of_replies,'0') : "" }} />
                                            <label for="no"> No</label>
                                        </div> 
                                    </div>
                                </div>
                                <div class="help-block with-errors">
                                    @if($errors->has('notify_me_of_replies')){{ $errors->first('notify_me_of_replies') }}@endif
                                </div>
                                <div class="form-control-feedback"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions m-t-10">
                    <button type="submit" class="btn btn-lg btn-info" id="btnsbt">{{$mode}} Topic</button>
                    <!-- <a href="javascript:void(0);" class="text-danger m-l-10 cancel_event"  id="delete_topic" onclick="swal_dlt_post_data(this.id, 'Post');">Delete</a>-->
                </div>
            </form>
            <div class="p-10 p-l-0">
                <span class="text-muted">Please ensure this post adheres to our </span><span class="text-info"> community guidelines</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        @include('user/includes/forumSidebar')
    </div>
</div>
@endsection 
@section('scripts')
<script type="text/javascript"> 
$(document).ready(function() {  
    ajaxFormValidator("#create_topic",createTopic);
    
    function createTopic(element,e){
        e.preventDefault();
        var formData = new FormData(element);
        $.ajax({
            type: 'post',
            url: "{{route('forumajaxrequest')}}", 
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.type=='success')
                {
                    $( "#btnsbt" ).prop( "disabled", true );
                    var Html = '<div class="alert alert-success"><ul><li>'+ response.message+ '</li></ul></div>';  
                    $( "#showResponseMesssage" ).html(Html);
                    setTimeout(function(){
                        window.location.href= response.redirectURL;
                    }, 1000);
                }                       
                else{ 
                    var messages = response.message;
                    serverValidator(element,messages);
                }
            }
        }); 
    }  
}); 
</script>
@stop  