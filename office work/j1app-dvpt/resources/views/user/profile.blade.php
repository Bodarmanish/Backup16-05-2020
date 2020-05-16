@extends('user.layouts.app')

@section('content') 
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="{{ route("home") }}" class="text-info">Home</a></li>
            <li class="active capitalize">Profile</li>
        </ol>
    </div>
</div> 
<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <div class="row m-b-10">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                    <h2 class="page-title capitalize no-margin"><b class="full_name">{{ucfirst(@$profile_info->first_name)}} {{ucfirst(@$profile_info->last_name)}} </b></h2>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 text-right">
                    <!-- <a class="p-10 cpointer text-center m-b-10 text-info di" onclick="popup_activity_log();">Activity Log</a> -->
                    <a href="{{ route('edit.profile') }}" class="btn btn-info cpointer text-center btn-xl m-b-10 di" /> Update Info</a>
                </div>
            </div>
<!--            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="alert alert-warning alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> 
                    Stage 1 of 3: Application next step: <a class="text-info" href="#">Upload resume</a>
                </div>
            </div>-->
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    @include('user.includes.status')
                    <div class="row pro_social">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <ul class="list-unstyled text-muted font-13">
                                <!-- <li><i class="fa fa-paperclip"></i>Member, Bronze - 20 Points</li> 
                                <li><i class="fa fa-exclamation-circle "></i>Badges: 3</li>-->
                                @if(!empty($profile_info->street) || !empty($profile_info->city) || !empty($profile_info->zip_code))
                                <li>
                                    <i class="fa fa-map-marker"></i>
                                    <span class="user_address">{{ $profile_info->street }}{{ (!empty($profile_info->street)) ? ', ' : '' }}{{ $profile_info->city }}{{ (!empty($profile_info->city)) ? ', ' : '' }}{{ $profile_info->zip_code }}</span>
                                </li>
                                @endif
                                @if(!empty($profile_info->email))
                                <li><i class="fa fa-envelope"></i>{{ $profile_info->email }}</li> 
                                @endif
                            </ul>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <ul class="list-unstyled text-muted font-13"> 
                                @if(!empty($profile_info->created_at) && date("Y", strtotime($profile_info->created_at)) > 2000)
                                <li><i class="fa fa-calendar"></i>Joined {{ dateformat($profile_info->created_at,DISPLAY_FULL_DATETIME) }}</li>
                                @endif
                                
                                @if(!empty($profile_info->updated_at) && date("Y", strtotime($profile_info->updated_at)) > 2000)
                                <li><i class="fa fa-clock-o"></i>Last active {{ dateformat($profile_info->updated_at,DISPLAY_FULL_DATETIME) }}</li>
                                @endif
                                
                                <li><i class="fa fa-twitter"></i><span class="twitter_url">@if(@$profile_info->twitter_url!="")<a href="{{ $profile_info->twitter_url }}" target="_blank" class="text-info">{{ $profile_info->twitter_url }}</a>@else<a class="text-info cpointer" href="{{ route('edit.profile','social_details') }}">What is your Twitter?</a> @endif</span></li>
                                
                                <li><i class="fa fa-facebook"></i><span class="facebook_url">@if(@$profile_info->facebook_url!="")<a href="{{ $profile_info->facebook_url }}" target="_blank" class="text-info">{{ $profile_info->facebook_url }}</a>@else<a class="text-info cpointer" href="{{ route('edit.profile','social_details') }}">What is your Facebook? </a> @endif</span></li>  
                                @if(!empty(@$profile_info->skype_id) && $profile_info->skype_id != '')
                                <li><i class="fa fa-skype"></i><span class="skype_id"><a href="skype:{{ $profile_info->skype_id }}" class="text-info">{{ $profile_info->skype_id }}</a></span></li>
                                @endif                            
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="col-lg-1 col-md-1 col-sm-1"></div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 text-center">
                            <p class="text-muted font-13">Favorites</p>
                            <p class="text-muted font-13">{{ @$favourite_count }}</p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 text-center">
                            <p class="text-muted font-13">Likes</p>
                            <p class="text-muted font-13">{{ @$topic_like_count }}</p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 text-center">
                            <p class="text-muted font-13">Topics</p>
                            <p class="text-muted font-13">{{ @$topic_count }}</p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 text-center">
                            <p class="text-muted font-13">Following</p>
                            <p class="text-muted font-13">{{ @$following_count }}</p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 text-center">
                            <p class="text-muted font-13">Comments</p>
                            <p class="text-muted font-13">{{ @$comment_count }}</p>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
                    </div>
                </div>
            </div>
        </div>
        @if(count($user_favorite_topic)>0)
            <div class="row">
                <div class="col-xs-12">
                    <h4 class="page-title capitalize m-b-10"><b>My Favorites</b></h4>
                </div>
            </div>
            @foreach($user_favorite_topic as $key => $topic)
                @if($key < 2)
                    @php
                        $topic_url = url("topicdetail/".$topic->ft_slug);
                    @endphp
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="alert alert-warning alert-dismissable hide" id="off_topic_notify_{{$topic->ft_id}}">
                            <button type="button" class="close" onclick="hide_alert(this);">&times;</button> 
                            <p class="m-b-0">You'll get a notification whenever someone comments on this topic.</p>
                            <a class="text-info cpointer" onclick="notifyTopics('{{$topic->ft_id}}',0,this)">Turn OFF Notifications</a>
                        </div>
                        <div class="alert alert-warning alert-dismissable hide" id="on_topic_notify_{{$topic->ft_id}}">
                            <button type="button" class="close" onclick="hide_alert(this);">&times;</button>
                            <p class="m-b-0">You'll no longer get notifications about this topic.</p>
                            <a class="text-info cpointer" onclick="notifyTopics('{{$topic->ft_id}}',1,this)">Turn On Notifications</a>
                        </div>
                        <div class="alert alert-warning alert-dismissable hide" id="report_topic_{{$topic->ft_id}}">
                            <button type="button" class="close" onclick="hide_alert(this);">&times;</button>
                            <p class="m-b-0 di">You won't see this topic in your timeline.</p>
                            <a class="text-info cpointer" onclick="undoReport({{$topic->ft_id}});">Undo</a>
                        </div>
                         <div class="show_favorite_menu alert alert-warning alert-dismissable hide " id="favorite_menu_{{$topic->ft_id}}">
                            <button type="button" class="close" onclick="hide_alert(this);">&times;</button>
                            <ul class="favorite_menu" id="favorite_topic_{{$topic->ft_id}}">
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="topicId_{{$topic->ft_id}}">
                        <div class="panel panel-default custom_panel">
                            <div class="panel-heading b-none">
                                <a class="text-info" href="{{$topic_url}}">{{ $topic->ft_title }}</a>
                                <div class="panel-action">
                                    <div class="dropdown">
                                        <a class="dropdown-toggle" id="examplePanelDropdown" data-toggle="dropdown" href="#" aria-expanded="false" role="button">
                                            <i class="fa fa-2x fa-angle-down"></i>
                                        </a>
                                        <ul class="dropdown-menu bullet dropdown-menu-right" aria-labelledby="examplePanelDropdown" role="menu">
                                            <li role="presentation" class="b-b">
                                                @if($topic->fuft_status==1)
                                                    <a href="javascript:void(0)" role="menuitem" onclick="addFavoriteMenu('{{safe_encrypt($topic->ft_id)}}',0,this)">Remove from Favorites</a>
                                                @else 
                                                    <a href="javascript:void(0)" role="menuitem" onclick="addFavoriteMenu('{{safe_encrypt($topic->ft_id)}}',1,this)">Add to Favorites</a>
                                                @endif
                                            </li> 
                                            <li role="presentation" class="b-b">
                                                @if($topic->ftn_status==1) 
                                                <a href="javascript:void(0)" role="menuitem" onclick="notifyTopics('{{$topic->ft_id}}',0,this);">Turn off notifications for this topic</a> 
                                                @else  
                                                <a href="javascript:void(0)" role="menuitem" onclick="notifyTopics('{{$topic->ft_id}}',1,this);">Turn on notifications for this topic</a>
                                                @endif
                                            </li>
                                            <li role="presentation" class="b-b"><a href="javascript:void(0)" onclick="popupReportTopic('{{$topic->ft_id}}');">Report topic</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-wrapper collapse in">
                                <div class="panel-body">
                                    <p>{!!  str_limit($topic->ft_desc, $limit = 300, $end = "<a href=$topic_url> more..</a>") !!} </p>
                                    <div class="pull-left">
                                        <small>by <span class="text-info"> {{ $topic->user_name }} </span> - {{dateformat($topic->ft_created_at,"F d")}}</small>
                                    </div>
                                </div>
                                <div class="panel-footer b-none p-t-0">  
                                    @if($topic->tag_title != "")
                                        <ul class="list-inline category_label">
                                            @foreach(custom_explode($topic->tag_title) as $tag_title)  
                                                <li><a href="" class="btn btn-outline btn-default btn-sm"><span class="text-info">{{ $tag_title }} </span></a> </li>
                                            @endforeach
                                        </ul>
                                    @endif  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach 
        @endif  
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <div class="row m-b-10">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                <img src="{{ $profile_photo_200x }}" class="thumbnail col-lg-6 col-md-6 col-sm-6 col-xs-8 p-5 avatar-md">
            </div>
        </div>
        <div class="row m-b-10">
            <div class="col-xs-12 m-b-10">
                @if(!empty($profile_percentage))
                <div class="active step_circle profile_circle di pull-left">
                    <div class="pie_progress cdefault" role="progressbar" data-goal="{{ @$profile_percentage }}%" data-barcolor="#41b3f9" data-barsize="4" aria-valuemin="-100" aria-valuemax="100">
                        <div class="pie_progress__content text-muted"><small>{{ @$profile_percentage }}%</small></div> 
                    </div>
                </div>
                @endif
                <small class="text-muted vm col-xs-4 m-t-10">Profile completeness</small>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-right m-b-10">
                @if(!empty($incomplete_warning_info))
                {!! $incomplete_warning_info !!} 
                @endif
            </div>
            <!--
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 m-b-10">
                <h4 class="m-b-0"><b>Badges</b></h4>
                <p class="font-13">Member, Bronze - 20 Points</p>
                <div class="badge_icons">
                    <ul class="list-unstyled list-inline text-muted">
                        <li><i class="fa fa-star"></i></li>
                        <li><i class="fa fa-lightbulb-o"></i></li>
                        <li><i class="fa fa-comments-o"></i></li>
                        <li><i class="fa fa-user"></i></li>
                    </ul>
                </div>
            </div>
            -->
        </div>
    </div>
</div> 
@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('.pie_progress').asPieProgress({
          namespace: 'pie_progress',
          trackcolor: '#CCCCCC',
        });
        $('.pie_progress').asPieProgress('start');
    });

    var social_signup = "{{ session('social_signup') }}";
    if(social_signup == 1){
        var Html = '<div class="alert swl-alert-success"><ul><li>Your account created successfully. Please check your inbox to set your new password to access your account in future.</li></ul></div>'; 
        if(Html != null ){
            notifyResponseTimerAlert(Html,"success","Success",5000);
        } 
    }
</script>
@stop