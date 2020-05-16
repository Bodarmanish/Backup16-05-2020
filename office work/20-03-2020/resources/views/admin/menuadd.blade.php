@extends('admin.layouts.app')
@php

$mode = "Add";
$action = route('menu.add');

if(!empty($id)){
    $mode = "Edit";
    $action = route('menu.edit',$id);
}
@endphp
@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Menu Manager</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('menu.list') }}">Menu Items</a></li>
                <li class="active">Add Menu Item</li>
            </ol>
        </div>
    </div>
    <div class="row"> 
        <div class="col-md-12 col-xs-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">{{ $mode }} Menu Item</h3>
                        <!--<p class="text-muted m-b-30">Add New Admin</p>-->
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="pull-right">
                            <a href="{{ route('menu.list') }}" class="btn btn-block btn-info"><i class="fa fa-list"></i> Menu Items</a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        @include('admin.includes.status')
                        <form method="post" action="{{ $action }}" class="form-horizontal form-validator">
                            {{ csrf_field() }}
                            <!--<input type="hidden" name="{{ $menu_item->id }}" />-->
                            <div class="form-group">
                                <label class="col-md-12">Menu Title <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="title" id="title" placeholder="Menu Title" class="form-control " required value="{{ ($mode == "Edit") ? $menu_item->title : old('title') }}">
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('display_name')){{ $errors->first('display_name') }}@endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-12">Menu Section <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <select name="permission_group_id" id="permission_group_id" class="form-control" required="" onchange="return loadRoutes(this);">
                                        <option value="">-- Select --</option>
                                        @if(!empty($menu_sections))
                                            @foreach($menu_sections as $section)
                                            <option value="{{ $section->id }}" {{ is_selected($menu_item->permission_group_id,$section->id) }}>{{ $section->display_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('permission_group_id')){{ $errors->first('permission_group_id') }}@endif
                                    </div>
                                </div>
                            </div>
                            
                            <div id="dd_routes" class="form-group hidden">
                                <label class="col-md-12">Route Name <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <select name="permission_id" id="permission_id" class="form-control" required="">
                                        <option value="">-- Select --</option>
                                    </select>
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('permission_id')){{ $errors->first('permission_id') }}@endif
                                    </div>
                                </div>
                            </div>
                            
<!--                            <div class="form-group">
                                <label class="col-md-12">Menu Icon</label>
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <input type="text" data-placement="bottomRight" class="form-control icp icp-auto" value=""/>
                                        <span class="input-group-addon"></span>
                                    </div>
                                </div>
                            </div>-->
                            
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info">Save Menu Item</button>
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
        @if(!empty($id))
            loadRoutes(document.getElementById('permission_group_id'),{{ $menu_item->permission_id }});
        @endif
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