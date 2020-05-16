@extends('User.layouts.app')

@section('content')
@php 
$company_name = config("app.name");
@endphp

<!-- Wrapper -->
<div class="white-box">
    <h2 class="text-info text-uppercase main-title">Testimonial</h2> 
    <div class="row m-b-15">
        @if(isset($testimonial_data) && count($testimonial_data) != 0)
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
            <div id="columns">
                @foreach ($testimonial_data as $testimonial)
                @php
                    $imgurl = empty(get_url('testimonial/'.$testimonial->id.'/crop/'.$testimonial->image)) 
                                ? url("assets/images/noimage.png") 
                                : get_url('testimonial/'.$testimonial->id.'/crop/'.$testimonial->image);
                @endphp
                <figure class="content_img">
                    <div class="content-overlay"></div>
                    <img class="content-image" src="{{ $imgurl }}">
                    <div class="content-details fadeIn-top"><p>
                            <p>{{$testimonial->description}}</p>
                        </p></div>
                    <figcaption class="short_testimonial_desc">
                        <p>{{ str_limit($testimonial->title, $limit = 45, $end = '...') }}</p>
                         <p class="overview"><b>{{$testimonial->client_name}} - {{get_country_name($testimonial->client_country)}}</b></p>
                    
                    </figcaption>
                     
                </figure>
                @endforeach
            </div> 
        </div>
        @else
        <h4 class="align_center">Sorry! No data found. </h4>
        @endif
    </div>
</div>
@endsection
