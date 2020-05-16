@php 
$placed_data = @$step_verified_data['placed_data']; 
$booked_data = @$step_verified_data['booked_data']; 
$pay_rate_basis = config('common.pay_rate_basis');
$program_enroll = @$step_verified_data['program_enroll'];
$j1_status_id = @$step_verified_data['j1_status_id'];
@endphp

<h3>Placement Position</h3> 
<div class="row"> 
    <div class="col-sm-12">
        @if(empty($step_status) && empty($placed_data)) 
            <p>This step is disable until user will reach to step.</p>  
        @else
            @if(!empty($booked_data))
                <div class="well">
                    <div class="panel">
                        <div class="row">
                            <div class="pull-left">
                                <div class="col-sm-12">
                                    <div class="panel-heading" id="schedule_interview_tab" role="tab">
                                        <a class="panel-title" data-toggle="collapse" href="#schedule_interview" data-parent="#interview" aria-expanded="true" aria-controls="schedule_interview">Booked Position</a>
                                    </div>
                                </div>
                            </div>
                            <div class= "pull-right">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="btn-group m-r-10">
                                            <button aria-expanded="false" data-toggle="dropdown" class="btn btn-danger dropdown-toggle" type="button">Action <span class="caret"></span></button>
                                            <ul role="menu" class="dropdown-menu">
                                                <li><a href="javascript:void(0)" id="not_selected" onclick="do_submit(this.id)">Not Selected by Host Company</a></li>
                                                <li><a href="javascript:void(0)" id="rejected_by_hc" onclick="do_submit(this.id)">Rejected by Host Company</a></li>
                                                <li><a href="javascript:void(0)" id="interview_refused_by_candidate" onclick="do_submit(this.id)">Interview refused by candidate</a></li>
                                                <li><a href="javascript:void(0)" id="traning_pos_not_open" onclick="do_submit(this.id)">Training Position No Longer Opened</a></li>
                                            </ul>
                                        </div>
                                        <a href="javascript:void(0)" id="confirm_placement" onclick="do_submit(this.id)" class="btn btn-success">Confirm Placement</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="panel-collapse collapse {{ (!empty($booked_data))?'in':'' }}" id="schedule_interview" aria-labelledby="exampleHeadingDefaultOne" role="tabpanel">
                            <div class="panel-body">
                                <div class="interview_preview"> 
                                    <form method="post" id="frmbook" id="frmbook">
                                        <table class="table sortable_data" cellspacing="0" width="100%" id="leaddata">
                                            <thead>
                                                <tr align="center">
                                                    <th>Position Name</th>
                                                    <th>Host Company Name</th>
                                                    <th>Stipend</th>
                                                    <th>Type</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Booked Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{$booked_data->position->pos_name}}</td>
                                                    <td>{{$booked_data->hostCompany->hc_name}}</td>
                                                    <td>{{$booked_data->salary}} / {{$pay_rate_basis[$booked_data->pay_rate_basis]}}</td>
                                                    <td>{{$booked_data->type == 1 ? "Booked" : "Placed"}}</td>
                                                    <td>{{dateformat($booked_data->start_date,DISPLAY_DATE)}}</td>
                                                    <td>{{dateformat($booked_data->end_date,DISPLAY_DATE)}}</td>
                                                    <td>{{dateformat($booked_data->booked_date,DISPLAY_DATETIME)}}</td>
                                                    <td>
                                                        <input type="radio" id="radio_{{$booked_data->id}}" name="position" class="css-checkbox" value="{{$booked_data->id}}" />
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </form>
                                    <div class="clear padding10"></div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            @endif
            
            @if(!empty($placed_data) && count($placed_data) > 0)
                <div class="well">
                    <div class="panel">
                        <div class="row">
                            <div class="pull-left">
                                <div class="col-sm-12">
                                    <div class="panel-heading" id="booked_position_tab" role="tab"> 
                                        <a class="panel-title" data-toggle="collapse" href="#booked_postion_data" data-parent="#booked_position" aria-expanded="true" aria-controls="booked_postion_data">Placement Details</a>
                                    </div>
                                </div>
                            </div>
                            @if(count($placed_data) == 1 && empty($program_enroll) && get_date_diff($placed_data[0]->start_date,$placed_data[0]->end_date,'%m') <= 8 && $j1_status_id == '2005')
                                <div class= "pull-right">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <a href="javascript:void(0)" id="enroll_in_route_66" onclick="do_submit(this.id)" class="btn btn-success">Enroll in Route 66</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="clearfix"></div>
                        </div>
                        <hr>
                        <div class="panel-collapse collapse in" id="booked_postion_data" aria-labelledby="exampleHeadingDefaultOne" role="tabpanel">
                            <div class="panel-body">

                                <table class="table sortable_data" cellspacing="0" width="100%" id="leaddata">
                                    <thead>
                                        <tr align="center">
                                            <th>Position Name</th>
                                            <th>Host Company Name</th>
                                            <th>Placement Order</th>
                                            <th>Stipend</th>
                                            <th>Type</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Placed Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($placed_data as $pd)
                                            <tr>
                                                <td>{{$pd->position->pos_name}}</td>
                                                <td>{{$pd->hostCompany->hc_name}}</td>
                                                <td>{{$pd->pla_order}}</td>
                                                <td> {{$pd->salary}} / {{$pay_rate_basis[$pd->pay_rate_basis]}}</td>
                                                <td> {{$pd->type == 1 ? "Booked" : "Placed"}}</td>
                                                <td> {{dateformat($pd->start_date,DISPLAY_DATE)}}</td>
                                                <td> {{dateformat($pd->end_date,DISPLAY_DATE)}}</td>
                                                <td> {{(!empty($pd->placed_date)) ? dateformat($pd->placed_date,DISPLAY_DATETIME) : '-' }}</td>
                                                @if(count($placed_data) == 1 && empty($program_enroll))
                                                    <input type="radio" id="radio_{{$pd->id}}" name="placed_position" class="css-checkbox" value="{{$pd->id}}" hidden="true" checked="true"/>
                                                @endif
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
        @endif
    </div>
</div>

 <script>
    function do_submit(action)
    {
        if(action == 'enroll_in_route_66')
        {
            var lead_radio = $('input[name=placed_position]');
        }else{
            var lead_radio = $('input[name=position]');
        }
        
        var url = "{{ route('hiring.stage') }} ";
        var user_id = $('meta[name="user_token"]').attr('content');
        if(lead_radio.filter(':checked').val())
        {
            var booked_pos_id = lead_radio.filter(':checked').val();
            $.ajax({
                    url: url,
                    type: "post",
                    dataType: "json",
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {'booked_pos_id':booked_pos_id,'portfolio_id': '{{$portfolio->id}}','user_id':user_id,'action': action},
                    success: function(response){  
                        if(response.type == "success")
                        {
                            var Html = '<div class="alert swl-alert-success"><p>'+response.message+'</p></div>'; 
                            notifyResponseTimerAlert(Html,"success","Success");
                            setTimeout(function(){
                               navigateStages('{{ $active_stage }}',"{{$active_step_key}}");
                            },2100);
                        }
                        else
                        {
                            var Html = '<div class="alert swl-alert-danger"><p>Failed to confirm placement for this user.</p></div>'; 
                            notifyResponseTimerAlert(Html,"error","Error");
                            return false;
                        }
                    }
                });
        }
        else
        {
            var Html = '<div class="alert swl-alert-danger"><p>Please select booked position.</p></div>'; 
            notifyResponseTimerAlert(Html,"error","Error");
            return false;
        }
    }

 </script>
 