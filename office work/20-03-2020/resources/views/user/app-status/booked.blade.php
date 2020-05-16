@php
    $is_multi_placement = @$step_verified_data['is_multi_placement'];
    $step_status = @$step_verified_data['step_status'];
    $placement_data = @$step_verified_data['placement_data'];
    $is_placement_confirmed = @$step_verified_data['is_placement_confirmed'];
@endphp
<div class="row">
    <div class="col-sm-12">
        <h3>Booking for Interview</h3>
        @if($step_status == 1 || $step_status == 2)
            @if($is_multi_placement == true)
                <div class="multi_tab_view m-b-20">
                    <ul class="nav nav-pills" id="{{ $active_step_key }}">
                        @php
                            $second_tab_order = 2;
                        @endphp
                        
                        @if(count($placement_data) == 1)
                            @php
                                $second_tab_order = ($placement_data[0]->pla_order == 2) ?  1 : 2;
                            @endphp
                            <li class="tab-success active"> <a href="#pd-{{ $placement_data[0]->id }}" data-toggle="tab" aria-expanded="false" title="Placement with {{ $placement_data[0]->hostCompany->hc_name }}">Placement with {{ str_limit($placement_data[0]->hostCompany->hc_name, 20, '...') }}</a> </li>
                            <li class="disabled"> <a>Placement {{ $second_tab_order }}</a> </li>
                        @else
                            @foreach($placement_data as $item)
                                @php
                                    $is_complete = $item->is_complete_class;
                                    $is_active = $item->is_active_class;
                                @endphp
                                <li class="{{ $is_complete.$is_active }}"> <a href="#pd-{{ $item->id }}" data-toggle="tab" aria-expanded="false" title="Placement with {{ $item->hostCompany->hc_name }}">
                                    @if($is_placement_confirmed == 1)
                                        Placement with {{ str_limit($item->hostCompany->hc_name, 20, '...') }}
                                    @else
                                        Placement {{ $item->pla_order }}
                                    @endif
                                </a> </li>
                            @endforeach
                        @endif
                    </ul>
                    
                    <div class="tab-content br-n pn">
                        @foreach($placement_data as $item)
                            @php
                                $is_active = $item->is_active_class;
                                $emp_interview_data = @$item->emp_interview_data;
                            @endphp
                            <div id="pd-{{ $item->id }}" class="tab-pane fade in {{ $is_active }}">
                                <div class="col-sm-12">
                                    <p>A <strong> {{ __('application_term.employer') }} has shown interest</strong> in your resume and would like to book you for an interview.<br/>
                                    <strong>"Booking for Interview" doesn't mean you have secured a training position yet</strong>, you will need to pass the interview and demonstrate to the {{ __('application_term.employer') }} why you are the right person for the {{ __('application_term.position') }}.</p>

                                    <div class="alert alert-warning p-25 m-b-20"> 
                                        <p>One of our representative has secured the following interview with <strong>"{{ $item->hostCompany->hc_name }}"</strong>, 
                                            @if(empty($emp_interview_data)) Your interview schedule will be arrange shortly.@endif
                                        </p>
                                        <ul>
                                            @if(!empty($emp_interview_data))
                                            <li><strong>Interview Duration:</strong> 30 Minutes</li>
                                            <li>
                                                {{ dateformat($emp_interview_data->dest_datetime,'H:i:a - l, F d, Y') }}
                                                <!--<a href="#"> Change time </a>  Pending to implement change time link--> 
                                            </li>
                                            <li>{{ get_timezone_label($emp_interview_data->dest_timezone_id) }}</li> 
                                            @endif
                                            <li><strong>{{ __('application_term.employer') }}:</strong> {{ $item->hostCompany->hc_name }}</li>
                                            <li><strong>{{ __('application_term.position') }}:</strong> {{ $item->position->pos_name }}</li>
                                        </ul>
                                    </div>
                                    @if(!empty($emp_interview_data->contact_email))
                                        <p>Please contact responsible person, if you are not able to attend the interview (<a href="mailto:{{ $emp_interview_data->contact_email }}">{{ $emp_interview_data->contact_email }}</a>)</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Bootstrap Accordion for mobile view -->
                    <div class="panel-group" id="application_status_accordion" role="tablist" aria-multiselectable="true"></div>
                    <!-- /Bootstrap Accordion for mobile view -->
                    <div class="clearfix"></div>
                </div>
            @else
                @php
                    $emp_interview_data = @$placement_data[0]->emp_interview_data;
                @endphp
                <div class="m-b-20">
                    <p>A <strong> {{ __('application_term.employer') }} has shown interest</strong> in your resume and would like to book you for an interview.<br/>
                    <strong>"Booking for Interview" doesn't mean you have secured a training position yet</strong>, you will need to pass the interview and demonstrate to the {{ __('application_term.employer') }} why you are the right person for the {{ __('application_term.position') }}.</p>

                    <div class="alert alert-warning p-25 m-b-20"> 
                        <p>One of our representative has secured the following interview with <strong>"{{ @$placement_data[0]->hostCompany->hc_name }}"</strong>, 
                            @if(empty($emp_interview_data)) Your interview schedule will be arrange shortly.@endif
                        </p>

                        <ul>
                            @if(!empty($emp_interview_data))
                            <li><strong>Interview Duration:</strong> 30 Minutes</li>
                            <li>
                                {{ dateformat($emp_interview_data->dest_datetime,'H:i:a - l, F d, Y') }}
                                <!--<a href="#"> Change time </a>  Pending to implement change time link--> 
                            </li>
                            <li>{{ get_timezone_label($emp_interview_data->dest_timezone_id) }}</li> 
                            @endif
                            <li><strong>{{ __('application_term.employer') }}:</strong> {{ @$placement_data[0]->hostCompany->hc_name }}</li>
                            <li><strong>{{ __('application_term.position') }}:</strong> {{ @$placement_data[0]->position->pos_name }}</li>
                        </ul>
                    </div>
                    @if(!empty($emp_interview_data->contact_email))
                        <p>Please contact responsible person, if you are not able to attend the interview (<a href="mailto:{{ $emp_interview_data->contact_email }}">{{ $emp_interview_data->contact_email }}</a>)</p>
                    @endif
                </div>
            @endif
            <div>
                @if(!empty($next_step_key) && $step_status == 2)
                    <button type="button" class="btn btn-info" onclick="loadStepContent('{{ $next_step_key }}')">Next Step</button>
                @endif
                <!--<hr class="full_dotted_line m-b-20" />
                <p>It's time to understand how to succeed in the job interview, prepare yourself with the help of our community. Join the discussion so that you are ever closer to your goal of obtaining a job offer.</p>
                <ul class="list-style-none m-b-10">
                    <li><a href="#">How to Prepare for an Interview Last Minute</a></li>
                    <li><a href="#">The Ultimate Guide to Job Interview Preparation</a></li>
                    <li><a href="#">How To Ace The 50 Most Common Interview Questions</a></li>
                    <li><a href="#">The Ultimate Interview Guide: 30 Prep Tips for Job</a></li>
                </ul>-->
            </div>
        @else
            <p>Once we find a {{ __('application_term.employer') }} you will see the details of your interview on here. </p>
        @endif
    </div>
</div>