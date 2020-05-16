@extends('User.layouts.app')

@section('content')
@php
    $portfolio_list = @$portfolio;
    $portfolio_status = @$portfolio_status;
@endphp

<!-- Wrapper -->  
<div class="row">
    <div class="col-sm-12">
        <div class="white-box">
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <h3 class="box-title">My Portfolio</h3>
                    <p class="m-b-30"></p>
                </div>
                @if(empty(array_intersect([0,1,2],$portfolio_status)))
                <div class="col-md-2 col-xs-12 pull-right">
                    <a href="{{ route('create.portfolio') }}" class="btn btn-block btn-info">Create New Portfolio</a>
                </div>
                @endif
            </div>
            @include('admin.includes.status')
            <div class="table-responsive"> 
                <table class="table color-table info-table">
                    <thead>
                        <tr>
                            <th>Portfolio Number</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($portfolio_list))
                            @foreach($portfolio_list as $portfolio)
                                @php
                                    if($portfolio->portfolio_status == 1){
                                        $status = 'InProgress';
                                    }elseif($portfolio->portfolio_status == 2){
                                        $status = 'Active';
                                    }elseif($portfolio->portfolio_status == 3){
                                        $status = 'Closed';
                                    }elseif($portfolio->portfolio_status == 4){
                                        $status = 'Completed';
                                    }else{
                                        $status = 'Draft';
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $portfolio->portfolio_number }}</td>
                                    <td>{{ $status }}</td>
                                    <td><a href="{{ route('portfolio.detail', $portfolio->portfolio_number) }}" data-toggle="tooltip" data-original-title="View Details"> View Details</a> @if($status=='Active') | <a href="#" data-toggle="tooltip" data-original-title="Go to Application Status">Go to Application Status</a> @endif</td>
                                </tr>
                            @endforeach
                        @else
                             <tr>
                                 <td colspan="3">No portfolio found.</td> 
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
