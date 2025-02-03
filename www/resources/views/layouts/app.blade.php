<!DOCTYPE html>
<html lang="{{ $html_locale }}">

<head>
    <title>App Name - @yield('title')</title>
    <script>
        window.datasite = {{ Illuminate\Support\Js::from($datasite) }};
    </script>
</head>

<body>
    @section('sidebar')
        This is the master sidebar.
    @show

    <div class="container">
        @yield('content')
    </div>
</body>

</html>
