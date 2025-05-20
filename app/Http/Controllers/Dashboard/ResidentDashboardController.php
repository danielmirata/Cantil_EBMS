<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResidentDashboardController extends Controller
{
    public function index()
    {
        return view('resident.dashboard');
    }

    public function services()
    {
        return view('resident.services');
    }

    public function documents()
    {
        return view('resident.document');
    }
}
