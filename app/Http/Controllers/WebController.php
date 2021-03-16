<?php

namespace App\Http\Controllers;

use Request, DB, Log, Hash, Curl, Auth;

class WebController extends Controller
{
    public function index()
    {
        return view('index');
    }
}
