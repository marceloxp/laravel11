<!DOCTYPE html>
<html lang="{{ $html_locale }}">

<head>
    @section('head')
        <meta charset="utf-8">
        <meta name="viewport"
            content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0, viewport-fit=cover">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge,Chrome=1">
        <meta name="env" content="{{ env('APP_ENV', 'undefined') }}">
        <meta name="now" content="{{ date('Y-m-d H:i:s') }}">
        <meta name="framework-version" content="{{ App::VERSION() }}">
        <meta name="app-version" content="{{ app_version() }}">
        <x-favicon url="favicon.png" />
        <x-css src="/css/style.min.css" />

        <title>App Name - @yield('title')</title>
        @if (!empty($autoAssets['css']))
            <link rel="stylesheet" href="{{ $autoAssets['css'] }}">
        @endif
        <script>
            window.datasite = {{ Illuminate\Support\Js::from($datasite) }};
        </script>
    @show
</head>

<body>
    <div class="container">
        @yield('content')
    </div>

    @yield('before_bottom_scripts')
    <script src="{{ vasset('js/vendor.min.js') }}"></script>
    <script src="{{ asset('libs/jsBaseClass.min.js') }}"></script>
    @if (!empty($autoAssets['js']))
        <script src="{{ $autoAssets['js'] }}"></script>
    @endif
    @yield('after_bottom_scripts')
</body>

</html>
