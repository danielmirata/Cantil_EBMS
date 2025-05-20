<?php

namespace App\Http\Controllers\Captain;

use App\Http\Controllers\Controller;
use App\Models\Official;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OfficialController extends Controller
{
    public function index()
    {
        $officials = Official::with('position')->whereNull('archived_at')->get();
        return view('captain.all_official', compact('officials'));
    }

    public function create()
    {
        $positions = Position::all();
        return view('captain.officials.create', compact('positions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'date_of_birth' => 'required|date',
            'place_of_birth' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'civil_status' => 'required|in:Single,Married,Widowed,Divorced',
            'nationality' => 'required|string|max:255',
            'religion' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact_number' => 'required|string|max:255',
            'house_number' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'municipality' => 'required|string|max:255',
            'zip' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'guardian_name' => 'required|string|max:255',
            'guardian_contact' => 'required|string|max:255',
            'guardian_relation' => 'required|string|max:255',
            'position_id' => 'required|exists:positions,id',
            'term_start' => 'required|date',
            'term_end' => 'required|date|after:term_start',
            'status' => 'required|in:Active,Inactive',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('profile_picture')) {
            $filename = time() . '_' . $request->file('profile_picture')->getClientOriginalName();
            $request->file('profile_picture')->storeAs('public/profile_pictures', $filename);
            $validated['profile_picture'] = $filename;
        }

        Official::create($validated);

        return redirect()->route('captain.officials.index')
            ->with('success', 'Official created successfully.');
    }

    public function archived()
    {
        $officials = Official::with('position')->whereNotNull('archived_at')->get();
        return view('captain.officials.archived', compact('officials'));
    }

    public function show(Official $official)
    {
        return view('captain.officials.show', compact('official'));
    }

    public function edit(Official $official)
    {
        return view('captain.officials.edit', compact('official'));
    }

    public function update(Request $request, Official $official)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'date_of_birth' => 'required|date',
            'place_of_birth' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'civil_status' => 'required|in:Single,Married,Widowed,Divorced',
            'nationality' => 'required|string|max:255',
            'religion' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact_number' => 'required|string|max:255',
            'house_number' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'municipality' => 'required|string|max:255',
            'zip' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'guardian_name' => 'required|string|max:255',
            'guardian_contact' => 'required|string|max:255',
            'guardian_relation' => 'required|string|max:255',
            'term_start' => 'required|date',
            'term_end' => 'required|date|after:term_start',
            'status' => 'required|in:Active,Inactive',
        ]);

        $official->update($validated);

        return redirect()->route('captain.officials.index')
            ->with('success', 'Official information updated successfully.');
    }

    public function updatePhoto(Request $request, Official $official)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($official->profile_picture) {
            Storage::delete('public/profile_pictures/' . $official->profile_picture);
        }

        $filename = time() . '_' . $request->file('profile_picture')->getClientOriginalName();
        $request->file('profile_picture')->storeAs('public/profile_pictures', $filename);

        $official->update(['profile_picture' => $filename]);

        return redirect()->back()->with('success', 'Profile picture updated successfully.');
    }

    public function archive(Official $official)
    {
        $official->update(['archived_at' => now()]);
        return redirect()->route('captain.officials.index')
            ->with('success', 'Official archived successfully.');
    }

    public function restore(Official $official)
    {
        $official->update(['archived_at' => null]);
        return redirect()->route('captain.officials.archived')
            ->with('success', 'Official restored successfully.');
    }
} 