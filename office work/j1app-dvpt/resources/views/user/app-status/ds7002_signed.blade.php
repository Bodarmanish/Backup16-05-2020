@php
    $is_multi_placement = @$step_verified_data['is_multi_placement'];
    $step_status = @$step_verified_data['step_status'];
    $placement_data = @$step_verified_data['placement_data'];
@endphp
<div class="row"> 
    <div class="col-sm-12">
        <h3>Training placement Plan Signed</h3>
    </div>
    @if($step_status == 1 || $step_status == 2)
        @if($is_multi_placement == true)
            <div class="col-sm-12">
                <div class="multi_tab_view m-b-20">
                    <ul class="nav nav-pills" id="{{ $active_step_key }}">
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
                                <li class="{{ $is_complete.$is_active }}"> <a href="#pd-{{ $item->id }}" data-toggle="tab" aria-expanded="false" title="Placement with {{ $item->hostCompany->hc_name }}">Placement with {{ str_limit($item->hostCompany->hc_name, 20, '...') }}</a> </li>
                            @endforeach
                        @endif
                    </ul>
                    <div class="tab-content br-n pn">
                        @php $is_active = ""; @endphp
                        @foreach($placement_data as $item)
                            @php $is_active = $item->is_active_class; @endphp
                            <div id="pd-{{ $item->id }}" class="tab-pane fade in {{ $is_active }}">
                                <div class="col-sm-12">
                                    <p>The {{ __('application_term.ds7002') }} is a Department of State form and serves as the official outline of the proposed internship.</p>
                                    <p>Once we managed to find a {{ strtolower(__('application_term.employer')) }}  and you are offered a job, we will send you the {{ __('application_term.ds7002') }} Form for your signature. You will then need to print, sign and date the {{ __('application_term.ds7002') }} Form and resend it to our office.</p>
                                    <!-- <p>Have question to ask? <a href="#" class="text-info">Join the discussion</a></p> -->
                                    <br/>
                                    @if(!empty($item->dstp_download_link))
                                    <div class="alert alert-warning">
                                        <p>Your <strong>{{ __('application_term.ds7002') }} Training Plan Signed</strong> completed you can download signed document by <a href="{{ $item->dstp_download_link }}">Click Here</a>.</p>
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
            </div>
        @else
            <div class="col-sm-12">
                <p>The {{ __('application_term.ds7002') }} is a Department of State form and serves as the official outline of the proposed internship.</p>
                <p>Once we managed to find a {{ strtolower(__('application_term.employer')) }} and you are offered a job, we will send you the {{ __('application_term.ds7002') }} Form for your signature. You will then need to print, sign and date the {{ __('application_term.ds7002') }} Form and resend it to our office.</p>
                <!-- <p>Have question to ask? <a href="#" class="text-info">Join the discussion</a></p> -->
                <br/>
                <br/>
                @if(!empty($placement_data[0]->dstp_download_link))
                <div class="alert alert-warning">
                    <p>Your <strong>{{ __('application_term.ds7002') }} Training Plan Signed</strong> completed you can download signed document by <a href="{{ $placement_data[0]->dstp_download_link }}">Click Here</a>.</p>
                </div>
                @endif
            </div>
        
        @endif
        
        @if($step_status == 2 && !empty($next_step_key))
        <div class="col-sm-12">
            <button type="button" class="btn btn-info" onclick="loadStepContent('{{ $next_step_key }}')">Next Step</button>
        </div>
        @endif
    @else
        <div class="col-sm-12">
            <p>The {{ __('application_term.ds7002') }} is a Department of State form and serves as the official outline of the proposed internship.</p>
            <p>Once we managed to find a {{ strtolower(__('application_term.employer')) }} and you are offered a job, we will send you the {{ __('application_term.ds7002') }} Form for your signature. You will then need to print, sign and date the {{ __('application_term.ds7002') }} Form and resend it to our office.</p>
            <!-- <p>Have question to ask? <a href="#" class="text-info">Join the discussion</a></p> -->
        </div>
    @endif
</div>