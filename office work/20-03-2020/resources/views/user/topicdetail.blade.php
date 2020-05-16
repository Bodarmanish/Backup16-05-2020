@extends('user.layouts.app')
@php
$user = Auth::user(); 

    $user_img_url = empty(get_url("user-avatar/{$data['topic_detail']->id}/200/{$data['topic_detail']->profile_photo}")) 
                    ? url("assets/images/noavatar.png") 
                    : get_url("user-avatar/{$data['topic_detail']->id}/200/{$data['topic_detail']->profile_photo}");
    if(Auth::check()){
        $user_dp_url = empty(get_url("user-avatar/{$user->id}/200/{$user->profile_photo}")) 
                    ? url("assets/images/noavatar.png") 
                    : get_url("user-avatar/{$user->id}/200/{$user->profile_photo}");
    } 
@endphp

@section('content') 
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="#" class="text-info">Home</a></li>
            <li><a href="{{ url('categories') }}" class="text-info">Forum</a></li>
            <li class="active capitalize"> {{$data['topic_detail']->ft_title}} </li>
        </ol>
    </div>
</div> 
<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <div class="row m-b-20">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h2 class="page-title capitalize no-margin"><b>{{$data['topic_detail']->ft_title}}</b></h2>
            </div>
        </div>
        <div class="row">
             <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="show_favorite_menu alert alert-warning alert-dismissable hide " id="favorite_menu_{{$data['topic_detail']->ft_id}}">
                    <button type="button" class="close" onclick="hide_alert(this);">&times;</button>
                    <ul class="favorite_menu" id="favorite_topic_{{$data['topic_detail']->ft_id}}">
                    </ul>
                </div>
             </div>
            <div class="alert alert-warning alert-dismissable hide" id="report_topic_{{$data['topic_detail']->ft_id}}">
                <button type="button" class="close" onclick="hide_alert(this);">&times;</button>
                <p class="m-b-0 di">You won't see this topic in your timeline.</p>
                <a class="text-info cpointer" onclick="undoReport({{$data['topic_detail']->ft_id}});">Undo</a>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default custom_panel">
                    <div class="panel-heading b-none"> 
                        <img src="{{$user_img_url}}" alt="{{ ucfirst($data['topic_detail']->user_name) }}" title="{{ ucfirst($data['topic_detail']->user_name) }}" class="thumbnail thumb-lg di"> 
                        <div class="m-l-10 di">
                            <p class="font-normal m-b-0"><small class="text-info">{{ ucfirst($data['topic_detail']->user_name) }}</small></p>
                            <p class="font-normal"><small class="text-muted">{{$data['topic_detail']->country_name}}</small></p>
                        </div>
                         @if(Auth::check() && $user->id != $data['topic_detail']->id)
                        <div class="panel-action">
                            <div class="dropdown"> 
                                <small class="font-11 font-normal text-muted vm">{{ dateformat($data['topic_detail']->ft_created_at,"F d") ." at ". dateformat($data['topic_detail']->ft_created_at,"H:s") }}</small>
                                <a class="dropdown-toggle vt" id="examplePanelDropdown" data-toggle="dropdown" href="#" aria-expanded="false" role="button">
                                    <i class="fa fa-2x fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu bullet dropdown-menu-right" aria-labelledby="examplePanelDropdown" role="menu">
                                    <li role="presentation" class="b-b">
                                        @if($data['topic_detail']->fuft_status==1)
                                        <a href="javascript:;" role="menuitem" onclick="addFavoriteMenu('{{safe_encrypt($data['topic_detail']->ft_id)}}',0,this)">Remove from Favorites</a>
                                        @else 
                                        <a href="javascript:;" role="menuitem" onclick="addFavoriteMenu('{{safe_encrypt($data['topic_detail']->ft_id)}}',1,this)">Add to Favorites</a>
                                        @endif
                                    </li>
                                    <li role="presentation" class="b-b"><a href="javascript:void(0)" onclick="popupReportTopic('{{$data['topic_detail']->ft_id}}');">Report topic</a></li>
                                    {{-- <li role="presentation" class="b-b"><a href="javascript:;" class="text-danger" role="menuitem">Delete Post</a></li> --}}
                                </ul>
                            </div>
                        </div>
                         @endif
                    </div>
                    <div class="clearfix"></div>
                    <div class="panel-wrapper collapse in">
                        <div class="panel-body">
                            <div class="min-height100 overflow-auto">
                                <h4>{{$data['topic_detail']->ft_title}}</h4>
                                <p>{{$data['topic_detail']->ft_desc}} </p>
                            </div>
                        </div>
                        <div class="panel-footer b-none"> 
                            <div class="row m-b-10">
                                <div class="col-sm-12 col-xs-12 b-t b-b p-t-10">
                                    <div class="col-sm-7 col-xs-12 no-padding m-b-10">
                                        <ul class="list-inline text-info m-b-0">
                                        @if(Auth::check()) 
                                            <li>
                                                @if($data['topic_detail']->ftl_status==1) 
                                                    <div id="{{$data['topic_detail']->ft_id}}" type="topic" onclick="topicAction('likeOrDislike',0,this)" class="cpointer text-info">Unlike</div> 
                                                    @else
                                                    <div id="{{$data['topic_detail']->ft_id}}" type="topic" onclick="topicAction('likeOrDislike',1,this)" class="cpointer text-info">Like</div>
                                                @endif 
                                            </li>
                                            <li>
                                                <div class="cpointer text-info" onclick="focusToThisId('comment_text');">Comment</div>  
                                            </li>
                                            @if($user->id != $data['topic_detail']->id)
                                                <li>
                                                    @if($data['topic_detail']->ftf_status==1) 
                                                        <div id="{{$data['topic_detail']->ft_id}}" type="topic" onclick="topicAction('followOrUnfollow',0,this)" class="cpointer text-info">Unfollow</div> 
                                                        @else
                                                        <div id="{{$data['topic_detail']->ft_id}}" type="topic" onclick="topicAction('followOrUnfollow',1,this)" class="cpointer text-info">Follow</div>
                                                    @endif  
                                                </li>
                                            @endif
                                            @if($data['topic_comment_count']>1)
                                            <li>
                                                <div class="cpointer text-info" onclick="scrollToThisId();">View Last Reply</div> 
                                            </li>
                                            @endif 
                                        @else 
                                            <li><a href="javascript:;" class="cpointer text-info" onclick="loginPopup();">Like</a>
                                            </li>
                                            <li>
                                                <a href="javascript:;" class="cpointer text-info" onclick="loginPopup();">Comment</a>  
                                            </li>
                                            <li><a href="javascript:;" class="cpointer text-info" onclick="loginPopup();">Follow</a>
                                            </li> 
                                            @if($data['topic_comment_count']>1)
                                            <li>
                                                <div class="cpointer text-info" onclick="scrollToThisId();">View Last Reply</div> 
                                            </li>
                                            @endif 
                                        @endif
                                        </ul>
                                    </div>
                                    <div class="col-sm-5 col-xs-12 no-padding text-right m-b-10">
                                        <ul class="list-inline text-info m-b-0" id="{{$data['topic_detail']->ft_id}}">
                                            <li>
                                                <div class="text-muted cdefault">
                                                    <i class="fa fa-thumbs-up m-r-5"></i><span class="like_count">{{$data['topic_like_count']}}</span>
                                                </div> 
                                            </li>
                                            <li>
                                                <span href="javascript:;" class="text-muted cdefault">
                                                    <i class="fa fa-comment-o m-r-5"></i> <p class="di m-b-0" id="comment_count">{{$data['topic_comment_count']}}</p>
                                                </span>
                                            </li>
                                            <li>
                                                <span href="javascript:;" class="text-muted cdefault">
                                                    <i class="fa fa-eye m-r-5"></i> <p class="di m-b-0" id="follow_count">{{$data['topic_view_count']}}</p>
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div> 
                            @php 
                                $last_id = 0;
                                $root_id = 0; 
                                  foreach ($data['topic_comment_list'] as $comment){ 
                                        $children[$comment->ftc_parent_id][] = $comment; 
                                    }
                                $parent = $root_id; 
                            @endphp
                            <ul class="list-style-none parent_comment">
                            @if(!empty($children[$root_id])) 
                                @php $no = 0; @endphp
                                @foreach($children[$root_id] as $parent_loop) 
                                    @php  $no++; $parent = $parent_loop->ftc_id; 
                                    if(count($children[$root_id])==$no){ 
                                        $last_id = $parent_loop->ftc_id;
                                    }
                                    $img_url = empty(get_url("user-avatar/{$parent_loop->user_id}/200/{$parent_loop->profile_photo}")) 
                                                ? url("assets/images/noavatar.png") 
                                                : get_url("user-avatar/{$parent_loop->user_id}/200/{$parent_loop->profile_photo}");
                                    
                                    @endphp
                                    <li id="{{$parent_loop->ftc_id}}">
                                        <div class="comment_text">
                                            <div class="row m-b-10">
                                                <div class="col-xs-12 col-sm-12 p-10">
                                                    <img src="{{$img_url}}" alt="{{ucfirst($parent_loop->user_name)}}" title="{{ucfirst($parent_loop->user_name)}}" class="thumbnail thumb-md col-sm-2 col-xs-3">
                                                    <div class="col-sm-10 col-xs-9">
                                                        <p class="font-normal text-info">
                                                        {{ucfirst($parent_loop->user_name)}}
                                                        </p>
                                                        <p class="font-normal m-b-0">
                                                        {{$parent_loop->ftc_comment}}
                                                        </p>
                                                        <ul class="list-inline text-info m-b-0" id="{{$parent_loop->ftc_id}}"> 
                                                            @if(Auth::check()) 
                                                            <li>
                                                                @if($parent_loop->fcl_status==1) 
                                                                <div id="{{$parent_loop->ftc_id}}" type="comment" onclick="topicAction('likeOrDislike',0,this)" class="cpointer text-info">Unlike</div> 
                                                                @else
                                                                <div id="{{$parent_loop->ftc_id}}" type="comment" onclick="topicAction('likeOrDislike',1,this)" class="cpointer text-info">Like</div>
                                                                @endif 
                                                            </li>
                                                            <li>
                                                                <a href="javascript:;" class="reply_button text-info" id="{{$parent_loop->ftc_id}}">Reply</a>
                                                            </li>
                                                            @if($user->id == $parent_loop->user_id)
                                                                <li>
                                                                    <a href="javascript:;" class="reply_button text-info" onclick="deleteComment('{{$parent_loop->ftc_id}}','{{$data['topic_detail']->ft_id}}');">Delete</a>
                                                                </li>
                                                            @endif
                                                            <li>
                                                                <div class="text-muted cdefault">
                                                                    <i class="fa fa-thumbs-up m-r-5"></i><span class="like_count">{{$parent_loop->comment_count}}</span>
                                                                </div>
                                                            </li>
                                                            @else 
                                                            <li>
                                                                <div onclick="loginPopup();" class="cpointer text-info">Like</div>
                                                            </li>
                                                            <li>
                                                                <div onclick="loginPopup();" class="cpointer text-info">Reply</div>
                                                            </li>
                                                            <li>
                                                                <div class="text-muted cdefault">
                                                                    <i class="fa fa-thumbs-up m-r-5"></i><span class="like_count">{{$parent_loop->comment_count}}</span>
                                                                </div>
                                                            </li>
                                                            @endif 
                                                        </ul> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="comment_reply">
                                            <ul class="child_comment">
                                                @if(!empty($children[$parent]))
                                               
                                                @foreach($children[$parent] as $child_loop) 
                                               @php
                                                 $img_url = empty(get_url("user-avatar/{$child_loop->user_id}/200/{$child_loop->profile_photo}")) 
                                                    ? url("assets/images/noavatar.png") 
                                                    : get_url("user-avatar/{$child_loop->user_id}/200/{$child_loop->profile_photo}");
                                               @endphp
                                                <li id="{{$child_loop->ftc_id}}">
                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-12 p-10">
                                                            <img src="{{ $img_url }}" alt="{{ucfirst($child_loop->user_name)}}" title="{{ucfirst($child_loop->user_name)}}" class="thumbnail thumb-sm col-sm-2 col-xs-3">
                                                            <div class="col-sm-10 col-xs-9">
                                                                <p class="font-normal text-info">
                                                                {{ucfirst($child_loop->user_name)}}
                                                                </p>
                                                                <p class="font-normal m-b-0">
                                                                {{$child_loop->ftc_comment}}
                                                                </p>
                                                                <ul class="list-inline text-info m-b-0" id="{{$child_loop->ftc_id}}">
                                                                    @if(Auth::check())
                                                                    <li>
                                                                        @if($child_loop->fcl_status==1) 
                                                                        <div id="{{$child_loop->ftc_id}}" type="comment" onclick="topicAction('likeOrDislike',0,this)" class="cpointer text-info">Unlike</div> 
                                                                        @else
                                                                        <div id="{{$child_loop->ftc_id}}" type="comment" onclick="topicAction('likeOrDislike',1,this)" class="cpointer text-info">Like</div>
                                                                        @endif
                                                                    </li>
                                                                    <li>
                                                                       <a href="javascript:;" class="reply_button text-info" id="{{$child_loop->ftc_parent_id}}">Reply</a>
                                                                    </li>
                                                                    @if($user->id == $child_loop->user_id)
                                                                        <li>
                                                                            <a href="javascript:;" class="reply_button text-info" onclick="deleteComment('{{$child_loop->ftc_id}}');">Delete</a>
                                                                        </li>
                                                                    @endif
                                                                    <li>
                                                                        <div class="text-muted cdefault">
                                                                            <i class="fa fa-thumbs-up m-r-5"></i><span class="like_count">{{$child_loop->comment_count}}</span>
                                                                        </div> 
                                                                    </li>
                                                                    @else
                                                                    <li> 
                                                                        <div onclick="loginPopup();" class="cpointer text-info">Like</div> 
                                                                    </li>
                                                                    <li>
                                                                        <div onclick="loginPopup();" class="cpointer text-info">Reply </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="text-muted cdefault">
                                                                            <i class="fa fa-thumbs-up m-r-5"></i><span class="like_count">{{$child_loop->comment_count}}</span>
                                                                        </div> 
                                                                    </li>
                                                                    @endif
                                                                </ul> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                @endforeach
                                                @endif 
                                            </ul>
                                        </div>
                                        @if(Auth::check()) 
                                        <div class="comment_form">
                                            <ul>
                                                <li>
                                                    <div id="reply_{{$parent_loop->ftc_id}}" class="hide"> 
                                                        <form name="replyToCommentFrm" id="replyToCommentFrm_{{$parent_loop->ftc_id}}"> 
                                                            <input type="hidden" name="action" value="addComment" />
                                                            <input type="hidden" name="topic_id" value="{{ safe_encrypt($data['topic_detail']->ft_id) }}" />
                                                            <input type="hidden" name="reply_id" id="reply_id" value="0"/>
                                                            <input type="hidden" name="depth_level" id="depth_level" value="0"/>
                                                            {{ csrf_field() }}
                                                            <div class="row">
                                                                 <div class="col-xs-12 col-sm-12 p-10">
                                                                     <div class="form-group m-b-0 {{ $errors->has('comment_text') ? 'has-error' : '' }}"> 
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon p-2"> 
                                                                                <img src="{{ $user_dp_url }}" class="thumbnail thumb-sm di m-b-0">   
                                                                            </span>
                                                                            <input type="text" name="comment_text" class="form-control post_comment" placeholder="Write a comment..." autocomplete="off">  
                                                                        </div> 
                                                                     </div>
                                                                 </div>
                                                            </div> 
                                                        </form>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        @endif
                                    </li>  
                                @endforeach
                            @endif
                            </ul> 
                            @if(Auth::check()) 
                            <div id="comment_wrapper">
                                <div id="comment_form_wrapper">
                                    <form id="topicCommentFrm" name="topicCommentFrm"> 
                                    <input type="hidden" name="action" value="addComment" />
                                    <input type="hidden" name="topic_id" value="{{ safe_encrypt($data['topic_detail']->ft_id) }}" />
                                    <input type="hidden" name="reply_id" id="reply_id" value="0"/>
                                    <input type="hidden" name="depth_level" id="depth_level" value="0"/>
                                    {{ csrf_field() }}
                                    <div class="row">
                                         <div class="col-xs-12 p-10">
                                             <div class="form-group m-b-0"> 
                                                <div class="input-group">
                                                    <span class="input-group-addon p-2"> 
                                                        <img src="{{$user_dp_url}}" class="thumbnail thumb-sm di m-b-0">   
                                                    </span> 
                                                    <input type="hidden" id="topic_id" name="topic_id" value="{{ safe_encrypt($data['topic_detail']->ft_id) }}" /> 
                                                    <input type="text" id="comment_text" name="comment_text" class="form-control post_comment" placeholder="Write a comment..." autocomplete="off">
                                                    <!--<span class="input-group-addon cpointer"><i class="fa fa-camera"></i></span>-->
                                                </div> 
                                             </div>
                                         </div>
                                    </div> 
