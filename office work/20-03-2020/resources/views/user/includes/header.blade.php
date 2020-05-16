<!-- Topbar header - style you can find in pages.scss -->
<!-- ================================================ --> 
 <nav class="navbar navbar-default navbar-static-top m-b-0">
    <div class="header-container">
        <div class="navbar-header">
            <!-- Logo -->
            <div class="top-left-part b-right-0">
                <a class="logo" href="{{ route('home') }}">
                    @if(file_exists(public_path($image_path.'logo.png'))) 
                        <img src="{{ asset($image_path.'logo.png') }}" alt="home" class="img-responsive logo_img" />
                    @else
                        <h1 class="logo_text">J-1 <span class="hidden-xs hidden-sm">Application</span></h1> 
                    @endif 
                </a>
            </div>
            <!-- /Logo -->
            @if(Auth::check()) 
                @php
                    $total_notification = @request()->total_user_notification_list;
                    $user_notification_list = @request()->user_notification_list;
                @endphp

                @if(!in_array($self,['applicationprocedure','privacy-notice','terms-condition','home']))
                    <ul class="nav navbar-top-links navbar-left">
                        <li><a href="javascript:void(0)" class="open-close waves-effect waves-light hidden-lg" onclick="open_close_left_sidebar();"><i class="ti-menu"></i></a></li>
                    </ul>
                @endif
                <ul class="nav navbar-top-links navbar-right pull-right">
                    
