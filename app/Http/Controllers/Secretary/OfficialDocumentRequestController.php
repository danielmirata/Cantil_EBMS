<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\OfficialDocumentRequest;
use App\Models\ResidentDocumentRequest;
use App\Models\DocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OfficialDocumentRequestController extends Controller
{
    public function index()
    {
        $requests = OfficialDocumentRequest::with('user')
            ->latest()
            ->get()
            ->map(function ($request) {
                $request->date_needed = Carbon::parse($request->date_needed);
                return $request;
            });
            
        // Get resident document requests
        $residentRequests = ResidentDocumentRequest::latest()->get();
        
        // Get captain document requests
        $captainRequests = DocumentRequest::with('user')->latest()->get();
            
        // Get counts for the stats cards
        $stats = [
            'total_requests' => OfficialDocumentRequest::count() + 
                              ResidentDocumentRequest::count() + 
                              DocumentRequest::count(),
            'pending_requests' => OfficialDocumentRequest::where('status', 'Pending')->count() + 
                                ResidentDocumentRequest::where('status', 'pending')->count() +
                                DocumentRequest::where('status', 'Pending')->count(),
            'processing_requests' => OfficialDocumentRequest::where('status', 'Processing')->count() + 
                                   ResidentDocumentRequest::where('status', 'processing')->count() +
                                   DocumentRequest::where('status', 'Processing')->count(),
            'ready_requests' => OfficialDocumentRequest::where('status', 'Ready for Pickup')->count() + 
                              ResidentDocumentRequest::where('status', 'ready for pickup')->count() +
                              DocumentRequest::where('status', 'Ready for Pickup')->count(),
            'completed_requests' => OfficialDocumentRequest::where('status', 'Completed')->count() + 
                                  ResidentDocumentRequest::where('status', 'completed')->count() +
                                  DocumentRequest::where('status', 'Completed')->count(),
            'rejected_requests' => OfficialDocumentRequest::where('status', 'Rejected')->count() + 
                                 ResidentDocumentRequest::where('status', 'rejected')->count() +
                                 DocumentRequest::where('status', 'Rejected')->count(),
        ];

        return view('secretary.sec_documents', compact('requests', 'residentRequests', 'captainRequests', 'stats'));
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
            $validated['status'] = 'Pending';
            $validated['date_needed'] = Carbon::parse($validated['date_needed']);
            
            OfficialDocumentRequest::create($validated);

            return redirect()->route('secretary.documents.index')
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
                'request_id' => 'required|exists:official_document_requests,id',
                'status' => 'required|in:Pending,Processing,Ready for Pickup,Completed,Rejected',
                'remarks' => 'nullable|string'
            ]);

            $documentRequest = OfficialDocumentRequest::findOrFail($validated['request_id']);
            
            $documentRequest->update([
                'status' => $validated['status'],
                'remarks' => $validated['remarks']
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'Document request updated successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Document request update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update document request. Please try again.'
            ], 500);
        }
    }
} 