<!DOCTYPE html>
<html lang="fa">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title',trans('admin.header_title'))</title>
    @include('admin.objects.styles_core_libs')
    @yield('style')
</head>

<body>

<div id="wrapper">

    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{url('admin')}}">{!! trans('admin.header_title') !!}</a>
        </div>
        @include('admin.objects.nav_top_links')
        @include('admin.objects.nav_static_side')
    </nav>
    <div id="page-wrapper">
        @yield('content')
    </div>
</div>


@include('admin.objects.scripts_core_libs')

@include('admin.objects.scripts_core_libs')
@include('admin.objects.scripts_admin')
@yield('scripts')
</body>
</html>