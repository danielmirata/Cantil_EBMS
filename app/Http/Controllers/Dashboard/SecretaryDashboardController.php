<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ResidentsInformation;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SecretaryDashboardController extends Controller
{
    public function index()
    {
        $registeredResidents = \App\Models\ResidentsInformation::count();
        $currentOfficials = \App\Models\Official::where('status', 'Active')->whereNull('archived_at')->count();
        $awaitingProcessing = \App\Models\DocumentRequest::where('status', 'pending')->count();
        $complaintsAndBlotters = \App\Models\Complaint::count();

        // Demographics
        $male = \App\Models\ResidentsInformation::where('gender', 'Male')->count();
        $female = \App\Models\ResidentsInformation::where('gender', 'Female')->count();
        $seniorCitizens = \App\Models\ResidentsInformation::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) >= 60')->count();
        $youth = \App\Models\ResidentsInformation::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 15 AND 30')->count();

        // Processed Requests Donut
        $totalRequests = \App\Models\DocumentRequest::count();
        $processedRequests = \App\Models\DocumentRequest::whereIn('status', ['approved', 'rejected'])->count();
        $processedPercent = $totalRequests > 0 ? round(($processedRequests / $totalRequests) * 100) : 0;

        return view('secretary.dashboard', compact(
            'registeredResidents',
            'currentOfficials',
            'awaitingProcessing',
            'complaintsAndBlotters',
            'male',
            'female',
            'seniorCitizens',
            'youth',
            'processedPercent'
        ));
    }

    /**
     * Display the map view for secretary with purok data.
     *
     * @return \Illuminate\View\View
     */
    public function showMap()
    {
        // Define all puroks
        $puroks = [
            'Purok Madasigon', 'Purok Mauswagon', 'Purok Mapahiuyom', 'Purok Matinabangon',
            'Purok Twin Heart', 'Purok Malipayon', 'Purok Makugihon', 'Purok Maalagaron',
            'Purok Matinagdanon', 'Purok Maabi-Abihon', 'Camella Homes', 'Lumina Homes'
        ];

        $purokData = [];
        
        // Process each purok
        foreach ($puroks as $purok) {
            // Get all residents in this purok
            $residents = ResidentsInformation::where('street', 'like', '%' . $purok . '%')
                ->orWhere('barangay', 'like', '%' . $purok . '%')
                ->get();

            // Calculate statistics
            $voters = $residents->where('voters', 'Yes')->count();
            $pwd = $residents->where('pwd', 'Yes')->count();
            $singleParent = $residents->where('single_parent', 'Yes')->count();
            
            // Age-based calculations
            $seniorCitizens = 0;
            $adults = 0;
            $children = 0;
            
            foreach ($residents as $resident) {
                $age = now()->diffInYears($resident->date_of_birth);
                if ($age >= 60) {
                    $seniorCitizens++;
                } elseif ($age >= 18) {
                    $adults++;
                } else {
                    $children++;
                }
            }
            
            // Get unique streets in this purok
            $streets = $residents->groupBy('street')->map(function($residents) {
                return [
                    'total_residents' => $residents->count(),
                    'total_voters' => $residents->where('voters', 'Yes')->count(),
                    'total_pwd' => $residents->where('pwd', 'Yes')->count(),
                    'total_seniors' => $residents->filter(function($r) {
                        return now()->diffInYears($r->date_of_birth) >= 60;
                    })->count()
                ];
            });

            // Compile purok data
            $purokData[$purok] = [
                'Total Population' => $residents->count(),
                'Total Voters' => $voters,
                'Senior Citizens' => $seniorCitizens,
                'Adults (18-59)' => $adults,
                'Children (0-17)' => $children,
                'Persons with Disabilities' => $pwd,
                'Single Parents' => $singleParent,
                'Households' => $residents->groupBy('house_number')->count(),
                'Streets' => $streets
            ];
        }

        return view('secretary.map', [
            'purokData' => $purokData
        ]);
    }

    public function getPurokStats()
    {
        try {
            $now = Carbon::now();
            $residents = ResidentsInformation::all();

            $stats = [
                'total_residents' => $residents->count(),
                'total_families' => $residents->groupBy('house_number')->count(),
                'area' => 1, // Default area in km², adjust as needed
                'gender' => [
                    'male' => $residents->where('gender', 'Male')->count(),
                    'female' => $residents->where('gender', 'Female')->count(),
                ],
                'voters' => [
                    'registered' => $residents->where('voters', 'Yes')->count(),
                    'non_registered' => $residents->where('voters', 'No')->count(),
                ],
                'pwd' => $residents->where('pwd', 'Yes')->count(),
                'single_parent' => $residents->where('single_parent', 'Yes')->count(),
                'age_groups' => [
                    'children' => $residents->filter(function($resident) use ($now) {
                        $age = Carbon::parse($resident->date_of_birth)->age;
                        return $age < 18;
                    })->count(),
                    'adults' => $residents->filter(function($resident) use ($now) {
                        $age = Carbon::parse($resident->date_of_birth)->age;
                        return $age >= 18 && $age < 60;
                    })->count(),
                    'senior' => $residents->filter(function($resident) use ($now) {
                        $age = Carbon::parse($resident->date_of_birth)->age;
                        return $age >= 60;
                    })->count(),
                ],
                'civil_status' => [
                    'single' => $residents->where('civil_status', 'Single')->count(),
                    'married' => $residents->where('civil_status', 'Married')->count(),
                    'widowed' => $residents->where('civil_status', 'Widowed')->count(),
                    'divorced' => $residents->where('civil_status', 'Divorced')->count(),
                ],
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            \Log::error('Error fetching purok statistics: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch purok statistics: ' . $e->getMessage()], 500);
        }
    }
}
