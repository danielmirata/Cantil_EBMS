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
        
        // Get total budget overview
        $totalBudget = Budget::sum('amount');
        $totalAllocated = Budget::sum(DB::raw('amount - remaining_amount'));
        $totalRemaining = Budget::sum('remaining_amount');
        $totalExpenses = Expense::sum('amount');
        $pendingExpenses = Expense::where('status', 'Pending')->sum('amount');

        // Low and out-of-stock notifications
        $lowStockItems = Inventory::where('quantity', '>', 0)->where('quantity', '<=', 5)->get();
        $outOfStockItems = Inventory::where('quantity', '<=', 0)->get();

        // Get data based on active tab
        $data = [
            'activeTab' => $activeTab,
            'totalBudget' => $totalBudget,
            'totalAllocated' => $totalAllocated,
            'remainingBudget' => $totalRemaining,
            'totalExpenses' => $totalExpenses,
            'pendingExpenses' => $pendingExpenses,
            'lowStockItems' => $lowStockItems,
            'outOfStockItems' => $outOfStockItems,
        ];

        switch ($activeTab) {
            case 'inventory':
                $inventoryQuery = Inventory::query();
                if ($request->filled('category')) {
                    $inventoryQuery->where('category', $request->input('category'));
                }
                if ($request->filled('supplier')) {
                    $inventoryQuery->where('supplier', $request->input('supplier'));
                }
                if ($request->filled('search')) {
                    $inventoryQuery->where('name', 'like', '%' . $request->input('search') . '%');
                }
                $data['inventory'] = $inventoryQuery->get();
                break;
            case 'budget':
                $data['budgets'] = Budget::all();
                break;
            case 'expenses':
                $expenseQuery = Expense::with('budget');
                if ($request->filled('category')) {
                    $expenseQuery->where('category', $request->input('category'));
                }
                if ($request->filled('date')) {
                    $expenseQuery->whereDate('created_at', $request->input('date'));
                }
                if ($request->filled('search')) {
                    $expenseQuery->where(function($query) use ($request) {
                        $query->where('description', 'like', '%' . $request->input('search') . '%')
                              ->orWhere('vendor', 'like', '%' . $request->input('search') . '%');
                    });
                }
                $data['expenses'] = $expenseQuery->get();
                break;
            case 'analytics':
                $data['budgets'] = Budget::all();
                $data['expenses'] = Expense::all();
                $data['inStockCount'] = Inventory::where('quantity', '>', 5)->count();
                $data['lowStockCount'] = Inventory::where('quantity', '>', 0)->where('quantity', '<=', 5)->count();
                $data['outOfStockCount'] = Inventory::where('quantity', '<=', 0)->count();
                $data['inStockNames'] = Inventory::where('quantity', '>', 5)->pluck('name')->toArray();
                $data['lowStockNames'] = Inventory::where('quantity', '>', 0)->where('quantity', '<=', 5)->pluck('name')->toArray();
                $data['outOfStockNames'] = Inventory::where('quantity', '<=', 0)->pluck('name')->toArray();
                break;
        }

        return view('secretary.inventory', $data);
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
                    'total_amount' => 'required|numeric|min:0',
                    'year' => 'required|string|max:4',
                ]);
                
                Budget::create([
                    'description' => $validated['name'],
                    'amount' => $validated['total_amount'],
                    'period' => $validated['year'],
                    'remaining_amount' => $validated['total_amount']
                ]);
                break;

            case 'expense':
                $validated = $request->validate([
                    'budget_id' => 'required|exists:budgets,id',
                    'description' => 'required|string|max:255',
                    'category' => 'required|string|max:255',
                    'amount' => 'required|numeric|min:0',
                    'vendor' => 'required|string|max:255',
                ]);

                DB::transaction(function () use ($validated) {
                    $budget = Budget::findOrFail($validated['budget_id']);
                    
                    if ($budget->remaining_amount < $validated['amount']) {
                        throw new \Exception('Insufficient budget remaining');
                    }

                    $budget->remaining_amount -= $validated['amount'];
                    $budget->save();

                    Expense::create([
                        'description' => $validated['description'],
                        'category' => $validated['category'],
                        'amount' => $validated['amount'],
                        'beneficiary' => $validated['vendor'],
                        'budget_allocation' => $validated['budget_id'],
                        'budget_remaining' => $budget->remaining_amount,
                        'date' => now(),
                        'status' => 'Approved'
                    ]);
                });
                break;
        }

        return redirect()->route('inventory.index', ['tab' => $type])->with('success', 'Item created successfully');
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
                
                Inventory::findOrFail($id)->update($validated);
                break;

            case 'budget':
                $validated = $request->validate([
                    'name' => 'required|string|max:255',
                    'total_amount' => 'required|numeric|min:0',
                    'year' => 'required|string|max:4',
                ]);
                
                $budget = Budget::findOrFail($id);
                $oldAmount = $budget->amount;
                $newAmount = $validated['total_amount'];
                $difference = $newAmount - $oldAmount;

                $budget->update([
                    'description' => $validated['name'],
                    'amount' => $newAmount,
                    'period' => $validated['year'],
                    'remaining_amount' => $budget->remaining_amount + $difference
                ]);
                break;

            case 'expense':
                $validated = $request->validate([
                    'budget_id' => 'required|exists:budgets,id',
                    'description' => 'required|string|max:255',
                    'category' => 'required|string|max:255',
                    'amount' => 'required|numeric|min:0',
                    'vendor' => 'required|string|max:255',
                ]);

                DB::transaction(function () use ($validated, $id) {
                    $expense = Expense::findOrFail($id);
                    $oldBudget = Budget::findOrFail($expense->budget_allocation);
                    $newBudget = Budget::findOrFail($validated['budget_id']);

                    // Revert old budget allocation
                    $oldBudget->remaining_amount += $expense->amount;
                    $oldBudget->save();

                    // Check if new budget has enough remaining
                    if ($newBudget->remaining_amount < $validated['amount']) {
                        throw new \Exception('Insufficient budget remaining');
                    }

                    // Update new budget allocation
                    $newBudget->remaining_amount -= $validated['amount'];
                    $newBudget->save();

                    $expense->update([
                        'description' => $validated['description'],
                        'category' => $validated['category'],
                        'amount' => $validated['amount'],
                        'beneficiary' => $validated['vendor'],
                        'budget_allocation' => $validated['budget_id'],
                        'budget_remaining' => $newBudget->remaining_amount
                    ]);
                });
                break;
        }

        return redirect()->route('inventory.index', ['tab' => $type])->with('success', 'Item updated successfully');
    }

    public function destroy(Request $request, $id)
    {
        $type = $request->input('type');
        
        switch ($type) {
            case 'inventory':
                Inventory::findOrFail($id)->delete();
                break;

            case 'budget':
                $budget = Budget::findOrFail($id);
                if ($budget->allocated > 0) {
                    return redirect()->back()->with('error', 'Cannot delete budget with allocated expenses');
                }
                $budget->delete();
                break;

            case 'expense':
                DB::transaction(function () use ($id) {
                    $expense = Expense::findOrFail($id);
                    $budget = Budget::findOrFail($expense->budget_id);
                    
                    $budget->allocated -= $expense->amount;
                    $budget->remaining_amount += $expense->amount;
                    $budget->save();
                    
                    $expense->delete();
                });
                break;
        }

        return redirect()->route('inventory.index', ['tab' => $type])->with('success', 'Item deleted successfully');
    }

    public function use(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:inventories,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'required|string|max:255'
        ]);

        $item = Inventory::findOrFail($validated['item_id']);

        if ($item->quantity < $validated['quantity']) {
            return redirect()->back()->with('error', 'Insufficient quantity available');
        }

        $item->quantity -= $validated['quantity'];
        $item->save();

        // You might want to log this usage in a separate table
        // For now, we'll just update the quantity

        return redirect()->route('inventory.index', ['tab' => 'inventory'])
            ->with('success', 'Item quantity updated successfully');
    }

    public function show($id)
    {
        $type = request()->input('type');
        switch ($type) {
            case 'inventory':
                $item = Inventory::findOrFail($id);
                break;
            case 'budget':
                $item = Budget::findOrFail($id);
                break;
            case 'expense':
                $item = Expense::findOrFail($id);
                break;
            default:
                return response()->json(['error' => 'Invalid type'], 400);
        }
        return response()->json($item);
    }
} 