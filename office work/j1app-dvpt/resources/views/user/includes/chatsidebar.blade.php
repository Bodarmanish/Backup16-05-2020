@if(Route::currentRouteName() == "timeline") 
<div class="chat-sidebar chatboxbar">
    <div class="panel panel-inverse m-b-0">
        <div class="panel-heading p-5 chat-sidebar-toggle cpointer"> 
            <i class="ti-comments-smiley fa-fw p-5 m-r-10 vm"></i><small class="">Chat</small>
            <div class="pull-right"><a href="#" data-perform="panel-collapse" class="hidden p-r-10"><i class="ti-minus"></i></a></div>
        </div>
        <div class="panel-wrapper">
            <div class="chat_slimscrollright">
                <ul class="chat-lists">
                    <li>
                        <a href="javascript:void(0)">
                            <img src="{{ asset($image_path.'users/varun.jpg') }}" alt="user-img" class="thumbnail di m-r-5">
                            <p class="d-inline-b vt text-info m-b-0">Varun Dhavan <small class="text-success db">online</small></p>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <img src="{{ asset($image_path.'users/2.jpg') }}" alt="user-img" class="thumbnail di m-r-5">
                            <p class="d-inline-b vt text-info m-b-0">Varun Dhavan <small class="text-success db">online</small></p>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <img src="{{ asset($image_path.'users/5.jpg') }}" alt="user-img" class="thumbnail di m-r-5">
                            <p class="d-inline-b vt text-info m-b-0">Varun Dhavan <small class="text-warning db">Away</small></p>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <img src="{{ asset($image_path.'users/6.jpg') }}" alt="user-img" class="thumbnail di m-r-5">
                            <p class="d-inline-b vt text-info m-b-0">Varun Dhavan <small class="text-muted db">offline</small></p>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <img src="{{ asset($image_path.'users/7.jpg') }}" alt="user-img" class="thumbnail di m-r-5">
                            <p class="d-inline-b vt text-info m-b-0">Varun Dhavan <small class="text-danger db">Busy</small></p>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <img src="{{ asset($image_path.'users/3.jpg') }}" alt="user-img" class="thumbnail di m-r-5"> 
                            <p class="d-inline-b vt text-info m-b-0"> Varun Dhavan<small class="text-danger db">Busy</small></p>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <img src="{{ asset($image_path.'users/4.jpg') }}" alt="user-img" class="thumbnail di m-r-5"> 
                            <p class="d-inline-b vt text-info m-b-0"> Varun Dhavan last<small class="text-muted db">offline</small></p>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="search_frd">
                <input class="form-control" placeholder="Search for a connection..." type="text">
            </div>
        </div>
    </div>
</div>
@endif   