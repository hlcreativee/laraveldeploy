<!DOCTYPE html>
<html>
<head>
    <title>Retail Pro</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    @include('components.sidebar')

    <div class="main">
        @yield('content')
    </div>

    <script src="{{ asset('js/sidebar.js') }}"></script>
</body>
</html>