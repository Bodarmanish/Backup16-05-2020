@extends('admin.layouts.app')
@php

$mode = "Add";
$action = route('hc.add');

if(!empty($id)){
    $mode = "Edit";
    $action = route('hc.edit',$id);
}else{
    $id = "";
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
                @if(check_route_access('hc.list'))
                <li><a href="{{ route('hc.list') }}">Host Companies</a></li>
                @endif
                <li class="active">Add Host Company</li>
            </ol>
        </div>
    </div>
    <div class="row"> 
        <div class="col-md-12 col-xs-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">{{ $mode }} Host Company</h3>
                    </div>
                    @if(check_route_access('hc.list'))
                    <div class="col-md-6 col-xs-12">
                        <div class="pull-right">
                            <a href="{{ route('hc.list') }}" class="btn btn-block btn-info"><i class="fa fa-list"></i> Host Companies</a>
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
                                        <label class="col-md-12">Host Company Name <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" name="hc_name" id="hc_name" placeholder="Host Company Name" class="form-control " required value="{{ ($mode == "Edit") ? $host_company->hc_name : old('hc_name') }}">
                                            <div class="clearfix"></div>
                                            <div class="help-block with-errors">
                                                @if ($errors->has('hc_name')){{ $errors->first('hc_name') }}@endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">Host Company Id Number (EIN) <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" name="hc_id_number" id="hc_id_number" placeholder="Host Company Id Number" class="form-control " required value="{{ ($mode == "Edit") ? $host_company->hc_id_number : old('hc_id_number') }}" onblur="return validateEIN(this.value)">
                                            <div class="clearfix"></div>
                                            <div class="help-block with-errors">
                                                <span id='eiv_num_err'></span>
                                                @if ($errors->has('hc_id_number')){{ $errors->first('hc_id_number') }}@endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-12">Description</label>
                                        <div class="col-md-12">
                                            <textarea rows="3" name="hc_description" id="hc_description" placeholder="Host Company Description" class="form-control " style="resize: none;">{!! ($mode == "Edit") ? $host_company->hc_description : old('hc_description') !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="col-md-12">Street</label>
                                        <div class="col-md-12">
                                            <input type="text" name="hc_street" id="hc_street" placeholder="Host Company Street" class="form-control " value="{{ ($mode == "Edit") ? $host_company->hc_street : old('hc_street') }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="col-md-12">Suite</label>
                                        <div class="col-md-12">
                                            <input type="text" name="hc_suite" id="hc_suite" placeholder="Host Company Suite" class="form-control " value="{{ ($mode == "Edit") ? $host_company->hc_suite : old('hc_suite') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="col-md-12">City</label>
                                        <div class="col-md-12">
                                            <input type="text" name="hc_city" id="hc_city" placeholder="Host Company City" class="form-control " value="{{ ($mode == "Edit") ? $host_company->hc_city : old('hc_city') }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="col-md-12">State</label>
                                        <div class="col-md-12">
                                            @php
                                            $states = get_states();
                                            @endphp
                                            <select name="hc_state" id="hc_state" class="form-control">
                                                <option value="">-- Select --</option>
                                                @if(!empty($states))
                                                    @foreach($states as $state)
                                                        <option value="{{ $state->state_id }}" {{ is_selected($state->state_id,$host_company->hc_state) }} {{ is_selected($state->state_id,old('hc_state')) }}>({{ $state->state_abbr }}) {{ $state->state_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">    
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="col-md-12">Zip Code / Postal Code</label>
                                        <div class="col-md-12">
                                            <input type="text" name="hc_zip" id="hc_zip" placeholder="Host Company Zip Code" class="form-control " value="{{ ($mode == "Edit") ? $host_company->hc_zip : old('hc_zip') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">Contact First Name </label>
                                        <div class="col-md-12">
                                            <input type="text" name="contact_first_name" id="contact_first_name" placeholder="Contact First Name" class="form-control " value="{{ ($mode == "Edit") ? $host_company->contact_first_name : old('contact_first_name') }}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">Contact Last Name </label>
                                        <div class="col-md-12">
                                            <input type="text" name="contact_last_name" id="contact_last_name" placeholder="Contact Last Name" class="form-control " value="{{ ($mode == "Edit") ? $host_company->contact_last_name : old('contact_last_name') }}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">Contact Title </label>
                                        <div class="col-md-12">
                                            <input type="text" name="contact_title" id="contact_title" placeholder="Contact Title" class="form-control " value="{{ ($mode == "Edit") ? $host_company->contact_title : old('contact_title') }}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">Contact Email </label>
                                        <div class="col-md-12">
                                            <input type="email" name="contact_email" id="contact_email" placeholder="Contact Email" class="form-control " value="{{ ($mode == "Edit") ? $host_company->contact_email : old('contact_email') }}" />
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">Contact Skype </label>
                                        <div class="col-md-12">
                                            <input type="text" name="contact_skype" id="contact_skype" placeholder="Contact Skype" class="form-control " value="{{ ($mode == "Edit") ? $host_company->contact_skype : old('contact_skype') }}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">Contact Phone </label>
                                        <div class="col-md-12">
                                            <input type="text" name="contact_phone" id="contact_phone" placeholder="Contact Phone" class="form-control " value="{{ ($mode == "Edit") ? $host_company->contact_phone : old('contact_phone') }}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">Phone Extension </label>
                                        <div class="col-md-12">
                                            <input type="text" name="contact_phone_extension" id="contact_phone_extension" placeholder="Phone Extension" class="form-control " value="{{ ($mode == "Edit") ? $host_company->contact_phone_extension : old('contact_phone_extension') }}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">Contact Fax </label>
                                        <div class="col-md-12">
                                            <input type="text" name="contact_fax" id="contact_fax" placeholder="Contact Fax" class="form-control " value="{{ ($mode == "Edit") ? $host_company->contact_fax : old('contact_fax') }}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">Contact Website </label>
                                        <div class="col-md-12">
                                            <input type="text" name="contact_website" id="contact_website" placeholder="Contact Website" class="form-control " value="{{ ($mode == "Edit") ? $host_company->contact_website : old('contact_website') }}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info">Save Host Company</button>
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
    var data = ["sneha","arpita","pogi","eer","sdfadf","dfgdgd","nb","assa"];

    function validateEIN(hc_id_number)
    {
        if(hc_id_number != '')
        {
            $('#eiv_num_err').html('');
            showLoader("#full-overlay");
            $.ajax({
                url: "{{route('hc.ajax')}}",
                type: "post",
                dataType: "json",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {'hc_id_number':hc_id_number,'mode':"{{$mode}}",'id':'{{$id}}', 'action':'validate_EIN'},
                success: function(response){  
                    if(response.type != "success")
                    {
                        var messages = response.message;
                        $("#hc_id_number").val('');
                        $('#eiv_num_err').html(messages.hc_id_number).css("color", "red");
                    }
                    hideLoader("#full-overlay");
                }
            });
        }
        
    }
    $(document).ready(function(){
        
        //var $input = $("#hc_name");
//        $("#hc_name").typeahead({
//            source: data,
//            autoSelect: true
//        });
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