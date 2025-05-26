<?php

namespace App\Http\Controllers\Official;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::orderBy('date', 'asc')
                           ->orderBy('time', 'asc')
                           ->get();

        return view('official.schedule', compact('schedules'));
    }
} 