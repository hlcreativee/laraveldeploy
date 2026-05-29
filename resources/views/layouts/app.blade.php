<!DOCTYPE html>
<html>
<head>
    <title>Retail Pro</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    @include('components.sidebar')

    <div class="main">
        @yield('content')
    </div>

</body>
</html>