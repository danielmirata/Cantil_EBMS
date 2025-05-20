<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blotter;
use Illuminate\Support\Facades\Log;

class SummonController extends Controller
{
    public function print(Request $request)
    {
        try {
            // Validate required parameters
            if (!$request->blotter_id) {
                return response()->json(['message' => 'Blotter ID is required'], 400);
            }

            // Get blotter data
            $blotter = Blotter::find($request->blotter_id);
            
            if (!$blotter) {
                return response()->json(['message' => 'Blotter record not found'], 404);
            }

            $data = [
                'blotter' => $blotter,
                'date' => $request->date,
                'day' => $request->day,
                'time' => $request->time,
                'issued_day' => $request->issued_day,
                'issued_month' => $request->issued_month
            ];

            // Log successful request
            Log::info('Summon generated successfully', [
                'blotter_id' => $blotter->id,
                'date' => $request->date
            ]);

            return view('secretary.CertificateType.summon', $data);

        } catch (\Exception $e) {
            // Log the error
            Log::error('Error generating summon: ' . $e->getMessage(), [
                'blotter_id' => $request->blotter_id,
                'exception' => $e
            ]);

            return response()->json([
                'message' => 'Error generating summon: ' . $e->getMessage()
            ], 500);
        }
    }
} 