<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OfficialDocumentRequestController extends Controller
{
    public function index()
    {
        $requests = DocumentRequest::latest()->get();
        return view('captain.documents', compact('requests'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'requester_name' => 'required|string|max:255',
                'contact_number' => 'required|string|max:20',
                'address' => 'required|string',
                'document_type' => 'required|string|max:255',
                'purpose' => 'required|string',
                'additional_info' => 'nullable|string'
            ]);

            DocumentRequest::create($validated);

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
            $validated = $request->validate([
                'request_id' => 'required|exists:document_requests,id',
                'status' => 'required|in:Pending,Processing,Ready for Pickup,Completed,Rejected',
                'remarks' => 'nullable|string'
            ]);

            $documentRequest = DocumentRequest::findOrFail($validated['request_id']);
            $documentRequest->update([
                'status' => $validated['status'],
                'remarks' => $validated['remarks']
            ]);

            return redirect()->route('documents.index')
                ->with('success', 'Document request status updated successfully.');
        } catch (\Exception $e) {
            Log::error('Document request status update failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update document request status. Please try again.');
        }
    }
} 