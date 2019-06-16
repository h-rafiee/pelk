<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>@yield('title','سامانه نشر الکترونیک')</title>
    <link href="{{asset('css/style.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/idangerous.swiper.css')}}" rel="stylesheet" type="text/css">
    @yield('styles')
</head>
<body>
    <div class="container">

        <header>
            <section>
                <nav>
                    <a href="{{url('/')}}" class="ic ic-logo"><h1>فروشگاه سامانه نشر الکترونیک</h1></a>
                    <div>
                        <a href="{{url('categories')}}" class="ic ic-cat {{@$cat_nav}}"></a>
                        <a href="{{url('login')}}" class="ic ic-user {{@$user_nav}}"></a>
                        {{--<a href="{{url('basket')}}" class="ic ic-basket"></a>--}}
                        <a href="{{url('search')}}" class="ic ic-search {{@$search_nav}}"></a>
                    </div>
                </nav>
            </section>
        </header>

        @yield('slider')

        <main>
            @yield('main')
            <footer>
                <span class="ic ic-logo"></span>
            </footer>
        </main>

    </div>
    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{asset('js/idangerous.swiper.js')}}"></script>
    <script src="{{asset('js/script.js')}}"></script>
    @yield('scripts')
</body>
</html>