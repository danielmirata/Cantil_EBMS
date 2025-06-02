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
            ], [
                'document_type.required' => 'Please select a document type.',
                'purpose.required' => 'Purpose is required.',
                'purpose.min' => 'Purpose must be at least 5 characters.',
                'date_needed.required' => 'Date needed is required.',
                'date_needed.after' => 'Date needed must be a future date.',
            ]);            // Set the user ID and default status
            $validated['user_id'] = auth()->id();
            $validated['status'] = 'Pending';
            
            Log::info('Creating document request with data:', $validated);
            
            $document = OfficialDocumentRequest::create($validated);
            
            Log::info('Document request created successfully:', [
                'id' => $document->id,
                'request_id' => $document->request_id,
                'document_type' => $document->document_type,
                'user_id' => $document->user_id
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Document request submitted successfully.',
                    'data' => $document
                ], 201);
            }

            return redirect()->back()
                ->with('success', 'Document request submitted successfully. Your request ID is: ' . $document->request_id);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Document request validation failed', [
                'user_id' => auth()->id(),
                'errors' => $e->errors()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($e)
                ->withInput();
            
        } catch (\Exception $e) {
            Log::error('Document request creation failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'request_data' => $request->except(['_token']),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to submit document request. Please try again.'
                ], 500);
            }
            
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