<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Project;
use App\Models\Budget;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('project')->latest()->paginate(10);
        $projects = Project::all();
        $budgets = Budget::all();
        
        $totalBudget = Budget::sum('amount');
        $totalExpenses = Expense::where('status', 'Approved')->sum('amount');
        $remainingBudget = Budget::sum('remaining_amount');
        $pendingExpenses = Expense::where('status', 'Pending')->sum('amount');
        $totalProjects = Project::whereIn('status', ['Planning', 'Ongoing'])->count();

        return view('secretary.inventory', compact(
            'expenses',
            'projects',
            'budgets',
            'totalBudget',
            'totalExpenses',
            'remainingBudget',
            'pendingExpenses',
            'totalProjects'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'date' => 'required|date',
            'description' => 'required|string',
            'category' => 'required|in:Infrastructure,Health Services,Education,Social Services,Public Safety,Environmental,Administrative,Events and Programs,Utilities,Maintenance,Other',
            'amount' => 'required|numeric|min:0',
            'location' => 'required|string',
            'beneficiary' => 'nullable|string',
            'budget_allocation' => 'nullable|string',
            'receipt_number' => 'nullable|string',
            'receipt_file_path' => 'nullable|string',
            'status' => 'required|in:Pending,Approved,Rejected'
        ]);

        try {
            $expense = Expense::create($validated);

            // If status is Approved, deduct from budget
            if ($validated['status'] === 'Approved' && $validated['budget_allocation']) {
                $budget = Budget::where('category', $validated['budget_allocation'])->first();
                if ($budget) {
                    $budget->remaining_amount -= $validated['amount'];
                    $budget->save();
                }
            }

            return redirect()->route('expenses.index')
                ->with('success', 'Expense created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create expense: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, Expense $expense)
    {
        try {
            \Log::info('Updating expense:', ['id' => $expense->id, 'request_data' => $request->all()]);

            $validated = $request->validate([
                'project_id' => 'nullable|exists:projects,id',
                'date' => 'required|date',
                'description' => 'required|string',
                'category' => 'required|in:Infrastructure,Health Services,Education,Social Services,Public Safety,Environmental,Administrative,Events and Programs,Utilities,Maintenance,Other',
                'amount' => 'required|numeric|min:0',
                'location' => 'required|string',
                'beneficiary' => 'nullable|string',
                'budget_allocation' => 'nullable|string',
                'receipt_number' => 'nullable|string',
                'receipt_file_path' => 'nullable|string',
                'status' => 'required|in:Pending,Approved,Rejected'
            ]);

            $oldStatus = $expense->status;
            $oldAmount = $expense->amount;
            $oldBudgetAllocation = $expense->budget_allocation;

            // Update the expense
            $expense->update($validated);

            // Handle budget changes
            if ($oldBudgetAllocation !== $validated['budget_allocation']) {
                // If budget allocation changed, add back to old budget and deduct from new budget
                if ($oldBudgetAllocation) {
                    $oldBudget = Budget::where('category', $oldBudgetAllocation)->first();
                    if ($oldBudget && $oldStatus === 'Approved') {
                        $oldBudget->remaining_amount += $oldAmount;
                        $oldBudget->save();
                    }
                }
            }

            if ($validated['budget_allocation']) {
                $budget = Budget::where('category', $validated['budget_allocation'])->first();
                if ($budget) {
                    if ($validated['status'] === 'Approved') {
                        if ($oldStatus !== 'Approved') {
                            // Newly approved
                            $budget->remaining_amount -= $validated['amount'];
                        } else if ($oldAmount !== $validated['amount']) {
                            // Amount changed
                            $budget->remaining_amount += $oldAmount - $validated['amount'];
                        }
                    } else if ($oldStatus === 'Approved') {
                        // Unapproved
                        $budget->remaining_amount += $validated['amount'];
                    }
                    $budget->save();
                }
            }

            \Log::info('Expense updated successfully', ['id' => $expense->id]);

            return response()->json([
                'success' => true,
                'message' => 'Expense updated successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to update expense:', ['id' => $expense->id, 'error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update expense: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return response()->json(['success' => true]);
    }

    public function getExpense(Expense $expense)
    {
        return response()->json($expense);
    }

    public function updateStatus(Request $request, Expense $expense)
    {
        try {
            \Log::info('Updating expense status:', [
                'expense_id' => $expense->id,
                'old_status' => $expense->status,
                'new_status' => $request->status
            ]);

            $validated = $request->validate([
                'status' => 'required|in:Pending,Approved,Rejected'
            ]);

            $oldStatus = $expense->status;
            $expense->update($validated);

            // Handle budget deduction/addition based on status change
            if ($expense->budget_allocation) {
                $budget = Budget::where('category', $expense->budget_allocation)->first();
                if ($budget) {
                    if ($validated['status'] === 'Approved' && $oldStatus !== 'Approved') {
                        // Deduct from budget when newly approved
                        $budget->remaining_amount -= $expense->amount;
                        \Log::info('Deducting from budget:', [
                            'budget_id' => $budget->id,
                            'amount' => $expense->amount,
                            'new_remaining' => $budget->remaining_amount
                        ]);
                    } elseif ($validated['status'] !== 'Approved' && $oldStatus === 'Approved') {
                        // Add back to budget when unapproved
                        $budget->remaining_amount += $expense->amount;
                        \Log::info('Adding back to budget:', [
                            'budget_id' => $budget->id,
                            'amount' => $expense->amount,
                            'new_remaining' => $budget->remaining_amount
                        ]);
                    }
                    $budget->save();
                }
            }

            \Log::info('Expense status updated successfully', [
                'expense_id' => $expense->id,
                'new_status' => $expense->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Expense status updated successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to update expense status:', [
                'expense_id' => $expense->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update expense status: ' . $e->getMessage()
            ], 500);
        }
    }
} 