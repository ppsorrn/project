<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    function showHome()
    {
        return view('home');
    }

    function showContact()
    {
        return view('contact');
    }
}
