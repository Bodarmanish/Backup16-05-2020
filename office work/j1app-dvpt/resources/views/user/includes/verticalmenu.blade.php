<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav slimscrollsidebar">
        <div class="sidebar-head">
            <h3><span class="fa-fw open-close cpointer" onclick="open_close_left_sidebar();"><i class="ti-close ti-menu font-bold"></i></span> <span class="hide-menu">Navigation</span></h3>
            <!-- <h3 class="text-right">
                <span class="fa-fw open-close cpointer" onclick="open_close_left_sidebar();"><i class="ti-close ti-menu font-bold"></i></span>
            </h3> -->
        </div>
        <ul class="nav custom-side-menu p-l-20 m-b-30" id="side-menu">
                @if(!empty($user->sponsor) && $odc_sponsor == $user->sponsor)
                <!-- <li><a href="{{ url('/timeline') }}" class="capitalize"><span>Timeline</span></a></li>
                <li><a href="{{ url('/connections') }}" class="capitalize"><span>connections</span></a></li> -->
                @endif
            <li>
                <div class="pointer_evnt_none p-10 m-t-10 p-b-5"> <b class="font-bold uppercase"> My Application </b></div>
                <ul class="nav nav-second-level collapse in">
                    <li><a href="{{ route('myportfolio') }}" class="capitalize"><span>My Portfolio</span></a></li>
                    <li><a href="{{ route('application-status')}}" class="capitalize" data-container="body" title="" data-toggle="popover" data-placement="right" data-content="Now you have access to rich content that not only will help you in the preparation of your trip and on arrival to the States." id="menu_application_status"><span>Application Status</span></a></li>
                    @if(!empty($user->sponsor) && $odc_sponsor == $user->sponsor)
                    <!-- <li> <a href="{{ url('/mytrip') }}" class="capitalize"><span>My Trip</span></a> </li> -->
                    @endif
                </ul>
            </li>
             <li>
                <div class="pointer_evnt_none p-10 m-t-10 p-b-5"> <b class="font-bold uppercase"> Forum </b></div>
                <ul class="nav nav-second-level collapse in">
                    <li> <a href="{{ url('/categories') }}" class="capitalize"><span>Categories</span></a> </li>
                    <li> <a href="{{ url('/recentdiscussions') }}" class="capitalize"><span>Recent Discussions</span></a> </li>
                    <li> <a href="{{ url('/following') }}" class="capitalize"><span>Following</span></a> </li>
                    <li> <a href="{{ url('/mytopics') }}" class="capitalize"><span>My Topics</span></a> </li>
                    <li> <a href="{{ url('/favoritetopic') }}" class="capitalize"><span>My Favorite Topics</span></a> </li>
                    <li> <a href="{{ url('/addtopic') }}" class="capitalize"><span>Post New Topic</span></a> </li>
                </ul>
            </li>
            
        </ul>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Left Sidebar -->
<!-- ============================================================== -->