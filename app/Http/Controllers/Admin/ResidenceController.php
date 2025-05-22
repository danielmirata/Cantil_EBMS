<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResidenceInformation;
use Illuminate\Http\Request;

class ResidenceController extends Controller
{
    /**
     * Display a listing of archived residents.
     *
     * @return \Illuminate\View\View
     */
    public function archived()
    {
        $archived_residents = ResidenceInformation::onlyTrashed()
            ->orderBy('archived_at', 'desc')
            ->get();

        return view('admin.residence_archive', compact('archived_residents'));
    }

    /**
     * Restore the specified archived resident.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $resident = ResidenceInformation::onlyTrashed()->findOrFail($id);
        
        try {
            $resident->restore();
            return redirect()->route('admin.residence.archived')
                ->with('success', 'Resident has been restored successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.residence.archived')
                ->with('error', 'Failed to restore resident. Please try again.');
        }
    }
} 