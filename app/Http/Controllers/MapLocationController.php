<?php

namespace App\Http\Controllers;

use App\Models\MapLocation;
use App\Models\Household;
use App\Models\ResidentsInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MapLocationController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'type' => 'required|in:rectangle,polygon,marker,purok',
                'coordinates' => 'required|string',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'household_id' => 'nullable|exists:households,id',
                'color' => 'nullable|string|max:50'
            ]);

            // Verify coordinates can be decoded
            $coordinates = json_decode($validated['coordinates'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid coordinates format');
            }

            // Set null for empty household_id
            if (empty($validated['household_id'])) {
                $validated['household_id'] = null;
            }

            // For purok markers, ensure the title starts with "Purok "
            if ($validated['type'] === 'purok' && !str_starts_with($validated['title'], 'Purok ')) {
                $validated['title'] = 'Purok ' . $validated['title'];
            }

            $location = MapLocation::create($validated);
            
            // Load the household relationship if it exists
            if ($location->household_id) {
                $location->load('household');
            }
            
            return response()->json($location);
        } catch (\Exception $e) {
            Log::error('Error saving map location: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to save location: ' . $e->getMessage()], 500);
        }
    }

    public function index()
    {
        try {
            $locations = MapLocation::with('household')->get();
            return response()->json($locations);
        } catch (\Exception $e) {
            Log::error('Error fetching map locations: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch locations'], 500);
        }
    }

    public function getHouseholds()
    {
        try {
            $households = Household::select(
                'id',
                'name',
                'house_number',
                'street'
            )
            ->orderBy('name')
            ->get();

            return response()->json($households);
        } catch (\Exception $e) {
            Log::error('Error fetching households: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch households: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $location = MapLocation::findOrFail($id);
            $location->delete();
            return response()->json(['message' => 'Location deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting map location: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete location'], 500);
        }
    }

    public function getPurokStats($purok)
    {
        try {
            $now = Carbon::now();
            $residents = ResidentsInformation::where('street', 'LIKE', "%{$purok}%")->get();

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
            Log::error('Error fetching purok statistics: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch purok statistics: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'type' => 'required|in:rectangle,polygon,marker,purok',
                'coordinates' => 'required|string',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'household_id' => 'nullable|exists:households,id',
                'color' => 'nullable|string|max:50'
            ]);

            // Set null for empty household_id
            if (empty($validated['household_id'])) {
                $validated['household_id'] = null;
            }

            $location = MapLocation::findOrFail($id);
            $location->update($validated);

            // Load the household relationship if it exists
            if ($location->household_id) {
                $location->load('household');
            }

            return response()->json($location);
        } catch (\Exception $e) {
            \Log::error('Error updating map location: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update location: ' . $e->getMessage()], 500);
        }
    }
} 