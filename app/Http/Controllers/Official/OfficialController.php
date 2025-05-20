<?php

namespace App\Http\Controllers\Official;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Official;
use App\Models\Position;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class OfficialController extends Controller
{
    public function index()
    {
        $officials = Official::with('position')->whereNull('archived_at')->get();
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
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'date_of_birth' => 'required|date',
            'place_of_birth' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'civil_status' => 'required|in:Single,Married,Widowed,Divorced',
            'nationality' => 'required|string|max:255',
            'religion' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact_number' => 'required|string|max:20',
            'house_number' => 'required|string|max:50',
            'street' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'municipality' => 'required|string|max:255',
            'zip' => 'required|string|max:10',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'guardian_name' => 'required|string|max:255',
            'guardian_contact' => 'required|string|max:20',
            'guardian_relation' => 'required|string|max:50',
            'position_id' => 'required|exists:positions,id',
            'term_start' => 'required|date',
            'term_end' => 'required|date|after:term_start',
            'status' => 'required|in:Active,Inactive',
            'profile_picture' => 'nullable|image|max:2048'
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('private/profile_pictures', $filename);
            $validated['profile_picture'] = $filename;
        }

        $official = Official::create($validated);

        return redirect()->route('officials.index')
            ->with('success', 'Official has been added successfully.');
    }

    public function archived()
    {
        $archived_officials = Official::with('position')
            ->whereNotNull('archived_at')
            ->get();
        return view('secretary.Official.archive_official', compact('archived_officials'));
    }

    public function archive(Official $official)
    {
        $official->update([
            'archived_at' => Carbon::now(),
            'status' => 'Inactive'
        ]);

        return redirect()->route('officials.index')
            ->with('success', 'Official has been archived successfully.');
    }

    public function restore(Official $official)
    {
        $official->update([
            'archived_at' => null,
            'status' => 'Active'
        ]);

        return redirect()->route('officials.archived')
            ->with('success', 'Official has been restored successfully.');
    }

    public function updateProfilePicture(Request $request, Official $official)
    {
        $request->validate([
            'profile_picture' => 'required|image|max:2048'
        ]);

        // Delete old profile picture if exists
        if ($official->profile_picture) {
            Storage::delete('private/profile_pictures/' . $official->profile_picture);
        }

        // Store new profile picture
        $file = $request->file('profile_picture');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('private/profile_pictures', $filename);

        $official->update(['profile_picture' => $filename]);

        return redirect()->back()
            ->with('success', 'Profile picture has been updated successfully.');
    }
} 