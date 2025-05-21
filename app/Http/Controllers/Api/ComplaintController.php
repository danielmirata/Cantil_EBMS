<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;

class ComplaintController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Convert declaration to boolean before validation
            $request->merge([
                'declaration' => filter_var($request->input('declaration'), FILTER_VALIDATE_BOOLEAN)
            ]);

            // Map 'address' to 'complete_address' if it exists
            if ($request->has('address')) {
                $request->merge(['complete_address' => $request->input('address')]);
            }

            $validated = $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'contact_number' => 'required|string',
                'email' => 'nullable|email',
                'complete_address' => 'required|string',
                'complaint_type' => 'required|string',
                'incident_date' => 'required|date',
                'incident_time' => 'required',
                'incident_location' => 'required|string',
                'complaint_description' => 'required|string',
                'evidence_photo' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'declaration' => 'required|boolean',
            ]);

            // Handle file upload
            if ($request->hasFile('evidence_photo')) {
                $file = $request->file('evidence_photo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/evidence_photos'), $filename);
                $validated['evidence_photo'] = 'evidence_photos/' . $filename;
            }

            // Set default status
            $validated['status'] = 'Pending';

            // Set user_id to the currently authenticated user
            $validated['user_id'] = auth()->id();

            $complaint = Complaint::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Complaint submitted successfully.',
                'data' => $complaint
            ])->header('Access-Control-Allow-Origin', '*')
              ->header('Access-Control-Allow-Methods', 'POST, OPTIONS')
              ->header('Access-Control-Allow-Headers', 'Content-Type, Accept');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422)->header('Access-Control-Allow-Origin', '*')
              ->header('Access-Control-Allow-Methods', 'POST, OPTIONS')
              ->header('Access-Control-Allow-Headers', 'Content-Type, Accept');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing your request',
                'error' => $e->getMessage()
            ], 500)->header('Access-Control-Allow-Origin', '*')
              ->header('Access-Control-Allow-Methods', 'POST, OPTIONS')
              ->header('Access-Control-Allow-Headers', 'Content-Type, Accept');
        }
    }

    public function track(Request $request)
    {
        try {
            $request->validate([
                'complaint_id' => 'required|string'
            ]);

            $complaint = Complaint::where('id', $request->complaint_id)->first();

            if (!$complaint) {
                return response()->json([
                    'success' => false,
                    'message' => 'Complaint not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'complaint' => [
                    'complaint_id' => $complaint->id,
                    'complaint_type' => $complaint->complaint_type,
                    'created_at' => $complaint->created_at,
                    'status' => $complaint->status,
                    'description' => $complaint->complaint_description,
                    'location' => $complaint->incident_location,
                    'has_evidence' => $complaint->evidence_photo ? '1' : '0',
                    'remarks' => $complaint->remarks,
                    'updated_at' => $complaint->updated_at
                ]
            ], 200, [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422, [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request',
                'error' => $e->getMessage()
            ], 500, [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]);
        }
    }

    public function index()
{
    try {
        \Log::info('Fetching complaints...');
        
        $complaints = Complaint::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')->get();
        \Log::info('Complaints fetched successfully', ['count' => $complaints->count()]);

        $formattedComplaints = $complaints->map(function ($complaint) {
            return [
                'complaint_id' => $complaint->id,
                'complaint_type' => $complaint->complaint_type,
                'created_at' => $complaint->created_at,
                'status' => $complaint->status,
                'description' => $complaint->complaint_description,
                'location' => $complaint->incident_location,
                'has_evidence' => $complaint->evidence_photo ? '1' : '0',
                'evidence_photo' => $complaint->evidence_photo, // Add this line
                'remarks' => $complaint->remarks,
                'updated_at' => $complaint->updated_at
            ];
        });

        \Log::info('Complaints formatted successfully');

        return response()->json([
            'success' => true,
            'complaints' => $formattedComplaints,
            'message' => $formattedComplaints->isEmpty() ? 'No complaints found' : 'Complaints retrieved successfully'
        ]);

    } catch (\Exception $e) {
        \Log::error('Error in ComplaintController@index', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching complaints',
            'error' => $e->getMessage()
        ], 500);
    }
}
} 