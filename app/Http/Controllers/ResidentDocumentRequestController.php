<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResidentDocumentRequest;
use Illuminate\Support\Facades\Auth;

class ResidentDocumentRequestController extends Controller
{
    // Show the request form
    public function create()
    {
        return view('resident.requestdocs');
    }

    // Handle form submission
    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_type' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:255',
            'date_needed' => 'required|date',
            'purpose' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'id_type' => 'required|string|max:255',
            'id_photo' => 'required|image|max:4096',
            'declaration' => 'accepted',
        ]);

        // Convert declaration to boolean
        $validated['declaration'] = $request->has('declaration') ? 1 : 0;

        // Handle file upload
        if ($request->hasFile('id_photo')) {
            $file = $request->file('id_photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $validated['id_photo'] = $file->storeAs('id_photos', $filename, 'public');
        }
        

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'Pending'; // Set initial status
        ResidentDocumentRequest::create($validated);

        return redirect()->route('resident.requestdocs')->with('success', 'Request submitted successfully!');
    }

    // Get all resident document requests for secretary view
    public function index()
    {
        return view('resident.requestdocs');
    }

    // Update resident document request status
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:Pending,Processing,Ready for Pickup,Completed,Rejected',
            'remarks' => 'nullable|string|max:1000'
        ]);

        $documentRequest = ResidentDocumentRequest::findOrFail($id);
        $documentRequest->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Request status updated successfully'
        ]);
    }

    // Get single resident document request details
    public function show($id)
    {
        $request = ResidentDocumentRequest::with('user')->findOrFail($id);
        return response()->json($request);
    }
} 