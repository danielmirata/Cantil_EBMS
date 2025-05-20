<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Complaint;

class ComplaintController extends Controller
{
    // Show the complaint form
    public function create()
    {
        return view('resident.complain');
    }

    // Handle form submission
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'complete_address' => 'required|string|max:255',
            'complaint_type' => 'required|string|max:255',
            'incident_date' => 'required|date',
            'incident_time' => 'required',
            'incident_location' => 'required|string|max:255',
            'complaint_description' => 'required|string',
            'evidence_photo' => 'nullable|image|max:4096',
            'declaration' => 'accepted',
        ]);

        // Handle file upload
        if ($request->hasFile('evidence_photo')) {
            $file = $request->file('evidence_photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $validated['evidence_photo'] = $file->storeAs('complaint_evidence', $filename, 'public');
        }

        $validated['declaration'] = $request->has('declaration') ? 1 : 0;

        Complaint::create($validated);

        return redirect()->route('resident.complain')->with('success', 'Complaint submitted successfully!');
    }
} 