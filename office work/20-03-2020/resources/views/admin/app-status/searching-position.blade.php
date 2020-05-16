@php 
$lead_data = @$step_verified_data['lead_data']; 
$user_name = @$step_verified_data['user_name']; 
$placed_data = @$step_verified_data['placed_data']; 
$program_enroll = @$step_verified_data['program_enroll'];
$is_route66 = @$step_verified_data['is_route66'];
$pay_rate_basis = config('common.pay_rate_basis');
@endphp

<h3>Searching Position</h3> 
<div class="row"> 
    <div class="col-sm-12">
        @if(empty($step_status)) 
            <p>This step is disable until user will reach to step.</p>  
        @else
            @if(count($placed_data) >= 2 ||(empty($program_enroll) && count($placed_data) == 1) || (empty($is_route66) && count($placed_data) >= 2))
                <p>Lead can not be added for this candidate because candidate already placed.</p>
            @else
                <div class="well">
                    @if(count($lead_data) == 0 && check_route_access('add.lead'))
                        <div class="row">
                            <div class= "pull-right">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <a href="{{ route('add.lead',$user_token) }}" class="btn btn-success">Add Lead</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(!empty($lead_data) && count($lead_data) > 0)
                    <div class="panel">
                        <div class="row">
                            <div class="pull-left">
                                <div class="col-sm-12">
                                    <div class="panel-heading" id="schedule_interview_tab" role="tab">
                                        <a class="panel-title" data-toggle="collapse" href="#schedule_interview" data-parent="#interview" aria-expanded="true" aria-controls="schedule_interview">Lead</a>
                                    </div>
                                </div>
                            </div>
                            @if(count($lead_data) < 3 && check_route_access('add.lead'))
                            <div class= "pull-right">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <a href="{{ route('add.lead',$user_token) }}" class="btn btn-success">Add Lead</a>
                                    </div>
                                </div>
                            </div>
                            @endif
<!--                            <div class= "pull-right">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <a href="javascript:void(0)" onclick="popup_booked_position('hc_interview')" class="btn btn-info">Scheduled HC Interview</a>
                                    </div>
                                </div>
                            </div>-->
                            <div class="clearfix"></div>
                        </div>
                        <hr>
                        <div class="panel-collapse collapse {{ (!empty($lead_data))?'in':'' }}" id="schedule_interview" aria-labelledby="exampleHeadingDefaultOne" role="tabpanel">
                            <div class="panel-body">
                                <div class="interview_preview"><input type="hidden" name="action" value="schedule_prescreen_interview"> 
                                    <table class="table sortable_data" cellspacing="0" width="100%" id="leaddata">
                                        <thead>
                                            <tr align="center">
                                                <th>Portfolio Number</th>
                                                <th>Position Name</th>
                                                <th>Host Company</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($lead_data as $ld)
                                                <tr>
                                                    <td>{{$portfolio->portfolio_number}}</td>
                                                    <td>{{$ld->position->pos_name}}</td>
                                                    <td>{{$ld->hostCompany->hc_name}}</td>
                                                    <td>{{dateformat($ld->position->start_date,DISPLAY_DATE)}}</td>
                                                    <td>{{dateformat($ld->position->end_date,DISPLAY_DATE)}}</td>
                                                    <td>
                                                        <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Delete" onclick="deleteLead('{{ encrypt($ld->id) }}')" > <i class="fa fa-close text-danger m-r-10"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="clear padding10"></div>
                                </div>
                            </div>
                        </div> 
                    </div>
                    @endif
                </div>
            @endif
        @endif
    </div>
</div>
<script>
    function deleteLead(leadId){
        var user_id = $('meta[name="user_token"]').attr('content');
        
        showLoader("#full-overlay");
        confirmAlert("On conform lead will be deleted.","warning","Are you sure?","Confirm",function(r,i){
            if(i){
                url = "{{ route('hiring.stage') }}";
                $.ajax({
                    type: 'post',
                    url: url,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }, 
                    data: {
                        'action': 'delete_lead',
                        'user_id': user_id,
                        'leadId': leadId
                    },
                    dataType: "json",
                    success: function(response) { 
                        loadStepContent();
                        if(response.type == "success")
                        {
                            var Html = '<div class="alert swl-alert-success"><p>'+response.message+'</p></div>'; 
                            notifyResponseTimerAlert(Html,"success","Success");
                            setTimeout(function(){
                                navigateStages('{{ $active_stage }}',"{{$active_step_key}}");
                            },3000);
                        }
                    }
                }); 
            }
            else{
                hideLoader("#full-overlay");
            }
        });
    }
 </script>