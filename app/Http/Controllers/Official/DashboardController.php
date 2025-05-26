<?php

namespace App\Http\Controllers\Official;

use App\Http\Controllers\Controller;
use App\Models\ResidentsInformation;
use App\Models\Household;
use App\Models\Official;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
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
            'voters' => [
                'registered' => $residents->where('voters', 'Yes')->count(),
                'non_registered' => $residents->where('voters', 'No')->count(),
            ],
            'pwd' => $residents->where('pwd', 'Yes')->count(),
            'single_parent' => $residents->where('single_parent', 'Yes')->count(),
            'age_groups' => [
                'children' => $residents->filter(function($resident) use ($now) {
                    $age = Carbon::parse($resident->date_of_birth)->age;
                    return $age < 18;
                })->count(),
                'adults' => $residents->filter(function($resident) use ($now) {
                    $age = Carbon::parse($resident->date_of_birth)->age;
                    return $age >= 18 && $age < 60;
                })->count(),
                'senior' => $residents->filter(function($resident) use ($now) {
                    $age = Carbon::parse($resident->date_of_birth)->age;
                    return $age >= 60;
                })->count(),
            ],
        ];

        return view('official.dashboard', compact(
            'totalResidents',
            'totalHouseholds',
            'totalOfficials',
            'totalPuroks',
            'recentResidents',
            'recentOfficials',
            'demographics'
        ));
    }

    public function getStatistics()
    {
        $totalResidents = ResidentsInformation::count();
        $totalHouseholds = Household::count();
        $totalOfficials = Official::where('status', 'Active')->whereNull('archived_at')->count();
        $totalPuroks = ResidentsInformation::distinct('street')->count('street');

        return response()->json([
            'totalResidents' => $totalResidents,
            'totalHouseholds' => $totalHouseholds,
            'totalOfficials' => $totalOfficials,
            'totalPuroks' => $totalPuroks
        ]);
    }

    public function getCharts()
    {
        $residents = ResidentsInformation::all();
        $now = Carbon::now();

        // Residency status distribution
        $residencyStatus = [
            'Permanent' => $residents->where('residency_status', 'Permanent')->count(),
            'Temporary' => $residents->where('residency_status', 'Temporary')->count(),
        ];

        // Purok distribution
        $purokDistribution = $residents->groupBy('street')
            ->map(function($group) {
                return $group->count();
            });

        return response()->json([
            'residencyStatus' => $residencyStatus,
            'purokDistribution' => $purokDistribution
        ]);
    }
} 