<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav slimscrollsidebar">
        <div class="sidebar-head">
            <h3><span class="fa-fw open-close"><i class="ti-close ti-menu"></i></span> <span class="hide-menu">Navigation</span></h3> </div>
        <div class="user-profile"></div>
        <ul class="nav" id="side-menu">
            <li>
                <a href="{{ route('dashboard') }}" class="waves-effect"><i class="mdi mdi-av-timer fa-fw" data-icon="v"></i>
                    <span class="hide-menu"> Dashboard </span>
                </a>
            </li>
            @if(!empty($menuitems))
                @foreach($menuitems as $section)
                    @if(!empty($section['display_name']))
                    @php
                    $icon_class = (!empty($section['icon_class'])) ? $section['icon_class'] : "fa fa-fw fa-cog" ;
                    @endphp
                    <li>
                        <a href="javascript:void(0);" class="waves-effect"><i class="{{ $icon_class }}" data-icon="v"></i> 
                            <span class="hide-menu"> {{ $section['display_name'] }} <span class="fa arrow"></span> </span>
                        </a>
                        @if(!empty($section['menus']))
                        <ul class="nav nav-second-level">
                            @foreach($section['menus'] as $item)
                            <li> <a href="{{ route($item['route_name']) }}"><span class="hide-menu">{{ $item['title'] }}</span></a> </li>
                            @endforeach
                        </ul>
                        @endif
                    </li>
                    @endif
                @endforeach
            @endif
            <li><a href="{{ route('logout') }}" class="waves-effect" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="mdi mdi-logout fa-fw"></i> <span class="hide-menu">Log out</span></a></li>
        </ul>
    </div>
</div>