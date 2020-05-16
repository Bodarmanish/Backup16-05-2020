@extends('admin.layouts.app')

@php
$menu_items = @$menu_items;
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
        <div class="col-sm-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">Menu Items</h3>
                        <p class="text-muted m-b-30">List of Menu Items</p>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="col-md-3 pull-right">
                            <a href="{{ route('menu.add.form') }}" class="btn btn-block btn-info">Add Menu Item</a>
                        </div>
                    </div>
                </div>
                @include('admin.includes.status')
                <div class="table-responsive">
                    <table id="menu_list" class="table table-bordered m-t-10">
                        <thead>
                            <tr>
                                <th>Menu Item Name</th>
                                <th>Menu Section</th>
                                <th>Route Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($menu_items))
                                @foreach($menu_items as $mi)
                                    <tr>
                                        <td>{{ $mi->title }}</td>
                                        <td>{{ $mi->menu_section }}</td>
                                        <td>{{ $mi->route_name }}</td>
                                        <td>
                                            <a href="{{ route('menu.edit.form',$mi->id) }}" data-toggle="tooltip" data-original-title="Edit"> <i class="fa fa-pencil text-inverse m-r-10"></i> </a>
                                            <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Delete" onclick="return deleteMenu('{{ route('menu.delete',$mi->id) }}');" > <i class="fa fa-close text-danger m-r-10"></i> </a>
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
        $('#menu_list').DataTable();
    });
    
    function deleteMenu(route){
        showLoader("#full-overlay");
        confirmAlert("On confirm record will be deleted.","warning","Are you sure?","Confirm",function(r,i){
            if(i){
                window.location.href = r;
            }
            else{
                hideLoader("#full-overlay");
            }
        },route);
    }
</script>
@endsection