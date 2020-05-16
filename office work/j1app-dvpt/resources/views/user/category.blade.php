@extends('user.layouts.app')

@section('content') 
<div class="row">
   <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="javascript:;" class="text-info">Home</a></li>
            <li><a href="{{ url('categories/') }}" class="text-info">Forum</a></li>
            <li class="active capitalize">{{ $data['parent_cat_name']}}</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"> 
        <div class="row m-b-20">
            <div class="col-xs-8"> 
                <h2 class="page-title capitalize no-margin"><b>{{$data['parent_cat_name']}}</b></h2> 
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
        @if(count($data['forum_sub_category_list'])>0)
        <div class="infinite-scroll">  
            @foreach($data['forum_sub_category_list'] as $subcat)
            @php
                $img_url = empty(get_url('forum-photo/'.$subcat->id.'/'.$subcat->banner_image)) ? url("assets/images/noimage.png") : get_url('forum-photo/'.$subcat->id.'/'.$subcat->banner_image);
                $topic_url = url('/subcategory/'.$subcat->slug);
            @endphp
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel panel-default custom_panel">
                        <div class="panel-heading b-none">
                            <a class="text-info" href="{{ $topic_url }}">{{ ucfirst($subcat->title) }}</a>
                        </div>
                        <div class="panel-wrapper collapse in">
                            <div class="panel-body p-b-20">
                                <div class="img-responsive m-b-10">
                                    @if($subcat->banner_image!="")
                                    <img class="img-responsive" alt="{{ ucfirst($subcat->title) }}" title="{{ ucfirst($subcat->title) }}" src="{{ $img_url }}" /> 
                                    @endif
                                </div> 
                                <p>{!!  str_limit($subcat->description, $limit = 500, $end = "<a href=$topic_url> more..</a>") !!} </p>
                                <button class="btn btn-default bg-light btn-outline icon-wifi pull-left"> <i class="fa fa-rss"></i> </button>
                                <div class="p-l-10 pull-left">
                                    <small class="text-muted"> {{ $subcat->topic_total }} discussions {{ $subcat->comment_total }} comments </small><br>
                                    @if($subcat->ft_title!='')
                                    <small>Most recent topic: <span class="text-info">{{ $subcat->ft_title }}</span> by <span class="text-info">{{ ucfirst($subcat->user_name) }}</span> - {{ dateformat($subcat->ft_created_at,"F d") }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="panel-footer b-none p-t-0 p-b-10">
                                @if ($subcat->tag_title != "")
                                <ul class="list-inline p-l-20 sub_category_label">
                                    @php $tagAry = custom_explode($subcat->tag_slug); @endphp
                                    @foreach(custom_explode($subcat->tag_title) as $key=>$tag_title)  
                                        <li><a href="{{ url("tag/".$tagAry[$key]) }}" class="btn btn-outline btn-default btn-sm"><span class="text-info">{{ ucfirst($tag_title) }} </span></a></li>
                                    @endforeach
                                </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach 
            {{ $data['forum_sub_category_list']->links() }}
        </div>
        @else
            <div class="row">
                <div class="col-lg-12">  
                    <p>No sub category found inside this parent category. Please click <a href="{{ url("categories") }}">here</a> to choose another category.</p> 
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