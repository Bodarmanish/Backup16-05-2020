@extends('User.layouts.app')

@section('content')
<!-- Wrapper --> 
<section id="section-1" class="light-blue-bg">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="fullCarousel" class="carousel slide" data-ride="carousel">
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner">
                        <div class="item active">
                            <img src="{{ asset($image_path.'bg.png') }}" alt="">
                            <div class="container">
                                <div class="carousel-caption text-left">
                                    <h1 class="home-title">Get your J-1 Visa Sponsorship</h1>
                                    <p>J1, you're one stop boutique to experience the American culture and boost your career</p>
                                    <p><a class="btn btn-info btn-lg btn-rounded apply_btn" href="{{ route('register')}}" role="button">Apply Now</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <img src="{{ asset($image_path.'bg.png') }}" alt="Los Angeles" style="width:100%;">
                            <div class="container">
                                <div class="carousel-caption text-left">
                                    <h1 class="home-title">Get your J-1 Visa Sponsorship</h1>
                                    <p>J1, you're one stop boutique to experience the American culture and boost your career</p>
                                    <p><a class="btn btn-info btn-lg btn-rounded apply_btn" href="{{ route('register')}}" role="button">Apply Now</a></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Left and right controls -->
                    <a class="left carousel-control" href="#fullCarousel" data-slide="prev">
                      <span class="glyphicon glyphicon-chevron-left"></span>
                      <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#fullCarousel" data-slide="next">
                      <span class="glyphicon glyphicon-chevron-right"></span>
                      <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="white-bg" id="section-2">
    <div class="container">
        <div class="row client_logo"> 
            <div class="col-md-4 col-xs-4">
                <img src="{{ asset($image_path.'palmetto_bluff.png') }}" class="img-responsive center-block" />
            </div>
            <div class="col-md-4 col-xs-4">
                <img src="{{ asset($image_path.'stein_erikson_lodge.png') }}" class="img-responsive center-block" />
            </div>
            <div class="col-md-4 col-xs-4">
                <img src="{{ asset($image_path.'hyatt_regency.png') }}" class="img-responsive center-block" />
            </div>
        </div>
    </div>
</section>

<section id="section-3">
    <div class="container custom_width">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1 class="m-t-50 m-b-50"><strong>Why choose J1?</strong><br/> Because you can do almost everything from the comfort of your chair...</h1>
            </div> 
        </div>
    </div>    
</section>

<section id="section-4" class="white-box p-b-0">
    <div class="container"> 
        <div class="row">
            <h3>Too much complications? We are here to fix that...</h3>
            <div class="col-md-6 m-t-20"> 
                <ul class="greenbullet">
                    <li>Find you a sponsor for your J1-Visa</li>
                    <li>Handle all paperwork, you just need to show up for the embassy interview</li>
                    <li>Prepare you for the transition</li>
                    <li>Arrange all your travel related information</li>
                    <li>Connect, connect and connect. Friends, colleagues and on top previous J1-Visa holders</li>
                    <li>Get regular updates whenever you want on your application status</li>
                    <li>Learn everything you need to know about the J1-Visa from other students</li>
                    <li>Get information on your future employer and your destination city</li>
                    <li>Have a question? Get immediate answers</li>
                    <li>Start events with your colleagues and friends</li>
                    <li>Know what is happening near you</li>
                </ul>
            </div> 
            <div class="col-md-6">
                <ul class="nav nav-pills"> 
                    <li class="active"><a href="#simplified_process" data-toggle="tab" aria-expanded="false">Simplified Process</a></li>
