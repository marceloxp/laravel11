<!DOCTYPE html>
<html lang="{{ $html_locale }}">

<head>
    <title>App Name - @yield('title')</title>
    {{-- Adiciona o CSS, se existir --}}
    @if (!empty($autoAssets['css']))
        <link rel="stylesheet" href="{{ $autoAssets['css'] }}">
    @endif
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

    <script src="{{ asset('lib/jsBaseClass.min.js') }}"></script>
    <script src="{{ asset('lib/jquery-3.7.1.min.js') }}"></script>
    @if (!empty($autoAssets['js']))
        <script src="{{ $autoAssets['js'] }}"></script>
    @endif
</body>

</html>
