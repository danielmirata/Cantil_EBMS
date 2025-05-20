<?php

namespace App\Http\Controllers;

use App\Models\Blotter;
use App\Models\Complaint;
use Illuminate\Http\Request;

class BlotterController extends Controller
{
    public function index()
    {
        $blotters = Blotter::latest()->get();
        $stats = [
            'total_blotters' => Blotter::count(),
            'pending_blotters' => Blotter::where('status', 'Pending')->count(),
            'resolved_blotters' => Blotter::where('status', 'Resolved')->count(),
            'rejected_blotters' => Blotter::where('status', 'Rejected')->count(),
        ];
        
        return view('secretary.blotter', compact('blotters', 'stats'));
    }

    public function transferFromComplaint(Request $request)
    {
        $complaint = Complaint::findOrFail($request->complaint_id);
        
        $blotter = Blotter::create([
            // Complainant Details (from complaint)
            'first_name' => $complaint->first_name,
            'last_name' => $complaint->last_name,
            'age' => $request->age ?? 0, // Default age if not provided
            'sex' => $request->sex ?? 'Male', // Default sex if not provided
            'civil_status' => $request->civil_status ?? 'Single', // Default civil status if not provided
            'complete_address' => $complaint->complete_address,
            'contact_number' => $complaint->contact_number,
            'email' => $complaint->email,
            'occupation' => $request->occupation ?? 'Not Specified', // Default occupation if not provided
            'relationship_to_respondent' => $request->relationship_to_respondent,
            'complaint_type' => $complaint->complaint_type,
            'incident_date' => $complaint->incident_date,
            'incident_time' => $complaint->incident_time,
            'incident_location' => $complaint->incident_location,
            'complaint_description' => $complaint->complaint_description,
            'evidence_photo' => $complaint->evidence_photo,
            'declaration' => $complaint->declaration,

            // Respondent Details (from form)
            'respondent_first_name' => $request->respondent_first_name,
            'respondent_last_name' => $request->respondent_last_name,
            'respondent_age' => $request->respondent_age,
            'respondent_sex' => $request->respondent_sex,
            'respondent_civil_status' => $request->respondent_civil_status,
            'respondent_address' => $request->respondent_address,
            'respondent_contact_number' => $request->respondent_contact_number,
            'respondent_occupation' => $request->respondent_occupation,

            // Additional Details (from form)
            'what_happened' => $request->what_happened,
            'who_was_involved' => $request->who_was_involved,
            'how_it_happened' => $request->how_it_happened,

            // Witness Details (from form)
            'witness_name' => $request->witness_name,
            'witness_address' => $request->witness_address,
            'witness_contact' => $request->witness_contact,

            // Action Details (from form)
            'initial_action_taken' => $request->initial_action_taken,
            'handling_officer_name' => $request->handling_officer_name,
            'handling_officer_position' => $request->handling_officer_position,
            'mediation_date' => $request->mediation_date,
            'action_result' => $request->action_result,
            'remarks' => $request->remarks,

            // Status
            'status' => 'Pending'
        ]);

        // Update complaint status to indicate it's been transferred
        $complaint->update(['status' => 'Transferred to Blotter']);

        return response()->json([
            'success' => true,
            'message' => 'Complaint successfully transferred to blotter'
        ]);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'blotter_id' => 'required|exists:blotters,id',
            'status' => 'required|in:Pending,Under Investigation,Resolved,Rejected,Transferred to Blotter',
            'remarks' => 'nullable|string'
        ]);

        $blotter = Blotter::findOrFail($request->blotter_id);
        
        $blotter->update([
            'status' => $request->status,
            'remarks' => $request->remarks
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Blotter status updated successfully'
        ]);
    }

    public function create()
    {
        return view('secretary.blotter.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'age' => 'required|integer|min:1',
            'sex' => 'required|string|in:Male,Female',
            'civil_status' => 'required|string|in:Single,Married,Widowed,Separated',
            'complete_address' => 'required|string',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'occupation' => 'required|string|max:255',
            'relationship_to_respondent' => 'nullable|string|max:255',
            'respondent_first_name' => 'required|string|max:255',
            'respondent_last_name' => 'required|string|max:255',
            'respondent_age' => 'required|integer|min:1',
            'respondent_sex' => 'required|string|in:Male,Female',
            'respondent_civil_status' => 'required|string|in:Single,Married,Widowed,Separated',
            'respondent_address' => 'required|string',
            'respondent_contact_number' => 'required|string|max:20',
            'respondent_occupation' => 'required|string|max:255',
            'complaint_type' => 'required|string|max:255',
            'incident_date' => 'required|date',
            'incident_time' => 'required',
            'incident_location' => 'required|string',
            'complaint_description' => 'required|string',
            'what_happened' => 'required|string',
            'who_was_involved' => 'required|string',
            'how_it_happened' => 'required|string',
            'witness_name' => 'nullable|string|max:255',
            'witness_address' => 'nullable|string',
            'witness_contact' => 'nullable|string|max:20',
            'initial_action_taken' => 'required|string',
            'handling_officer_name' => 'required|string|max:255',
            'handling_officer_position' => 'required|string|max:255',
            'mediation_date' => 'nullable|date',
            'action_result' => 'required|string',
            'remarks' => 'nullable|string',
            'evidence_photo' => 'nullable|image|max:2048',
            'declaration' => 'required|boolean'
        ]);

        if ($request->hasFile('evidence_photo')) {
            $path = $request->file('evidence_photo')->store('evidence_photos', 'public');
            $validated['evidence_photo'] = $path;
        }

        $validated['status'] = 'Pending';

        $blotter = Blotter::create($validated);

        return redirect()->route('secretary.blotter')
            ->with('success', 'Blotter record created successfully.');
    }
} 