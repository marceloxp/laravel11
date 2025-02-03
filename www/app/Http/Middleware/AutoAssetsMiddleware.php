<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

class AutoAssetsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Obtém o nome da rota atual
        $routeName = Route::currentRouteName();
        if (!$routeName) {
            view()->share('autoAssets', []);
            return $next($request);
        }

        // Caminhos dos arquivos CSS e JS
        $cssPath = public_path("css/{$routeName}.css");
        $jsPath = public_path("js/{$routeName}.js");

        // URLs dos arquivos
        $assets = [
            'css' => File::exists($cssPath) ? asset("css/{$routeName}.css") : null,
            'js' => File::exists($jsPath) ? asset("js/{$routeName}.js") : null,
        ];

        // Compartilha os assets com as views
        view()->share('autoAssets', $assets);

        $response = $next($request);

        return $response;
    }
}
