<?php

use Illuminate\Support\Facades\Config;

if (!function_exists('vasset')) {
    function vasset($path)
    {
        $version = Config::get('app.version', '1.0.0');
        return asset($path) . "?v={$version}";
    }
}
