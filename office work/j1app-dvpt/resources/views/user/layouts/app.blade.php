<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0" />
      
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" type="image/x-icon" sizes="16x16" href="{{ asset($image_path.'favicon.ico') }}" />
    <title>{{ config('app.name') }}</title>
    
    <!-- Bootstrap Core CSS -->
    <link href="{{ asset($css_path.'bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <!-- Intro Js Css -->
    <link href="{{ asset($css_path.'introjs.css') }}" rel="stylesheet" /> 
    <!-- Menu CSS -->
    <link href="{{ asset($plugin_path.'sidebar-nav/dist/sidebar-nav.min.css') }}" rel="stylesheet" />
    <!-- toast CSS -->
    <link href="{{ asset($plugin_path.'toast-master/css/jquery.toast.css') }}" rel="stylesheet" />
    <!-- morris CSS -->
    <link href="{{ asset($plugin_path.'morrisjs/morris.css') }}" rel="stylesheet" />
    <!-- Calendar CSS -->
    <link href="{{ asset($plugin_path.'calendar/dist/fullcalendar.css') }}" rel="stylesheet" />
    <!-- animation CSS -->
    <link href="{{ asset($css_path.'animate.css') }}" rel="stylesheet" />
    <link href="{{ asset($plugin_path.'calendar/dist/fullcalendar.css') }}" rel="stylesheet" />
    <!-- Date Picker CSS -->
    <link href="{{ asset($plugin_path.'bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset($plugin_path.'bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Dropify img/file CSS -->    
    <link href="{{ asset($plugin_path.'dropify/dist/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />
    <!--alerts CSS -->
    <link href="{{ asset($plugin_path.'sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
     
    <!-- morris CSS -->
    <link href="{{ asset($plugin_path.'css-chart/css-chart.css') }}" rel="stylesheet" />
    
    <!-- Typehead CSS -->
    <link href="{{ asset($plugin_path.'typeahead.js-master/dist/typehead-min.css') }}" rel="stylesheet" /> 
    <link href="{{ asset($plugin_path.'switchery/dist/switchery.min.css') }}" rel="stylesheet" />
    
    <!-- Croppic CSS -->
    <link href="{{ asset($plugin_path.'croppic/croppic.css') }}" rel="stylesheet" />
    <link href="{{ asset($plugin_path.'croppic/upload.css') }}" rel="stylesheet" />
    
    <!-- Wizard CSS -->
    <link href="{{ asset($plugin_path.'jquery-wizard-master/css/wizard.css') }}" rel="stylesheet">
    
    <!-- FormValidation -->
    <link href="{{ asset($plugin_path.'jquery-wizard-master/libs/formvalidation/formValidation.min.css') }}">
    
    <!-- Theme CSS -->
    <link href="{{ asset($css_path.'style.css') }}" rel="stylesheet">
    <link href="{{ asset($css_path.'bootstrap/dist/css/carousel.css') }}" rel="stylesheet" /> 
     
    <!-- color CSS -->
    <link href="{{ asset($css_path.'colors/default.css') }}" id="theme" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset($css_path.'front-custom.css') }}" rel="stylesheet"> 
    
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <script src="{{ asset($plugin_path.'jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="{{ asset($css_path.'bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- Intro Js JavaScript -->
    <script src="{{ asset($js_path.'intro.js') }}"></script> 
    <!-- End javascript for Animated scroll to top -->
    
    <script src="{{ asset('js/helper.js') }}"></script> 
    @include('common.detecttz')
</head> 

<body class="fix-header boxed-layout MM_{{ config('common.maintence_mode') }}"> 
    <!-- Preloader -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> 
        </svg>
    </div>
   
    <!-- Topbar header - style you can find in pages.scss -->
    @include('user.includes.header')
    <!-- End Top Navigation -->
    
    <!-- Wrapper -->
    @if(in_array($self,['home']))
        <div id="container-fluid">
    @else
        <div id="wrapper"> 
    @endif
        
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        @if(!in_array($self,['applicationprocedure','privacy-notice','terms-condition','home','show-faq','testimonial','check.invitation']))
            @include('user.includes.verticalmenu')
            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid"> 
                    @yield('content') 
                </div>
            </div>
            <!-- End Page Content -->
        @else
            <div id="page-wrapper" class="full_wrapper">
                <div class="container-fluid no-padding"> 
                    @yield('content')
                </div>
            </div>
        @endif
        <!-- End Left Sidebar -->
         
        <!-- Start Footer Content-->
        @include('user.includes.footer-script')
        <!-- End Footer Content-->
        
        <!-- Start Chat Modal Content-->
        @include('user.includes.chatsidebar')
        <!-- End Chat Modal Content-->
        
        <!-- Start All Modal Content-->
        @include('user.includes.modal') 
        <!-- End All Modal Content-->
        
        @yield('scripts')   
    </div>
    <!-- End Wrapper -->
</body>
</html> 