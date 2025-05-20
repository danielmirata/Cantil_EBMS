<?php

namespace App\Http\Controllers;

use App\Models\OfficialDocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OfficialDocumentRequestController extends Controller
{
    public function index()
    {
        $requests = OfficialDocumentRequest::with('user')->latest()->get();
        return view('captain.documents', compact('requests'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'document_type' => 'required|string|max:255',
                'purpose' => 'required|string',
                'additional_info' => 'nullable|string',
                'date_needed' => 'required|date|after:today'
            ]);

            $validated['user_id'] = auth()->id();
            
            OfficialDocumentRequest::create($validated);

            return redirect()->route('documents.index')
                ->with('success', 'Document request submitted successfully.');
        } catch (\Exception $e) {
            Log::error('Document request creation failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to submit document request. Please try again.')
                ->withInput();
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            Log::info('Update request data:', $request->all());

            $validated = $request->validate([
                'request_id' => 'required|exists:official_document_requests,id',
                'document_type' => 'required|string|max:255',
                'purpose' => 'required|string',
                'date_needed' => 'required|date',
                'additional_info' => 'nullable|string',
                'status' => 'required|in:Pending,Processing,Ready for Pickup,Completed,Rejected',
                'remarks' => 'nullable|string'
            ]);

            Log::info('Validated data:', $validated);

            $documentRequest = OfficialDocumentRequest::findOrFail($validated['request_id']);
            
            $updateData = [
                'document_type' => $validated['document_type'],
                'purpose' => $validated['purpose'],
                'date_needed' => $validated['date_needed'],
                'additional_info' => $validated['additional_info'],
                'status' => $validated['status'],
                'remarks' => $validated['remarks']
            ];

            Log::info('Update data:', $updateData);

            $documentRequest->fill($updateData);
            $saved = $documentRequest->save();

            if (!$saved) {
                Log::error('Update failed - save returned false');
                return response()->json(['error' => 'Failed to update document request'], 500);
            }

            return response()->json(['success' => true, 'message' => 'Document request updated successfully']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', ['errors' => $e->errors()]);
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Document request update failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Failed to update document request. Please try again.'], 500);
        }
    }
} 