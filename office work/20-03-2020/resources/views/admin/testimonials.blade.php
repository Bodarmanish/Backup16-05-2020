@extends('admin.layouts.app')

@php
    $testimonial_data = @$testimonial_data;

    $allow_image_ext = collect(config('common.allow_image_ext'))->implode(', ');
    $upload_image_ext = collect(config('common.allow_image_ext'))->implode('|');
    $upload_img_size = config("common.upload_img_size");
    $allowed_img_size = config("common.upload_img_size")*1024;
   @endphp

@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Testimonial Manager</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="active">Testimonials</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">Testimonials</h3>
                        <p class="text-muted m-b-30">List of Testimonial</p>
                    </div>
                    @if(check_route_access('testimonials.add.form'))
                        <div class="col-md-6 col-xs-12">
                            <div class="col-md-4 pull-right">
                                <a href="{{ route('testimonials.add.form') }}" class="btn btn-block btn-info">Add Testimonial</a>
                            </div>
                        </div>
                    @endif
                </div>
                 @include('admin.includes.status')
                <div class="table-responsive">
                    <table id="testimonial_list" class="table table-bordered m-t-10">
                        <thead>
                            <tr>
                                <!--<th>Image</th>-->
                                <th>Title</th>
                                <th>Client Name</th>
                                <th>Client Country</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                          @if (!empty($testimonial_data))
                            @foreach($testimonial_data as $testimonial)
                                @php
                                    $status = ($testimonial->status == 1) ? "Active" : "De-Active";
                                    $status_class = ($testimonial->status == 1) ? "success" : "danger";
                                    $imgurl = empty(get_url('testimonial/'.$testimonial->id.'/crop/'.$testimonial->image)) 
                                        ? url("assets/images/noimage.png") 
                                        : get_url('testimonial/'.$testimonial->id.'/crop/'.$testimonial->image);
                                 
                                @endphp
                                <tr>
                                    <!--<td width="200px"><img src="{{$imgurl}}" width="200" class="img-responsive thumbnail" /></td>-->
                                    <td>{{ $testimonial->title }}</td>
                                    <td>{{ $testimonial->client_name }}</td>
                                    <td>{{ get_country_name($testimonial->client_country) }}</td>
                                    <td>
                                        <span class="label label-{{ $status_class }}">{{ $status }}</span>
                                    </td>
                                    <td>
                                        @if(check_route_access('testimonials.edit.form'))
                                            <a href="{{ route('testimonials.edit.form',encrypt($testimonial->id)) }}" data-toggle="tooltip" data-original-title="Edit" onclick="return  showLoader('#full-overlay');"> <i class="fa fa-pencil text-inverse m-r-10"></i> </a>
                                        @endif
                                        @if(check_route_access('testimonials.delete'))
                                            <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Delete" onclick="return confirmDelete('{{ route('testimonials.delete',encrypt($testimonial->id)) }}');" > <i class="fa fa-close text-danger m-r-10"></i> </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div id="crop-container" style="display:none"></div>
            </div>
        </div>
    </div>
</div> 
@endsection
@section('scripts')
 <script>
    $(document).ready(function() {
        $('#testimonial_list').DataTable();
    });
 </script>
@endsection