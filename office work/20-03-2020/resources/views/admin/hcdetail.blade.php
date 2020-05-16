@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">HC & Position Manager</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                @if(check_route_access('hc.list'))
                <li><a href="{{ route('hc.list') }}">Host Companies</a></li>
                @endif
                <li class="active">Host Company Detail</li>
            </ol>
        </div>
    </div>
    <div class="row"> 
        <div class="col-md-12 col-xs-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">Host Company Detail</h3>
                    </div>
                    @if(check_route_access('hc.list'))
                    <div class="col-md-6 col-xs-12">
                        <div class="pull-right">
                            <a href="{{ route('hc.list') }}" class="btn btn-block btn-info"><i class="fa fa-list"></i> Host Companies</a>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="row well  m-t-10">
                    <div class="col-md-12">
                        <div class="row  p-b-10">
                            <div class="col-md-3"> <strong>Host Company Name</strong>
                                <br>
                                <p class="text-muted">{{$host_company->hc_name}}</p>
                            </div>
                            <div class="col-md-3"> <strong>Host Company Id Number (EIN)</strong>
                                <br>
                                <p class="text-muted">{{$host_company->hc_id_number}}</p>
                            </div>
                            <div class="col-md-3"> <strong>Description</strong>
                                <br>
                                <p class="text-muted">{{$host_company->hc_description}}</p>
                            </div>
                            <div class="col-md-3"> <strong>Street</strong>
                                <br>
                                <p class="text-muted">{{$host_company->hc_street}}</p>
                            </div>
                        </div>
                        <div class="row p-b-10">
                            <div class="col-md-3"> <strong>Suite</strong>
                                <br>
                                <p class="text-muted">{{$host_company->hc_suite}}</p>
                            </div>
                            <div class="col-md-3"> <strong>City</strong>
                                <br>
                                <p class="text-muted">{{$host_company->hc_city}}</p>
                            </div>
                            <div class="col-md-3"> <strong>State</strong>
                                <br>
                                <p class="text-muted">
                                    {{ get_state_name($host_company->hc_state) }}</p>
                            </div>
                            <div class="col-md-3"> <strong>Zip Code / Postal Code</strong>
                                <br>
                                <p class="text-muted">{{$host_company->hc_zip}}</p>
                            </div>
                        </div>
                        <div class="row p-b-10">
                            <div class="col-md-3"> <strong>Contact First Name</strong>
                                <br>
                                <p class="text-muted">{{$host_company->contact_first_name}}</p>
                            </div>
                            <div class="col-md-3"> <strong>Contact Last Name</strong>
                                <br>
                                <p class="text-muted">{{$host_company->contact_last_name}}</p>
                            </div>
                            <div class="col-md-3"> <strong>Contact Title</strong>
                                <br>
                                <p class="text-muted">{{$host_company->contact_title}}</p>
                            </div>
                            <div class="col-md-3"> <strong>Contact Email</strong>
                                <br>
                                <p class="text-muted">{{$host_company->contact_email}}</p>
                            </div>
                        </div>
                        <div class="row p-b-10">
                            <div class="col-md-3"> <strong>Contact Skype</strong>
                                <br>
                                <p class="text-muted">{{ $host_company->contact_skype}}</p>
                            </div>
                            <div class="col-md-3"> <strong>Contact Phone</strong>
                                <br>
                                <p class="text-muted">{{$host_company->contact_phone}}</p>
                            </div>
                            <div class="col-md-3"> <strong>Phone Extension</strong>
                                <br>
                                <p class="text-muted">{{$host_company->contact_phone_extension}}</p>
                            </div>
                            <div class="col-md-3"> <strong>Contact Fax</strong>
                                <br>
                                <p class="text-muted">{{$host_company->contact_fax}}</p>
                            </div>
                        </div>
                        <div class="row p-b-10">
                            <div class="col-md-3"> <strong>Contact Website</strong>
                                <br>
                                <p class="text-muted">{{$host_company->contact_website}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection