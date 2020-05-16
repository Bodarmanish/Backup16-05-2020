@extends('user.layouts.app')

@section('content') 
@php
    $portfolio = @$portfolio;
    if($portfolio->portfolio_status==1){
        $portfolio_status = "InProgress";
    }
    elseif($portfolio->portfolio_status==2){
        $portfolio_status = "Active";
    }
    elseif($portfolio->portfolio_status==3){
        $portfolio_status = "Closed";
    }
    elseif($portfolio->portfolio_status==4){
        $portfolio_status = "Completed";
    }
    else{
        $portfolio_status = "Draft";
    }
@endphp
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="{{ route("home") }}" class="text-info">Home</a></li>
            <li class="active capitalize">Portfolio Detail</li>
        </ol>
    </div>
</div> 
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row m-b-10">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                    <h2 class="page-title capitalize no-margin"><b class="full_name">PF No: {{ $portfolio->portfolio_number }}</b></h2>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 text-right">
                    <a href="{{ route('myportfolio') }}" class="btn btn-info cpointer text-center btn-xl m-b-10 di" /> <i class="fa fa-arrow-left"></i> Back</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    @include('user.includes.status')
                    <div class="row pro_social">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <ul class="list-unstyled text-muted font-13">
                                <li><strong>Portfolio Status:</strong> {{ $portfolio_status }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
@endsection