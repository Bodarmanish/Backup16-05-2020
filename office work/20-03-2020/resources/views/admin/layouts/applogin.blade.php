<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <link rel="icon" type="image/x-icon" sizes="16x16" href="{{ asset($image_path.'favicon.ico') }}">
    <title>{{ config('app.name') }} Admin Panel</title>
     
    <!-- Bootstrap Core CSS -->
    <link href="{{ asset($css_path.'bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset($css_path.'style.css') }}" rel="stylesheet">

    <!-- color CSS -->
    <link href="{{ asset($css_path.'colors/default.css') }}" id="theme" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset($css_path.'admin-custom.css') }}" rel="stylesheet"> 
    
    <!-- ============================================================== -->
    <script src="{{ asset($plugin_path.'jquery/dist/jquery.min.js') }}"></script>
     
    <script src="{{ asset('js/helper.js') }}"></script>
    @include('common.detecttz')
</head>

<body>
    
    <!-- Preloader -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> 
        </svg>
    </div> 
    
    @yield('content')
    
    @include('admin.includes.footer-script')
</body> 
</html>
