<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Official;
use App\Models\ResidenceInformation;
use App\Models\OfficialDocumentRequest;
use App\Models\Project;

class CaptainDashboardController extends Controller
{
    public function index()
    {
        return view('captain.dashboard');
    }

    public function schedule()
    {
        $schedules = Schedule::orderBy('date', 'asc')->get();
        return view('captain.c_schedule', compact('schedules'));
    }

    public function officials()
    {
        $officials = Official::with('position')->active()->get();
        return view('captain.all_official', compact('officials'));
    }

    public function residents()
    {
        $residents = ResidenceInformation::with('household')->whereNull('archived_at')->get();
        return view('captain.all_residence', compact('residents'));
    }

    public function documents()
    {
        $requests = OfficialDocumentRequest::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('captain.documents', compact('requests'));
    }

    public function projects()
    {
        $projects = Project::orderBy('created_at', 'desc')->get();
        return view('captain.project', compact('projects'));
    }

    public function map()
    {
        return view('captain.map');
    }

    public function inventory()
    {
        return view('captain.inventory');
    }
}
