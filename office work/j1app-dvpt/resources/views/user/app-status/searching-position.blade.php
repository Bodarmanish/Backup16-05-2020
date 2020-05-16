@php
    $placement_data = @$step_verified_data['placement_data'];
    $step_status = @$step_verified_data['step_status'];
    $is_multi_placement = @$step_verified_data['is_multi_placement'];
    $is_placement_confirmed = @$step_verified_data['is_placement_confirmed'];
@endphp
<div class="row">
    <div class="col-sm-12">
        <h3>Search Vacancies</h3>
        @if($step_status == 1 || $step_status == 2)
            @if($is_multi_placement == true)
                <div class="multi_tab_view m-b-20">
                    <ul class="nav nav-pills" id="{{ $active_step_key }}">
                        @php
                            $second_tab_order = 2;
                            $total_placement = count($placement_data);
                        @endphp
                        
                        @foreach($placement_data as $item)
                            @php
                                $is_complete = $item->is_complete_class;
                                $is_active = (count($placement_data) == $item->pla_order && count($placement_data) > 1) ? " active " : "";
                                $second_tab_order = ($item->pla_order == 2) ?  1 : 2;
                            @endphp
                            <li class="{{ $is_complete.$is_active }}"> <a href="#pd-{{ $item->id }}" data-toggle="tab" aria-expanded="false" title="Placement with {{ $item->hostCompany->hc_name }}">
                                @if($total_placement != 1 && $is_placement_confirmed == 1)
                                    Placement with {{ str_limit($item->hostCompany->hc_name, 20, '...') }}
                                @else
                                    Placement {{ $item->pla_order }}
                                @endif
                            </a> </li>
                        @endforeach
                        
                        @if(count($placement_data) == 1)
                        <li class="active tab-progress"> <a href="#pd-0" data-toggle="tab" aria-expanded="false" title="Placement {{ $second_tab_order }}">Placement {{ $second_tab_order }}</a> </li> 
                        @endif
                    </ul>
                    <div class="tab-content br-n pn">
                        @foreach($placement_data as $item)
                            @php
                                $is_active = (count($placement_data) == $item->pla_order && count($placement_data) > 1) ? " active " : "";
                            @endphp
                            <div id="pd-{{ $item->id }}" class="tab-pane fade in {{ $is_active }}">
                                <div class="col-sm-12">
                                    @if($item->type == 2)
                                        <div class="alert alert-success">Your placement confirmed with {{ strtolower(__('application_term.employer')) }} <strong>{{ $item->hostCompany->hc_name }}</strong>.</div>
                                    @else
                                        <p>We are currently searching for a suitable {{ strtolower(__('application_term.position')) }} for your <strong>@if($portfolio->program_id) {{ @$portfolio->program()->program_name }} @endif</strong> program. This step can take some time so please be patient, you will receive an update on your email.</p>
                                        <div class="alert alert-success">Your profile is currently reviewed by at least one {{ strtolower(__('application_term.employer')) }}.</div>
                                        <!-- {{--<div class="alert alert-success">Congratulations! {{ __('application_term.host_company') }} <strong>{{ $item->hostCompany->hc_name }}</strong> is reviewing your profile.</div>--}} -->
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        
                        @if(count($placement_data) == 1)
                            <div id="pd-0" class="tab-pane fade in active">
                                <div class="col-sm-12">
                                    <p>We are currently searching for a suitable {{ strtolower(__('application_term.position')) }} for your <strong>@if($portfolio->program_id) {{ @$portfolio->program()->program_name }} @endif</strong> program. This step can take some time so please be patient, you will receive an update on your email.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    <!-- Bootstrap Accordion for mobile view -->
                    <div class="panel-group" id="application_status_accordion" role="tablist" aria-multiselectable="true"></div>
                    <!-- /Bootstrap Accordion for mobile view -->
                    <div class="clearfix"></div>
                </div>
            @else
                <div class="m-b-20">
                    @if(!empty($placement_data[0]->type) && $placement_data[0]->type == 1)
                        <div class="alert alert-warning">Your profile is reviewed by at least one {{ strtolower(__('application_term.employer')) }}.</div>
                    @elseif(!empty($placement_data[0]->type) && $placement_data[0]->type == 2)
                        <div class="alert alert-success">Congratulations! Your placement is confirmed with {{ __('application_term.employer') }} <strong>{{ $placement_data[0]->hostCompany->hc_name }}</strong>.</div>
                    @else
                        <p>We are currently searching for a suitable {{ strtolower(__('application_term.position')) }} for your @if($portfolio->program_id)<strong>{{ @$portfolio->program->program_name }}</strong>@endif program. This step can take some time so please be patient, you will receive an update on your email.</p>
                    @endif
                </div>
            @endif
            <div>
                @if($step_status == 2 && !empty($next_step_key))
                    <button type="button" class="btn btn-info" onclick="loadStepContent('{{ $next_step_key }}')">Next Step</button>
                @endif
                <!-- <hr class="full_dotted_line m-b-20" />
                <p>Get some wild tips from previous J1 Visa students ...</p>
                <ul class="list-style-none m-b-10">
                    <li><a href="#">How to Pass a Job Interview</a></li>
                    <li><a href="#">How To Write A Killer Resume Objective</a></li>
                    <li><a href="#">Seven Secrets of the Killer Resume</a></li>
                    <li><a href="#">100 Things You Can Do To Improve Your English</a></li>
                </ul>
                <p>Have question to ask? <a href="#">Join the discussion</a></p> -->
            </div>
        @else
            <p>We start searching for available vacancies once you complete the <strong>Registration with J1</strong> stage.</p>     
        @endif
       
    </div>
</div>
<script>
    $(document).ready(function(){

    });
</script>