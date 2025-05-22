<?php

namespace App\Http\Controllers;

use App\Models\MapLocation;
use App\Models\Household;
use Illuminate\Http\Request;

class AdminMapController extends Controller
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
} 