<!--                    <li class="dropdown">
                        <a class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#">
                            <i class="fa fa-user"></i> <span class="hidden-sm hidden-xs hidden-md hidden-lg">Connection Requests</span>
                        </a>
                        <ul class="dropdown-menu conn_request">
                            <li>
                                <div class="drop-title">You have 4 new messages</div>
                            </li>
                            <li>
                                <ul class="slimscrolldropdownmenu p-l-0 conn_request_list">
                                    <li>
                                        <div class="message-center">
                                            <a class="cdefault">
                                                <div class="user-img">
                                                    <img src="{{ asset($image_path.'users/pawandeep.jpg') }}" alt="user" class="img-circle">
                                                    <span class="profile-status online pull-right"></span>
                                                </div>
                                                <div class="mail-contnet">
                                                    <h5>Pavan kumar</h5>
                                                    <span class="mail-desc">9:30 AM</span>
                                                    <div class="m-t-5">
                                                        <button class="btn btn-info btn-xs m-r-5">
                                                            <span class="visible-xs"><i class="fa fa-user-plus"></i></span>
                                                            <span class="hidden-xs">Confirm</span></button>
                                                        <button class="btn btn-danger btn-xs">
                                                            <span class="visible-xs"><i class="fa fa-user-times"></i></span>
                                                            <span class="hidden-xs">Delete Request</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </a>
                                            <a class="cdefault">
                                                <div class="user-img">
                                                    <img src="{{ asset($image_path.'/users/sonu.jpg') }}" alt="user" class="img-circle">
                                                    <span class="profile-status busy pull-right"></span>
                                                </div>
                                                <div class="mail-contnet">
                                                    <h5>Sonu Nigam</h5>
                                                    <span class="mail-desc">I've sung a song! See you at</span>
                                                    <div class="m-t-5">
                                                        <button class="btn btn-info btn-xs m-r-5">
                                                            <span class="visible-xs"><i class="fa fa-user-plus"></i></span>
                                                            <span class="hidden-xs">Confirm</span></button>
                                                        <button class="btn btn-danger btn-xs">
                                                            <span class="visible-xs"><i class="fa fa-user-times"></i></span>
                                                            <span class="hidden-xs">Delete Request</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </a>
                                            <a class="cdefault">
                                                <div class="user-img"> <img src="{{ asset($image_path.'/users/arijit.jpg') }}" alt="user" class="img-circle"> <span class="profile-status away pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>Arijit Sinh</h5>
                                                    <span class="mail-desc">I am a singer!</span>
                                                    <div class="m-t-5">
                                                        <button class="btn btn-info btn-xs m-r-5">
                                                            <span class="visible-xs"><i class="fa fa-user-plus"></i></span>
                                                            <span class="hidden-xs">Confirm</span></button>
                                                        <button class="btn btn-danger btn-xs">
                                                            <span class="visible-xs"><i class="fa fa-user-times"></i></span>
                                                            <span class="hidden-xs">Delete Request</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </a>
                                            <a class="cdefault b-bottom-0">
                                                <div class="user-img"> <img src="{{ asset($image_path.'/users/pawandeep.jpg') }}" alt="user" class="img-circle"> <span class="profile-status offline pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>Pavan kumar</h5>
                                                    <span class="mail-desc">Just see the my admin!</span>
                                                    <div class="m-t-5">
                                                        <button class="btn btn-info btn-xs m-r-5">
                                                            <span class="visible-xs"><i class="fa fa-user-plus"></i></span>
                                                            <span class="hidden-xs">Confirm</span></button>
                                                        <button class="btn btn-danger btn-xs">
                                                            <span class="visible-xs"><i class="fa fa-user-times"></i></span>
                                                            <span class="hidden-xs">Delete Request</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a class="text-center p-t-10 p-b-10 b-t" href="{{url('/connections')}}"> <b>See All </b></a>
                            </li>
                        </ul>
                    </li>-->
                    
                    <li class="dropdown" id="j1_notification_dropdown">
                        <a class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#">
                            @if (!empty($user_notification_list) && $user->has_notification == 1)
                                <div class="notify"><span class="heartbit"></span><span class="point"></span></div>
                            @endif
                            <i class="mdi mdi-bell"></i><span class="hidden-sm hidden-xs hidden-md hidden-lg">Notifications</span> 
                        </a>

                        <ul class="dropdown-menu dropdown-tasks">
                            @if($total_notification>0 && !empty($user_notification_list))
                            <li>
                                <div class="drop-title">You have {{ $total_notification }} new notification</div>
                            </li>
                            <li>
                                <ul class="slimscrolldropdownmenu p-l-0">
                                    @foreach($user_notification_list as $notification)
                                        <li>
                                            <div class="message-center notification">
                                                <a href="{{url('/viewnotification/'.secure_id($notification->log_id))}}">
                                                    <div class="notification-title"><h5>{{$notification->notification_text}} </h5></div>
                                                    <div class="notification-desc"><span>{{$notification->notification_log_text}} </span></div>
                                                    <div class="notification-time text-right"><span class="time">{{ dateformat($notification->created_at, DISPLAY_DATETIME) }}</span></div>
                                                </a>
                                            </div>
                                        </li>
                                    @endforeach  
                                </ul>
                            </li>
                            @else
                                <li>
                                    <div class="message-center">
                                        <a>
                                            <div class="mail-contnet">
                                                <span class="mail-desc">You have not any new notification</span> 
                                            </div>
                                        </a>
                                    </div>
                                </li>
                            @endif
                            <li class="b-t">
                                <a class="text-center p-t-10 p-b-10" href="{{ route('notifications') }}"> <strong>See all notifications</strong> <i class="fa fa-angle-right"></i> </a>
                            </li>
                        </ul>
                    </li>

                    <!-- <li class="dropdown">
                        <a class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#">
                            <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                            <i class="mdi mdi-gmail"></i> <span class="hidden-sm hidden-xs hidden-md hidden-lg"> Messages</span>
                        </a>
                        <ul class="dropdown-menu mailbox">
                            <li>
                                <div class="drop-title">You have 4 new messages</div>
                            </li>
                            <li>
                                <ul class="slimscrolldropdownmenu p-l-0">
                                    <li>
                                        <div class="message-center notification">
                                            <a href="{{url('/messages')}}">
                                                <div class="user-img"> <img src="{{ asset($image_path.'/users/pawandeep.jpg') }}" alt="user" class="img-circle"> <span class="profile-status online pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <span class="mail-desc">Just see the my admin!</span>
                                                    <span class="sl-date">9:30 AM</span>
                                                </div>
                                            </a>
                                            <a href="{{url('/messages')}}">
                                                <div class="user-img"> <img src="{{ asset($image_path.'/users/pawandeep.jpg') }}" alt="user" class="img-circle"> <span class="profile-status online pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <span class="mail-desc">I've sung a song! See you atI've sung a song! See you at</span>
                                                    <span class="sl-date">9:10 AM</span>
                                                </div>
                                            </a>
                                            <a href="{{url('/messages')}}">
                                                 <div class="user-img"> <img src="{{ asset($image_path.'/users/sonu.jpg') }}" alt="user" class="img-circle"> <span class="profile-status busy pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <span class="mail-desc">I am a singer!</span>
                                                    <span class="sl-date">9:08 AM</span>
                                                </div>
                                            </a>
                                            <a href="{{url('/messages')}}">
                                                <div class="user-img"> <img src="{{ asset($image_path.'/users/pawandeep.jpg') }}" alt="user" class="img-circle"> <span class="profile-status offline pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <span class="mail-desc">Just see the my admin!</span>
                                                    <span class="sl-date">9:02 AM</span>
                                                </div>
                                            </a>
                                            <a href="{{url('/messages')}}">
                                                <div class="user-img"> <img src="{{ asset($image_path.'/users/arijit.jpg') }}" alt="user" class="img-circle"> <span class="profile-status away pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <span class="mail-desc">I've sung a song! See you atI've sung a song! See you at</span>
                                                    <span class="sl-date">9:10 AM</span>
                                                </div>
                                            </a>
                                            <a href="{{url('/messages')}}">
                                                <div class="user-img"> <img src="{{ asset($image_path.'/users/pawandeep.jpg') }}" alt="user" class="img-circle"> <span class="profile-status offline pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <span class="mail-desc">I am a singer!</span>
                                                    <span class="sl-date">9:08 AM</span>
                                                </div>
                                            </a>
                                            <a href="{{url('/messages')}}">
                                                <div class="user-img"> <img src="{{ asset($image_path.'/users/arijit.jpg') }}" alt="user" class="img-circle"> <span class="profile-status away pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <span class="mail-desc">Just see the my admin!</span>
                                                    <span class="sl-date">9:02 AM</span>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li class="b-t">
                                <a class="text-center p-t-10 p-b-10" href="{{url('/messages')}}"> <b>See All </b></a>
                            </li>
                        </ul>
                    </li> -->

                    <li class="dropdown">
                        <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#"> 
                            <img src="{{ $profile_photo_50x }}" alt="{{ Str::title($user_name) }}" width="36" class="img-responsive thumbnail pull-left m-b-0 avatar-md">    
                            <b class="hidden-sm hidden-xs hidden-md full_name">{{ Str::title($user_name) }}</b><span class="caret m-l-10"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-user animated flipInY profile-dropdown-list">
                            <li>
                                <div class="dw-user-box">
                                    <div class="u-img"> 
                                        <img src="{{ $profile_photo_50x }}" alt="{{ Str::title($user_name) }}" class="avatar-md">
                                    </div>
                                    <div class="u-text text-center">
                                        <h4 class="text-info m-b-10 full_name">{{ Str::title($user_name) }} </h4>
                                        <a href="{{ route('view.profile') }}" class="btn btn-rounded btn-danger btn-sm">View Profile</a></div>
                                </div>
                                {{-- <p class="text-muted">{{ $user->email }}</p> --}}
                            </li>
                            <li role="separator" class="divider"></li>
 <li><a href="{{ route('edit.profile') }}" class="cpointer"><i class="fa fa-user m-r-5"></i> My Profile Settings…</a></li>
                            <li><a class="logout cpointer sign-out" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-power-off m-r-5"></i> Sign Out </a> 
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            @else
                <ul class="nav navbar-top-links navbar-right pull-right">
                    <li><a href="{{ route('login') }}"><i class="fa fa-sign-in m-r-5"></i> Sign In</a></li>
                    <li><a href="{{ route('register') }}"><i class="fas fa fa-user-plus m-r-5"></i> Sign up</a></li>
                </ul>
            @endif
        </div>
    </div>
</nav>
<!-- End Top Navigation -->
<!-- Start Maintenance Message--> 
@if(config("common.maintence_mode") == "ON")
<div id="page-wrapper" class="full_wrapper no-padding mm_box hide">
    <div class="alert alert-warning text-center m-b-0 notify_info_box">
        <button type="button" class="close" id="notify_ok" data-dismiss="alert" aria-hidden="true">&times;</button> Sorry for the inconvenience, we are down for the scheduled maintenance.
    </div> 
</div>
@endif
<!-- End Maintenance Message -->
