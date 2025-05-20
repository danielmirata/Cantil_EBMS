<?php

namespace App\Http\Controllers;

use App\Models\Household;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResidentController extends Controller
{
    public function create()
    {
        $households = Household::select('id', 'name', 'house_number', 'street', 'barangay')
            ->orderBy('name')
            ->get();
        return view('secretary.Residence.new_residence', compact('households'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // ... existing validation rules ...
            'household_type' => 'required|in:new,existing',
            'household_name' => 'required_if:household_type,new',
            'existing_household' => 'required_if:household_type,existing|exists:households,id',
        ]);

        try {
            DB::beginTransaction();

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

            // Create resident
            $resident = Resident::create($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Resident added successfully',
                'redirect' => route('residents.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error adding resident: ' . $e->getMessage()
            ], 500);
        }
    }
} 