<!--                                    <div class="row">
                                         <div class="col-xs-12 b-none">
                                             <button type="submit" class="btn btn-info" name="addComment-btn" id="addComment-btn">Submit</button>   
                                         </div>
                                    </div> -->
                                </form>
                                </div>
                            </div>
                            @else 
                                <div class="row m-b-10 b-t p-t-10">
                                    <div class="col-xs-12">
                                        Please <a href="javascript:;" onclick="loginPopup();" class="text-info">sign in</a> or <a href="{{ route('register') }}" class="text-info">create an account</a> to participate in this conversation. 
                                    </div>
                                </div> 
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="last_reply_id" id="last_reply_id" value="{{$last_id}}" />
        @if(Auth::check()) 
        <form id="topic_action_form" method="post">
            <input type="hidden" id="topicId" name="topicId" value="{{ safe_encrypt($data['topic_detail']->ft_id) }}" /> 
            <input type="hidden" name="topic_action_value" id="topic_action_value" value="" />  
            <input type="hidden" name="comment_id" id="comment_id" value="" />  
            <input type="hidden" name="action" id="topic_action" value="" /> 
            <input type="hidden" name="topic_actionfor" id="topic_actionfor" value="" /> 
        </form>
        @endif
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        @include('user.includes.topicSidebar')
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">  
$(document).on("keydown",".post_comment", function (e) {
    if (this.value.trim().length == 0 && e.keyCode == 13) {  
        e.preventDefault(); 
    } else if (e.keyCode == 13) {
        addComment($(this).closest('form'),e);
    }
});

