@extends('admin.layouts.app')

@php
$forums = @$forums;
@endphp

@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Community Forum Category</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="active">Forum Category</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">Community Category</h3>
                        <p class="text-muted m-b-30">List of Community Forum Category</p>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="col-md-4 pull-right">
                            @if(check_route_access('forum.cat.add.form'))
                            <a href="{{ route('forum.cat.add.form') }}" class="btn btn-block btn-info">Add New Forum Category</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="white-box well">
                    <div class="row">
                        <form id="document_filter" method="post" class="form" action="{{ route('forum.cat.search') }}">
                            {{ csrf_field() }}
                            <div class="form-group col-md-4 col-xs-12">
                                <label>Select Forum Category</label>
                                <select name="forum_category" id="forum_category" class="form-control">
                                    <option value="">-- Select Forum Category --</option>
                                    @if (!empty($forums))
                                        @foreach($forums as $value)
                                        <option value="{{$value->id}}" {{is_selected(request()->get('forum_category'),$value->id)}}>{{$value->title}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <!--<button type="button" class="btn btn-info" onclick="sendForm();">Search</button>-->
                                    <button type="submit" class="btn btn-info">Search</button>
                                    <a type="button" class="btn btn-danger" href="{{ route('forum.cat.list') }}">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @include('admin.includes.status')
                <div class="table-responsive">
                    <table id="forums_table" class="table table-bordered m-t-10">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Category Title</th>
<!--                                <th>Image</th>-->
                                <th>Keyword</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($forums))
                                @foreach($forums as $forum)
                                    @php
                                        $status = ($forum->status == 1) ? "Active" : "De-Active";
                                        $status_class = ($forum->status == 1) ? "success" : "danger";
                                    @endphp
                                    <tr>
                                        <td>{{ $forum->id }}</td>
                                        <td>{{ $forum->title }}</td>
<!--                                        <td>
                                            @php
                                            $img_url = get_url("forum-photo/".$forum->id."/".$forum->banner_image);
                                            if(empty($img_url)){
                                                $img_url = url("assets/images/noimage.png");
                                            }
                                            @endphp
                                        <img src="{{$img_url}}" width="200" class="img-responsive thumbnail" />
                                        </td>-->
                                        <td>
                                            {{ $forum->keyword }}
                                        </td>
                                        <td><span class="label label-{{ $status_class }}">{{ $status }}</span></td>
                                       <td>
                                           @if(check_route_access('forum.cat.edit.form'))
                                            <a href="{{ route('forum.cat.edit.form',$forum->slug) }}" data-toggle="tooltip" data-original-title="Edit"> <i class="fa fa-pencil text-inverse m-r-10"></i> </a>
                                           @endif
                                           @if(check_route_access('forum.cat.delete'))
                                            <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Delete" onclick="return confirmDelete('{{ route('forum.cat.delete',$forum->slug) }}');" > <i class="fa fa-close text-danger m-r-10"></i> </a>
                                           @endif
                                        </td>
                                    </tr>
                                @endforeach
                           @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
</div> 
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#forums_table').dataTable();
    });
</script>
@endsection
