@extends('admin.layouts.app')

@php
$faqs = @$faqs;
@endphp

@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">FAQ Manager</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="active">FAQ!</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">FAQ Management</h3>
                        <p class="text-muted m-b-30">List of FAQ's </p>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="col-md-3 pull-right">
                            <a href="{{ route('faq.add.form') }}" class="btn btn-block btn-info">Add FAQ!</a>
                        </div>
                        <button class="btn btn-success pull-right" onclick="return updateOrder()">Confirm Faq Order</button>
                    </div>
                </div>
                @include('admin.includes.status')
                <div class="table-responsive">
                    <table id="faq_list" class="table table-bordered m-t-10" cellpadding="0" cellspacing="0" border="1">
                        <thead>
                            <tr>
                                <!--<th width="5%">Sr. No.</th>-->
                                <th width="7%">Faq Order</th>
                                <th width="15%">Question</th>
                                <th width="60%" >Brief Answer</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($faqs))
                                @foreach($faqs as $faq)
                                    @php
                                        $status = ($faq->status == 1) ? "Active" : "De-Active";
                                        $status_class = ($faq->status == 1) ? "success" : "danger";
                                    @endphp
                                        <tr>
                                            <input type="hidden" name="faq_order[{{ $faq->id }}]" data-id="{{ $faq->id }}" value="{{ $faq->faq_order }}" />
                                            <!--<td>{{ $faq->id }}</td>-->
                                            <td style="text-align:center">{{ $faq->faq_order }}</td>
                                            <td>{{ $faq->question }}</td>
                                            <td>{!! $faq->answer !!}</td>
                                            <td><span class="label label-{{ $status_class }}">{{ $status }}</span></td>
                                            <td>
                                                <a href="{{ route('faq.edit.form',encrypt($faq->id)) }}" data-toggle="tooltip" data-original-title="Edit"> <i class="fa fa-pencil text-inverse m-r-10"></i> </a>
                                                <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Delete" onclick="return confirmDelete('{{ route('faq.delete',encrypt($faq->id)) }}');" > <i class="fa fa-close text-danger m-r-10"></i> </a>
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
<script type="text/javascript">
$(document).ready(function() {
    $('#faq_list').dataTable({
        "bStateSave": true,
        "fnStateSave": function (oSettings, oData) {
            localStorage.setItem('offersDataTables', JSON.stringify(oData));
        },
        "fnStateLoad": function (oSettings) {
            return JSON.parse(localStorage.getItem('offersDataTables'));
        },
        paging: false
    });
});
   
</script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.24/jquery-ui.min.js"></script>
<script>
   var $x = jQuery.noConflict();
</script>
<script type="text/javascript">
$(function () {
    $("#faq_list").sortable({
        items: 'tr:not(tr:first)',
        cursor: 'pointer',
        axis: 'y',
        dropOnEmpty: false,
        start: function (e, ui) {
            ui.item.addClass("selected");
        },
        stop: function (e, ui) {
            ui.item.removeClass("selected");
            $(this).find("tr").each(function (index) {
                if (index > 0) {
                  $(this).find('input').val(index);
                  $(this).find("td").eq(0).html(index);
                }
            });
        }
    });
});
function updateOrder(){
        var data = [];
        $("#faq_list").find("tr").each(function (index) {
             if (index > 0) {
               var ele = $(this).find('input').val(index);
               data.push(ele.attr("data-id")+','+ele.val());
            }
        });
        $.ajax({
            type: "post",
            url: "{{ route('faq.setorder') }}",
            data: {
                'orderlist':data,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response){
                  if(response=='true'){
                      swal("Order Set!", "Faq Order Set Successfully!", "success");
                  }
            },
            error: function(response){
                alert("error")
            },
        });
}
</script>
@endsection
