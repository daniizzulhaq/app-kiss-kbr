<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BpdasDashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.bpdas');
    }
}