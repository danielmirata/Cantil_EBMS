<?php

namespace App\Http\Controllers\Captain;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResidentController extends Controller
{
    public function index()
    {
        $residents = Resident::whereNull('archived_at')->get();
        return view('captain.all_residence', compact('residents'));
    }

    public function create()
    {
        return view('captain.residents.create');
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
            'residency_status' => 'required|in:Resident,Non-Resident',
            'voter_status' => 'required|in:Registered,Not Registered',
            'single_parent' => 'required|boolean',
            'pwd' => 'required|boolean',
            'senior_citizen' => 'required|boolean',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('profile_picture')) {
            $filename = time() . '_' . $request->file('profile_picture')->getClientOriginalName();
            $request->file('profile_picture')->storeAs('public/profile_pictures', $filename);
            $validated['profile_picture'] = $filename;
        }

        Resident::create($validated);

        return redirect()->route('captain.residents.index')
            ->with('success', 'Resident created successfully.');
    }

    public function archived()
    {
        $residents = Resident::whereNotNull('archived_at')->get();
        return view('captain.residents.archived', compact('residents'));
    }

    public function show(Resident $resident)
    {
        return view('captain.residents.show', compact('resident'));
    }

    public function edit(Resident $resident)
    {
        return view('captain.residents.edit', compact('resident'));
    }

    public function update(Request $request, Resident $resident)
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
            'residency_status' => 'required|in:Resident,Non-Resident',
            'voter_status' => 'required|in:Registered,Not Registered',
            'single_parent' => 'required|boolean',
            'pwd' => 'required|boolean',
            'senior_citizen' => 'required|boolean',
        ]);

        $resident->update($validated);

        return redirect()->route('captain.residents.index')
            ->with('success', 'Resident information updated successfully.');
    }

    public function updatePhoto(Request $request, Resident $resident)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($resident->profile_picture) {
            Storage::delete('public/profile_pictures/' . $resident->profile_picture);
        }

        $filename = time() . '_' . $request->file('profile_picture')->getClientOriginalName();
        $request->file('profile_picture')->storeAs('public/profile_pictures', $filename);

        $resident->update(['profile_picture' => $filename]);

        return redirect()->back()->with('success', 'Profile picture updated successfully.');
    }

    public function archive(Resident $resident)
    {
        $resident->update(['archived_at' => now()]);
        return redirect()->route('captain.residents.index')
            ->with('success', 'Resident archived successfully.');
    }

    public function restore(Resident $resident)
    {
        $resident->update(['archived_at' => null]);
        return redirect()->route('captain.residents.archived')
            ->with('success', 'Resident restored successfully.');
    }
} 