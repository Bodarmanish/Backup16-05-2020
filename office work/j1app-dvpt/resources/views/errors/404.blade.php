@extends('user.layouts.appBlank')
@section('content')
 <nav class="navbar navbar-default navbar-static-top m-b-0">
    <div class="header-container">
        <div class="navbar-header">
            <!-- Logo -->
            <div class="top-left-part b-right-0">
                <a class="logo" href="{{ route('home') }}">
                    @if(file_exists(public_path($image_path.'logo.png'))) 
                        <img src="{{ asset($image_path.'logo.png') }}" alt="home" class="img-responsive logo_img" />
                    @else
                        <h1 class="logo_text">J-1 <span class="hidden-xs hidden-sm">Application</span></h1> 
                    @endif 
                </a>
            </div>
            <!-- /Logo -->
        </div>
    </div>
</nav> 
<section id="wrapper" class="error-page">
  <div class="error-box1">
    <div class="error-body text-center">
        <h1 class="text-warning"><img src="{{ asset($image_path.'404.png') }}" /></h1>
        <h3 class="text-uppercase">This page isn't available</h3>
        <p class="text-muted m-t-30 m-b-30">The link you followed may be broken, or the page may have been removed.</p>
        <a href="{{ url('/') }}" class="btn btn-info btn-rounded waves-effect waves-light m-b-40">Back to home</a> </div> 
  </div>
</section>
@endsection
