<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseDashboardController extends Controller
{
    protected function checkAccountType($requiredType)
    {
        if (!auth()->check() || auth()->user()->account_type !== $requiredType) {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }
        return null;
    }
} 