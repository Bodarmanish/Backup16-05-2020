@extends('user.layouts.app')
@php
    $user = Auth::user(); 
    $img_url = empty(get_url('forum-photo/'.$data['cat_info']->id.'/'.$data['cat_info']->banner_image)) ? url("assets/images/noimage.png") : get_url('forum-photo/'.$data['cat_info']->id.'/'.$data['cat_info']->banner_image);
@endphp
@section('content')   
<div class="row">
   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="javascript:;" class="text-info">Home</a></li>
            <li><a href="{{ url('categories/') }}" class="text-info">Forum</a></li> 
            <li><a href="{{ url("category/".$data['cat_info']->CatTitleSlug) }}" class="text-info">{{ ucfirst($data['cat_info']->CatTitle) }}</a></li> 
            <li class="active capitalize">{{ ucfirst($data['cat_info']->title) }}</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <div class="row">
            <div class="col-xs-12">
                <div class="img-responsive m-b-20">
                    <img class="img-responsive" alt="{{ ucfirst($data['cat_info']->title) }}" title="{{ ucfirst($data['cat_info']->title) }}" src="{{ $img_url }}" />  
                </div>
                <h1><b>{{ ucfirst($data['cat_info']->title) }}</b></h1>
                <p class="m-b-20">{!! $data['cat_info']->description !!}</p>
            </div>
        </div>
        @if(count($data['topic_list'])>0)
        <div class="infinite-scroll"> 
            @foreach($data['topic_list'] as $topic)
                @php
                    $topic_url = url('/topicdetail/'.$topic->slug);
                @endphp
                @if(Auth::check())
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="alert alert-warning alert-dismissable hide" id="off_topic_notify_{{$topic->id}}">
                            <button type="button" class="close" onclick="hide_alert(this);">&times;</button> 
                            <p class="m-b-0">You'll get a notification whenever someone comments on this topic.</p>
                            <a class="text-info cpointer" onclick="notifyTopics('{{$topic->id}}',0,this)">Turn OFF Notifications</a>
                        </div>
                        <div class="alert alert-warning alert-dismissable hide" id="on_topic_notify_{{$topic->id}}">
                            <button type="button" class="close" onclick="hide_alert(this);">&times;</button>
                            <p class="m-b-0">You'll no longer get notifications about this topic.</p>
                            <a class="text-info cpointer" onclick="notifyTopics('{{$topic->id}}',1,this)">Turn On Notifications</a>
                        </div>
                        <div class="alert alert-warning alert-dismissable hide" id="report_topic_{{$topic->id}}">
                            <button type="button" class="close" onclick="hide_alert(this);">&times;</button>
                            <p class="m-b-0 di">You won't see this topic in your timeline.</p>
                            <a class="text-info cpointer" onclick="undoReport({{$topic->id}});">Undo</a>
                        </div>
                        <div class="show_favorite_menu alert alert-warning alert-dismissable hide " id="favorite_menu_{{$topic->id}}">
                            <button type="button" class="close" onclick="hide_alert(this);">&times;</button>
                            <ul class="favorite_menu" id="favorite_topic_{{$topic->id}}">
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
                <div class="row"> 
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="topicId_{{$topic->id}}">
                        <div class="panel panel-default custom_panel">
                            <div class="panel-heading b-none">
                                <a class="text-info" href="{{$topic_url}}">{{ $topic->title }}</a>
                                @if(Auth::check() && $user->id != $topic->user_id)
                                <div class="panel-action">
                                    <div class="dropdown">
                                        <a class="dropdown-toggle" id="examplePanelDropdown" data-toggle="dropdown" href="#" aria-expanded="false" role="button">
                                            <i class="fa fa-2x fa-angle-down"></i>
                                        </a>
                                        <ul class="dropdown-menu bullet dropdown-menu-right" aria-labelledby="examplePanelDropdown" role="menu">
                                            <li role="presentation" class="b-b">
                                                @if($topic->fuft_status==1)
                                                <a href="javascript:void(0)" role="menuitem" onclick="addFavoriteMenu('{{safe_encrypt($topic->id)}}',0,this)">Remove from Favorites</a>
                                                @else 
                                                <a href="javascript:void(0)" role="menuitem" onclick="addFavoriteMenu('{{safe_encrypt($topic->id)}}',1,this)">Add to Favorites</a>
                                                @endif
                                            </li>
                                            <li role="presentation" class="b-b">
                                                @if($topic->ftn_status==1) 
                                                <a href="javascript:void(0)" role="menuitem" onclick="notifyTopics('{{$topic->id}}',0,this);">Turn off notifications for this topic</a> 
                                                @else  
                                                <a href="javascript:void(0)" role="menuitem" onclick="notifyTopics('{{$topic->id}}',1,this);">Turn on notifications for this topic</a>
                                                @endif
                                            </li>
                                            <li role="presentation" class="b-b"><a href="javascript:void(0)" onclick="popupReportTopic('{{$topic->id}}');">Report topic</a></li>
                                        </ul>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="panel-wrapper collapse in">
                                <div class="panel-body p-b-20">
                                    <p>{!!  str_limit($topic->description, $limit = 500, $end = "<a href=$topic_url> more..</a>") !!} </p>
                                    <p><small class="text-muted"> by <span class="text-info">{{ ucfirst($topic->user_name) }} </span> - {{ dateformat($topic->created_at,"F d") }}</small></p>
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
                    <p>No topics found inside this sub category. Please click <a href="{{ url("category/".$data['cat_info']->CatTitleSlug) }}">here</a> to choose another category.</p> 
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