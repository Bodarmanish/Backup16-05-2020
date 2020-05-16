@if(!empty($action))
    @if($action == "loadRouteDropdown")   
        <option value="">-- Select --</option>
        @if(!empty($permissions))
            @foreach($permissions as $permission)
            <option value="{{ $permission->id }}">{{ $permission->display_name }}</option>
            @endforeach
        @endif
    @elseif($action == "update_hc_status_form")
        @php
        $status = $hc->status;
        $id = $hc->id;
        @endphp
        <div class="modal-content">
            <form method="post" id="update_hc_status_form" action="{{ route('hc.status.update') }}" class="form-horizontal">
                <input type="hidden" name="action" value="update_hc_status" />
                <input type="hidden" name="id" value="{{ $id }}" />
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Update HC Status</h4>
                </div>
                <div class="modal-body" style="min-height: 100px;">
                    <div id="popup_notify_id"></div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Select Status</label>
                            <select name="hc_status" class="form-control">
                                <option value="1" {{ is_selected($status,1) }}>Active</option>
                                <option value="2" {{ is_selected($status,2) }}>De-Active</option>
                                <option value="3" {{ is_selected($status,3) }}>Under Review</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info text-left">Save</button>
                    <button type="button" class="btn btn-danger text-left" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
        <script type="text/javascript">
            $(document).ready(function(){
                var notify_id = "popup_notify_id";
                ajaxFormValidator("#update_hc_status_form",function(ele,event){
                    event.preventDefault();
                    
                    var form_data = new FormData(ele);
                    var action = "{{ route('hc.status.update') }}";
                    
                    $.ajax({
                        url: action,
                        type: 'post',
                        data: form_data,
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success: function(response){
                            var messages = response.message;
                            if(response.type == "success")
                            {
                                notifyResponse("#"+notify_id,messages,response.type,2000);
                                setTimeout(function(){
                                    document.location.href = document.location.href;
                                },2100);
                            }
                            else if(response.type == "validation_error"){
                                var Html = '<div class="alert swl-alert-danger"><ul>'; 
                                $.each( messages, function( key, value ) {
                                    Html += '<li>' + value+ '</li>';  
                                });
                                Html += '</ul></div>';  
                                notifyResponseTimerAlert(Html,"error","Error");
                            }
                        },
                    });
                });
            });
        </script>
    @elseif($action == "loadSubCategory")   
        <option value="">-- Select Forum Sub Category --</option>
        @if(!empty($forums))
            @foreach($forums as $forum)
               <option value="{{ $forum->id }}">{{ $forum->title }}</option>
            @endforeach
        @endif
    @elseif($action == "loadfollow")
        @if(!empty($follow) && count($follow) != 0)
            [ {{$categorydata->category_title}} > {{$categorydata->sub_category_title}} > {{$categorydata->title}} ]
        @endif    
            <fieldset class="p-t-20" style="border:0;">
                <table class="table scrollingtable sortable_data" cellspacing="0" width="100%" id="follow_list">
                    <thead>
                        <tr>
                            <th width="5%" height="25">Sr. No.</th>
                            <th width="10%" height="25">Followed By</th>
                            <th width="10%" height="25">Contact Info.</th>
                            <th width="10%" height="25">Timestamp</th>
                            <th width="10%" height="25">Notification Status</th>
                        </tr>
                    </thead>
                    <tbody>
                @if(!empty($follow) && count($follow) != 0)
                    @foreach($follow as $forum)
                        <tr>
                            <td>{{ $forum->id }}</td>
                            <td>{{ $forum->full_name }}</td>
                            <td><a href="mailto:{{ $forum->email }}">
                                   {{ $forum->email }}</a><br>
                                @if(!empty($forum->skype_id))
                                <img width="14" height="14" src="{{ url("assets/images/skype_icon_small.png")}}" border="0"> {{ $forum->skype_id }}<br>
                                @endif
                                @if(!empty($forum->phone_number))
                                <img src="{{ url("assets/images/phone_number.png")}}" height="14" border="0"/> {{ $forum->phone_number }}
                                @endif
                            </td>
                            <td>{{ dateformat($forum->created_at,DISPLAY_DATE)}}</td>
                            <td>
                                @if($forum->notification_status == 1)
                                <span style="color: green">ON</span>
                                @else
                                <span style="color: red">OFF</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                <tr>
                    <td class="text-center" colspan='5'>Sorry! No Data Found</td>
                </tr>
               @endif
                    </tbody>
                </table>
            </fieldset>
        <script type="text/javascript">
        $('#follow_list').dataTable();
        </script>
        @elseif($action == "loadreplies")
            <fieldset class="p-t-20" style="border:0;">
                <table class="table scrollingtable sortable_data" cellspacing="0" width="100%" id="reply_list">
                    <thead>
                        <tr>
                            <th width="10%" height="25">Sr. No.</th>
                            <th width="15%" height="25">Posted By</th>
                            <th width="10%" height="25">Contact Info.</th>
                            <th width="40%" height="25">Comments</th> 
                            <th width="5%" height="25">Status</th>
                            <th width="20%" height="25">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                @if(!empty($replydata) && count($replydata) != 0)
                    @foreach($replydata as $reply)
                        <tr>
                            <td>{{ $reply->id }}</td>
                            <td>{{ $reply->full_name }}</td>
                            <td><a href="mailto:{{ $reply->email }}">
                                   {{ $reply->email }}</a><br>
                                @if(!empty($reply->skype_id))
                                <img width="14" height="14" src="{{ url("assets/images/skype_icon_small.png")}}" border="0"> {{ $reply->skype_id }}<br>
                                @endif
                                @if(!empty($reply->phone_number))
                                <img src="{{ url("assets/images/phone_number.png")}}" height="14" border="0"/> {{ $reply->phone_number }}
                                @endif
                            </td>
                            <td>{{ $reply->comment_text }}</td>
                            <td>
                                @if($reply->status == 1)
                                    <span style="color: green">Like</span>
                                @else
                                    <span style="color: red">Unlike</span>
                                @endif
                            </td>
                            <td>
                                @if(check_route_access('comment.edit.form'))
                                <a href="{{ route('comment.edit.form',encrypt($reply->id)) }}" data-toggle="tooltip" data-original-title="Edit"> <i class="fa fa-pencil text-inverse m-r-10"></i> </a>
                                @endif 
                                @if(check_route_access('comment.delete'))
                                                <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Delete" onclick="return confirmDelete('{{ route('comment.delete',encrypt($reply->id)) }}');" > <i class="fa fa-close text-danger m-r-10"></i> </a>
                                @endif 
                                <form style="display:inline;" method="post" action="" name="dlt_apiuser" id="{{ $reply->id }}_api_user_dlt" >
                                            <input type="checkbox" name="notify_me_of_replies"  style="display: none;"  onchange="change_comment_status('{{ $reply->id }}');" id="{{ $reply->id }}" value="1" class="mini-switch" {{ $reply->status=="1" ? 'checked="checked"' : '' }}>
                                            <span class="mini-switch-replace" title="{{ $reply->status=="1" ? 'Inactive Comment Status' : 'Active Comment Status' }}" ></span>
                                </form>
                            </td>
                            
                        </tr>
                        @endforeach
                    @else
                    <tr>
                        <td class="text-center" colspan='5'>Sorry! No Data Found</td>
                    </tr>
                    @endif
                    </tbody>
                </table>
            </fieldset>
        <script type="text/javascript">
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
            $(document).ready(function() {
                $('.mini-switch-replace').click(function () {
                    $(this).prev().click();
                });
            });
        $('#reply_list').dataTable();
        </script>
        @elseif($action == "loadlikes" || $action == "loadunlikes")
        @if(!empty($likes) && count($likes) != 0)
            [ {{$categorydata->category_title}} > {{$categorydata->sub_category_title}} > {{$categorydata->title}} ]
        @endif
            <fieldset class="p-t-20" style="border:0;">
                <table class="table scrollingtable sortable_data" cellspacing="0" width="100%" id="likes_list">
                    <thead>
                        <tr>
                            <th width="5%" height="25">Sr. No.</th>
                            <th width="10%" height="25">Liked / Unliked By</th>
                            <th width="10%" height="25">Contact Info.</th>
                            <th width="10%" height="25">Timestamp</th>
                            <th width="10%" height="25">Status</th>
                        </tr>
                    </thead>
                    <tbody>
            @if(!empty($likes) && count($likes) != 0)
                @foreach($likes as $like)
                    <tr>
                        <td>{{ $like->id }}</td>
                        <td>{{ $like->full_name }}</td>
                        <td><a href="mailto:{{ $like->email }}">
                               {{ $like->email }}</a><br>
                            @if(!empty($like->skype_id))
                            <img width="14" height="14" src="{{ url("assets/images/skype_icon_small.png")}}" border="0"> {{ $like->skype_id }}<br>
                            @endif
                            @if(!empty($like->phone_number))
                            <img src="{{ url("assets/images/phone_number.png")}}" height="14" border="0"/> {{ $like->phone_number }}
                            @endif
                        </td>
                        <td>{{ dateformat($like->created_at,DISPLAY_DATE)}}</td>
                        <td>
                            @if($like->status == 1)
                            <span style="color: green">Like</span>
                            @else
                            <span style="color: red">Unlike</span>
                            @endif
                        </td>

                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan='5'>Sorry! No Data Found</td>
                </tr>
            @endif
                    </tbody>
                </table>
            </fieldset>
        <script type="text/javascript">
        $('#likes_list').dataTable();
        </script>
        @elseif($action == "loadviews")
        @if(!empty($views) && count($views) != 0)
        [ {{$categorydata->category_title}} > {{$categorydata->sub_category_title}} > {{$categorydata->title}} ]
        @endif
            <fieldset class="p-t-20" style="border:0;">
                <table class="table scrollingtable sortable_data" cellspacing="0" width="100%" id="views_list">
                    <thead>
                        <tr>
                            <th width="5%" height="25">Sr. No.</th>
                            <th width="10%" height="25">Viewed By</th>
                            <th width="10%" height="25">Contact Info.</th>
                            <th width="10%" height="25">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
            @if(!empty($views) && count($views) != 0)
                @foreach($views as $view)
                    <tr>
                        <td>{{ $view->id }}</td>
                        <td>{{ $view->full_name }}</td>
                        <td><a href="mailto:{{ $view->email }}">
                               {{ $view->email }}</a><br>
                            @if(!empty($view->skype_id))
                            <img width="14" height="14" src="{{ url("assets/images/skype_icon_small.png")}}" border="0"> {{ $view->skype_id }}<br>
                            @endif
                            @if(!empty($view->phone_number))
                            <img src="{{ url("assets/images/phone_number.png")}}" height="14" border="0"/> {{ $view->phone_number }}
                            @endif
                        </td>
                        <td>{{ dateformat($view->created_at,DISPLAY_DATE)}}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan='5'>Sorry! No Data Found</td>
                </tr>
            @endif
                    </tbody>
                </table>
            </fieldset> 
        <script type="text/javascript">
        $('#views_list').dataTable();
        </script>
    @elseif($action == "reject_document_reason_form")
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Explain to user why this document is rejected</h4>
        </div>
        <form id="document_reject_reason_form" action="{{ route('document.reject.reason') }}" autocomplete="off">
            <input type="hidden" name="doc_id" value="{{ $doc_id }}" />
            <input type="hidden" name="action" value="document_reject_reason"/>
            <div class="response"></div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="font-light font-13">Document <span class="text-danger">*</span></label>
                            <textarea name="reject_message" class="form-control" placeholder="Enter explanation to user why this document is rejected" required=""></textarea> 
                            <div class="help-block with-errors"></div>
                        </div> 
                    </div> 
                </div> 
            </div>   
            <div class="modal-footer">
                <button type="submit" class="btn btn-info text-left">Submit</button>
                <button type="button" class="btn btn-danger text-left" data-dismiss="modal">Close</button>
            </div>
        </form>
    </div> 
    <script type="text/javascript">
        $(document).ready(function(){ 
            ajaxFormValidator("#document_reject_reason_form",function(ele,event){
                event.preventDefault();
                
                var form_data = new FormData(ele);
                var action = "{{ route('document.reject.reason') }}"; 
                var document_id = {{ $doc_id }};
                $.ajax({
                    url: action,
                    type: 'post',
                    data: form_data,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response){  
                        if(response.type == "success")
                        { 
                            showLoader("#full-overlay");
                            confirmAlert("On confirm document will be rejected.","warning","Are you sure?","Confirm",function(r,i){
                                if(i){
                                    documentAction(document_id,"reject",function(){
                                        hide_popup();
                                        var Html = '<div class="alert swl-alert-success"><ul><li>Document rejected successfully.</li></ul></div>'; 
                                        notifyResponseTimerAlert(Html,"success","Success"); 
                                        @if(!empty($active_step_key) && !empty($active_stage_key))
                                            setTimeout(function(){
                                                navigateStages("{{$active_stage_key}}","{{$active_step_key}}");
                                            }, 3000);
                                        @elseif(!empty($section_id))
                                            setTimeout(function(){
                                                loadDocumentList("{{$section_id}}");
                                            }, 3000);
                                        @endif
                                    });
                                }
                                else{
                                    hideLoader("#full-overlay");
                                }
                            }); 
                        }
                        else if(response.type == "validation_error"){
                            var Html = '<div class="alert swl-alert-danger"><ul>'; 
                            $.each( response.message, function( key, value ) { 
                                Html += '<li>' + value+ '</li>';  
                            });
                            Html += '</ul></div>';
                            notifyResponseTimerAlert(Html,"error","Error");
                        } 
                    },
                });
            });
        });
    </script>
    @elseif($action == "interview_preview")
    <input type="hidden" name="action" value="schedule_prescreen_interview"/> 
    <table class="table sortable_data" cellspacing="0" width="100%">
        <thead>
            <tr align="center">
                <th>Admin</th>
                <th>Admin Timezone</th>
                <th>Admin Date/Time</th>
                <th>Candidate</th>
                <th>Candidate Timezone</th>
                <th>Candidate Date/Time</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $int_data->admin_name }}</td>
                <td>{{ $int_data->admin_timezone_name }}</td>
                <td>{{ dateformat($int_data->date_interview_admin,DISPLAY_DATETIME) }}</td>
                <td>{{ $int_data->user_name }}</td>
                <td>{{ (!empty($int_data->user_timezone_name))? $int_data->user_timezone_name : 'N/A' }}</td>
                <td>{{ (!empty($int_data->user_datetime))? dateformat($int_data->user_datetime,DISPLAY_DATETIME) : 'N/A' }}</td>
                <input type="hidden" name="admin_id" value="{{$int_data->admin_id}}">
                <input type="hidden" name="admin_timezone" value="{{$int_data->time_zone_admin}}">
                <input type="hidden" name="admin_datetime" value="{{$int_data->date_interview_admin}}">
                <input type="hidden" name="user_timezone" value="{{$int_data->user_timezone}}">
                <input type="hidden" name="user_datetime" value="{{ $int_data->user_datetime }}">
                </td>
            </tr>
        </tbody>
    </table>
    <div class="clear padding10"></div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <div class="col-sm-12">
                    <button type="reset" class="btn btn-danger" onclick="cancelInterview('{{ $int_data->active_step_key }}')">Cancel</button>
                    <button type="submit" class="btn btn-info">Add J1 Interview</button>
                </div>
            </div>
        </div>
    </div>
    @elseif($action == "booked_position")
        @php
            $pay_rate_basis = config('common.pay_rate_basis');
        @endphp
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Manage Candidate's Lead</h4>
        </div>
        <form id="positionbooked_form" autocomplete="off">
            <input type="hidden" name="pos_id" value="{{ $pos_id }}" />
            <input type="hidden" name="hc_id" value="{{ $hc_id }}" />
            <div class="response"></div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Start date: <span class="text-danger">*</span></label>
                                <input type="text" name="start_date" placeholder="Date and Time" class="form-control datepicker" required="" autocomplete="off" value="">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>End date: <span class="text-danger">*</span></label>
                                <input type="text" name="end_date" placeholder="Date and Time" class="form-control datepicker" required="" autocomplete="off" value="">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                    </div> 
                    <div class="col-xs-12">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Stipend:<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="salary" name="salary" placeholder="Enter stipend" required>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Pay Rate Basis:<span class="text-danger">*</span></label>
                                <select name="pay_rate_basis" id="pay_rate_basis" class="form-control" required>
                                    <option value="">Select option</option>
                                    @foreach($pay_rate_basis as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                    </div> 
                </div> 
            </div>   
            <div class="modal-footer">
                <button type="submit" class="btn btn-info text-left">Booked Position</button>
                <button type="button" class="btn btn-danger text-left" data-dismiss="modal">Close</button>
            </div>
        </form>
    </div> 
    <script type="text/javascript">
        $(document).ready(function(){ 
            load_datepicker();
            
            ajaxFormValidator("#positionbooked_form",function(ele,event){
                event.preventDefault();
                showLoader("#full-overlay");
                
                var user_id = $('meta[name="user_token"]').attr('content');
                var form_data = new FormData(ele);
                form_data.append('user_id',user_id);
                form_data.append('action',"save_booked_position");
                url = "{{route('hiring.stage')}}";
                $.ajax({
                    url: url,
                    type: 'post',
                    data: form_data,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response){
                        if(response.type == "success")
                        {
                            hide_popup();
                            var Html = '<div class="alert swl-alert-success"><p>'+response.message+'</p></div>'; 
                            notifyResponseTimerAlert(Html,"success","Success");
                            setTimeout(function(){
                                 navigateStages('2',"2_booked");
                            },3000);
                        }
                        else
                        {
                            var messages = response.message;
                            serverValidator(ele,messages);
                        }
                        hideLoader("#full-overlay");
                    }
                });
            });
        });
    </script>
    @elseif($action == "hc_interview")
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Schedule Interview</h4>
        </div>
        <form id="interview_form" autocomplete="off">
            <input type="hidden" name="pos_id" value="{{ $pos_id }}" />
            <input type="hidden" name="hc_id" value="{{ $hc_id }}" />
            <input type="hidden" name="portfolio_id" value="{{ $portfolio_id }}" />
            <div class="response"></div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Timezone: <span class="text-danger">*</span></label>
                                <select name="time_zone_user" id="time_zone_user" class="form-control" required="">
                                    <option value="">-- Select Timezone --</option>
                                    @foreach($timezones as $zone)
                                        <option value="{{ $zone->zone_id }}">{{ $zone->zone_label }}</option>
                                    @endforeach
                                </select> 
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Host Company/Placement Date and Time: <span class="text-danger">*</span></label>
                                <input type="text" name="end_date" placeholder="Date and Time" class="form-control datetimepicker" required="" autocomplete="off" value="">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                    </div> 
                </div> 
            </div>   
            <div class="modal-footer">
                <button type="submit" class="btn btn-info text-left">Schedule Interview</button>
                <button type="button" class="btn btn-danger text-left" data-dismiss="modal">Close</button>
            </div>
        </form>
    </div> 
    <script type="text/javascript">
        $(document).ready(function(){ 
            load_datetimepicker();
            
            ajaxFormValidator("#interview_form",function(ele,event){
                event.preventDefault();
                showLoader("#full-overlay");
                
                var user_id = $('meta[name="user_token"]').attr('content');
                var form_data = new FormData(ele);
                form_data.append('user_id',user_id);
                form_data.append('action',"schedule_hc_interview");
                url = "{{route('hiring.stage')}}";
                $.ajax({
                    url: url,
                    type: 'post',
                    data: form_data,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response){
                        if(response.type == "success")
                        {
                            hide_popup();
                            var Html = '<div class="alert swl-alert-success"><p>'+response.message+'</p></div>'; 
                            notifyResponseTimerAlert(Html,"success","Success");
                            setTimeout(function(){
                                location.reload(true);
                            },3000);
                        }
                        else
                        {
                            var messages = response.message;
                            serverValidator(ele,messages);
                        }
                        hideLoader("#full-overlay");
                    }
                });
            });
        });
    </script>
    @elseif($action == "document_type")
    <select name="document_type" id="document_type" class="form-control" required>
        <option value="">-- Select Document Type --</option>
        @if (!empty($document_types))
            @foreach($document_types as $data)
                <option value="{{$data->id}}" {{ is_selected(old('document_type'),$data->id) }}>{{$data->name}}</option>
            @endforeach
        @endif
    </select>
    @else
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Failed to load data</h4>
        </div> 
        <div class="modal-body">
            <div class="row">
                <div class="col-xs-12">
                    Failed to load data
                </div>
            </div> 
        </div>
    </div>
    @endif
@endif