<!--                    <li><a href="#connections" data-toggle="tab" aria-expanded="false">Connections</a></li> 
                    <li><a href="#forums" data-toggle="tab" aria-expanded="false">Forums</a></li>
                    <li><a href="#instant_messanges" data-toggle="tab" aria-expanded="false">Instant Messages</a></li>-->
                </ul>
                <div class="tab-content br-n pn">  
                    <div id="simplified_process" class="tab-pane fade in active"> 
                        <img src="{{ asset($image_path.'application_status.png') }}" class="img-responsive center-block" />
                    </div>
                    <div id="connections" class="tab-pane fade in"> 
                        <img src="{{ asset($image_path.'connections.png') }}" class="img-responsive center-block" />
                    </div>
                    <div id="forums" class="tab-pane fade in"> 
                        <img src="{{ asset($image_path.'forums.png') }}" class="img-responsive center-block" />
                    </div>
                    <div id="instant_messanges" class="tab-pane fade in"> 
                        <img src="{{ asset($image_path.'instant_messanges.png') }}" class="img-responsive center-block" />
                    </div>
                </div> 
            </div> 
        </div> 
        <div class="row">
            <div class="col-md-6 user_thought">
                <img src="{{ asset($image_path.'user.png') }}" class="img-responsive img-circle arrow_box_img">
                <div class="left_arrow_box col-md-10">
                    <p>I don't have to travel to the office to submit a document.</p>
                </div>
            </div>			
        </div>
    </div>
</section>

<section id="section-5">
    <div class="container">
        <div class="row m-b-15 m-t-50">
            <div class="col-md-12 text-center">
                <h1>Join now and think later...</h1>
                <p><a class="btn btn-info btn-lg btn-rounded apply_btn" href="{{ route('register') }}" role="button">Apply Now</a></p>
            </div>  
        </div> 
        <div class="blankheight"></div>
    </div>
</section>

@if(!empty($testimonial_data) && count($testimonial_data) != 0)
<section id="section-6">
    <div class="container"> 
        <div class="row m-b-50">
            <div class="col-md-12 text-center">
                <h1 class="m-0">Our clients trust us for a reason</h1>
                <h2 class="m-0">Read what they're saying about us</h2> 
            </div>   
        </div>
    </div>
</section>

<section id="section-4" class="white-bg">
    <div class="container"> 
        <div class="row m-b-50 m-t-50"> 
            <div class="col-md-12"> 
                <div id="testimonialCarousel" class="carousel slide" data-ride="carousel"> 
                    <!-- Wrapper for carousel items -->
                    <div class="carousel-inner">
                        @foreach ($testimonial_data as $testimonial)
                        @php
                            $imgurl = empty(get_url('testimonial/'.$testimonial->id.'/crop/'.$testimonial->image)) 
                                        ? url("assets/images/noimage.png") 
                                        : get_url('testimonial/'.$testimonial->id.'/crop/'.$testimonial->image);
                        @endphp
                        <div class="item carousel-item {{ $loop->first ? 'active' : '' }}">
                            <div class="row">
                                <div class="col-sm-4"> 
                                    <img src="{{ $imgurl }}" class="img-responsive test_default_img" />
                                </div> 
                                <div class="col-sm-8 text-left"> 
                                    <div class="testimonial">
                                        <p class="testimonial-text">{{$testimonial->title}}</p> 
                                    </div> 
                                    <div class="testimonial-info">
                                        <p class="overview"><b>{{$testimonial->client_name}} - {{get_country_name($testimonial->client_country)}}</b></p>
                                        <p><a class="btn btn-info btn-lg btn-rounded apply_btn" href="/testimonial" role="button">Read Testimonial</a></p>
                                    </div>                                    
                                </div> 
                            </div>			
                        </div>
                       @endforeach
                    </div>
                    <!-- Left and right controls -->
                    <a class="left carousel-control" href="#testimonialCarousel" data-slide="prev">
                      <span class="glyphicon glyphicon-chevron-left custom_left"></span>
                      <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#testimonialCarousel" data-slide="next">
                      <span class="glyphicon glyphicon-chevron-right custom_right"></span>
                      <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div> 
    </div>
