@php
    $step_status = @$step_verified_data['step_status'];
    $contract_status = @$step_verified_data['contract_status'];
    $contracts = @$step_verified_data['contracts'];
    $agencies = @$step_verified_data['agencies'];
    $agency_data = @$step_verified_data['agency_data'];
@endphp
<div class="row">
    <div class="col-xs-12">
        <h3>{{ $step_title }}</h3> 
        
        <div id="{{ $notify_id }}" class="col-xs-12"></div>
        @if(empty($step_status))
            <p>Step is disable</p>
        @else
            <div>
                @if(!empty($contracts))
                <p>Below is the list agencies willing to contract with you, You can either <strong>Accept</strong> or <strong>Reject</strong> the agency contract.</p>
                <div class="table-responsive">
                    <table class="table color-bordered-table info-bordered-table">
                        <thead>
                            <tr>
                                <th>Agency Name</th>
                                <th>Action</th>
                                <th>Expire On</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($contracts as $contract)
                            @php
                                $form_id = "frm_agency_request_{$loop->count}";
                            @endphp
                            <tr>
                                <td>{{ $contract->agency->agency_name }}</td>
                                <td>
                                    @if($contract->request_by == 2 && $contract->request_status == 1)
                                        <span class="label label-warning">Request Sent</span>
                                    @elseif($contract->request_status == 2)
                                        <span class="label label-success">Request Accepted</span>
                                    @elseif($contract->request_status == 3)
                                        <span class="label label-danger">Rejected</span>
                                    @endif

                                    @if($contract_status == 2 && $contract->request_by == 1 && $contract->request_status == 1 && $contract->is_expired == 0)
                                        <form id="{{ $form_id }}" method="post" action="" onsubmit="return agencyRequestAction(this);">
                                            <input type="hidden" name="active_step_key" value="{{ $active_step_key }}" />
                                            <input type="hidden" name="contract" value="{{ encrypt($contract->id) }}" />
                                            <input type="hidden" name="agency" value="{{ encrypt($contract->agency->id) }}" />
                                            <input type="hidden" name="btn_action" value="" />
                                            <button type="submit" class="btn btn-success" value="accept" onclick="return setFormBtnAction('{{ $form_id }}',this.value);">Accept</button>
                                            <button type="submit" class="btn btn-danger" value="reject" onclick="return setFormBtnAction('{{ $form_id }}',this.value);">Reject</button>
                                        </form>
                                    @endif
                                </td>
                                <td>
                                    @if($contract->request_status != 2)
                                        @if($contract->is_expired == 1)
                                            <span class="label label-danger">Expired</span>
                                        @else
                                            {{ get_countdown_date($contract->created_at) }}
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
            @if($contract_status == 1)
            <div>
                <p>Your contract started with agency <strong>{{ @$agency_data->agency_name }}</strong>.</p>
            </div>
            @elseif($contract_status == 3)
            <div>
                <form name="frm_agency_request" id="frm_agency_request">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Select agency for send contract request:</label>
                            <select name="agency" id="agency" class="form-control" required>
                                <option value="">-- Select Option --</option>
                                @if(!empty($agencies))
                                @foreach($agencies as $agency)
                                <option value="{{ encrypt($agency->id) }}">{{ $agency->agency_name }}</option>
                                @endforeach
                                @endif
                            </select>
                            <div class="clearfix"></div>
                            <div class="help-block with-errors">
                                @if ($errors->has('agency')){{ $errors->first('agency') }}@endif
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="agree" value="1" required /> I agree terms and conditions.
                            </label>
                            <div class="clearfix"></div>
                            <div class="help-block with-errors">
                                @if ($errors->has('agree')){{ $errors->first('agree') }}@endif
                            </div>
                        </div>
                    </div>
                    @if($is_step_locked != 1)
                    <div class="col-xs-12">
                        <div class="form-actions">
                            <button type="submit" name="submit_request" id="submit_request" class="btn btn-md btn-info m-b-10">Send Request</button>
                        </div>
                    </div>
                    @endif
                </form>
            </div>
            @endif

            @if(!empty($next_step_key) && $step_status == 2)
                <button type="button" class="btn btn-info" onclick="loadStepContent('{{ $next_step_key }}')">Next Step</button>
            @endif
        @endif 
    </div>
</div>
<script>
    var notify_id = "{{ $notify_id }}";
    var active_stage = "{{ $active_stage }}";
    $(document).ready(function(){
        
        var form_selector = "#frm_agency_request";
        
        ajaxFormValidator(form_selector,function(ele,event){

            event.preventDefault();
            show_inner_loader(".timeline_stp_desc","#all_tab_data");

            var form_data = new FormData(ele);
            var url = "{{ route('contract.request') }}";

            $.ajax({
                url: url,
                type: 'post',
                data: form_data,
                dataType: 'json',
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response){
                    var messages = response.message;
                    if(response.type == "success"){
                        notifyResponseTimerAlert(messages,response.type,response.type.charAt(0).toUpperCase()+ response.type.slice(1));
                        notifyResponse("#"+notify_id,messages,response.type);

                        setTimeout(function(){ navigateStages(active_stage); }, 3000);
                    }
                    else if(response.type == "validation_error"){
                        var Html = '<div class="alert swl-alert-danger"><ul>'; 
                        $.each( messages, function( key, value ) {
                            Html += '<li>' + value+ '</li>';  
                        });
                        Html += '</ul></div>';  
                        notifyResponseTimerAlert(Html,"error","Error",10000);
                    }
                    else{
                        notifyResponse("#"+notify_id,messages,response.type);
                    }
                    hide_inner_loader('.timeline_stp_desc',"#all_tab_data");
                },
            });
        });
    });
    
    function agencyRequestAction(ele){
        
        var confirm_message = "";
        var btn_action = $(ele).find("input[name='btn_action']").val();
        
        if(btn_action == "" || btn_action == "undefined" || btn_action == null){
            return false;
        }
        
        if(btn_action == "accept"){
            confirm_message = "By confirm, your contract will be started with the agency, make sure remaining contract requests will be expired automatically.";
        }
        else if(btn_action == "reject"){
            confirm_message = "By confirm you will reject request from agency.";
        }
        
        show_inner_loader(".timeline_stp_desc","#all_tab_data");
        confirmAlert(ele,confirm_message,"warning","Are you sure?","Confirm",function(ele,state){
            
            if(state){
                var form_data = new FormData(ele);
                $(ele).find("input[name='btn_action']").val("");

                var url = "{{ route('contract.request.action') }}";

                $.ajax({
                    url: url,
                    type: 'post',
                    data: form_data,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response){
                        if(response.type == "success"){
                            navigateStages(active_stage);
                        }
                    },
                });
            }
            hide_inner_loader('.timeline_stp_desc',"#all_tab_data");
        });
        
        return false;
    }
    
</script>