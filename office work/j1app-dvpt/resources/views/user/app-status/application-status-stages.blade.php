@if(!empty($action))
    @if($action == "navigate_stage")
        @php
            $active_stage = (!empty($active_stage))?@$active_stage:1;
            $active_step = (!empty($active_step))?@$active_step:1;
            $active_step_key = (!empty($active_step_key))?@$active_step_key:1;
            $step_list = @$step_list;
            $app_status_stages = @$app_status_stages;
            $sidebar_title = $app_status_stages[$active_stage]['sidebar_title'];
            $tab_id = "stage_content";
        @endphp
        <div id="{{ $tab_id }}" role="tabpanel" class="application_status tab-pane fade active in">
            <div class="row">
                <div class="col-lg-7 col-md-8 col-sm-7 col-xs-12 timeline-xs-l-width">
                    <div class="timeline_stp_desc">
                        <div class="panel panel-default active m-b-0"> 
                            <div class="panel-wrapper collapse in">
                                <div class="panel-body">
                                    {!! $active_step_content !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="col-lg-5 col-md-4 col-sm-5 col-xs-5 timeline-xs-r-width">
                    <div class="open-timeline-panel" onclick="toggle_timeline_stps();"><i class="ti-angle-left ti-angle-right"></i></div>
                    <div class="timeline-xs-r-pnl timeline_stps">
                        <div class="steps_sidebar">
                            <a data-toggle="offcanvas" class="steps_sidebar_toggle text-white cpointer" onclick="toggle_sidebar('{{ $tab_id }}');">
                                <span class="icon_mini" rel="tooltip" title="{{ $sidebar_title }}">
                                    <i class="fa fa-bars font-13 m-r-5 hidden-xs"></i><small>  {{ $sidebar_title }}</small> 
                                </span>
                                <span class="icon_collapse text-white" rel="tooltip" title="{{ $sidebar_title }}">
                                    <i class="fa fa-times font-13 m-r-5 hidden-xs" aria-hidden="true"></i><small>{{ $sidebar_title }}</small> 
                                </span>
                            </a>
                        </div>
                        <div class="timeline_stp white-box no-padding"> 
                            @foreach($step_list as $step)
                                @if(!empty($step->j1_status_id))
                                    <div class="panel panel-default" id="stp_{{ $step->as_order_key }}">
                                        <div class="panel-heading b-bottom-0">  
                                            <div class="form-group">
                                                <div class="row timeline-step-inner"> 
                                                    @if($step->user_step_status == 2)
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 step_circle" onclick="loadStepContent('{{ $step->as_order_key }}');">
                                                            @if($active_stage!=4)<div class="step_no text-white bg-success">{{ $step->as_order }}</div>@endif
                                                            <div class="pie_progress step_pie_progress" role="progressbar" data-goal="100%" data-barcolor="#7ace4c" data-barsize="3" aria-valuemin="-100" aria-valuemax="100" rel="tooltip" title="{{ $step->as_title }}">
                                                                <div class="pie_progress__content text-success">
                                                                    <i class="fa fa-check"></i>
                                                                </div>
                                                            </div>
                                                            @if($step->step_alert == 1)
                                                                <i class="mdi mdi-alert-outline step-alert"></i>
                                                            @endif
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 text-left step_title">
                                                            <h4><a class="text-success cpointer" onclick="loadStepContent('{{ $step->as_order_key }}');">{{ $step->as_title }}</a></h4>
                                                            <div class="panel-wrapper collapse"> 
                                                                <small>{!! $step->as_desc_after !!}</small> 
                                                            </div>
                                                        </div>
                                                        <div class="step_accordion">
                                                            <a data-perform="panel-collapse" class="stp_collapse m-t-5 cpointer">
                                                                <i class="text-success fa fa-chevron-down"></i>
                                                            </a> 
                                                        </div>
                                                    @elseif($step->user_step_status == 1)
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 step_circle" onclick="loadStepContent('{{ $step->as_order_key }}');">
                                                            @if($active_stage!=4)<div class="step_no text-white bg-info">{{ $step->as_order }}</div>@endif
                                                            <div class="pie_progress" role="progressbar" data-goal="0%" data-barcolor="#41b3f9" data-barsize="3" aria-valuemin="-100" aria-valuemax="100" rel="tooltip" title="{{ $step->as_title }}">
                                                                <div class="pie_progress__content text-info">
                                                                    <i class="fa {{ $step->as_icon }}"></i>
                                                                </div>
                                                            </div>
                                                            @if($step->step_alert == 1)
                                                                <i class="mdi mdi-alert-outline step-alert"></i>
                                                            @endif
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 text-left step_title">
                                                            <h4><a class="text-info cpointer" onclick="loadStepContent('{{ $step->as_order_key }}','{{ $active_stage }}');" >{{ $step->as_title }}</a></h4>
                                                            <div class="panel-wrapper collapse"> 
                                                                <small>{!! $step->as_desc_current !!}</small> 
                                                            </div>
                                                        </div>
                                                        <div class="step_accordion">
                                                            <a data-perform="panel-collapse" class="stp_collapse m-t-5 cpointer">
                                                                <i class="text-info fa fa-chevron-down"></i>
                                                            </a> 
                                                        </div>
                                                    @else
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 step_circle">
                                                            @if($active_stage!=4)<div class="step_no text-white bg-muted">{{ $step->as_order }}</div>@endif
                                                            <div class="pie_progress" role="progressbar" data-goal="0%" data-barcolor="#41b3f9" data-barsize="3" aria-valuemin="-100" aria-valuemax="100" rel="tooltip" title="{{ $step->as_title }}">
                                                                <div class="pie_progress__content text-muted">
                                                                    <i class="fa {{ $step->as_icon }}"></i>
                                                                </div>
                                                            </div> 
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 text-left step_title">
                                                            <h4><span class="text-muted cpointer" >{{ $step->as_title }}</span></h4>
                                                            <div class="panel-wrapper collapse"> 
                                                                <small>{!! $step->as_desc_before !!}</small> 
                                                            </div>
                                                        </div>
                                                        <div class="step_accordion">
                                                            <a data-perform="panel-collapse" class="stp_collapse m-t-5 cpointer">
                                                                <i class="text-muted fa fa-chevron-down"></i>
                                                            </a> 
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>    
                </div>    
            </div>  
        </div>
    @elseif($action == "navigate_step")
        @php
            $active_stage = (!empty($active_stage))?@$active_stage:1;
            $active_step = (!empty($active_step))?@$active_step:1;
            $current_stage = @$current_stage;
            $current_step = @$current_step;
            $active_step_key = @$request_step_data->as_order_key;
            $prev_step_key = @$request_step_data->prev_order_key;
            $next_step_key = @$request_step_data->next_order_key;
            $notify_id = "notify_{$active_stage}_{$active_step}";
            $is_step_success = @$step_verified_data['is_step_success'];
            $step_title = @$step_verified_data['step_title'];
            $is_multi_placement = @$step_verified_data['is_multi_placement'];
        @endphp
        @if ($active_step_key == "1_eligibility_test")
            @include('user.app-status.eligibility-test')

        @elseif ($active_step_key == "1_resume_upload")
            @include('user.app-status.resume')

        @elseif ($active_step_key == "1_resume_approval")
            @include('user.app-status.resume-approval')

        @elseif ($active_step_key == "1_skype")
            @include('user.app-status.skype')

        @elseif ($active_step_key == "1_j1_interview")
            @include('user.app-status.j1-interview')

        @elseif ($active_step_key == "1_j1_agreement")
            @include('user.app-status.j1-agreement')

        @elseif ($active_step_key == "1_registration_fee")
            @include('user.app-status.registration-fee')

        @elseif ($active_step_key == "1_additional_info")
            @include('user.app-status.additional-info')
            
        @elseif ($active_step_key == "2_contract_placement")
            @include('user.app-status.contract')

        @elseif ($active_step_key == "2_supporting_documents")
            @include('user.app-status.documents')

        @elseif ($active_step_key== "2_searching_position")
            @include('user.app-status.searching-position')

        @elseif ($active_step_key == "2_booked")
            @include('user.app-status.booked')

        @elseif ($active_step_key == "2_placed")
            @include('user.app-status.placed')

        @elseif ($active_step_key == "3_contract_sponsor")
            @include('user.app-status.contract')
            
        @elseif ($active_step_key == "3_post_placement_documents")
            @include('user.app-status.documents')


        @elseif ($active_step_key == "3_ds7002_pending")
            @include('user.app-status.ds7002-pending')

        @elseif ($active_step_key == "3_ds7002_created")
            @include('user.app-status.ds7002_created')

        @elseif ($active_step_key == "3_ds7002_signed")
            @include('user.app-status.ds7002_signed')

        @elseif ($active_step_key == "3_ds2019_sent")
            @include('user.app-status.ds2019_sent')

        @elseif ($active_step_key == "3_us_embassy_interview")
            @include('user.app-status.embassy-interview')

        @elseif ($active_step_key == "3_us_visa_outcome")
            @include('user.app-status.visa')

        @elseif ($active_step_key == "3_flight_info")
            @include('user.app-status.flight-info')

        @elseif ($active_step_key == "3_arrival_in_usa")
            @include('user.app-status.arrived')

        @elseif ($active_step_key == "1_program_completed")
            @include('UserInterface.applicationstatus.programCompleted')

        @elseif ($active_step_key == "1_medical_insurance")
            @include('UserInterface.applicationstatus.medicalInsurance') 

        @elseif ($active_step_key == "1_arrival_check_in")
            @include('UserInterface.applicationstatus.arrivalCheckIn') 

        @elseif ($active_step_key == "1_monthly_check_in")
            @include('UserInterface.applicationstatus.monthlyCheckIn') 

        @elseif ($active_step_key == "1_mid_term_participant_evaluation")
            @include('UserInterface.applicationstatus.midTermParticipantEvaluation')

        @elseif ($active_step_key == "1_review_your_mid_supervisor_evaluation")
            @include('UserInterface.applicationstatus.reviewYourMidSupervisorEvaluation')

        @elseif ($active_step_key == "1_final_term_participant_evaluation")
            @include('UserInterface.applicationstatus.finalTermParticipantEvaluation')

        @elseif ($active_step_key == "1_review_your_final_supervisor_evaluation")
            @include('UserInterface.applicationstatus.reviewYourFinalSupervisorEvaluation')

        @endif
    @endif
@endif