<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ResidentsInformation;
use Illuminate\Support\Facades\DB;

class SecretaryDashboardController extends Controller
{
    public function index()
    {
        return view('secretary.dashboard');
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
}
