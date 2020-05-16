@extends('admin.layouts.app')
@php
$position_data = @$position;
$pay_rate_basis = config('common.pay_rate_basis');
@endphp

@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Position</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="active">Position</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">Position</h3>
                        <p class="text-muted m-b-30">List of Position</p>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="row">
                            <div class="col-md-3 pull-right">
                                <a href="{{ URL::previous() }}" class="btn btn-block btn-info"><i class="fa fa-arrow-left"></i> Back</a>
                            </div>
                            <div class="col-md-3 pull-right">
                                <a href="javascript:void(0)" onclick="add_lead()" class="btn btn-block btn-info">Save Lead</a>
                            </div>
                        </div>
                    </div>
                    <div class="row m-b-10">
                        <div class="col-md-12">
                            <div class="alert alert-info text-dark"> 
                                <p>
                                    <strong>User Id: </strong>{{$user_detail->id}} |
                                    <strong>User Name: </strong>{{$user_detail->first_name}} | 
                                    <strong>User Email: </strong>{{$user_detail->email}}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <form id="frmaddlead" action="{{ route('hiring.stage') }}" method="post">
                    <input type="hidden" name="user_id" value="{{ $user_token }}" />
                    <input type="hidden" name="action" value="addlead" />
                    <div class="table-responsive">
                        <table id="position_list" class="table table-bordered m-t-10">
                            <thead>
                                <tr>
                                    <th>Position Name</th>
                                    <th>Host Company</th>
                                    <!--<th>Position Description</th>-->
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Duration Months</th>
                                    <th>Stipend</th>
                                    <th>Tips (Y/N)</th>
                                    <!--<th>Housing Description</th>-->
                                    <th>Total Openings</th>
                                    <th>Left Available</th>
                                    <th>Placed</th>
                                    <th>Host Company Interviewing</th>
                                    <th>Created By</th>
                                    <th>Select</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($position_data))
                                    @foreach($position_data as $pd)
                                        @php
                                            $pd->host_company = $pd['hostCompany'];
                                            $pd->positionAdmin = $pd['positionAdmin'];
                                            $salary = (!empty($pd->salary) && !empty($pd->pay_rate_basis)) 
                                                    ? $pd->salary .'/'. $pay_rate_basis[$pd->pay_rate_basis]
                                                    : ((!empty($pd->salary)) ?  $pd->salary :"-" );
                                        @endphp
                                        <tr>
                                            <td>{{$pd->pos_name}}</td>
                                            <td>{{$pd->host_company->hc_name}}</td>
                                            <!--<td>{{$pd->pos_description}}</td>-->
                                            <td>{{dateformat($pd->start_date,DISPLAY_DATE)}}</td>
                                            <td>{{dateformat($pd->end_date,DISPLAY_DATE)}}</td>
                                            <td>{{get_date_diff($pd->start_date,$pd->end_date)}}</td>
                                            <td>{{$salary}}</td>
                                            <td>{{$pd->tips}}</td>
                                            <!--<td>{{$pd->housing_description}}</td>-->
                                            <td>{{$pd->no_of_openings}}</td>
                                            <td  @if($pd->leftAval_count < 0) style="color: red; font-weight: bold" @endif>{{$pd->leftAval_count}}</td>
                                            <td>{{$pd->placed_count}}</td>
                                            <td>{{$pd->booked_count}}</td>
                                            <td>{{$pd->positionAdmin->first_name}} {{$pd->positionAdmin->last_name}}</td>
                                            <td class="text-center">
                                                <input type="radio" id="radio_{{$pd->id}}" name="position" value="{{ $pd->hc_id."-".$pd->id}}" title="Available position"/>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 
@endsection
@section('scripts')
 <script>
    $(document).ready(function() {
          $('#position_list').DataTable();
    });
    
    function add_lead()
    {
        var selected = $('input[name=position]:checked').val();
        
        if(selected != "undefined" && selected != "" && selected != null && selected.length > 0){
            var form_ele = document.getElementById("frmaddlead");
            var form_data = new FormData(form_ele);
            $.ajax({
                url: "{{ route('hiring.stage') }}",
                type: "post",
                data: form_data,
                dataType: 'json',
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response){  
                    if(response.type == "success")
                    {
                        var Html = '<div class="alert swl-alert-success"><p>'+response.message+'</p></div>'; 
                        notifyResponseTimerAlert(Html,"success","Success");
                        setTimeout(function(){
                            document.location.href = response.redirectURL;
                        },3000);
                    }
                    else
                    {
                        var Html = '<div class="alert swl-alert-danger"><p>'+response.message+'</p></div>'; 
                        notifyResponseTimerAlert(Html,"error","Error");
                        return false;
                    }
                }
            });
        }
        else{
            var Html = '<div class="alert swl-alert-danger"><p>Please select any training position to add lead.</p></div>'; 
            notifyResponseTimerAlert(Html,"error","Error");
            return false;
        }
    }
    
 </script>
@endsection
