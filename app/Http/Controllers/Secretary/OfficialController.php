<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Official;
use App\Models\Position;

class OfficialController extends Controller
{
    public function index()
    {
        $officials = Official::with('position')
            ->whereNull('archived_at')
            ->whereNull('deleted_at')
            ->get();
        $positions = Position::all();
        
        // Add debugging information
        \Log::info('Active officials count: ' . $officials->count());
        \Log::info('Positions count: ' . $positions->count());
        
        if ($officials->isEmpty()) {
            \Log::info('No active officials found in database');
        } else {
            foreach ($officials as $official) {
                \Log::info('Active Official: ' . $official->first_name . ' ' . $official->last_name);
                \Log::info('Position: ' . ($official->position ? $official->position->position_name : 'No position'));
            }
        }
        
        return view('secretary.Official.all_official', compact('officials', 'positions'));
    }

    public function create()
    {
        $positions = Position::all();
        return view('secretary.Official.new_official', compact('positions'));
    }

    public function store(Request $request)
    {
        try {
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
                'father_name' => 'nullable|string|max:255',
                'mother_name' => 'nullable|string|max:255',
                'guardian_name' => 'required|string|max:255',
                'guardian_contact' => 'required|string|max:20',
                'guardian_relation' => 'required|in:Parent,Spouse,Sibling,Relative,Friend',
                'position_id' => 'required|exists:positions,id',
                'term_start' => 'required|date',
                'term_end' => 'required|date|after:term_start',
                'status' => 'required|in:Active,Inactive',
                'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            DB::beginTransaction();

            try {
                if ($request->hasFile('profile_picture')) {
                    $profilePicture = $request->file('profile_picture');
                    $filename = time() . '_' . $profilePicture->getClientOriginalName();
                    $profilePicture->storeAs('private/profile_pictures', $filename);
                    $validated['profile_picture'] = $filename;
                }

                $official = Official::create($validated);
                
                DB::commit();
                
                return redirect()->route('officials.index')
                    ->with('success', 'Official has been added successfully.');
                    
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error creating official: ' . $e->getMessage());
                throw $e;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error storing official: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'There was an error adding the official. Please try again.')
                ->withInput();
        }
    }

    public function archived()
    {
        $archived_officials = Official::with('position')
            ->withTrashed()
            ->where(function($query) {
                $query->whereNotNull('archived_at')
                    ->orWhereNotNull('deleted_at');
            })
            ->get();
            
        // Add debugging information
        \Log::info('Archived officials count: ' . $archived_officials->count());
        foreach ($archived_officials as $official) {
            \Log::info('Archived Official: ' . $official->first_name . ' ' . $official->last_name);
            \Log::info('Archived at: ' . $official->archived_at);
            \Log::info('Deleted at: ' . $official->deleted_at);
        }
            
        return view('secretary.Official.archive_official', compact('archived_officials'));
    }

    public function archive(Official $official)
    {
        try {
            $official->update([
                'archived_at' => now(),
                'status' => 'Inactive'
            ]);
            
            return redirect()->route('officials.index')
                ->with('success', 'Official has been archived successfully.');
        } catch (\Exception $e) {
            Log::error('Error archiving official: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'There was an error archiving the official. Please try again.');
        }
    }

    public function restore($id)
    {
        try {
            $official = Official::withTrashed()->findOrFail($id);
            
            // Update the official's status
            $official->update([
                'archived_at' => null,
                'status' => 'Active'
            ]);
            
            // If the official was soft-deleted, restore it
            if ($official->trashed()) {
                $official->restore();
            }
            
            return redirect()->route('officials.archived')
                ->with('success', 'Official has been restored successfully.');
        } catch (\Exception $e) {
            Log::error('Error restoring official: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'There was an error restoring the official. Please try again.');
        }
    }

    public function updatePhoto(Request $request, Official $official)
    {
        try {
            $request->validate([
                'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            if ($request->hasFile('profile_picture')) {
                // Delete old profile picture if exists
                if ($official->profile_picture && Storage::exists('private/profile_pictures/' . $official->profile_picture)) {
                    Storage::delete('private/profile_pictures/' . $official->profile_picture);
                }

                // Store new profile picture
                $filename = time() . '_' . $request->file('profile_picture')->getClientOriginalName();
                $request->file('profile_picture')->storeAs('private/profile_pictures', $filename);

                // Update official record
                $official->update([
                    'profile_picture' => $filename
                ]);

                return redirect()->back()->with('success', 'Profile picture updated successfully.');
            }

            return redirect()->back()->with('error', 'No image file was uploaded.');
        } catch (\Exception $e) {
            Log::error('Error updating official photo: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an error updating the profile picture. Please try again.');
        }
    }

    public function updateInfo(Request $request, Official $official)
    {
        try {
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
                'father_name' => 'nullable|string|max:255',
                'mother_name' => 'nullable|string|max:255',
                'guardian_name' => 'required|string|max:255',
                'guardian_contact' => 'required|string|max:20',
                'guardian_relation' => 'required|in:Parent,Spouse,Sibling,Relative,Friend',
                'position_id' => 'required|exists:positions,id',
                'term_start' => 'required|date',
                'term_end' => 'required|date|after:term_start',
                'status' => 'required|in:Active,Inactive'
            ]);

            DB::beginTransaction();

            try {
                $official->update($validated);
                DB::commit();
                return redirect()->back()->with('success', 'Official information updated successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error updating official information: ' . $e->getMessage());
                return redirect()->back()->with('error', 'There was an error updating the official information. Please try again.');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error in updateInfo: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An unexpected error occurred. Please try again.');
        }
    }

    public function show($id)
    {
        try {
            $official = Official::with('position')->findOrFail($id);
            return view('secretary.Official.partials.official_details', compact('official'));
        } catch (\Exception $e) {
            \Log::error('Error showing official: ' . $e->getMessage());
            return response()->json(['error' => 'There was an error retrieving the official information.'], 500);
        }
    }
} 