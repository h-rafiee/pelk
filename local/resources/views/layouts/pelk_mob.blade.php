<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>@yield('title','کتابخوان')</title>
    @yield('styles')
</head>
<body>
    <div class="container">
        <main>
            @yield('main')
            <footer>
                <span class="ic ic-logo"></span>
            </footer>
        </main>

    </div>
</body>
</html>