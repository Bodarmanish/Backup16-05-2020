@if(isset($commentdata))
<li id="{{$commentdata->ftc_id}}">
    <div class="comment_text">
        <div class="row m-b-10">
            <div class="col-xs-12 col-sm-12 p-10">
                @php
                    if($commentdata->ftc_parent_id==0)
                    { 
                        $size = "thumb-md";
                        $replyTo = $commentdata->ftc_id; 
                    }
                    else
                    {
                        $size = "thumb-sm";
                        $replyTo = $commentdata->ftc_parent_id;
                    }
                    $user_img_url = empty(get_url("user-avatar/{$commentdata->id}/200/{$commentdata->profile_photo}")) 
                                ? url("assets/images/noavatar.png") 
                                : get_url("user-avatar/{$commentdata->id}/200/{$commentdata->profile_photo}");
                    $topic_id = $commentdata->ftc_parent_id == 0 ? $commentdata->ftc_ft_id : null; 
                                
                @endphp
                <img src="{{$user_img_url}}" alt="{{ucfirst($commentdata->user_name)}}" title="{{ucfirst($commentdata->user_name)}}" class="thumbnail {{ $size }} col-sm-2 col-xs-3">
                <div class="col-sm-10 col-xs-9">
                    <p class="font-normal text-info">
                    {{ucfirst($commentdata->user_name)}}
                    </p>
                    <p class="font-normal m-b-0">
                    {{$commentdata->ftc_comment}}
                    </p>
                    <ul class="list-inline text-info m-b-0" id="{{$commentdata->ftc_id}}">
                        <li>
                            @if($commentdata->fcl_status==1) 
                                <div id="{{$commentdata->ftc_id}}" type="comment" onclick="topicAction('likeOrDislike',0,this)" class="cpointer text-info">Unlike</div> 
                            @else
                                <div id="{{$commentdata->ftc_id}}" type="comment" onclick="topicAction('likeOrDislike',1,this)" class="cpointer text-info">Like</div>
                            @endif
                        </li>
                        <li>
                           <a href="javascript:;" class="reply_button text-info" id="{{$replyTo}}">Reply</a>
                        </li>
                        <li>
                           <a href="javascript:;" class="reply_button text-info" onclick="deleteComment('{{$commentdata->ftc_id}}','{{$topic_id}}');">Delete child</a>
                        </li>
                        <li>
                            <div class="text-muted cdefault">
                                <i class="fa fa-thumbs-up m-r-5"></i><span class="like_count">{{$commentdata->comment_count}}</span>
                            </div> 
                        </li>
                    </ul> 
                </div>
            </div>
        </div>
    </div>
    <div class="comment_reply">
        <ul class="child_comment">
            
        </ul>
    </div> 
    @if($commentdata->ftc_parent_id==0 )
        <div class="comment_form">
            <ul>
                <li>
                    <div id="reply_{{$commentdata->ftc_id}}" class="hide"> 
                        <form name="replyToCommentFrm" id="replyToCommentFrm_{{$commentdata->ftc_id}}"> 
                            <input type="hidden" name="action" value="addComment" />
                            <input type="hidden" name="topic_id" value="{{ safe_encrypt($commentdata->ftc_ft_id) }}" />
                            <input type="hidden" name="reply_id" id="reply_id" value="0"/>
                            <input type="hidden" name="depth_level" id="depth_level" value="0"/>
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 p-10">
                                    <div class="form-group m-b-0"> 
                                        <div class="input-group">
                                            <span class="input-group-addon p-2"> 
                                                <img src="" class="thumbnail thumb-sm di m-b-0">   
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
@endif
              