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

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'complete_address' => 'required|string|max:500',
            'complaint_type' => 'required|string|max:255',
            'incident_date' => 'required|date',
            'incident_time' => 'required',
            'incident_location' => 'required|string|max:255',
            'complaint_description' => 'required|string',
            'evidence_photo' => 'nullable|image|max:2048',
            'declaration' => 'required|accepted'
        ]);

        try {
            $data = $request->all();
            $data['status'] = 'Pending';
            
            // Convert declaration checkbox value to integer
            $data['declaration'] = $request->has('declaration') ? 1 : 0;
            
            // Handle file upload if present
            if ($request->hasFile('evidence_photo')) {
                $path = $request->file('evidence_photo')->store('complaints/evidence', 'public');
                $data['evidence_photo'] = $path;
            }

            $complaint = Complaint::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Complaint submitted successfully',
                'complaint' => $complaint
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error submitting complaint: ' . $e->getMessage()
            ], 500);
        }
    }
} 