<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Record;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RecordController extends Controller
{
    public function index()
    {
        $records = Record::latest()->paginate(10);
        
        $stats = [
            'total_requests' => Record::count(),
            'pending_requests' => Record::where('status', 'pending')->count(),
            'completed_requests' => Record::where('status', 'completed')->count(),
            'today_requests' => Record::whereDate('created_at', Carbon::today())->count(),
        ];

        return view('secretary.records.index', compact('records', 'stats'));
    }

    public function create()
    {
        return view('secretary.records.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_name' => 'required|string|max:255',
            'request_type' => 'required|string|in:clearance,certification,other',
            'purpose' => 'required|string',
            'status' => 'required|string|in:pending,processing,completed,cancelled',
            'remarks' => 'nullable|string',
        ]);

        $user = Auth::user();
        $validated['processed_by'] = $user ? ($user->name ?? $user->fullname ?? $user->username ?? 'System') : 'System';
        
        \Log::info('DEBUG processed_by value', ['processed_by' => $validated['processed_by'], 'user' => $user]);

        Record::create($validated);

        return redirect()->route('secretary.records.index')
            ->with('success', 'Record created successfully.');
    }

    public function show(Record $record)
    {
        return view('secretary.records.show', compact('record'));
    }

    public function edit(Record $record)
    {
        return view('secretary.records.edit', compact('record'));
    }

    public function update(Request $request, Record $record)
    {
        $validated = $request->validate([
            'resident_name' => 'required|string|max:255',
            'request_type' => 'required|string|in:clearance,certification,other',
            'purpose' => 'required|string',
            'status' => 'required|string|in:pending,processing,completed,cancelled',
            'remarks' => 'nullable|string',
        ]);

        $record->update($validated);

        return redirect()->route('secretary.records.index')
            ->with('success', 'Record updated successfully.');
    }

    public function destroy(Record $record)
    {
        $record->delete();

        return redirect()->route('secretary.records.index')
            ->with('success', 'Record deleted successfully.');
    }

    public function export()
    {
        $records = Record::all();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="walk-in-records.csv"',
        ];

        $callback = function() use ($records) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, ['Date & Time', 'Request Type', 'Resident Name', 'Purpose', 'Status', 'Processed By', 'Remarks']);
            
            // Add data
            foreach ($records as $record) {
                fputcsv($file, [
                    $record->created_at->format('M d, Y h:i A'),
                    $record->request_type,
                    $record->resident_name,
                    $record->purpose,
                    $record->status,
                    $record->processed_by,
                    $record->remarks
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 