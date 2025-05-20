<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    public function index()
    {
        $activityLogs = ActivityLog::latest()->get();
        return view('secretary.activity_logs', compact('activityLogs'));
    }

    public function create()
    {
        return view('secretary.activity_logs_create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_type' => 'required|string',
            'resident_name' => 'required|string',
            'description' => 'required|string',
            'status' => 'required|in:pending,completed,cancelled',
            'processed_by' => 'required|string',
        ]);

        ActivityLog::create($validated);

        return redirect()->route('secretary.activity-logs')
            ->with('success', 'Activity log created successfully.');
    }
}
