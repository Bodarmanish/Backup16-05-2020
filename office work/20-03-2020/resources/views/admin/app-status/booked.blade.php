@php 
$lead_data = @$step_verified_data['lead_data']; 
$booked_data = @$step_verified_data['booked_data']; 
$pay_rate_basis = config('common.pay_rate_basis');
@endphp

<h3>Booked Position</h3> 
<!--<p>The purpose of our pre-screening interview is to evaluate your English level and gather additional information regarding your application.</p>-->
<div class="row"> 
    <div class="col-sm-12">
        @if(empty($step_status)) 
            <p>This step is disable until user will reach to step.</p>  
        @else
            @if (!empty($lead_data) && count($lead_data)>0 )
                <div class="well">
                    <div class="panel">
                        <div class="row">
                            <div class="pull-left">
                                <div class="col-sm-12">
                                    <div class="panel-heading" id="lead_data_tab" role="tab">
                                        <a class="panel-title" data-toggle="collapse" href="#lead_data"  aria-expanded="true" aria-controls="lead_data">Training Position</a>
                                    </div>
                                </div>
                            </div>
                            <div class= "pull-right">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <a href="javascript:void(0)" onclick="popup_booked_position('booked_position')" class="btn btn-success">Booked Position</a>
                                    </div>
                                </div>
                            </div>
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
                        <div class="panel-collapse collapse {{ (!empty($lead_data))?'in':'' }}" id="lead_data" aria-labelledby="exampleHeadingDefaultOne" role="tabpanel">
                            <div class="panel-body">
                                <form method="post" id="frmbook" id="frmbook">
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
                                                        <input type="radio" id="radio_{{$ld->id}}" name="position" title="No more positions availableposition" class="css-checkbox" value="{{$ld->id}}" />
                                                        <input type="hidden" name="hc_id_{{$ld->id}}" id="hc_id_{{$ld->id}}" value="{{$ld->hc_id}}" />
                                                        <input type="hidden" name="pos_id_{{$ld->id}}" id="pos_id_{{$ld->id}}" value="{{$ld->pos_id}}" />
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </form>
                                <div class="clear padding10"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <p>Please add the positions for this candidate.</p> 
            @endif
            @if(!empty($booked_data) && count($booked_data)>0)
                <div class="well">
                    <div class="panel">
                        <div class="row">
                            <div class="pull-left">
                                <div class="col-sm-12">
                                    <div class="panel-heading" id="booked_position_tab" role="tab">
                                        <a class="panel-title" data-toggle="collapse" href="#booked_postion_data" data-parent="#booked_position" aria-expanded="true" aria-controls="booked_postion_data">Booked Position</a>
                                    </div>
                                </div>
                            </div>
                            <div class= "pull-right">
                                <div class="form-group">
                                    
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <hr>
                        <div class="panel-collapse collapse in" id="booked_postion_data" aria-labelledby="exampleHeadingDefaultOne" role="tabpanel">
                            <div class="panel-body">
                                <table class="table sortable_data" cellspacing="0" width="100%" id="bookeddata">
                                    <thead>
                                        <tr align="center">
                                            <th>Position Name</th>
                                            <th>Host Company</th>
                                            <th>Salary</th>
                                            <th>Type</th>
                                            <th>Placement Order</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Booked Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($booked_data as $bd)
                                        <tr>
                                            <td>{{$bd->position->pos_name}}</td>
                                            <td>{{$bd->hostCompany->hc_name}}</td>
                                            <td>{{$bd->salary}} / {{$pay_rate_basis[$bd->pay_rate_basis]}}</td>
                                            <td>{{$bd->type == 1 ? "Booked" : "Placed"}}</td>
                                            <td>{{$bd->pla_order}}</td>
                                            <td>{{dateformat($bd->start_date,DISPLAY_DATE)}}</td>
                                            <td>{{dateformat($bd->end_date,DISPLAY_DATE)}}</td>
                                            <td>{{dateformat($bd->booked_date,DISPLAY_DATETIME)}}</td>
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
function popup_booked_position(action)
{
    var lead_radio = $('input[name=position]');
    var url = " {{ route('hiring.stage') }} ";
    var user_id = $('meta[name="user_token"]').attr('content');

    if(lead_radio.filter(':checked').val())
    {
        var lead_id = lead_radio.filter(':checked').val();
        var pos_id = $("#pos_id_"+lead_id).val();
        var hc_id = $("#hc_id_"+lead_id).val();
        show_popup();
        get_common_ajax(url,{
            pos_id: pos_id,
            hc_id: hc_id,
            portfolio_id: '{{$portfolio->id}}',
            user_id: user_id,
            action: action,

        });
    }
    else
    {
        var Html = '<div class="alert swl-alert-danger"><p>Please select any training position to booked position.</p></div>'; 
        notifyResponseTimerAlert(Html,"error","Error");
        return false;
    }
}

 </script>
 