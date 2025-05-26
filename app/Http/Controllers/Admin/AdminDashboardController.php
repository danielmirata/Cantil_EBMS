<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResidentsInformation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function getPurokStats()
    {
        try {
            $now = Carbon::now();
            $residents = ResidentsInformation::all();

            $stats = [
                'total_residents' => $residents->count(),
                'total_families' => $residents->groupBy('house_number')->count(),
                'area' => 1, // Default area in km², adjust as needed
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
                'civil_status' => [
                    'single' => $residents->where('civil_status', 'Single')->count(),
                    'married' => $residents->where('civil_status', 'Married')->count(),
                    'widowed' => $residents->where('civil_status', 'Widowed')->count(),
                    'divorced' => $residents->where('civil_status', 'Divorced')->count(),
                ],
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            \Log::error('Error fetching purok statistics: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch purok statistics: ' . $e->getMessage()], 500);
        }
    }
} 