<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        app('metasocial')->set('title', 'Home Page');
        return view('pages.home');
    }

    public function about()
    {
        app('metasocial')->set('title', 'About Page');
        return view('pages.about');
    }
}
