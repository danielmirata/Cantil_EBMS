<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CertificateController extends Controller
{
    public function generateClearance(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'address' => 'required|string',
            'purpose' => 'required|string',
            'date_issued' => 'required|date',
            'additional_info' => 'nullable|string'
        ]);

        $token = uniqid('cert_', true);
        Cache::put($token, $data, now()->addMinutes(10));

        return response()->json([
            'success' => true,
            'print_url' => route('secretary.documents.print.clearance', ['id' => $token])
        ]);
    }

    public function generateResidency(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'address' => 'required|string',
            'purpose' => 'required|string',
            'date_issued' => 'required|date',
            'additional_info' => 'nullable|string'
        ]);

        $token = uniqid('cert_', true);
        Cache::put($token, $data, now()->addMinutes(10));

        return response()->json([
            'success' => true,
            'print_url' => route('secretary.documents.print.residency', ['id' => $token])
        ]);
    }

    public function generateCertification(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'address' => 'required|string',
            'purpose' => 'required|string',
            'date_issued' => 'required|date',
            'additional_info' => 'nullable|string'
        ]);

        $token = uniqid('cert_', true);
        Cache::put($token, $data, now()->addMinutes(10));

        return response()->json([
            'success' => true,
            'print_url' => route('secretary.documents.print.certification', ['id' => $token])
        ]);
    }
} 