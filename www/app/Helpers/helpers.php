<?php

use Illuminate\Support\Facades\Config;

if (!function_exists('app_version')) {
    function app_version($default = '0.0.1')
    {
        return Config::get('app.version', $default);
    }
}

if (!function_exists('vasset')) {
    function vasset($path)
    {
        return asset($path) . '?v=' . app_version();
    }
}
