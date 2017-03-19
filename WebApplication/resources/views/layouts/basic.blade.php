<!DOCTYPE html>

<html>

    <head>
        @yield('title')
    </head>

    <body>
        @yield('content')
        <script src="{{ asset('js/app.js') }}"></script>
        @yield('scripts')
    </body>

</html>

