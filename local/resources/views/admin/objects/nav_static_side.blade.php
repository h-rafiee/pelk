<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li>
                <a href="{{url('admin')}}"><i class="fa fa-dashboard fa-fw"></i> {!! trans('admin.dashboard') !!}</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-user fa-fw"></i> {!! trans('admin.members') !!}<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{url('admin/users')}}">{!! trans('admin.users') !!}</a>
                    </li>
                    <li>
                        <a href="{{url('admin/administrators')}}">{!! trans('admin.administrators') !!}</a>
                    </li>
                </ul>
                <!-- /.nav-second-level -->
            </li>
            <li>
                <a href="{{url('admin/tags')}}"><i class="fa fa-tags"></i> {!! trans('admin.tags') !!}</a>
            </li>
            <li>
                <a href="{{url('admin/categories')}}"><i class="fa fa-list"></i> {!! trans('admin.categories') !!}</a>
            </li>
            <li>
                <a href="{{url('admin/books')}}"><i class="fa fa-book"></i> {!! trans('admin.books') !!}</a>
            </li>
            <li>
                <a href="{{url('admin/magazines')}}"><i class="fa fa-newspaper-o"></i> {!! trans('admin.magazines') !!}</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-laptop fa-fw"></i> {!! trans('admin.web') !!}<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{url('admin/web/sliders')}}">{!! trans('admin.sliders') !!}</a>
                    </li>
                    <li>
                        <a href="{{url('admin/web/template')}}">{!! trans('admin.home-template') !!}</a>
                    </li>
                </ul>
                <!-- /.nav-second-level -->
            </li>
            <li>
                <a href="#"><i class="fa fa-mobile fa-fw"></i> {!! trans('admin.mobile') !!}<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{url('admin/mobile/sliders')}}">{!! trans('admin.sliders') !!}</a>
                    </li>
                    <li>
                        <a href="{{url('admin/mobile/template')}}">{!! trans('admin.home-template') !!}</a>
                    </li>
                </ul>
                <!-- /.nav-second-level -->
            </li>
            {{--<li>--}}
                {{--<a href="forms.html"><i class="fa fa-edit fa-fw"></i> Forms</a>--}}
            {{--</li>--}}
            {{--<li>--}}
                {{--<a href="#"><i class="fa fa-wrench fa-fw"></i> UI Elements<span class="fa arrow"></span></a>--}}
                {{--<ul class="nav nav-second-level">--}}
                    {{--<li>--}}
                        {{--<a href="panels-wells.html">Panels and Wells</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a href="buttons.html">Buttons</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a href="notifications.html">Notifications</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a href="typography.html">Typography</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a href="icons.html"> Icons</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a href="grid.html">Grid</a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
                {{--<!-- /.nav-second-level -->--}}
            {{--</li>--}}
            {{--<li>--}}
                {{--<a href="#"><i class="fa fa-sitemap fa-fw"></i> Multi-Level Dropdown<span class="fa arrow"></span></a>--}}
                {{--<ul class="nav nav-second-level">--}}
                    {{--<li>--}}
                        {{--<a href="#">Second Level Item</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a href="#">Second Level Item</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a href="#">Third Level <span class="fa arrow"></span></a>--}}
                        {{--<ul class="nav nav-third-level">--}}
                            {{--<li>--}}
                                {{--<a href="#">Third Level Item</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a href="#">Third Level Item</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a href="#">Third Level Item</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a href="#">Third Level Item</a>--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                        {{--<!-- /.nav-third-level -->--}}
                    {{--</li>--}}
                {{--</ul>--}}
                {{--<!-- /.nav-second-level -->--}}
            {{--</li>--}}
            {{--<li>--}}
                {{--<a href="#"><i class="fa fa-files-o fa-fw"></i> Sample Pages<span class="fa arrow"></span></a>--}}
                {{--<ul class="nav nav-second-level">--}}
                    {{--<li>--}}
                        {{--<a href="blank.html">Blank Page</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a href="login.html">Login Page</a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
                {{--<!-- /.nav-second-level -->--}}
            {{--</li>--}}
        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>