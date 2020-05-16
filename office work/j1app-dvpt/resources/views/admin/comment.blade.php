@extends('admin.layouts.app')

@php
$comments = @$comments;
@endphp

@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Community Forum Topics</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="active">Forum Comments</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">Community Topics Management</h3>
                        <p class="text-muted m-b-30">List of Community Topics</p>
                    </div>
                </div>
                <div class="white-box well">
                    <div class="row">
                        <form id="document_filter" method="get" action="{{ route('comment.list',$id) }}">
                            {{ csrf_field() }}
                            <div class="form-group col-md-4 col-xs-12">
                                <label>Comment Text</label>
                                <input type="text" name="comment_text" id="comment_text" placeholder="Enter Topic Title" value="{{request()->get('comment_text')}}" class="form-control">
                            </div>
                            <div class="form-group col-md-4 col-xs-12">
                                <label>First Name</label>
                                <input type="text" name="first_name" id="first_name" placeholder="Enter First Name" value="{{request()->get('first_name')}}" class="form-control">
                            </div>
                            <div class="form-group col-md-4 col-xs-12">
                                <label>Last Name</label>
                                <input type="text" name="last_name" id="last_name" placeholder="Enter last Name" value="{{request()->get('last_name')}}" class="form-control">
                            </div>
                            <div class="form-group col-md-4 col-xs-12">
                                <label>Email</label>
                                <input type="text" name="email" id="email" placeholder="Enter last Name" value="{{request()->get('email')}}" class="form-control">
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                      <!--<button type="button" class="btn btn-info" onclick="sendForm();">Search</button>-->
                                      <button type="submit" class="btn btn-info">Search</button>
                                      <a type="button" class="btn btn-info" href="{{ route('comment.list',$id) }}">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @include('admin.includes.status')
                <div class="table-responsive">
                    <table id="comment_list" class="table table-bordered m-t-10">
                        <thead>
                            <tr>
                                <th rowspan="2">Sr. No.</th>
                                <th width="50%" rowspan="2">Comment Text</th>
                                <th width="20%" rowspan="2">Topic Title</th>
                                <th rowspan="2">Reply</th>
                                <th colspan="3" class="text-center">Comment Posted By</th>
                                <th rowspan="2">Status</th>
                                <th width="20%" rowspan="2">Action</th>
                            </tr>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($comments))
                                @foreach($comments as $comment)
                                    <tr>
                                        <td>{{ $comment->id }}</td>
                                        <td>{{ $comment->comment_text }}</td>
                                        <td>{{ $comment->topic_name }}</td>
                                        <td>
                                        <a data-target=".bs-example-modal-lg" data-toggle="modal"  class="popup-with-form btn btn-success" onclick="return show_topic_reply_popup({{ $comment->id }},'loadreplies')">{{ $comment->total_reply }} <i class="mdi mdi-reply" data-icon="v"></i></a>
                                        </td>
                                        <td>{{ $comment->first_name }}</td>
                                        <td>{{ $comment->last_name }}</td>
                                        <td>{{ $comment->email }}</td>
                                        <td>
                                            <form style="display:inline;" method="post" action="" name="dlt_apiuser" id="{{ $comment->id }}_api_user_dlt" >
                                            <input type="checkbox" name="notify_me_of_replies"  style="display: none;"  onchange="change_comment_status('{{ $comment->id }}');" id="{{ $comment->id }}" value="1" class="mini-switch" {{ $comment->status=="1" ? 'checked="checked"' : '' }}>
                                            <span class="mini-switch-replace" title="{{ $comment->status=="1" ? 'Inactive Comment Status' : 'Active Comment Status' }}" ></span>
                                            </form>
                                        </td>
                                        <td>
                                        @if(check_route_access('comment.edit.form'))
                                         <a href="{{ route('comment.edit.form',encrypt($comment->id)) }}" data-toggle="tooltip" data-original-title="Edit"> <i class="fa fa-pencil text-inverse m-r-10"></i> </a>
                                        @endif
                                        @if(check_route_access('comment.delete'))
                                                <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Delete" onclick="return confirmDelete('{{ route('comment.delete',encrypt($comment->id)) }}');" > <i class="fa fa-close text-danger m-r-10"></i> </a>
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
<div class="modal fade bs-example-modal-lg" tabindex="-1"  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4> 
            </div>
            <div class="modal-body" id="modal_content">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@section('scripts')
<script type="text/javascript">
    function show_topic_reply_popup(cid,action){
        var url = "{{ route('load.topic.data') }}";
        $.ajax({
            url: url,
            type: 'post',
            data: { tid: cid,action:action },
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response){
                $("#myLargeModalLabel").html("Show Topic Comments");
                $("#modal_content").html(response.data);
            },
        });
    }
    $(document).ready(function() {
        $('#comment_list').dataTable();
        $("#comment_list").on("click", ".mini-switch-replace", function(){
            $(this).prev().click();
        });
    });
    function change_comment_status(id){
        showLoader("#full-overlay");
        var is_checked = 0;
        if($("#"+id).prop("checked") == true){
            is_checked = 1;
        }else if($("#"+id).prop("checked") == false){
            is_checked = 0;
        }
        var url = "{{ route('comment.change.status') }}";

        $.ajax({
            url: url,
            type: 'post',
            data: { action:'change_comment_status', comment_id:id, is_active:is_checked },
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response){
                hideLoader("#full-overlay");
                if(response.type == "success"){
                    notifyAlert(response.message,response.type,"Success");
                }
            },
        });
    }
            
</script>
@endsection
