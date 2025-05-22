<?php

namespace App\Http\Controllers;

use App\Models\MapLocation;
use App\Models\Household;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
    public function index()
    {
        return view('admin.map');
    }

    public function getLocations()
    {
        $locations = MapLocation::with('household')->get();
        return response()->json($locations);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'coordinates' => 'required|string',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'household_id' => 'nullable|exists:households,id',
            'color' => 'nullable|string'
        ]);

        $location = MapLocation::create($validated);
        return response()->json($location);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'coordinates' => 'required|string',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'household_id' => 'nullable|exists:households,id',
            'color' => 'nullable|string'
        ]);

        $location = MapLocation::findOrFail($id);
        $location->update($validated);
        return response()->json($location);
    }

    public function destroy($id)
    {
        $location = MapLocation::findOrFail($id);
        $location->delete();
        return response()->json(['message' => 'Location deleted successfully']);
    }

    public function getHouseholds()
    {
        $households = Household::select('id', 'name', 'house_number', 'street')->get();
        return response()->json($households);
    }

    public function getPurokStats($purokName)
    {
        $stats = [
            'total_residents' => 0,
            'total_families' => 0,
            'area' => 0,
            'age_groups' => [
                'children' => 0,
                'adults' => 0,
                'senior' => 0
            ],
            'gender' => [
                'male' => 0,
                'female' => 0
            ],
            'pwd' => 0,
            'single_parent' => 0,
            'voters' => [
                'registered' => 0
            ],
            'civil_status' => [
                'single' => 0,
                'married' => 0,
                'widowed' => 0,
                'divorced' => 0
            ]
        ];

        // Get residents in the purok
        $residents = Resident::where('purok', $purokName)->get();

        foreach ($residents as $resident) {
            // Total residents
            $stats['total_residents']++;

            // Age groups
            $age = $this->calculateAge($resident->date_of_birth);
            if ($age < 18) {
                $stats['age_groups']['children']++;
            } elseif ($age >= 60) {
                $stats['age_groups']['senior']++;
            } else {
                $stats['age_groups']['adults']++;
            }

            // Gender
            $stats['gender'][strtolower($resident->gender)]++;

            // Special groups
            if ($resident->is_pwd) {
                $stats['pwd']++;
            }
            if ($resident->is_single_parent) {
                $stats['single_parent']++;
            }
            if ($resident->is_registered_voter) {
                $stats['voters']['registered']++;
            }

            // Civil status
            $stats['civil_status'][strtolower($resident->civil_status)]++;
        }

        // Get total families
        $stats['total_families'] = Household::where('purok', $purokName)->count();

        return response()->json($stats);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $results = [];

        // Search in map locations
        $locations = MapLocation::where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->with('household')
            ->get();

        foreach ($locations as $location) {
            $results[] = [
                'type' => 'Location',
                'title' => $location->title,
                'description' => $location->description,
                'coordinates' => $location->coordinates,
                'color' => $location->color,
                'household' => $location->household
            ];
        }

        // Search in households
        $households = Household::where('name', 'LIKE', "%{$query}%")
            ->orWhere('house_number', 'LIKE', "%{$query}%")
            ->orWhere('street', 'LIKE', "%{$query}%")
            ->get();

        foreach ($households as $household) {
            $results[] = [
                'type' => 'Household',
                'id' => $household->id,
                'name' => $household->name,
                'description' => "{$household->house_number}, {$household->street}",
                'household' => $household
            ];
        }

        return response()->json($results);
    }

    private function calculateAge($dateOfBirth)
    {
        return date_diff(date_create($dateOfBirth), date_create('today'))->y;
    }
} 