@extends('admin.layouts.app')
  
@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">User Manager</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="active">User Application Status Progress Manager</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">User Application Status Progress Manager</h3>
                        <p class="text-muted m-b-30">Progress Manager</p>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="row">
                            <div class="col-md-3 pull-right">
                                <a href="{{ route('user.app.list') }}" class="btn btn-block btn-info"><i class="fa fa-arrow-left"></i> Back</a>
                            </div> 
                            <div class="col-md-3 pull-right">
                                <a href="{{ \Request::url() }}" class="btn btn-block btn-info"><i class="fa fa fa-refresh"></i> Refresh</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m-b-10">
                    <div class="col-md-12">
                        <div class="alert alert-info text-dark"> 
                            <p>
                                <strong>User Id: </strong>{{@$user_id}} | 
                                <strong>User Name: </strong>{{@$full_name}} | 
                                <strong>User Email: </strong>{{@$email}}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="app_progress">
                            @if(!empty($app_status_stages))
                            <nav>
                                <ul class="nav nav-tabs" role="tablist">
                                @php
                                    $stage_count = 1;
                                    foreach($app_status_stages as $key => $stage)
                                    {
                                    $is_active = '';
                                    if(($active_stage == $stage_count) || (empty($active_stage) && $stage_count == 1))
                                    {
                                        $is_active = "active";
                                    }
                                @endphp
                                <li role="presentation" class="{{ $is_active }}" id="{{$stage_count}}"><a href="#{{ $stage['stage_key'] }}" aria-controls="{{ $stage['stage_key'] }}" role="tab" data-toggle="tab" aria-expanded="true" onclick="navigateStages({{ $key }});">{{ $stage['stage_title'] }}</a></li> 
                                @php
                                    $stage_count++;
                                }
                                @endphp  
                            </ul>
                            </nav>
                            <div class="content-wrap tab-content" id="all_tab_data">
                                @include('admin.app-status.application-status-stages')
                            </div>
                            @else
                                <p>Application status progress will start shortly.</p>
                            @endif
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>
  
<script type="text/javascript">
function loadStepContent(active_step_key, callback_success){
    var stage_id = "all_tab_data"; 
    var url = "{{ route('navigatestage') }}";
    var user_id = $('meta[name="user_token"]').attr('content');
    showLoader("#full-overlay");
    $.ajax({
        url: url,
        type: 'post',
        data: { action:"navigate_step", active_step_key: active_step_key, user_id: user_id },
        dataType: 'json',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response){
            $("#"+stage_id+" .tab-content").html(response.step_content);
            hideLoader("#full-overlay");
            load_datepicker();
            load_datetimepicker();
            if(typeof callback_success === "function")
            {
                callback_success();
            }
        },
    });
}

function navigateStages(stage_number,step_key){
    var user_id = $('meta[name="user_token"]').attr('content');
    var url = "{{ route('navigatestage') }}";
    var data = {
                action:"navigate_stage",
                active_stage: stage_number,
                request_step_key: step_key,
                user_id : user_id,
            };
    showLoader("#full-overlay");
    $.ajax({
        url: url,
        type: 'post',
        data: data,
        dataType: 'json',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response){ 
            if(response.type == "success")
            {  
                $('.nav-tabs li').removeClass('active');
                $("#"+stage_number).addClass('active');
                application_status_content = response.application_status_content; 
                $("#all_tab_data").html(application_status_content); 
                load_datepicker();
                load_datetimepicker();
                hideLoader("#full-overlay");
            }
        },
    });
}
</script>
@endsection