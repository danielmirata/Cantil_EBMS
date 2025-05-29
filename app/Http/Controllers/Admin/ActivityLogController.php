<?php

namespace App\Http\Controllers\Admin;

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
        
        return view('admin.activity_logs', compact('activities', 'stats'));
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
                'Description',
                'Resident Name',
                'Status',
                'Processed By',
                'IP Address',
                'User Agent',
                'Additional Data'
            ]);

            // Add data
            foreach ($activities as $activity) {
                fputcsv($file, [
                    $activity->created_at->format('Y-m-d H:i:s'),
                    $activity->transaction_type,
                    $activity->description,
                    $activity->resident_name,
                    $activity->status,
                    $activity->processed_by,
                    $activity->ip_address,
                    $activity->user_agent,
                    json_encode($activity->additional_data)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function notifications()
    {
        $notifications = ActivityLog::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        return response()->json($notifications);
    }
} 