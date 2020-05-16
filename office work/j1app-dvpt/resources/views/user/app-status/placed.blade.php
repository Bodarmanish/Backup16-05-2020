@php
    $is_multi_placement = @$step_verified_data['is_multi_placement'];
    $step_status = @$step_verified_data['step_status'];
    $placement_data = @$step_verified_data['placement_data'];
    $is_placement_confirmed = @$step_verified_data['is_placement_confirmed'];
@endphp
<div class="row">
    <div class="col-sm-12">
        <h3>Placement (Hiring)</h3>
        @if(!empty($placement_data))
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
                            <li class="disabled"> <a title="Disabled Placement {{ $second_tab_order }}">Placement {{ $second_tab_order }}</a> </li>
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
                        @php $is_active = ""; @endphp
                        @foreach($placement_data as $item)
                            @php
                                $is_active = $item->is_active_class;
                            @endphp
                            <div id="pd-{{ $item->id }}" class="tab-pane fade in {{$is_active}}">
                                <div class="col-sm-12">
                                    @if($item->type == 2)
                                        <p><strong>{{ $item->hostCompany->hc_name }}</strong> confirmed that they are ready to offer you a {{ strtolower(__('application_term.position')) }}. It's time to speed up the process. </p>
                                        <div class="alert alert-warning p-25 m-b-20">
                                            <p>Confirmed placement at <strong>{{ $item->hostCompany->hc_name }}</strong></p>
                                            <p>{{ __('application_term.position') }}: <strong>{{ $item->position->pos_name }}</strong></p> 
                                        </div>
                                    @else
                                        <p>Thank you for taking the interview with the {{ strtolower(__('application_term.employer')) }} <strong>{{ $item->hostCompany->hc_name }}</strong>, we are waiting to hear back from this company to see if they confirmed you were selected for the available {{ strtolower(__('application_term.position')) }}.</p>
                                        <div class="alert alert-warning p-25 m-b-20">
                                            <p><strong>{{ __('application_term.employer') }}:</strong> {{ $item->hostCompany->hc_name }}</p>
                                            <p><strong>{{ __('application_term.position') }}:</strong> {{ $item->position->pos_name }}</p>
                                        </div>
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
                <div class="m-b-20">
                    @if(!empty($placement_data))
                        @if($placement_data[0]->type == 2)
                            <p><strong>{{ $placement_data[0]->hostCompany->hc_name }}</strong> confirmed that they are ready to offer you a {{ strtolower(__('application_term.position')) }}. It's time to speed up the process. </p>
                            <div class="alert alert-warning p-25 m-b-20">
                                <p><strong>{{ __('application_term.employer') }}:</strong> {{ $placement_data[0]->hostCompany->hc_name }}</p>
                                <p><strong>{{ __('application_term.position') }}:</strong> {{ $placement_data[0]->position->pos_name }}</p>
                            </div>
                        @else
                            <p>Thank you for taking the interview with the {{ strtolower(__('application_term.employer')) }} <strong>{{ $placement_data[0]->hostCompany->hc_name }}</strong>, we are waiting to hear back from this company to see if they confirmed you were selected for the available {{ strtolower(__('application_term.position')) }}.</p>
                            <div class="alert alert-warning p-25 m-b-20">
                                <p><strong>{{ __('application_term.employer') }}:</strong> {{ $placement_data[0]->hostCompany->hc_name }}</p>
                                <p><strong>{{ __('application_term.position') }}:</strong> {{ $placement_data[0]->position->pos_name }}</p>
                            </div>
                        @endif
                    @endif
                </div>
            @endif
        @else
        <div class="m-b-20"><div class="alert alert-danger p-25 m-b-20">Placement not booked yet, Please contact to administrator.</div></div>
        @endif
        <div>
        @if($step_status == 2)
            <button type="button" class="btn btn-info" onclick="return navigateStages('3');">Next Stage</button>
        @endif    
            <!-- <hr class="full_dotted_line m-b-20" />
            <p>Get some wild tips from previous J1 Visa students ...</p>
            <ul class="list-style-none m-b-10">
                <li><a href="#">How to Pass a Job Interview</a></li>
                <li><a href="#">How To Write A Killer Resume Objective</a></li>
                <li><a href="#">Seven Secrets of the Killer Resume</a></li>
                <li><a href="#">100 Things You Can Do To Improve Your English</a></li>
             </ul>
            <p>Have question to ask? <a href="#" class="text-info">Join the discussion</a></p> -->
        </div>
    </div>
</div>
<script>
    function getSponsorConnection(){
        $.ajax({
            url: "{{ route('sponsorupdated') }}",
            type: 'post',
            dataType: 'json',
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response){
                if(response.type == "success"){
                    stageComplete('3',"You have successfully completed this stage. Now it's the time to contract with sponsor agency...");
                }
                else
                {
                    navigateStages('3');
                }
                hide_inner_loader(".timeline_stp_desc","#all_tab_data");
            },
        });
    }
    
    
</script>