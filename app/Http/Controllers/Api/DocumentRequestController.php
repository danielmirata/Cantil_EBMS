<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ResidentDocumentRequest;

class DocumentRequestController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Convert declaration to boolean before validation
            $request->merge([
                'declaration' => filter_var($request->input('declaration'), FILTER_VALIDATE_BOOLEAN)
            ]);

            $validated = $request->validate([
                'document_type' => 'required|string',
                'fullname' => 'required|string',
                'contact_number' => 'required|string',
                'email' => 'nullable|email',
                'purok' => 'required|string',
                'date_needed' => 'required|date',
                'purpose' => 'required|string',
                'notes' => 'nullable|string',
                'id_type' => 'required|string',
                'id_photo' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'declaration' => 'required|boolean',
            ]);

            // Handle file upload
            if ($request->hasFile('id_photo')) {
                $file = $request->file('id_photo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/id_photos'), $filename);
                $validated['id_photo'] = 'id_photos/' . $filename;
            }

            // Set user_id to the currently authenticated user
            $validated['user_id'] = auth()->id();

            $docRequest = ResidentDocumentRequest::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Resident document request submitted successfully.',
                'data' => $docRequest
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

    public function myRequests(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $requests = ResidentDocumentRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Fetched user document requests.',
            'data' => $requests
        ]);
    }
}