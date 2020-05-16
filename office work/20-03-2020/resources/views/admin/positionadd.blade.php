@extends('admin.layouts.app')
@php

$mode = "Add";
$action = route('hc.pos.add');

if(!empty($id)){
    $mode = "Edit";
    $action = route('hc.pos.edit',$id);
}
@endphp
@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">HC & Position Manager</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                @if(check_route_access('hc.pos.list'))
                <li><a href="{{ route('hc.pos.list') }}">Position</a></li>
                @endif
                <li class="active">Add Position</li>
            </ol>
        </div>
    </div>
    <div class="row"> 
        <div class="col-md-12 col-xs-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">{{ $mode }} Position</h3>
                    </div>
                    @if(check_route_access('hc.pos.list'))
                    <div class="col-md-6 col-xs-12">
                        <div class="pull-right">
                            <a href="{{ route('hc.pos.list') }}" class="btn btn-block btn-info"><i class="fa fa-list"></i> Positions</a>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-md-12">
                        @include('admin.includes.status')
                        <form method="post" action="{{ $action }}" class="form-horizontal form-validator">
                            {{ csrf_field() }}
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">Host Company <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select name="hc_id" id="hc_id" class="form-control" required="">
                                                <option value="">-- Select --</option>
                                                @if(!empty($host_companies))
                                                    @foreach($host_companies as $hc)
                                                        <option value="{{ $hc->id }}" {{ is_selected($hc->id,$position->hc_id) }} {{ is_selected($hc->id,old('hc_id')) }} >{{ $hc->hc_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <div class="clearfix"></div>
                                            <div class="help-block with-errors">
                                                @if ($errors->has('hc_id')){{ $errors->first('hc_id') }}@endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">Position Name <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" name="pos_name" id="pos_name" placeholder="Position Name" class="form-control " required value="{{ ($mode == "Edit") ? $position->pos_name : old('pos_name') }}">
                                            <div class="clearfix"></div>
                                            <div class="help-block with-errors">
                                                @if ($errors->has('pos_name')){{ $errors->first('pos_name') }}@endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-12">Position Description</label>
                                        <div class="col-md-12">
                                            <textarea rows="3" name="pos_description" id="pos_description" placeholder="Position Description" class="form-control " style="resize: none;">{!! ($mode == "Edit") ? $position->pos_description : old('pos_description') !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label>Start Date <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="text" name="start_date" id="start_date" placeholder="Start Date" class="form-control datepicker" required value="{{ ($mode == "Edit") ? dateformat($position->start_date) : old('start_date') }}" autocomplete="off">
                                            <div class="help-block with-errors">
                                                @if ($errors->has('start_date')){{ $errors->first('start_date') }}@endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label>End Date <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="text" name="end_date" id="end_date" placeholder="End Date" class="form-control datepicker" required value="{{ ($mode == "Edit") ? dateformat($position->end_date) : old('end_date') }}" autocomplete="off">
                                            <div class="help-block with-errors">
                                                @if ($errors->has('end_date')){{ $errors->first('end_date') }}@endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">Number Of Openings</label>
                                        <div class="col-md-12">
                                            <input type="text" name="no_of_openings" id="no_of_openings" placeholder="Number Of Openings" class="form-control" value="{{ ($mode == "Edit") ? $position->no_of_openings : old('no_of_openings') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">Status</label>
                                        <div class="col-md-12">
                                            <div class="radio-list">
                                                <label><input type="radio" name="status" value="1" checked {{ is_checked($position->status,'1') }}> Active </label>
                                                <label><input type="radio" name="status" value="0" {{ is_checked($position->status,'0') }}> De-Active </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">Salary Amount</label>
                                        <div class="col-md-12">
                                            <input type="text" name="salary" id="salary" placeholder="Salary Amount" class="form-control" value="{{ ($mode == "Edit") ? $position->salary : old('salary') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        @php
                                        $rates = config('common.pay_rate_basis');
                                        @endphp
                                        <label class="col-md-12">Pay Rate Basis</label>
                                        <div class="col-md-12">
                                            <select name="pay_rate_basis" id="pay_rate_basis" class="form-control" >
                                                <option value="">-- Select --</option>
                                                @foreach($rates as $key => $rate)
                                                    <option value="{{ $key }}" {{ is_selected($key,$position->pay_rate_basis) }} {{ is_selected($key,old('pay_rate_basis')) }} >{{ $rate }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">Tips</label>
                                        <div class="col-md-12">
                                            <input type="text" name="tips" id="tips" placeholder="Tips" class="form-control" value="{{ ($mode == "Edit") ? $position->tips : old('tips') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">Is Housing?</label>
                                        <div class="col-md-12">
                                            <div class="radio-list">
                                                <label><input type="radio" name="is_housing" value="1" checked {{ is_checked($position->is_housing,'1') }} {{ is_checked(old('is_housing'),'1') }}> Yes </label>
                                                <label><input type="radio" name="is_housing" value="0" {{ is_checked($position->is_housing,'0') }} {{ is_checked(old('is_housing'),'0') }}> No </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-12">Housing Description</label>
                                        <div class="col-md-12">
                                            <textarea rows="3" name="housing_description" id="housing_description" placeholder="Housing Description" class="form-control " style="resize: none;">{!! ($mode == "Edit") ? $position->housing_description : old('housing_description') !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info">Save Position</button>
                                    <button type="reset" class="btn btn-danger">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    var mode = "{{ $mode }}";
    $(document).ready(function(){
        
    });
    
    function loadRoutes(ele,selected){
        showLoader("#full-overlay");
        var value = ele.value;
        
        if(value.length == 0){
            $("#dd_routes").addClass('hidden')
            $("#dd_routes select").attr("disabled",true);
            hideLoader("#full-overlay");
            return;
        }
        
        var url = "{{ route('menu.loadroute') }}";
        $.ajax({
            url: url,
            type: 'post',
            data: { permission_group_id: value },
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            /*statusCode: {
                404: function(jqXHR,textStatus,errorThrown) {
                    alert("Page Not Found");
                },
                500: function(jqXHR,textStatus,errorThrown) {
                    alert("Internal server error");
                },
            },*/
            success: function(response){
                
                if(response.type == "success"){
                    $("#dd_routes").removeClass('hidden')
                    $("#dd_routes select").html(response.data).removeAttr("disabled");
                    
                    if(selected != "" && selected != "undefined" && selected != null){
                        $("#dd_routes select").val(selected);
                    }
                }
                else{
                    $("#dd_routes").addClass('hidden')
                    $("#dd_routes select").attr("disabled",true);
                }
                hideLoader("#full-overlay");
            },
        });
    }
</script>
@endsection