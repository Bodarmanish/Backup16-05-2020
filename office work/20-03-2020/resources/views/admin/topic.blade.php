@extends('admin.layouts.app')

@php
$topics = @$topics;
$topicdata = @$topicdata;
@endphp

@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Community Forum Topics</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="active">Forum Topic</li>
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
                        <form id="document_filter" method="post" action="{{ route('topic.search') }}">
                            {{ csrf_field() }}
                            <div class="form-group col-md-4 col-xs-12">
                                    <label>Select Forum Category</label>
                                    <select name="forum_category" id="forum_category" class="form-control" onchange="return loadSubCategory(this);">
                                        <option value="">-- Select Forum Category --</option>
                                        @if (!empty($forums_category))
                                            @foreach($forums_category as $value)
                                                <option value="{{$value->id}}" {{is_selected(request()->get('forum_category'),$value->id)}}>{{$value->title}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                            </div>
                            <div class="form-group col-md-4 col-xs-12" id="dd_subcat">
                                <label>Select Forum Sub Category</label>
                                <select name="forum_sub_category" id="forum_sub_category" class="form-control">
                                    <option value="">-- Please Select Forum Category First --</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4 col-xs-12">
                                <label>Topic Title / Description</label>
                                <input type="text" name="topic_title_description" id="topic_title_description" placeholder="Enter Topic Title / Description" value="{{request()->get('topic_title_description')}}" class="form-control">
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
                                      <a type="button" class="btn btn-danger" href="{{ route('topic.list') }}">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @include('admin.includes.status')
                <div class="table-responsive">
                    <table id="topic_list" class="table table-bordered m-t-10">
                        <thead>
                            <tr>
                                <th width="5%" rowspan="2">Sr. No.</th>
                                <th width="10%" rowspan="2">Topic Title</th>
                                <th width="10%" rowspan="2">Sub Category Title</th>
                                <th width="7%" colspan="3" class="text-center">Topic Posted By</th>
                                <th width="5%" rowspan="2">Followers<br><span class="note_font">(Click Number And Show Followers Details)</span></th>
                                <th width="10%" rowspan="2">Likes / Unlike<br><span class="note_font">(Click Number And Show Likes/Unlike Details)</span></th>
                                <th width="5%" rowspan="2">Viewer<br><span class="note_font">(Click Number And Show Viewer Details)</span></th>
                                <th width="5%" rowspan="2">Notify me of replies</th>
                                <th width="8%" rowspan="2">Status</th>
                                <th width="12%" rowspan="2">Action</th>
                            </tr>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($topics))
                                @foreach($topics as $topic)
                                    @php
                                        $status = ($topic->status == 1) ? "Active" : "De-Active";
                                        $status_class = ($topic->status == 1) ? "success" : "danger";
                                        
                                        $notify = ($topic->notify_me_of_replies == 1) ? "Active" : "De-Active";
                                    @endphp
                                    <tr>
                                        <td>{{ $topic->id }}</td>
                                        <td>{{ $topic->title }}</td>
                                        <td>{{ $topic->cat_name }}</td>
                                        <td>{{ $topic->first_name }}</td>
                                        <td>{{ $topic->last_name }}</td>
                                        <td>{{ $topic->email }}</td>
                                        <td class="text-center">
                                            <a data-target=".bs-example-modal-lg" data-toggle="modal" class="popup-with-form btn btn-success" onclick="return show_topic_data_popup({{ $topic->id }},'loadfollow','{{ $topic->title }} Followers')">{{ $topic->total_followers }} <i class="mdi mdi-account-multiple" data-icon="v"></i></a>
                                        </td>
                                        <td class="text-center">
                                            <a data-target=".bs-example-modal-lg" data-toggle="modal" class="popup-with-form btn btn-success" onclick="return show_topic_data_popup({{ $topic->id }},'loadlikes','{{ $topic->title }} Likes')">{{ $topic->total_likes }} <i class="mdi mdi-thumb-up" data-icon="v"></i></a>
                                            <a data-target=".bs-example-modal-lg" data-toggle="modal" href="#modelForm" class="popup-with-form btn btn-success" onclick="return show_topic_data_popup({{ $topic->id }},'loadunlikes','{{ $topic->title }} Unlikes')">{{ $topic->total_unlikes }} <i class="mdi mdi-thumb-down" data-icon="v"></i></a>
                                             </td>
                                        <td class="text-center">
                                            <a data-target=".bs-example-modal-lg" data-toggle="modal" class="popup-with-form btn btn-success" onclick="return show_topic_data_popup({{ $topic->id }},'loadviews','{{ $topic->title }} Views')">{{ $topic->total_views }} <i class="mdi mdi-eye" data-icon="v"></i></a> </td>
                                        <td>
                                         <form style="display:inline;" method="post" action="" name="dlt_apiuser" id="{{ $topic->id }}_api_user_dlt" >
                                            <input type="checkbox" name="notify_me_of_replies"  style="display: none;"  onchange="change_notify_me_status('{{ $topic->id }}');" id="{{ $topic->id }}" value="1" class="mini-switch" {{ $topic->notify_me_of_replies=="1" ? 'checked="checked"' : '' }}>
                                            <span class="mini-switch-replace" title="{{ $topic->notify_me_of_replies=="1" ? 'Inactive Notify Me Status' : 'Active Notify Me Status' }}" ></span>
                                        </form>
                                        </td>
                                        <td><span class="label label-{{ $status_class }}">{{ $status }}</span></td>
                                       <td>
                                           @if(check_route_access('topic.edit.form'))
                                                <a href="{{ route('topic.edit.form',$topic->slug) }}" data-toggle="tooltip" data-original-title="Edit"> <i class="fa fa-pencil text-inverse m-r-10"></i> </a>
                                           @endif
                                           @if(check_route_access('topic.delete'))
                                                <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Delete" onclick="return confirmDelete('{{ route('topic.delete',encrypt($topic->id)) }}');" > <i class="fa fa-close text-danger m-r-10"></i> </a>
                                           @endif
                                           @if(check_route_access('comment.list'))
                                                <a href="{{ route('comment.list',encrypt($topic->id)) }}" data-toggle="tooltip" data-original-title="Comment"> <i class="fa fa-comment text-warning m-r-10"></i> </a>
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
@endsection
@section('scripts')
<script type="text/javascript">
    function change_notify_me_status(id){
        showLoader("#full-overlay");
        var is_checked = 0;
        if($("#"+id).prop("checked") == true){
            is_checked = 1;
        }else if($("#"+id).prop("checked") == false){
            is_checked = 0;
        }
        var url = "{{ route('topic.notify.status') }}";

        $.ajax({
            url: url,
            type: 'post',
            data: { action:'change_notify_me_status', notify_id:id, is_active:is_checked },
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
    
    $(document).ready(function() {
        var category =$('#forum_category').val();
        if(category != ""){
            loadSubCategory(document.getElementById('forum_category'),{{ request()->get('forum_sub_category') }}) ;
        }
        $('#topic_list').dataTable({
            "bStateSave": true,
            "fnStateSave": function (oSettings, oData) {
                localStorage.setItem('offersDataTables', JSON.stringify(oData));
            },
            "fnStateLoad": function (oSettings) {
                return JSON.parse(localStorage.getItem('offersDataTables'));
            }
        });
        $("#topic_list").on("click", ".mini-switch-replace", function(){
            $(this).prev().click();
        });

    });
    function loadSubCategory(ele,selected){
        showLoader("#full-overlay");
        var value = ele.value;
        var url = "{{ route('forum.get.subcat') }}";
        $.ajax({
            url: url,
            type: 'post',
            data: { id: value },
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            /*statusCode: {
                404: function(jqXHR,textStatus,errorThrown) {
                    alert("Page Not Found");
                },
                500: function(jqXHR,textStatus,errorThrown) {
                    alert("Internal server error");
                },
            },*/
            success: function(response){
                
                if(response.type == "success"){
                    
                    $("#dd_subcat select").html(response.data).removeAttr("disabled");
                    if(selected != "" && selected != "undefined" && selected != null){
                        $("#dd_subcat select").val(selected);
                    }
                }
                else{
                    $("#dd_subcat select").attr("disabled",true);
                }
                hideLoader("#full-overlay");
            },
        });
    }
    function show_topic_data_popup(tid,action,title){
        var url = "{{ route('load.topic.data') }}";
        $.ajax({
            url: url,
            type: 'post',
            data: { tid: tid,action:action },
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response){
                $("#myLargeModalLabel").html(title);
                $("#modal_content").html(response.data);
            },
        });
    }
</script>
@endsection
