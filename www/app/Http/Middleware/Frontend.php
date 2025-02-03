<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

class Frontend
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $url = [
            'routename' => Route::currentRouteName(),
            'current' => $request->url(),
            'root' => $request->root(),
            'path' => $request->path(),
            'full' => $request->fullUrl(),
        ];
        app('datasite')->set('url', $url);

        $env = env('APP_ENV');
        app('datasite')->set('env', $env);

        $csrf_token = csrf_token();
        app('datasite')->set('csrf_token', $csrf_token);

        $html_locale = str_replace('_', '-', app()->getLocale());
        View::share('html_locale', $html_locale);
        return $next($request);
    }
}
