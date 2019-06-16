<!DOCTYPE html>
<html lang="fa">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title',trans('admin.header_title'))</title>
    @include('admin.objects.styles_core_libs')
    @include('admin.objects.fav_icon')
</head>

<body>

<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">
                        <img src="{{url('img/logo.jpg')}}" width="25px" class="img-rounded" alt="{!! trans('admin.header_title') !!}">
                        {!! trans('admin.header_title') !!}
                    </h2>
                </div>
                <div class="panel-body">
                    <form role="form" method="post">
                        {!! csrf_field() !!}
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control" placeholder="{!! trans('global.placeholder_username')  !!}" name="username" type="text" autofocus required>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="{!! trans('global.placeholder_password') !!}" name="password" type="password" value="" required>
                            </div>
                            <button class="btn btn-lg btn-success btn-block">{!! trans('global.login') !!}</button>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.objects.scripts_core_libs')
@include('admin.objects.scripts_admin')

</body>

</html>
