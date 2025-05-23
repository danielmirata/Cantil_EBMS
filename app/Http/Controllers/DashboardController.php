<?php

namespace App\Http\Controllers;

use App\Models\ResidentsInformation;
use App\Models\Official;
use App\Models\DocumentRequest;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getStatistics()
    {
        $statistics = [
            'registeredResidents' => ResidentsInformation::count(),
            'currentOfficials' => Official::where('status', 'Active')->whereNull('archived_at')->count(),
            'awaitingProcessing' => DocumentRequest::where('status', 'pending')->count(),
            'complaintsAndBlotters' => Complaint::count(),
        ];

        return response()->json($statistics);
    }

    public function getCharts()
    {
        // Demographics
        $male = ResidentsInformation::where('gender', 'Male')->count();
        $female = ResidentsInformation::where('gender', 'Female')->count();
        $seniorCitizens = ResidentsInformation::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) >= 60')->count();
        $youth = ResidentsInformation::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 15 AND 30')->count();
        $demographics = [
            'male' => $male,
            'female' => $female,
            'seniorCitizens' => $seniorCitizens,
            'youth' => $youth,
        ];

        // Processed Requests Donut
        $totalRequests = DocumentRequest::count();
        $processedRequests = DocumentRequest::whereIn('status', ['approved', 'rejected'])->count();
        $processedPercent = $totalRequests > 0 ? round(($processedRequests / $totalRequests) * 100) : 0;

        return response()->json([
            'demographics' => $demographics,
            'processedRequests' => [
                'total' => $totalRequests,
                'processed' => $processedRequests,
                'percent' => $processedPercent
            ]
        ]);
    }
} 