$(document).on("click","a.reply_button", function (event) {  
    event.preventDefault();
    var id = $(this).attr("id");
    $("#reply_"+id).removeClass('hide');
    focusToThisId("reply_"+id+" input[name='comment_text']"); 
    $("#reply_"+ id +" #reply_id").attr("value", id);
    $("#reply_"+ id +" #depth_level").attr("value", 1);
});   
 
function addComment(element,e){ 
    e.preventDefault(); 
    var formId = $(element).attr("id");
    var btnElement = $("input[type=submit]",formId);
    showLoader(btnElement,'Loading');
    var formID = $("#"+formId).serialize();
    $.ajax({
        type: 'post',
        url: '{{ url("forumAjaxRequest") }}', 
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: formID,
        dataType: 'json',
        success: function(response) { 
            $("#"+formId).trigger("reset");
            hideLoader(btnElement,'Submit');
            if(response.type=='success')
            {   
                $("#comment_count").html(response.data.topiccommentcount); 
                var replyTo = $("#" + formId + " #reply_id").val(); 
                if (replyTo == 0) {
                    $("ul.parent_comment").append(response.data.html);
                    $("#last_reply_id").val(response.data.last_reply_id);
                }
                else {
                    $("li#" + replyTo + " ul.child_comment:first").append(response.data.html);
                } 
            } 
        }
    });  
}  

