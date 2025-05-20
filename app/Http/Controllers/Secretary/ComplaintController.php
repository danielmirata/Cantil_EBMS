<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use Carbon\Carbon;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::latest()->get();
        
        $stats = [
            'total_complaints' => Complaint::count(),
            'pending_complaints' => Complaint::where('status', 'Pending')->count(),
            'resolved_complaints' => Complaint::where('status', 'Resolved')->count(),
            'rejected_complaints' => Complaint::where('status', 'Rejected')->count(),
        ];

        return view('secretary.sec_complain', compact('complaints', 'stats'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'complaint_id' => 'required|exists:complaints,id',
            'status' => 'required|in:Pending,Under Investigation,Resolved,Rejected,Transferred to Blotter',
            'remarks' => 'nullable|string|max:500'
        ]);

        try {
            $complaint = Complaint::findOrFail($request->complaint_id);
            $complaint->status = $request->status;
            $complaint->remarks = $request->remarks;
            $complaint->save();

            return response()->json([
                'success' => true,
                'message' => 'Complaint status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating complaint status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $complaint = Complaint::findOrFail($id);
        return response()->json($complaint);
    }
} 