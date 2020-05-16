@extends('user.layouts.app')
@php
    $user = Auth::user(); 
@endphp

@section('content')    
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="#" class="text-info">Home</a></li>
            <li><a href="{{ url("categories") }}" class="text-info">Forum</a></li>
            <li class="active capitalize"> Recent Discussions </li>
        </ol>
    </div>
</div> 
<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <div class="row m-b-20">
            <div class="col-xs-12">
                <h2 class="page-title capitalize no-margin"><b>Recent Discussions</b></h2>
            </div>
        </div>
        @if(count($data['topic_list'])>0)
        <div class="infinite-scroll">  
            @foreach($data['topic_list'] as $topic)
                @php
                    $topic_url = url('/topicdetail/'.$topic->ft_slug);
                @endphp
                @if(Auth::check())
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
                        <div class="show_favorite_menu alert alert-warning alert-dismissable hide " id="favorite_menu_{{$topic->ft_id}}">
                            <button type="button" class="close" onclick="hide_alert(this);">&times;</button>
                            <ul class="favorite_menu" id="favorite_topic_{{$topic->ft_id}}">
                            </ul>
                        </div>
                        <div class="alert alert-warning alert-dismissable hide" id="report_topic_{{$topic->ft_id}}">
                            <button type="button" class="close" onclick="hide_alert(this);">&times;</button>
                            <p class="m-b-0 di">You won't see this topic in your timeline.</p>
                            <a class="text-info cpointer" onclick="undoReport({{$topic->ft_id}});">Undo</a>
                        </div>
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="topicId_{{$topic->ft_id}}">
                        <div class="panel panel-default custom_panel">
                            <div class="panel-heading b-none"><a class="text-info" href="{{$topic_url}}">{{$topic->ft_title}}</a>
                                @if(Auth::check() && $user->id != $topic->user_id)
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
                                @endif
                            </div>
                            <div class="panel-wrapper collapse in">
                                <div class="panel-body">
                                    <p>{!!  str_limit($topic->ft_desc, $limit = 500, $end = "<a href=$topic_url> more..</a>") !!} </p>
                                </div>
                                <div class="panel-footer b-none p-t-0 p-b-10"> 
                                    @if($topic->tag_title != "")
                                    <ul class="list-inline category_label">
                                        @php $tagAry = custom_explode($topic->tag_slug); @endphp
                                        @foreach(custom_explode($topic->tag_title) as $key => $tag_title)  
                                            <li><a href="{{ url("tag/".$tagAry[$key]) }}" class="btn btn-outline btn-default btn-sm"><span class="text-info">{{ $tag_title }} </span></a> </li>
                                        @endforeach
                                    </ul>
                                    @endif 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            {{ $data['topic_list']->links() }} 
        </div>
        @else
            <div class="row">
                <div class="col-lg-12">  
                    <p>Not found recent discussion.</p> 
                </div>
            </div>
        @endif
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        @include('user/includes/forumSidebar')
    </div>
</div> 
@endsection
@section('scripts')
<script type="text/javascript">
    $('ul.pagination').hide();
    $(function() {
        $('.infinite-scroll').jscroll({
            autoTrigger: true,
            loadingHtml: '<img class="center-block" src="{{url('assets/images/loader.gif')}}" alt="Loading..." />',
            padding: 0,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'div.infinite-scroll',
            callback: function() {
                $('ul.pagination').remove();
            }
        });
    });
</script>
@stop