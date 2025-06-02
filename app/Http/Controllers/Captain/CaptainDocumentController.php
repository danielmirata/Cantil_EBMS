<?php

namespace App\Http\Controllers\Captain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DocumentRequest;
use Illuminate\Support\Facades\Auth;

class CaptainDocumentController extends Controller
{
    public function index()
    {
        $requests = DocumentRequest::latest()->paginate(10);
        return view('captain.documents', compact('requests'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_type' => 'required|string',
            'purpose' => 'required|string',
            'additional_info' => 'nullable|string',
        ]);

        $documentRequest = DocumentRequest::create([
            'document_type' => $validated['document_type'],
            'purpose' => $validated['purpose'],
            'status' => 'Pending',
            'user_id' => Auth::id(),
            'remarks' => $validated['additional_info'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Document request submitted successfully.');
    }

    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'request_id' => 'required|exists:document_requests,id',
            'status' => 'required|in:Pending,Processing,Ready for Pickup,Completed,Rejected',
            'remarks' => 'nullable|string',
        ]);

        $documentRequest = DocumentRequest::findOrFail($validated['request_id']);
        $documentRequest->update([
            'status' => $validated['status'],
            'remarks' => $validated['remarks'],
        ]);

        return response()->json(['message' => 'Status updated successfully']);
    }

    public function printDocument($type, $id)
    {
        $documentRequest = DocumentRequest::findOrFail($id);
        
        // Map document types to their corresponding views
        $viewMap = [
            'Barangay Clearance' => 'documents.print.clearance',
            'Certificate of Residency' => 'documents.print.residency',
            'Certificate of Indigency' => 'documents.print.certification',
            'Barangay Business Permit' => 'documents.print.business-permit',
            'Certificate of Good Moral' => 'documents.print.good-moral',
        ];

        if (!isset($viewMap[$documentRequest->document_type])) {
            abort(404, 'Document type not found');
        }

        return view($viewMap[$documentRequest->document_type], compact('documentRequest'));
    }
} 