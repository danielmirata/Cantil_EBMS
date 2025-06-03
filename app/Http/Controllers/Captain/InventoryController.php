<?php

namespace App\Http\Controllers\Captain;

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
        
        // Get inventory items with filters
        $inventory = Inventory::when($request->category, function($query, $category) {
                return $query->where('category', $category);
            })
            ->when($request->supplier, function($query, $supplier) {
                return $query->where('supplier', $supplier);
            })
            ->when($request->search, function($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        // Get budgets
        $budgets = Budget::with('expenses')->get();

        // Get expenses with filters
        $expenses = Expense::when($request->category, function($query, $category) {
                return $query->where('category', $category);
            })
            ->when($request->date, function($query, $date) {
                return $query->whereDate('created_at', $date);
            })
            ->when($request->search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('description', 'like', "%{$search}%")
                      ->orWhere('vendor', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        // Calculate totals
        $totalBudget = $budgets->sum('amount');
        $totalExpenses = $expenses->where('status', 'Approved')->sum('amount');
        $remainingBudget = $totalBudget - $totalExpenses;
        $pendingExpenses = $expenses->where('status', 'Pending')->sum('amount');

        // Get inventory status counts
        $lowStockItems = $inventory->where('quantity', '>', 0)->where('quantity', '<=', 5);
        $outOfStockItems = $inventory->where('quantity', '<=', 0);
        $inStockItems = $inventory->where('quantity', '>', 5);

        $inStockCount = $inStockItems->count();
        $lowStockCount = $lowStockItems->count();
        $outOfStockCount = $outOfStockItems->count();

        $inStockNames = $inStockItems->pluck('name')->toArray();
        $lowStockNames = $lowStockItems->pluck('name')->toArray();
        $outOfStockNames = $outOfStockItems->pluck('name')->toArray();

        return view('captain.inventory', compact(
            'inventory',
            'budgets',
            'expenses',
            'totalBudget',
            'totalExpenses',
            'remainingBudget',
            'pendingExpenses',
            'lowStockItems',
            'outOfStockItems',
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
        
        if ($type === 'inventory') {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'quantity' => 'required|numeric|min:0',
                'unit' => 'required|string|max:50',
                'cost' => 'required|numeric|min:0',
                'supplier' => 'required|string|max:255'
            ]);

            Inventory::create($validated);
            return redirect()->back()->with('success', 'Inventory item added successfully.');
        }
        
        if ($type === 'budget') {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0',
                'period' => 'required|string|max:255'
            ]);

            Budget::create($validated);
            return redirect()->back()->with('success', 'Budget added successfully.');
        }
        
        if ($type === 'expense') {
            $validated = $request->validate([
                'budget_id' => 'required|exists:budgets,id',
                'description' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0',
                'vendor' => 'required|string|max:255'
            ]);

            Expense::create($validated);
            return redirect()->back()->with('success', 'Expense added successfully.');
        }

        return redirect()->back()->with('error', 'Invalid request type.');
    }

    public function show($id)
    {
        $item = Inventory::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $type = $request->input('type');
        
        if ($type === 'inventory') {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'quantity' => 'required|numeric|min:0',
                'unit' => 'required|string|max:50',
                'cost' => 'required|numeric|min:0',
                'supplier' => 'required|string|max:255'
            ]);

            $item = Inventory::findOrFail($id);
            $item->update($validated);
            return redirect()->back()->with('success', 'Inventory item updated successfully.');
        }
        
        if ($type === 'budget') {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0',
                'period' => 'required|string|max:255'
            ]);

            $budget = Budget::findOrFail($id);
            $budget->update($validated);
            return redirect()->back()->with('success', 'Budget updated successfully.');
        }
        
        if ($type === 'expense') {
            $validated = $request->validate([
                'budget_id' => 'required|exists:budgets,id',
                'description' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0',
                'vendor' => 'required|string|max:255'
            ]);

            $expense = Expense::findOrFail($id);
            $expense->update($validated);
            return redirect()->back()->with('success', 'Expense updated successfully.');
        }

        return redirect()->back()->with('error', 'Invalid request type.');
    }

    public function destroy(Request $request, $id)
    {
        $type = $request->input('type');
        
        if ($type === 'inventory') {
            $item = Inventory::findOrFail($id);
            $item->delete();
            return redirect()->back()->with('success', 'Inventory item deleted successfully.');
        }
        
        if ($type === 'budget') {
            $budget = Budget::findOrFail($id);
            $budget->delete();
            return redirect()->back()->with('success', 'Budget deleted successfully.');
        }
        
        if ($type === 'expense') {
            $expense = Expense::findOrFail($id);
            $expense->delete();
            return redirect()->back()->with('success', 'Expense deleted successfully.');
        }

        return redirect()->back()->with('error', 'Invalid request type.');
    }

    public function use(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:inventories,id',
            'quantity' => 'required|numeric|min:1',
            'notes' => 'required|string'
        ]);

        $item = Inventory::findOrFail($validated['item_id']);
        
        if ($item->quantity < $validated['quantity']) {
            return redirect()->back()->with('error', 'Not enough items in stock.');
        }

        $item->quantity -= $validated['quantity'];
        $item->save();

        return redirect()->back()->with('success', 'Item used successfully.');
    }
} 