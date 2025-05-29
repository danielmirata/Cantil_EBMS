<?php

namespace App\Http\Controllers;

use App\Models\OfficialDocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OfficialDocumentRequestController extends Controller
{    public function index()
    {
        $documents = OfficialDocumentRequest::with('user')->latest()->get();
        return view('official.documents', compact('documents'));
    }public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'document_type' => 'required|string|max:255',
                'purpose' => 'required|string|min:5|max:500',
                'additional_info' => 'nullable|string|max:1000',
                'date_needed' => 'required|date|after:today',
            ]);            // Set the user ID and status
            $validated['user_id'] = auth()->id();
            $validated['status'] = 'Pending';
            
            Log::info('Creating document request with data:', $validated);
            
            $document = OfficialDocumentRequest::create($validated);
            
            Log::info('Document request created:', [
                'id' => $document->id,
                'request_id' => $document->request_id,
                'document_type' => $document->document_type
            ]);

            // Log successful creation
            Log::info('Document request created successfully', [
                'user_id' => auth()->id(),
                'document_type' => $validated['document_type']
            ]);

            return redirect()->back()
                ->with('success', 'Document request submitted successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Document request validation failed', [
                'errors' => $e->errors()
            ]);
            return redirect()->back()
                ->withErrors($e)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Document request creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
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