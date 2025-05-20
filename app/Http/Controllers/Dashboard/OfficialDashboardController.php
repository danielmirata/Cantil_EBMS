<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OfficialDashboardController extends Controller
{
    public function index()
    {
        return view('official.dashboard');
    }
}
