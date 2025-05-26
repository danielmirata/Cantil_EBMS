<?php

namespace App\Http\Controllers\Captain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Project;
use App\Models\Budget;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        // Get expenses with related data
        $expenses = Expense::with('project')->latest()->paginate(10);
        
        // Get budget statistics
        $totalBudget = Budget::sum('amount');
        $totalExpenses = Expense::where('status', 'Approved')->sum('amount');
        $remainingBudget = Budget::sum('remaining_amount');
        $pendingExpenses = Expense::where('status', 'Pending')->sum('amount');

        return view('captain.inventory', compact(
            'expenses',
            'totalBudget',
            'totalExpenses',
            'remainingBudget',
            'pendingExpenses'
        ));
    }
} 