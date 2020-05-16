<!doctype html>
<?php
        $user = config('apivariable.user_html');
        $as = config('apivariable.application_status');
        $asa = config('apivariable.application_status_activity');
?>
<html>
    <head>
        <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>J1 Application | J1 API Documentation</title>
        
        <link rel="stylesheet" href="{{ asset('api-doc/asset/css/bootstrap.css') }}">  
        <link href="{{ asset('api-doc/asset/css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('api-doc/asset/css/font-awesome.css') }}" rel="stylesheet">
    </head>
    <body>
        @include('api-document.include.header')
        
        <div class="container-fluid bg_gray ptb-30">
            <div class="row">
                @include('api-document.include.left-panel')
                
                <div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
                   <div class="card form-group">
                        <div class="card-body">
                            <h3 class="card-title">Notes</h3>
                            <div class="card-text">
                                <p><strong class="text-danger">Authorization Token</strong>: You would get Authorization Token from login APIs response. Keep it stored during a login session and pass it in necessary APIs. In simple word Authorization Token is a server thing which is generated by server attached with all APIs.</p>
                            </div>
                            <h3 class="card-title">Base Url</h3>
                            <div class="card-text">
                                <strong class="text-danger">{{url('api')}}</strong>
                            </div>
                        </div>
                    </div>                
                    @include('api-document.user')
                    @include('api-document.application-status')
                    @include('api-document.application-status-activity')
                </div>
            </div>
        </div>
        
        <script src="{{ asset('api-doc/asset/js/jquery.min.js') }}"></script>
        <script src="{{ asset('api-doc/asset/js/jquery-1.10.2.js') }}"></script>
        <script src="{{ asset('api-doc/asset/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('api-doc/asset/js/jquery.scrollTo.min.js') }}"></script>
        <script src="{{ asset('api-doc/asset/js/custom.js') }}"></script>
        <script src="{{ asset('api-doc/asset/js/user-custom.js') }}"></script>
        
    </body>
</html>
