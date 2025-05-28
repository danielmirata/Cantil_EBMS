<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blotter;
use Illuminate\Support\Facades\Auth;

class BlotterController extends Controller
{
    public function print(Request $request)
    {
        $blotter = Blotter::findOrFail($request->blotter_id);
        
        return view('secretary.CertificateType.blotter_record', [
            'blotter' => $blotter,
            'incident_date' => $request->incident_date,
            'incident_time' => $request->incident_time,
            'what_happened' => $request->what_happened,
            'who_was_involved' => $request->who_was_involved,
            'how_it_happened' => $request->how_it_happened,
            'secretary_name' => $request->secretary_name,
            'barangay_captain_name' => $request->barangay_captain_name
        ]);
    }
} 