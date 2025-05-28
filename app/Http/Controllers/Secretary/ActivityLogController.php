<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    public function index()
    {
        $activities = ActivityLog::orderBy('created_at', 'desc')->paginate(10);
        
        // Calculate statistics
        $stats = [
            'total_activities' => ActivityLog::count(),
            'today_activities' => ActivityLog::whereDate('created_at', Carbon::today())->count(),
            'user_activities' => ActivityLog::where('transaction_type', 'user')->count(),
            'system_activities' => ActivityLog::where('transaction_type', 'system')->count()
        ];
        
        return view('secretary.activity_logs', compact('activities', 'stats'));
    }

    public function create()
    {
        return view('secretary.activity_logs_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaction_type' => 'required|string',
            'resident_name' => 'required|string',
            'description' => 'required|string',
            'status' => 'required|string',
            'processed_by' => 'required|string'
        ]);

        ActivityLog::create([
            'transaction_type' => $request->transaction_type,
            'resident_name' => $request->resident_name,
            'description' => $request->description,
            'status' => $request->status,
            'processed_by' => $request->processed_by
        ]);

        return redirect()->route('secretary.activity-logs')
            ->with('success', 'Activity log created successfully.');
    }

    public function export()
    {
        $activities = ActivityLog::orderBy('created_at', 'desc')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="activity_logs.csv"',
        ];

        $callback = function() use ($activities) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'Date & Time',
                'Transaction Type',
                'Resident Name',
                'Description',
                'Status',
                'Processed By'
            ]);

            // Add data
            foreach ($activities as $activity) {
                fputcsv($file, [
                    $activity->created_at->format('Y-m-d H:i:s'),
                    $activity->transaction_type,
                    $activity->resident_name,
                    $activity->description,
                    $activity->status,
                    $activity->processed_by
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function notifications()
    {
        $notifications = \App\Models\ActivityLog::orderBy('created_at', 'desc')->limit(10)->get();
        return response()->json($notifications);
    }
} 