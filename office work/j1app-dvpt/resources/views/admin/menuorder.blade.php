@extends('admin.layouts.app')

@php
$menu_sections = @$menu_sections;
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
                <li class="active">Menu Items</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="white-box">
                <div class="row m-b-20">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-10">Menu Section Ordering</h3>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="pull-right">
                            <!--<button class="btn btn-info" onclick="return toggleExploreSections('section-ordering')"><i class="fa fa-fw fa-list-ul"></i>Explore</button>-->
                            <button class="btn btn-info" onclick="return updateOrder('form-menu-section-ordering')">Confirm Section Order</button>
                        </div>
                    </div>
                </div>
                
                @if(!empty($menu_sections))
                    <form action="{{ route('menu.order.update') }}" id="form-menu-section-ordering">
                        <input type="hidden" name="action" value="section_ordering" />
                        <ul id="section-ordering" class="order-menu-sections">
                        @foreach($menu_sections as $section)
                            <li>
                                <label>{{ $section->display_name }}</label>
                                <input type="hidden" name="menu_section_order[{{ $section->id }}]" value="{{ $loop->iteration }}" />
                            </li>
                        @endforeach
                        </ul>
                    </form>
                @endif
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="white-box">
                <div class="row m-b-20">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-10">Menu Item Ordering</h3>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="pull-right">
                            <button class="btn btn-block btn-info" onclick="return updateOrder('form-menu-item-ordering')">Confirm Item Order</button>
                        </div>
                    </div>
                </div>
                
                @if(!empty($menu_sections))
                <div class="row">
                    <div class="col-md-6 m-b-20">
                        <select class="form-control" onchange="return loadMenuItems(this);">
                            <option value="">-- Select --</option>
                            @foreach($menu_sections as $section)
                            <option value="{{ $section->id }}">{{ $section->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="order-menu-sections">
                            <form id="form-menu-item-ordering">
                                <input type="hidden" name="action" value="item_ordering" />
                                @foreach($menu_sections as $section)
                                    @php
                                        $section->menus = collect($section->menus)->all();
                                    @endphp
                                    @if(!empty($section->menus))
                                        <ul id="item-ordering-{{ $section->id }}" class="order-menu-items" style="display: none;">
                                            @foreach($section->menus as $menu)
                                                <li>
                                                    <label>{{ $menu->title }}</label>
                                                    <input type="hidden" name="menu_item_order[{{ $menu->id }}]" value="{{ $loop->iteration }}" />
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                @endforeach
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div> 
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $('#section-ordering').sortable({
            nested: false,
            exclude: ".order-menu-items > li",
        });
    });
    
    function loadMenuItems(ele){
        var value = ele.value;

        if(value.length !== 0){
            var selector = "#item-ordering-"+value;
            $(".order-menu-items").hide();
            $(selector).show();
            $(selector).sortable({
                nested: false,
            });
        }
        else{
            $(".order-menu-items").hide();
        }
    }
    
    function updateOrder(form_id){
        showLoader("#full-overlay");
        
        var form_selector = "#"+form_id;
        
        $(form_selector+" ul").each(function(index,ele){
            var id = "#"+$(this).attr("id");
            
            $(id+" li").each(function(i,e){
                
                var order = i+1;
                $(this).find('input').val(order);
            })
        });

        var form_ele = document.getElementById(form_id);
        var action = $(form_selector).attr('action');
        var form_data = new FormData(form_ele);
        
        $.ajax({
            url: action,
            type: 'post',
            data: form_data,
            dataType: 'json',
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            /*statusCode: {
                404: function(jqXHR,textStatus,errorThrown) {
                    alert("Page not found");
                },
                500: function(jqXHR,textStatus,errorThrown) {
                    alert("Internal server error");
                },
            },*/
            success: function(response){
                hideLoader("#full-overlay");
                if(response.type == "success"){
                    notifyAlert(response.message,response.type);
                }
            },
        });
    }
    
    function toggleExploreSections(ele_id){
        if($("#"+ele_id+" .order-menu-items").hasClass('hidden')){
            $("#"+ele_id+" .order-menu-items").removeClass('hidden');
        }
        else{
            $("#"+ele_id+" .order-menu-items").addClass('hidden');
        }
    }
</script>
@endsection