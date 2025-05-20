<?php

namespace App\Http\Controllers\Captain;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class CScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::orderBy('date', 'asc')
                           ->orderBy('time', 'asc')
                           ->get();

        return view('captain.c_schedule', compact('schedules'));
    }
} 