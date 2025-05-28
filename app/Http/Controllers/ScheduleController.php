<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the schedules.
     */
    public function index(Request $request)
    {
        $query = Schedule::latest();
        $search = $request->input('search');
        $dateSearch = null;
        if ($search) {
            // Try to parse the search as a date in human format
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
                  ->orWhere('time', 'like', "%$search%")
                ;
                if ($dateSearch) {
                    $q->orWhere('date', $dateSearch);
                } else {
                    $q->orWhere('date', 'like', "%$search%") ;
                }
            });
        }
        $schedules = $query->paginate(5)->appends(['search' => $search]);
        return view('secretary.Schedule.schedule', compact('schedules', 'search'));
    }

    /**
     * Store a newly created schedule in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'required',
            'venue' => 'required|string|max:255'
        ]);

        Schedule::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Schedule created successfully.'
        ]);
    }

    /**
     * Update the specified schedule in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'required',
            'venue' => 'required|string|max:255'
        ]);

        $schedule->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Schedule updated successfully.'
        ]);
    }

    /**
     * Remove the specified schedule from storage.
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Schedule deleted successfully.'
        ]);
    }
}