</section>
@endif
@if(!empty($faq_data) && count($faq_data) != 0)
<section id="section-7" class="light-blue-bg">
    <div class="container">
        <div class="row m-b-50 m-t-50">
            <div class="col-md-6">
                <h1>Get answers to your <b>FAQ</b>s here</h1>
                <p><a class="btn btn-info btn-lg btn-rounded apply_btn" href="mailto:info@j1application.com" role="button">Send us an email</a></p>
                <p class="response_time">(Our average response time is AWESOME)</p>
            </div> 
            <div class="col-md-6">
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
                               <div class="card-body">
                                   {!! $faq->answer !!}
                               </div>
                           </div>
                       </div>
                       @endforeach
                   </div>
                @if(count($faq_data) >= 10)
                    <p><a class="btn btn-info btn-lg btn-rounded apply_btn m-t-20" href="{{ route('show-faq') }}" role="button">Read More</a></p>
                @endif
            </div> 
        </div>
    </div>
</section>
<!--@endif-->

@if(!empty($vacancies))
<section>
    <div class="container multiblock_slider"> 
        <div class="row m-t-50">
            <div class="col-md-12 text-center">
                <h1><strong>Available Vacancies</strong></h1>
            </div>
        </div>
        <div class="row m-b-50 m-t-15"> 
            <div class="MultiCarousel" data-items="1,2,3" data-slide="1" id="MultiCarousel"  data-interval="1000" data-cycle="true">
                <div class="MultiCarousel-inner">
                    @foreach($vacancies as $value)
                        <div class="item">
                            <div class="pad15">
                                <img src="{{$value->main_image}}" alt="" class="img-responsive vacancy-img"> 
                                <div class="vacancy_data m-t-30 m-b-20 text-left">
                                    <strong>{{$value->pos_name}}</strong>
                                    <br/>{{$value->emp_name}}
                                    <br/>{{$value->state_name}}<br/>
                                </div> 
                                <p><a class="btn btn-info btn-lg btn-rounded apply_btn pull-left" href="{{ route('register') }}" role="button">Apply Now</a></p>
                            </div>
                        </div>
                    @endforeach
          
                </div>
                <!-- Left and right controls -->
                @if (count($vacancies) > 3)
                <a class="leftLst" href="#MultiCarousel" data-slide="prev">
                  <span class="glyphicon glyphicon-chevron-left"></span>
                  <span class="sr-only">Previous</span>
                </a>
                <a class="rightLst" href="#MultiCarousel" data-slide="next">
                  <span class="glyphicon glyphicon-chevron-right"></span>
                  <span class="sr-only">Next</span>
                </a> 
                @endif
            </div> 
        </div>
    </div>
</section>
@endif

<!--<section>
    <div class="container">  
        <div class="row m-t-30">
            <div class="col-md-12 text-center m-b-50">
                <h1><strong>Recent News & Upcoming Events</strong></h1>
            </div>
            <div class="col-md-6"> 
                <img src="{{ asset($image_path.'blank_bg.png') }}" class="img-responsive" />
                <div class="news_content">
                    <h3 class="text-info">VIRTUAL JOB FAIR <br/><span>Week of December 5th!</span></h3> 
                    <p>Californian resort, 5 diamond Award is looking for FB participants for the summer season from April 2017 to October 2017!<br/>Work and Travel program available.</p>
                    <p>Please <a href="{{ route('register') }}">Apply Now</a>.</p>
                </div>
            </div>
            <div class="col-md-6"> 
                <img src="{{ asset($image_path.'blank_bg.png') }}" class="img-responsive" /> 
                <div class="news_content">
                    <h3 class="text-info">Attention all participants at the Greenbrier Resort</h3>
                    <p>Attention all participants at the Greenbrier Resort please comment below if you need assistance due to the recent flood. Please include your phone number. If you and your friends are safe please list their names. For any emergencies this evening please call your sponsor emergency hotline. If you are not training at the Greenbrier but know someone who is tag them here. If you are experiencing a life threatening emergency please call 911.</p>
                </div> 
            </div> 
        </div>
    </div>
</section>-->
@endsection
