@extends('user.layouts.app')

@section('content') 
<div class="row">
   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="javascript:;" class="text-info">Home</a></li>
            <li class="active capitalize">J1 Community Forum</li>
        </ol>
    </div>
</div> 
<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"> 
        @if(Session::has('welcome_message')) 
        <div class="alert alert-warning alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> 
            {!! session('welcome_message') !!} 
        </div>    
        @endif

        <div class="row m-b-20">
            <div class="col-xs-8">
                <h2 class="page-title capitalize no-margin"><b>J1 Community Forum</b></h2>
            </div>
            <div class="col-xs-4 search-dropdown">
                <button class="dropdown-toggle form-control text-left" type="button" data-toggle="dropdown">
                    Miami <span class="caret pull-right m-t-5"></span>
                </button>
                <ul class="dropdown-menu">
                    <div class="p-5">
                        <input type="search" placeholder="Serach" class="form-control" />
                    </div>
                    <ul class="list-style-none dropdown-list">
                        <li><a href="#">Aberdeen</a></li>
                        <li><a href="#">Abilene</a></li>
                        <li><a href="#">Akron</a></li>
                        <li><a href="#">Albany</a></li>
                        <li><a href="#">Albuquerque</a></li>
                        <li><a href="#">Alexandria</a></li>
                        <li><a href="#">Allentown</a></li>
                    </ul>
                </ul>
            </div>
        </div> 
        @if(count($data['forum_category_list'])>0)
            <div class="infinite-scroll">  
                @foreach($data['forum_category_list'] as $category)
                @php
                    $img_url = empty(get_url('forum-photo/'.$category->id.'/'.$category->banner_image)) ? url("assets/images/noimage.png") : get_url('forum-photo/'.$category->id.'/'.$category->banner_image);
                @endphp
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel panel-default custom_panel">
                                <div class="panel-heading b-none">
                                    <a href="{{ url('/category/'.$category->slug) }}" class="text-info">{{ ucfirst($category->title) }}</a>
                                </div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body p-b-20">
                                        <div class="img-responsive m-b-10">   
                                            <img class="img-responsive" alt="{{ ucfirst($category->title) }}" title="{{ ucfirst($category->title) }}" src="{{ $img_url }}" />
                                        </div> 
                                        <p>{!! $category->description !!}</p> 
                                        <button class="btn btn-default bg-light btn-outline icon-wifi pull-left"> <i class="fa fa-rss"></i> </button>
                                        <div class="p-l-10 pull-left">
                                            <small class="text-muted"> {{ $category->topic_total }} discussions {{ $category->comment_total }} comments </small><br>
                                            @if(!empty($category->ft_title))
                                            <small>Most recent topic: <span class="text-info">{{ $category->ft_title }}</span> by <span class="text-info">{{ ucfirst($category->user_name) }}</span> - {{ dateformat($category->ft_created_at,"F d") }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="panel-footer b-none p-t-0 p-b-10">
                                        @if ($category->tag_title != "")
                                        <ul class="list-inline p-l-20 category_label"> 
                                            @php $tagAry = custom_explode($category->tag_slug); @endphp
                                            @foreach(custom_explode($category->tag_title) as $key=>$tag_title)  
                                                <li><a href="{{ url("tag/".$tagAry[$key]) }}" class="btn btn-outline btn-default btn-sm"><span class="text-info">{{ ucfirst($tag_title) }}</span></a></li>
                                            @endforeach
                                        </ul>
                                        @endif  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
               {{ $data['forum_category_list']->links() }}
            </div>
        @else 
            <div class="row m-b-10">
                <div class="col-lg-5 col-md-8 col-sm-8 col-xs-8 nodata_box">
                    <div class="arrow"></div>
                    <div class="nodata_box_content text-left">
                        <h4><b>Silent as the antarctica, right?</b></h4>
                        <p>Let's start your first discussion before we freeze...</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-6 col-sm-6 col-xs-6 m-b-20">
                    <div class="no_timeline_img pull-right">
                        <img src="" >
                    </div>
                </div>
                <div class="col-lg-7 col-md-6 col-sm-6 col-xs-6 text-left">
                    <a href="{{ url("addtopic") }}" class="btn btn-lg btn-info p-15 font-13">Post New Topic</a>
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
