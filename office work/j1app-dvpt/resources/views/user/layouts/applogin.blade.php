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
    <title>{{ config('app.name') }}</title>
     
    <!-- Bootstrap Core CSS -->
    <link href="{{ asset($css_path.'bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset($css_path.'style.css') }}" rel="stylesheet">

    <!-- color CSS -->
    <link href="{{ asset($css_path.'colors/default.css') }}" id="theme" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset($css_path.'front-custom.css') }}" rel="stylesheet">
    
    <!-- ============================================================== -->
    <script src="{{ asset($plugin_path.'jquery/dist/jquery.min.js') }}"></script>
    
    <script src="{{ asset('js/helper.js') }}"></script> 
    @include('common.detecttz')
</head>

<body class="fix-header boxed-layout login_registration MM_{{ config('common.maintence_mode') }}">
    
    <!-- Preloader -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> 
        </svg>
    </div>
     
    <!-- Topbar header - style you can find in pages.scss -->
    @include('user.includes.header')
    <!-- End Top Navigation -->
    
    @yield('content')
    
    <!-- Start All Modal Content-->
    @include('user.includes.modal') 
    <!-- End All Modal Content--> 
     
    <!-- All Jquery --> 
    <!-- Bootstrap Core JavaScript -->
    <script src="{{ asset($css_path.'bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="{{ asset($plugin_path.'sidebar-nav/dist/sidebar-nav.min.js') }}"></script>
    <!--slimscroll JavaScript -->
    <script src="{{ asset($js_path.'jquery.slimscroll.js') }}"></script>
    <!--Wave Effects -->
    <script src="{{ asset($js_path.'waves.js') }}"></script>
    
    <!-- Custom Theme JavaScript -->
    <script src="{{ asset($js_path.'validator.js') }}"></script>
    <script src="{{ asset($js_path.'custom.js') }}"></script>
    
    @yield('scripts')
</body> 
</html>
