@extends('User.layouts.app')

@section('content')
<!-- Wrapper -->
<div class="white-box">
    <h2 class="text-info text-uppercase main-title">FAQ</h2> 
    <div class="row m-b-15">
        @if(isset($faq_data) && count($faq_data) != 0)
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
            <div class="accordion md-accordion" id="accordionEx1" role="tablist" aria-multiselectable="true">
                @foreach($faq_data as $index => $faq)
                <div class="card">
                    <div class="card-header custom_header" role="tab" id="headingTwo{{$index}}">
                        <a class="collapsed text_decor" data-toggle="collapse" data-parent="#accordionEx{{$index}}" href="#collapseTwo{{$index}}"
                           aria-expanded="false" aria-controls="collapseTwo{{$index}}">
                            <h4 class="title_weight">
                                {{$faq->question}} 
                                <span class="glyphicon glyphicon-chevron-down" style="float:right;"></span>

                            </h4>
                        </a>
                    </div>
                    <div id="collapseTwo{{$index}}" class="collapse" role="tabpanel" aria-labelledby="headingTwo{{$index}}"
                         data-parent="#accordionEx{{$index}}">
                        <div class="faq-text">
                            {!! $faq->answer !!}
                        </div>
                    </div>

                </div>
                @endforeach
            </div>
        </div> 
        @else
        <h4 class="align_center">Sorry! No data found. </h4>
        @endif
    </div>
</div>

@endsection
