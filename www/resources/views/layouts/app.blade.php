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
        <meta name="framework-version" content="{{ framework_version() }}">
        <meta name="app-version" content="{{ app_version() }}">

        {{ app('metasocial')->print() }}

        <x-favicon url="favicon.png" />
        <x-css src="/css/style.min.css" />

        @if (!empty($autoAssets['css']))
            <link rel="stylesheet" href="{{ $autoAssets['css'] }}">
        @endif

        <!-- Datasite -->
        <script id="datasite" type="application/json">
            {!! json_encode($datasite, JSON_UNESCAPED_SLASHES) !!}
        </script>

        <!-- Load Datasite -->
        <script>
            window.datasite = JSON.parse(document.getElementById("datasite").text);
        </script>
    @show
</head>

<body>
    <div class="menu">
        @include('includes.menu')
        <img src="{{ vasset('images/fbshare.png') }}" width="320" alt="Laravel Logo">
    </div>
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
