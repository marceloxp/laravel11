<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

if (!function_exists('app_version')) {
    function app_version($default = '0.0.1')
    {
        return Config::get('app.version', $default);
    }
}

if (!function_exists('framework_version')) {
    function framework_version()
    {
        return App::VERSION();
    }
}

if (!function_exists('vasset')) {
    function vasset($path)
    {
        return asset($path) . '?v=' . app_version();
    }
}

if (!function_exists('get_env')) {
    function get_env()
    {
        return env('APP_ENV', 'local');
    }

    function env_is_local()
    {
        return (get_env() == 'local');
    }

    function env_is_stage()
    {
        return (get_env() == 'stage');
    }

    function env_is_production()
    {
        return (get_env() == 'production');
    }

    function env_is_stage_or_production()
    {
        return (env_is_stage() or env_is_production());
    }
}

if (!function_exists('image_mime_type')) {
    function image_mime_type(string $path): ?string
    {
        // Lista dos principais tipos MIME para imagens
        $mimeTypes = [
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'gif'  => 'image/gif',
            'webp' => 'image/webp',
            'bmp'  => 'image/bmp',
            'svg'  => 'image/svg+xml',
        ];

        // Obtém a extensão do arquivo (sem o ponto)
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        // Retorna o MIME type ou vazio se não for encontrado
        return $mimeTypes[$extension] ?? '';
    }
}
