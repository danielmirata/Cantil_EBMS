<?php

namespace App\Http\Controllers\Captain;

use App\Http\Controllers\Controller;
use App\Models\MapLocation;
use App\Models\Household;
use App\Models\ResidentsInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MapController extends Controller
{
    public function index()
    {
        return view('captain.map');
    }

    public function getLocations()
    {
        $locations = MapLocation::with('household')->get();
        return response()->json($locations);
    }

    public function getHouseholds()
    {
        $households = Household::select('id', 'name', 'house_number', 'street')
            ->orderBy('house_number')
            ->get();
        return response()->json($households);
    }

    public function getPurokStats($purokName)
    {
        try {
            $now = Carbon::now();
            $residents = ResidentsInformation::where('street', 'LIKE', "%{$purokName}%")
                ->orWhere('barangay', 'LIKE', "%{$purokName}%")
                ->get();

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

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $results = collect();

        // Search in map locations
        $locations = MapLocation::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->with('household')
            ->get()
            ->map(function($location) {
                return [
                    'type' => 'Location',
                    'title' => $location->title,
                    'description' => $location->description,
                    'coordinates' => $location->coordinates,
                    'color' => $location->color,
                    'household' => $location->household
                ];
            });

        // Search in households
        $households = Household::where('name', 'like', "%{$query}%")
            ->orWhere('house_number', 'like', "%{$query}%")
            ->orWhere('street', 'like', "%{$query}%")
            ->get()
            ->map(function($household) {
                return [
                    'type' => 'Household',
                    'id' => $household->id,
                    'name' => $household->name,
                    'description' => "{$household->house_number}, {$household->street}",
                ];
            });

        // Search in residents
        $residents = ResidentsInformation::where('first_name', 'like', "%{$query}%")
            ->orWhere('middle_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->get()
            ->map(function($resident) {
                return [
                    'type' => 'Resident',
                    'name' => "{$resident->first_name} {$resident->middle_name} {$resident->last_name}",
                    'description' => "{$resident->house_number}, {$resident->street}",
                ];
            });

        $results = $results->concat($locations)
            ->concat($households)
            ->concat($residents);

        return response()->json($results);
    }
} 