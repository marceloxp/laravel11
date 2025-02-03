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

    {{-- Adiciona o JS, se existir --}}
    @if (!empty($autoAssets['js']))
        <script src="{{ $autoAssets['js'] }}"></script>
    @endif
</body>

</html>
