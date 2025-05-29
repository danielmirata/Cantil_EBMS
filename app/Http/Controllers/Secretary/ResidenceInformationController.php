<?php

namespace App\Http\Controllers\Secretary;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ResidenceInformation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Household;

class ResidenceInformationController extends Controller
{
    public function new_residence()
    {
        $households = Household::select('id', 'name', 'house_number', 'street', 'barangay')
            ->orderBy('name')
            ->get();
        
        // Log available households for debugging
        Log::info('Available households:', [
            'count' => $households->count(),
            'households' => $households->toArray()
        ]);
        
        return view('secretary.Residence.new_residence', compact('households'));
    }

    public function store(Request $request)
    {
        try {
            // Log the incoming request data
            Log::info('Residence Information Request Data:', $request->all());

            // Log household validation data
            if ($request->household_type === 'existing') {
                Log::info('Existing household validation:', [
                    'selected_household_id' => $request->existing_household,
                    'household_type' => $request->household_type,
                    'available_households' => Household::pluck('id')->toArray()
                ]);
            }

            // Validate the request data
            try {
                $validated = $request->validate([
                    'residency_status' => 'required|in:Permanent,Temporary',
                    'voters' => 'required|in:Yes,No',
                    'pwd' => 'required|in:Yes,No',
                    'pwd_type' => 'nullable|string|max:255',
                    'single_parent' => 'required|in:Yes,No',
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
                    'contact_number' => 'required|string|max:20',
                    'house_number' => 'required|string|max:255',
                    'street' => 'required|string|max:255',
                    'barangay' => 'required|string|max:255',
                    'municipality' => 'required|string|max:255',
                    'zip' => 'required|string|max:255',
                    'father_name' => 'nullable|string|max:255',
                    'mother_name' => 'nullable|string|max:255',
                    'guardian_name' => 'required|string|max:255',
                    'guardian_contact' => 'required|string|max:20',
                    'guardian_relation' => 'required|in:Parent,Spouse,Sibling,Relative,Friend',
                    'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                    'household_type' => 'required|in:new,existing',
                    'household_name' => 'nullable|required_if:household_type,new|string|max:255',
                    'existing_household' => 'nullable|required_if:household_type,existing|exists:households,id'
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                Log::error('Validation failed:', [
                    'errors' => $e->errors(),
                    'request_data' => $request->all()
                ]);
                throw $e;
            }

            Log::info('Validated data:', $validated);

            DB::beginTransaction();

            try {
                // Handle household creation/selection
                if ($request->household_type === 'new') {
                    $household = Household::create([
                        'name' => $request->household_name,
                        'house_number' => $request->house_number,
                        'street' => $request->street,
                        'barangay' => $request->barangay,
                        'municipality' => $request->municipality,
                        'zip_code' => $request->zip
                    ]);
                    $validated['household_id'] = $household->id;
                } else {
                    $validated['household_id'] = $request->existing_household;
                }

                // Handle profile picture upload if present
                if ($request->hasFile('profile_picture')) {
                    try {
                        $profilePicture = $request->file('profile_picture');
                        $filename = time() . '_' . $profilePicture->getClientOriginalName();
                        $profilePicture->storeAs('private/profile_pictures', $filename);
                        $validated['profile_picture'] = $filename;
                        Log::info('Profile picture uploaded:', ['filename' => $filename]);
                    } catch (\Exception $e) {
                        Log::error('Error uploading profile picture:', [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        throw new \Exception('Error uploading profile picture: ' . $e->getMessage());
                    }
                }

                // Create new resident record
                $resident = ResidenceInformation::create($validated);
                
                DB::commit();
                
                Log::info('Resident created successfully:', ['id' => $resident->id, 'data' => $validated]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Resident information has been successfully stored.',
                    'redirect' => route('secretary.residence.all')
                ]);
                    
            } catch (\Exception $e) {
                DB::rollBack();
                
                Log::error('Error creating resident record:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'data' => $validated
                ]);
                
                throw new \Exception('Database error: ' . $e->getMessage());
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation errors
            Log::error('Validation errors:', $e->errors());
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error storing resident information: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function all_residents()
    {
        $residents = ResidenceInformation::whereNull('archived_at')
            ->whereNull('deleted_at')
            ->get();
        return view('secretary.Residence.all_residence', compact('residents'));
    }

    public function archived_residents()
    {
        $archived_residents = ResidenceInformation::withTrashed()
            ->whereNotNull('archived_at')
            ->orWhereNotNull('deleted_at')
            ->get();
        return view('secretary.Residence.archive_residents', compact('archived_residents'));
    }

    public function archive(ResidenceInformation $resident)
    {
        try {
            DB::beginTransaction();
            
            $resident->update(['archived_at' => now()]);
            $resident->delete(); // This will set deleted_at
            
            DB::commit();
            
            return redirect()->route('secretary.residence.archived')
                ->with('success', 'Resident has been archived successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error archiving resident: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'There was an error archiving the resident. Please try again.');
        }
    }

    public function restore($id)
    {
        try {
            $resident = ResidenceInformation::withTrashed()->findOrFail($id);
            $resident->update(['archived_at' => null]);
            $resident->restore(); // This will clear deleted_at
            
            return redirect()->back()
                ->with('success', 'Resident has been restored successfully.');
        } catch (\Exception $e) {
            Log::error('Error restoring resident: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'There was an error restoring the resident. Please try again.');
        }
    }

    public function show($id)
    {
        try {
            $resident = ResidenceInformation::withTrashed()->findOrFail($id);
            // Return a partial view for AJAX
            return view('secretary.Residence.partials.resident_details', compact('resident'));
        } catch (\Exception $e) {
            \Log::error('Error showing resident: ' . $e->getMessage());
            return response()->json(['error' => 'There was an error retrieving the resident information.'], 500);
        }
    }

    public function updatePhoto(Request $request, ResidenceInformation $resident)
    {
        try {
            $request->validate([
                'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            if ($request->hasFile('profile_picture')) {
                // Delete old profile picture if exists
                if ($resident->profile_picture) {
                    Storage::delete('private/profile_pictures/' . $resident->profile_picture);
                }

                $profilePicture = $request->file('profile_picture');
                $filename = time() . '_' . $profilePicture->getClientOriginalName();
                $profilePicture->storeAs('private/profile_pictures', $filename);
                
                $resident->update(['profile_picture' => $filename]);
                
                return redirect()->back()->with('success', 'Profile picture updated successfully.');
            }

            return redirect()->back()->with('error', 'No image file uploaded.');
        } catch (\Exception $e) {
            Log::error('Error updating profile picture: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating profile picture. Please try again.');
        }
    }

    public function updateInfo(Request $request, ResidenceInformation $resident)
    {
        try {
            Log::info('Attempting to update resident information', ['resident_id' => $resident->id, 'request_data' => $request->all()]);
            $validated = $request->validate([
                'residency_status' => 'required|in:Permanent,Temporary',
                'voters' => 'required|in:Yes,No',
                'pwd' => 'required|in:Yes,No',
                'pwd_type' => 'nullable|string|max:255',
                'single_parent' => 'required|in:Yes,No',
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
                'contact_number' => 'required|string|max:20',
                'house_number' => 'required|string|max:255',
                'street' => 'required|string|max:255',
                'barangay' => 'required|string|max:255',
                'municipality' => 'required|string|max:255',
                'zip' => 'required|string|max:255',
                'father_name' => 'nullable|string|max:255',
                'mother_name' => 'nullable|string|max:255',
                'guardian_name' => 'required|string|max:255',
                'guardian_contact' => 'required|string|max:20',
                'guardian_relation' => 'required|in:Parent,Spouse,Sibling,Relative,Friend'
            ]);

            Log::info('Validation successful for resident update', ['validated_data' => $validated]);
            
            $resident->update($validated);
            
            Log::info('Resident update attempted', ['resident_id' => $resident->id, 'update_result' => $resident->wasChanged()]);

            return response()->json([
                'success' => true,
                'message' => 'Resident information updated successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating resident information: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating resident information. Please try again.'
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $resident = ResidenceInformation::findOrFail($id);
            // Return a partial view with the edit form
            return view('secretary.Residence.edit_form', compact('resident'));
        } catch (\Exception $e) {
            Log::error('Error retrieving resident for edit: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load resident information'], 500);
        }
    }
}
