<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // dashboard index
    public function index()
    {
        return view('dashboard');
    }
}