function topicAction(action,value,element){
    
    $("#topic_action_value").val(value);
    $("#topic_action").val(action);
    var actionfor = $(element).attr("type"); 
    var commentId = ""; 
    $("#topic_actionfor").val(actionfor);
    var replaceId = {{$data['topic_detail']->ft_id}};
    if(actionfor=="comment"){
        var commentId = $(element).attr("id");
        $("#comment_id").val(commentId);
        var replaceId = commentId;
    } 
    
    var formID = $("#topic_action_form").serialize(); 
    $.ajax({
        type: 'post',
        url: '/forumAjaxRequest',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: formID,
        success: function(response) { 
            if(response.type=="success"){
                if(action=="likeOrDislike"){
                    if(value==1){ 
                        $(element).closest("li").html('<div id="'+replaceId+'" type="'+actionfor+'" onclick="topicAction(\'likeOrDislike\',0,this)" class="cpointer">Unlike</div>'); 
                    }else{
                        $(element).closest("li").html('<div id="'+replaceId+'" type="'+actionfor+'" onclick="topicAction(\'likeOrDislike\',1,this)" class="cpointer">Like</div>');
                    } 
                    if(actionfor=="comment"){
                        $("ul#" + replaceId + " li span.like_count").html(response.data.likecount);
                    }else{
                        $("ul#" + replaceId + " li span.like_count").html(response.data.likecount);
                    }
                }else if(action=="followOrUnfollow"){ 
                    if(value==1){
                        $(element).closest("li").html('<div id="'+replaceId+'" type="'+actionfor+'" onclick="topicAction(\'followOrUnfollow\',0,this)" class="cpointer">Unfollow</div>'); 
                    }else{
                        $(element).closest("li").html('<div id="'+replaceId+'" type="'+actionfor+'" onclick="topicAction(\'followOrUnfollow\',1,this)" class="cpointer">Follow</div>'); 
                    }
                }  
            }
        }
    }); 
} 
    
function scrollToThisId(ID){  
    var ID = $('#last_reply_id').val();
    $('html, body').animate({
        scrollTop: $("#"+ID).offset().top
    }, 2000);
} 
function focusToThisId(ID){
    $("#"+ID).focus();
} 
function loginPopup(){
    $("#login-modal").modal(); 
}
</script>
@stop  