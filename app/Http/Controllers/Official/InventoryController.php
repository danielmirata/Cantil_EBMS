<?php

namespace App\Http\Controllers\Official;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Budget;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->query('tab', 'inventory');
        $query = $request->query();

        // Get overview statistics
        $totalBudget = Budget::sum('amount');
        $totalExpenses = Expense::sum('amount');
        $remainingBudget = $totalBudget - $totalExpenses;
        $pendingExpenses = Expense::where('status', 'pending')->sum('amount');

        // Get inventory items with filters
        $inventory = Inventory::query()
            ->when($request->filled('category'), function ($q) use ($request) {
                return $q->where('category', $request->category);
            })
            ->when($request->filled('supplier'), function ($q) use ($request) {
                return $q->where('supplier', $request->supplier);
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                return $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->get();

        // Get low stock and out of stock items
        $lowStockItems = Inventory::where('quantity', '>', 0)
            ->where('quantity', '<=', 5)
            ->get();
        $outOfStockItems = Inventory::where('quantity', '<=', 0)->get();

        // Get budgets
        $budgets = Budget::all();

        // Get expenses with filters
        $expenses = Expense::query()
            ->when($request->filled('category'), function ($q) use ($request) {
                return $q->where('category', $request->category);
            })
            ->when($request->filled('date'), function ($q) use ($request) {
                return $q->whereDate('created_at', $request->date);
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                return $q->where(function ($query) use ($request) {
                    $query->where('description', 'like', '%' . $request->search . '%')
                        ->orWhere('vendor', 'like', '%' . $request->search . '%');
                });
            })
            ->get();

        // Initialize analytics variables
        $inStockCount = null;
        $lowStockCount = null;
        $outOfStockCount = null;
        $inStockNames = null;
        $lowStockNames = null;
        $outOfStockNames = null;

        // Analytics data
        if ($activeTab === 'analytics') {
            $inStockCount = Inventory::where('quantity', '>', 5)->count();
            $lowStockCount = $lowStockItems->count();
            $outOfStockCount = $outOfStockItems->count();

            $inStockNames = Inventory::where('quantity', '>', 5)->pluck('name')->toArray();
            $lowStockNames = $lowStockItems->pluck('name')->toArray();
            $outOfStockNames = $outOfStockItems->pluck('name')->toArray();
        }

        return view('official.inventory', compact(
            'activeTab',
            'totalBudget',
            'totalExpenses',
            'remainingBudget',
            'pendingExpenses',
            'inventory',
            'lowStockItems',
            'outOfStockItems',
            'budgets',
            'expenses',
            'inStockCount',
            'lowStockCount',
            'outOfStockCount',
            'inStockNames',
            'lowStockNames',
            'outOfStockNames'
        ));
    }

    public function store(Request $request)
    {
        $type = $request->input('type');

        switch ($type) {
            case 'inventory':
                $validated = $request->validate([
                    'name' => 'required|string|max:255',
                    'category' => 'required|string|max:255',
                    'quantity' => 'required|numeric|min:0',
                    'unit' => 'required|string|max:50',
                    'cost' => 'required|numeric|min:0',
                    'supplier' => 'required|string|max:255',
                ]);

                Inventory::create($validated);
                break;

            case 'budget':
                $validated = $request->validate([
                    'name' => 'required|string|max:255',
                    'amount' => 'required|numeric|min:0',
                    'period' => 'required|string|max:255',
                ]);

                Budget::create($validated);
                break;

            case 'expense':
                $validated = $request->validate([
                    'budget_id' => 'required|exists:budgets,id',
                    'description' => 'required|string|max:255',
                    'category' => 'required|string|max:255',
                    'amount' => 'required|numeric|min:0',
                    'vendor' => 'required|string|max:255',
                ]);

                // Check if budget has enough remaining amount
                $budget = Budget::findOrFail($validated['budget_id']);
                if ($budget->remaining_amount < $validated['amount']) {
                    return back()->with('error', 'Insufficient budget remaining.');
                }

                DB::transaction(function () use ($validated, $budget) {
                    Expense::create($validated);
                    $budget->update([
                        'allocated' => $budget->allocated + $validated['amount']
                    ]);
                });
                break;
        }

        return back()->with('success', ucfirst($type) . ' added successfully.');
    }

    public function show($id)
    {
        $item = Inventory::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $type = $request->input('type');

        switch ($type) {
            case 'inventory':
                $validated = $request->validate([
                    'name' => 'required|string|max:255',
                    'category' => 'required|string|max:255',
                    'quantity' => 'required|numeric|min:0',
                    'unit' => 'required|string|max:50',
                    'cost' => 'required|numeric|min:0',
                    'supplier' => 'required|string|max:255',
                ]);

                $item = Inventory::findOrFail($id);
                $item->update($validated);
                break;

            case 'budget':
                $validated = $request->validate([
                    'name' => 'required|string|max:255',
                    'amount' => 'required|numeric|min:0',
                    'period' => 'required|string|max:255',
                ]);

                $budget = Budget::findOrFail($id);
                $budget->update($validated);
                break;

            case 'expense':
                $validated = $request->validate([
                    'budget_id' => 'required|exists:budgets,id',
                    'description' => 'required|string|max:255',
                    'category' => 'required|string|max:255',
                    'amount' => 'required|numeric|min:0',
                    'vendor' => 'required|string|max:255',
                ]);

                $expense = Expense::findOrFail($id);
                $oldBudget = Budget::findOrFail($expense->budget_id);
                $newBudget = Budget::findOrFail($validated['budget_id']);

                DB::transaction(function () use ($validated, $expense, $oldBudget, $newBudget) {
                    // Revert old budget allocation
                    $oldBudget->update([
                        'allocated' => $oldBudget->allocated - $expense->amount
                    ]);

                    // Check if new budget has enough remaining amount
                    if ($newBudget->remaining_amount < $validated['amount']) {
                        throw new \Exception('Insufficient budget remaining.');
                    }

                    // Update expense and new budget
                    $expense->update($validated);
                    $newBudget->update([
                        'allocated' => $newBudget->allocated + $validated['amount']
                    ]);
                });
                break;
        }

        return back()->with('success', ucfirst($type) . ' updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $type = $request->input('type');

        switch ($type) {
            case 'inventory':
                $item = Inventory::findOrFail($id);
                $item->delete();
                break;

            case 'budget':
                $budget = Budget::findOrFail($id);
                if ($budget->allocated > 0) {
                    return back()->with('error', 'Cannot delete budget with allocated expenses.');
                }
                $budget->delete();
                break;

            case 'expense':
                $expense = Expense::findOrFail($id);
                $budget = Budget::findOrFail($expense->budget_id);

                DB::transaction(function () use ($expense, $budget) {
                    $budget->update([
                        'allocated' => $budget->allocated - $expense->amount
                    ]);
                    $expense->delete();
                });
                break;
        }

        return back()->with('success', ucfirst($type) . ' deleted successfully.');
    }

    public function use(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:inventories,id',
            'quantity' => 'required|numeric|min:1',
            'notes' => 'required|string|max:255',
        ]);

        $item = Inventory::findOrFail($validated['item_id']);

        if ($item->quantity < $validated['quantity']) {
            return back()->with('error', 'Insufficient quantity available.');
        }

        $item->update([
            'quantity' => $item->quantity - $validated['quantity']
        ]);

        // You might want to create a usage log here
        // UsageLog::create([
        //     'inventory_id' => $item->id,
        //     'quantity' => $validated['quantity'],
        //     'notes' => $validated['notes'],
        //     'user_id' => auth()->id(),
        // ]);

        return back()->with('success', 'Item used successfully.');
    }
} 