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
 
    <!-- Theme CSS -->
    <link href="{{ asset($css_path.'style.css') }}" rel="stylesheet">
    <link href="{{ asset($css_path.'bootstrap/dist/css/carousel.css') }}" rel="stylesheet" /> 
     
    <!-- color CSS -->
    <link href="{{ asset($css_path.'colors/default.css') }}" id="theme" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset($css_path.'front-custom.css') }}" rel="stylesheet"> 

</head>

<body class="fix-header boxed-layout MM_{{ config('common.maintence_mode') }}"> 
    
    @yield('content')
      
</body> 
</html>
