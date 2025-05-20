<?php

namespace App\Http\Controllers;

use App\Models\Official;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OfficialController extends Controller
{
    public function index()
    {
        $officials = Official::with('position')->active()->get();
        return view('secretary.Official.all_official', compact('officials'));
    }

    public function create()
    {
        $positions = Position::all();
        return view('secretary.Official.new_official', compact('positions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'position_id' => 'required|exists:positions,id',
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
            'guardian_name' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:255',
            'guardian_relation' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|max:2048',
            'term_start' => 'required|date',
            'term_end' => 'required|date|after:term_start',
            'status' => 'required|in:Active,Inactive'
        ]);

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $validated['profile_picture'] = $path;
        }

        Official::create($validated);

        return redirect()->route('officials.index')
            ->with('success', 'Official added successfully.');
    }

    public function archive(Official $official)
    {
        $official->update(['archived_at' => now()]);
        return redirect()->route('officials.index')
            ->with('success', 'Official archived successfully.');
    }

    public function restore(Official $official)
    {
        $official->update(['archived_at' => null]);
        return redirect()->route('officials.index')
            ->with('success', 'Official restored successfully.');
    }

    public function archived()
    {
        $archived_officials = Official::with('position')->archived()->get();
        return view('secretary.Official.archive_official', compact('archived_officials'));
    }

    public function updateProfilePicture(Request $request, Official $official)
    {
        $request->validate([
            'profile_picture' => 'required|image|max:2048'
        ]);

        if ($official->profile_picture) {
            Storage::disk('public')->delete($official->profile_picture);
        }

        $path = $request->file('profile_picture')->store('profile_pictures', 'public');
        $official->update(['profile_picture' => $path]);

        return redirect()->back()->with('success', 'Profile picture updated successfully.');
    }
} 