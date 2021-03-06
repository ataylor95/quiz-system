<!DOCTYPE html>

<html>

    <head>
        @yield('title')
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <!-- Scripts -->
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
    </head>

    <body>
        @yield('content')
        <script src="{{ asset('js/app.js') }}"></script>
        @yield('scripts')
    </body>

</html>

