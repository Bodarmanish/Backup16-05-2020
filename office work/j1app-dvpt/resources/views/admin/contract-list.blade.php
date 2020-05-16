@extends('admin.layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Agency Manager</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="active">Agencies Contract</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">Contract Requests</h3>
                        <p class="text-muted m-b-30">List of Contract Requests</p>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="row">
                            <div class="col-md-3 pull-right">
                                <a href="{{ URL::previous() }}" class="btn btn-block btn-info"><i class="fa fa-arrow-left"></i> Back</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="white-box well">
                    <div class="row">
                        <form id="frm_filter" method="post" action="{{ route('agency.filter.contract') }}">
                            {{ csrf_field() }}
                            <div class="form-group col-md-4 col-xs-12">
                                <label>Request Status</label>
                                <select name="request_status" id="request_status" class="form-control">
                                    <option value="">-- Select Option --</option>
                                    <option value="1" {{is_selected(request()->get('request_status'),1)}} }} >Requested</option>
                                    <option value="2" {{ is_selected(request()->get('request_status'),2) }} >Accepted</option>
                                    <option value="3" {{ is_selected(request()->get('request_status'),3) }} >Rejected</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4 col-xs-12">
                                <label>Request By</label>
                                <select name="request_by" id="request_by" class="form-control">
                                    <option value="">-- Select Option --</option>
                                    <option value="1" {{ is_selected(request()->get('request_by'),1) }} >Admin</option>
                                    <option value="2" {{ is_selected(request()->get('request_by'),2) }} >User</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4 col-xs-12">
                                <label>Email</label>
                                <input type="text" name="email" id="email" placeholder="Enter Email" value="{{request()->get('email')}}" class="form-control">
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info">Search</button>
                                    <a type="button" class="btn btn-danger" href="{{ route('agency.contract.list') }}">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @include('admin.includes.status')
                <div class="table-responsive">         
                    <table id="contract_list" class="table table-bordered m-t-10">
                        <thead>
                            <tr>
                                <th>Agency Name</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Contract Type</th>
                                <th>Request Status</th>
                                <th>Request Sent By</th>
                                <th>Is Expired</th>
                                <th style="width: 200px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="tableview">
                          @if (!empty($contract_data))
                            @foreach($contract_data as $ad)
                                @php 
                                    $form_id = "frm_agency_request_{$loop->iteration}";
                                @endphp
                                <tr>
                                    <td>@isset($ad->agency_name){{ $ad->agency_name }}@endisset</td>
                                    <td>@isset($ad->first_name) {{ $ad->first_name }} {{ $ad->last_name }}@endisset</td>
                                    <td>@isset($ad->email) {{ $ad->email }} @endisset</td>
                                    <td>@switch($ad->contract_type)
                                            @case(1)
                                                    Register
                                                @break
                                            @case(2)
                                                    Placement
                                                @break
                                             @case(3)
                                                    Sponsor
                                                @break
                                            @default
                                                    General
                                        @endswitch
                                    </td>
                                    <td>
                                        <span id="status_{{$form_id}}"></span>
                                        @switch($ad->request_status)
                                            @case(1)
                                                <span class="label label-warning">Requested</span>
                                                @break
                                            @case(2)
                                                <span class="label label-success">Accepted</span>
                                                @break
                                            @case(3)
                                                <span class="label label-danger">Rejected</span>
                                                @break
                                            @default
                                                @break
                                        @endswitch
                                    </td>
                                    
                                    <td>
                                        @if($ad->request_by == 1)
                                            <span class="label label-info">Admin</span>
                                        @elseif($ad->request_by == 2)
                                            <span class="label label-info">User</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($ad->is_expired)
                                            @case(0)
                                                    <span class="label label-success">Not Expired</span>
                                                @break
                                            @default
                                                    <span class="label label-danger">Expired</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($ad->request_status == 1 && $ad->request_by == 2)
                                        <form id="{{ $form_id }}" method="post" onsubmit="return agencyRequestAction(this);">
                                            <input type="hidden" name="btn_action" value="" />
                                            <input type="hidden" name="agency_contract" value="{{ encrypt($ad->id) }}" />
                                            <button type="submit" name="submit_accept"  class="btn btn-sm btn-info btn-outline" value="accept" onclick="return setFormBtnAction('{{ $form_id }}',this.value);">Accept</button>
                                            <button type="submit" name="submit_reject" class="btn btn-sm btn-danger btn-outline" value="reject" onclick="return setFormBtnAction('{{ $form_id }}',this.value);">Reject</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> 
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $('#contract_list').DataTable();
    });
    
    function agencyRequestAction(ele){
        
        var confirm_message = "";
        var btn_action = $(ele).find("input[name='btn_action']").val();
        
        if(btn_action == "" || btn_action == "undefined" || btn_action == null){
            return false;
        }
        
        if(btn_action == "accept"){
            confirm_message = "By confirm, your contract will be started with the user.";
        }
        else if(btn_action == "reject"){
            confirm_message = "By confirm you will reject request from user.";
        }
        
        confirmAlert(confirm_message,"warning","Are you sure?","Confirm",function(ele,state){
            
            if(state){
                var form_data = new FormData(ele);
                $(ele).find("input[name='btn_action']").val("");
                var form_id = $(ele).attr('id');
                var url = "{{ route('agency.contract.action') }}"; 
                $.ajax({
                    url: url,
                    type: 'post',
                    data: form_data,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response){
                        notifyResponseTimerAlert(response.message,response.type,response.type.charAt(0).toUpperCase()+ response.type.slice(1));
                        if(response.type == 'success'){
                            $("#"+form_id).addClass('hide');
                            if(btn_action == "accept"){
                                $("#status_"+form_id).addClass('label label-success').html('Accepted').next().closest("span").addClass("hide");
                            }
                            else if(btn_action == "reject"){
                                $("#status_"+form_id).addClass('label label-danger').html('Rejected').next().closest("span").addClass("hide");
                            }
        
                        }
                    },
                });
            }
            
        },ele);
        
        return false;
    }
</script>
@endsection