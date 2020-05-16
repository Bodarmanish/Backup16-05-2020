@if(!empty($action))
    @if($action == "navigate_stage")
    <div role="tabpanel" class="tab-pane fade active in" id="{{ @$active_stage_key }}"> 
        <div class="vtabs">
            <div class="row">
                <div class="col-md-3 col-xs-12">
                    <ul class="nav tabs-vertical">
                        @php
                        $step_count = 1; 
                        foreach(@$step_list as $key => $step){ 
                            if(!empty($step->j1_status_id)){ 
                        @endphp
                            <li class="tab {{ (@$active_step_key == $step->as_order_key)?"active":"" }}" id="stp_{{ $step->as_order_key }}">
                                <a data-toggle="tab" href="#{{ $step->as_order_key }}" onclick="loadStepContent('{{ $step->as_order_key }}');"> 
                                    <span>{{ $step->as_title }}</span>
                                    @if($step->admin_step_status == 1)
                                        <i class="fa fa-check-circle pull-right"></i>
                                    @elseif(@$portfolio_key == $step->as_order_key)
                                        <i class="fa fa-hourglass-half pull-right"></i>
                                    @endif
                                </a>  
                            </li>
                        @php
                            }
                            $step_count++;
                        }
                        @endphp
                    </ul>
                </div> 
                <div class="col-md-9 col-xs-12">
                    <div class="tab-content">
                        {!! $active_step_content !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    @elseif($action == "navigate_step")
        @php
            $active_stage = (!empty($active_stage))?@$active_stage:1;
            $active_step = (!empty($active_step))?@$active_step:1; 
            $active_step_key = @$request_step_data->as_order_key;
            $prev_step_key = @$request_step_data->prev_order_key;
            $next_step_key = @$request_step_data->next_order_key;
            $notify_id = "notify_{$active_stage}_{$active_step}";
            $is_step_success = @$step_verified_data['is_step_success'];
            $step_status = @$step_verified_data['step_status']; 
            $step_title = @$step_verified_data['step_title']; 
        @endphp
        @if ($active_step_key == "1_eligibility_test")
            @include('admin.app-status.eligibility-test')

        @elseif ($active_step_key == "1_resume_upload")
            @include('admin.app-status.resume')

        @elseif ($active_step_key == "1_resume_approval")
            @include('admin.app-status.resume-approval')

        @elseif ($active_step_key == "1_skype")
            @include('admin.app-status.skype')

        @elseif ($active_step_key == "1_j1_interview")
            @include('admin.app-status.j1-interview')

        @elseif ($active_step_key == "1_j1_agreement")
            @include('admin.app-status.j1-agreement')

        @elseif ($active_step_key == "1_registration_fee")
            @include('admin.app-status.registration-fee')

        @elseif ($active_step_key == "1_additional_info")
            @include('admin.app-status.additional-info')
        
        @elseif ($active_step_key == "2_contract_placement")
            @include('admin.app-status.contract-placement')
            
        @elseif ($active_step_key == "2_supporting_documents")
            @include('admin.app-status.documents')

        @elseif ($active_step_key== "2_searching_position")
            @include('admin.app-status.searching-position')

        @elseif ($active_step_key == "2_booked")
            @include('admin.app-status.booked')

        @elseif ($active_step_key == "2_placed")
            @include('admin.app-status.placed')

        @elseif ($active_step_key == "3_contract_sponsor")
            @include('admin.app-status.contract-sponsor')
            
        @elseif ($active_step_key == "3_post_placement_documents")
            @include('admin.app-status.documents')

       @elseif ($active_step_key == "3_ds7002_pending")
            @include('admin.app-status.ds7002-pending')

        @elseif ($active_step_key == "3_ds7002_created")
            @include('admin.app-status.ds7002-created')

        @elseif ($active_step_key == "3_ds7002_signed")
            @include('admin.app-status.ds7002-signed')

        @elseif ($active_step_key == "3_ds2019_sent")
            @include('admin.app-status.ds2019-sent')

        @elseif ($active_step_key == "1_predeparture_orientation")
            @include('UserInterface.applicationstatus.predepartureOrientation')

        @elseif ($active_step_key == "3_us_embassy_interview")
            @include('admin.app-status.embassy-interview')

        @elseif ($active_step_key == "3_us_visa_outcome")
            @include('admin.app-status.visa')

        @elseif ($active_step_key == "3_flight_info")
            @include('admin.app-status.flight-info')

        @elseif ($active_step_key == "3_arrival_in_usa")
            @include('admin.app-status.arrived')

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