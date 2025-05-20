<?php

namespace App\Http\Controllers;

use App\Models\Household;
use Illuminate\Http\Request;

class HouseholdController extends Controller
{
    public function getMembers(Household $household)
    {
        $members = $household->residents()
            ->select([
                'first_name',
                'middle_name',
                'last_name',
                'suffix',
                'date_of_birth',
                'gender',
                'civil_status',
                'contact_number'
            ])
            ->get();

        return response()->json($members);
    }
} 