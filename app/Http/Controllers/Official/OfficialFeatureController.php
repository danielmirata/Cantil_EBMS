<?php

namespace App\Http\Controllers\Official;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Official;
use App\Models\OfficialDocumentRequest;
use App\Models\Project;
use App\Models\Inventory;
use App\Models\Schedule;
use App\Models\ResidentsInformation;
use App\Models\Household;
use Carbon\Carbon;

class OfficialFeatureController extends Controller
{
    public function dashboard()
    {
        // Get total counts
        $totalResidents = ResidentsInformation::count();
        $totalHouseholds = Household::count();
        $totalOfficials = Official::where('status', 'Active')->whereNull('archived_at')->count();
        $totalPuroks = ResidentsInformation::distinct('street')->count('street');

        // Get recent residents
        $recentResidents = ResidentsInformation::latest()
            ->take(5)
            ->get();

        // Get recent officials
        $recentOfficials = Official::where('status', 'Active')
            ->whereNull('archived_at')
            ->latest()
            ->take(5)
            ->get();

        // Get demographics
        $now = Carbon::now();
        $residents = ResidentsInformation::all();
        
        $demographics = [
            'gender' => [
                'male' => $residents->where('gender', 'Male')->count(),
                'female' => $residents->where('gender', 'Female')->count(),
            ],
            'age_groups' => [
                'children' => $residents->filter(function($resident) use ($now) {
                    return $now->diffInYears($resident->date_of_birth) < 18;
                })->count(),
                'adults' => $residents->filter(function($resident) use ($now) {
                    $age = $now->diffInYears($resident->date_of_birth);
                    return $age >= 18 && $age < 60;
                })->count(),
                'senior' => $residents->filter(function($resident) use ($now) {
                    return $now->diffInYears($resident->date_of_birth) >= 60;
                })->count(),
            ],
            'voters' => [
                'registered' => $residents->where('is_registered_voter', true)->count(),
            ],
            'pwd' => $residents->where('is_pwd', true)->count(),
            'single_parent' => $residents->where('is_single_parent', true)->count(),
        ];

        return view('official.dashboard', compact(
            'totalResidents',
            'totalHouseholds',
            'totalOfficials',
            'totalPuroks',
            'recentResidents',
            'recentOfficials',
            'demographics',
            'residents'
        ));
    }

    public function schedule(Request $request)
    {
        $query = Schedule::latest();
        $search = $request->input('search');
        $dateSearch = null;

        if ($search) {
            try {
                $parsed = \Carbon\Carbon::parse($search);
                $dateSearch = $parsed->format('Y-m-d');
            } catch (\Exception $e) {
                $dateSearch = null;
            }

            $query->where(function($q) use ($search, $dateSearch) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('venue', 'like', "%$search%")
                  ->orWhere('time', 'like', "%$search%");

                if ($dateSearch) {
                    $q->orWhere('date', $dateSearch);
                } else {
                    $q->orWhere('date', 'like', "%$search%");
                }
            });
        }

        $schedules = $query->paginate(5)->appends(['search' => $search]);

        return view('official.c_schedule', compact('schedules', 'search'));
    }

    public function officials()
    {
        $officials = Official::with('position')->active()->get();
        return view('official.all_official', compact('officials'));
    }

    public function residents()
    {
        $residents = ResidentsInformation::all();
        return view('official.all_residence', compact('residents'));
    }

    public function documents()
    {
        $documents = OfficialDocumentRequest::with('user')->latest()->get();
        return view('official.documents', compact('documents'));
    }

    public function projects()
    {
        $projects = Project::latest()->get();
        return view('official.project', compact('projects'));
    }

    public function map()
    {
        return view('official.map');
    }

    public function inventory()
    {
        $inventory = Inventory::latest()->get();
        return view('official.inventory', compact('inventory'));
    }
}