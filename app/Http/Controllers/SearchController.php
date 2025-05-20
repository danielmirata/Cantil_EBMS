<?php

namespace App\Http\Controllers;

use App\Models\Household;
use App\Models\MapLocation;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return response()->json([]);
        }

        // Search in households
        $households = Household::where('name', 'like', "%{$query}%")
            ->orWhere('house_number', 'like', "%{$query}%")
            ->orWhere('street', 'like', "%{$query}%")
            ->get()
            ->map(function ($household) {
                return [
                    'type' => 'Household',
                    'title' => $household->name,
                    'description' => "{$household->house_number}, {$household->street}",
                    'coordinates' => null, // Will be filled if location exists
                    'color' => null
                ];
            });

        // Search in map locations
        $locations = MapLocation::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->with('household')
            ->get()
            ->map(function ($location) {
                return [
                    'type' => 'Location',
                    'title' => $location->title,
                    'description' => $location->description,
                    'coordinates' => $location->coordinates,
                    'color' => $location->color,
                    'household' => $location->household ? [
                        'name' => $location->household->name,
                        'house_number' => $location->household->house_number,
                        'street' => $location->household->street
                    ] : null
                ];
            });

        // Combine and sort results
        $results = $households->concat($locations)->values()->all();

        return response()->json($results);
    }
} 