<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="user_token" content="{{ @$user_token }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset($image_path.'favicon.ico') }}">
    <title>{{ config('app.name') }} Admin Panel</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{ asset($css_path.'bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Menu CSS -->
    <link href="{{ asset($plugin_path.'sidebar-nav/dist/sidebar-nav.min.css') }}" rel="stylesheet">
    <!-- animation CSS -->
    <link href="{{ asset($css_path.'animate.css') }}" rel="stylesheet">
    
    <!-- Date Picker CSS -->
    <link href="{{ asset($plugin_path.'bootstrap-datetimepicker-master/css/bootstrap-admin-datetimepicker.css') }}" rel="stylesheet"/>
    <link href="{{ asset($plugin_path.'bootstrap-datepicker/datepicker.css') }}" rel="stylesheet" type="text/css" />

    <!--alerts CSS -->
    <link href="{{ asset($plugin_path.'sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    
    <!-- DataTable CSS -->
    <link href="{{ asset($plugin_path.'datatables/jquery.dataTables.min.css') }}" rel="stylesheet"> 
    
    <!-- Croppic CSS -->
    <link href="{{ asset($plugin_path.'croppic/croppic.css') }}" rel="stylesheet" />
    <link href="{{ asset($plugin_path.'croppic/upload.css') }}" rel="stylesheet" />

    <!-- switch CSS -->
    <link href="{{ asset($plugin_path.'switchery/dist/switchery.min.css') }}" rel="stylesheet" />
    
    <!-- Add new plugin styles here -->
    <!-- Bootstrap Tokenfield CSS -->
    <link href="{{ asset($plugin_path.'bootstrap-tokenfield/bootstrap-tokenfield.css') }}" type="text/css" rel="stylesheet">
    
    <!-- Tokenfield Typeahead CSS -->
    <link href="{{ asset($plugin_path.'typeahead.js-master/dist/typehead-min.css') }}" type="text/css" rel="stylesheet">
    
    <!-- Jquery UI CSS -->
    <link href="{{ asset($plugin_path.'jquery-ui/jquery-ui.css') }}" type="text/css" rel="stylesheet">
    
    <!-- Dropify img/file CSS -->    
    <link href="{{ asset($plugin_path.'dropify/dist/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />
    
    <!-- IMPORTANT: keep below css style at the bottom of all other css -->
    <!-- Style CSS -->
    <link href="{{ asset($css_path.'style.css') }}" rel="stylesheet">
    <!-- color CSS -->
    <link href="{{ asset($css_path.'colors/'.$theme.'.css') }}" id="theme" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset($css_path.'admin-custom.css') }}" rel="stylesheet"> 
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    
    <!-- jQuery -->
    <script src="{{ asset($plugin_path.'jquery/dist/jquery.min.js') }}"></script>

    <script src="{{ asset('js/helper.js') }}"></script>
    @include('common.detecttz')
</head>

<body class="fix-header">
    <!-- ============================================================== -->
    <!-- Preloader -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Wrapper -->
    <!-- ============================================================== -->
    <div id="wrapper">
        
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        @include('admin.includes.header')
        <!-- End Top Navigation -->
        
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        @include('admin.includes.sidebar')
        <!-- ============================================================== -->
        <!-- End Left Sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page Content -->
        <!-- ============================================================== -->
        <div id="page-wrapper">
            @include('admin.includes.thems-setting')
            
            @yield('content')
            
            @include('admin.includes.footer')
            
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    
    @include('admin.includes.footer-script')
    @include('admin.includes.modal')
    
    @yield('scripts')
    
    <div id="full-overlay">
        <div class="spinner"></div>
    </div>
</body>

</html>
