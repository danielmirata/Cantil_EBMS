<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BudgetController extends Controller
{
    public function index()
    {
        $budgets = Budget::latest()->paginate(10);
        $monthlyBudgets = Budget::monthly()->get();
        $annualBudgets = Budget::annual()->get();
        return view('secretary.budget.index', compact('budgets', 'monthlyBudgets', 'annualBudgets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'period' => 'required|in:Q1,Q2,Q3,Q4',
            'description' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Set initial remaining amount equal to amount
            $validated['remaining_amount'] = $validated['amount'];

            $budget = Budget::create($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Budget created successfully',
                'budget' => $budget
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create budget: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Budget $budget)
    {
        return response()->json([
            'success' => true,
            'budget' => $budget
        ]);
    }

    public function update(Request $request, Budget $budget)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'period' => 'required|in:Q1,Q2,Q3,Q4',
            'description' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Calculate the difference in amount
            $amountDifference = $validated['amount'] - $budget->amount;
            
            // Update remaining amount based on the difference
            $validated['remaining_amount'] = $budget->remaining_amount + $amountDifference;

            $budget->update($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Budget updated successfully',
                'budget' => $budget
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update budget: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Budget $budget)
    {
        try {
            DB::beginTransaction();

            // Check if there are any expenses using this budget
            if ($budget->expenses()->exists()) {
                throw new \Exception('Cannot delete budget that has associated expenses.');
            }

            $budget->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Budget deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete budget: ' . $e->getMessage()
            ], 500);
        }
    }
} 