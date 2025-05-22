<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Official;
use Illuminate\Http\Request;

class OfficialController extends Controller
{
    /**
     * Display a listing of active officials.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $officials = Official::whereNull('archived_at')
            ->with('position')
            ->get();

        return view('admin.officials.index', compact('officials'));
    }

    /**
     * Display a listing of archived officials.
     *
     * @return \Illuminate\View\View
     */
    public function archived()
    {
        $archived_officials = Official::onlyTrashed()
            ->with('position')
            ->orderBy('archived_at', 'desc')
            ->get();

        return view('admin.official_archive', compact('archived_officials'));
    }

    /**
     * Restore the specified archived official.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $official = Official::onlyTrashed()->findOrFail($id);
        
        try {
            $official->restore();
            return redirect()->route('admin.officials.archived')
                ->with('success', 'Official has been restored successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.officials.archived')
                ->with('error', 'Failed to restore official. Please try again.');
        }
    }
} 