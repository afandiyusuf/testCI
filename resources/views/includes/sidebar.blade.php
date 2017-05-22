<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{ url('/') }}" class="site_title"><i class="fa fa-paw"></i> <span>COMIC NG</span></a>
        </div>
        
        <div class="clearfix"></div>
        
        <!-- menu profile quick info -->
        <div class="profile">
            <div class="profile_pic">
                <img src="{{ Gravatar::src(Auth::user()->email) }}" alt="Avatar of {{ Auth::user()->name }}" class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h2>{{ Auth::user()->name}}</h2>
            </div>
        </div>
        <!-- /menu profile quick info -->
        <div class="clearfix"></div>
        <br>
        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section" >
                <h3 style="padding-top: 20px">Admin Menu 1</h3>
                <ul class="nav side-menu">
                    @if(Auth::user()->can('open-comic'))
                    <li><a><i class="fa fa-home"></i>Comic Menu<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
{{--                             <li><a href="#">Comic List</a></li> --}}
                            @if(Auth::user()->can('create-comic'))
                            <li><a href="{{ url('/admin/comic/create') }}">Create New Comic</a></li>
                            @endif
                            <li><a href="{{ url('/admin/comic/search/99') }}">Search all comic</a></li>
                            <li><a href="{{ url('/admin/comic/search/1') }}">Search published comic</a></li>
                            <li><a href="{{ url('/admin/comic/search/0') }}">Search unpublished comic</a></li>
                            @if(Auth::user()->can('open-author'))
                            <li><a href="{{ url('/admin/author') }}">Manage Author</a></li>
                            @endif
                            @if(Auth::user()->can('open-category'))
                            <li><a href="{{ url('/admin/category') }}">Manage Category</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if(Auth::user()->can('open-user'))
                    <li><a><i class="fa fa-table"></i>User Menu<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ url('/admin/user') }}">User List</a></li>
                        </ul>
                    </li>
                    @endif

                    @if(Auth::user()->can('open-comment'))
                    <li><a><i class="fa fa-table"></i>Comment Menu<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ url('/admin/comment/get') }}">Comment List</a></li>
                        </ul>
                    </li>
                    @endif
                    @if(Auth::user()->can('open-role'))
                     <li><a><i class="fa fa-table"></i>Role Menu<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @if(Auth::user()->can('create-role'))
                                <li><a href="{{ url('/admin/role/create') }}">Create/Delete Role</a></li>
                            @endif
                            @if(Auth::user()->can('assign-role'))
                                <li><a href="{{ url('/admin/role/assign') }}">Assign/Unassign Role</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif
                </ul>
            </div>
           {{--  <div class="menu_section">
                <h3>Group 2</h3>
                <ul class="nav side-menu">
                    <li>
                        <a><i class="fa fa-sitemap"></i> Multilevel Menu <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li>
                                <a href="#">Level One</a>
                                <li>
                                    <a>Level One<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li class="sub_menu">
                                            <a href="#">Level Two</a>
                                        </li>
                                        <li>
                                            <a href="#">Level Two</a>
                                        </li>
                                        <li>
                                            <a href="#">Level Two</a>
                                        </li>
                                    </ul>
                                </li>
                            <li>
                                <a href="#">Level One</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div> --}}
        
        </div>
        <!-- /sidebar menu -->
        
        <!-- /menu footer buttons -->
        <div class="sidebar-footer hidden-small">
            <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Logout" href="{{ url('/logout') }}">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
        </div>
        <!-- /menu footer buttons -->
    </div>
</div>