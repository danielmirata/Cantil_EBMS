<?php

namespace App\Http\Controllers;

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
        $totalExpenses = Expense::where('status', 'Approved')->sum('amount');
        $remainingBudget = Budget::sum('remaining_amount');
        $pendingExpenses = Expense::where('status', 'Pending')->sum('amount');

        // Get data based on active tab
        $data = [
            'activeTab' => $activeTab,
            'totalBudget' => $totalBudget,
            'totalExpenses' => $totalExpenses,
            'remainingBudget' => $remainingBudget,
            'pendingExpenses' => $pendingExpenses,
        ];

        switch ($activeTab) {
            case 'inventory':
                $data['inventory'] = Inventory::all();
                break;
            case 'budget':
                $data['budgets'] = Budget::all();
                break;
            case 'expenses':
                $data['expenses'] = Expense::with('budget')->get();
                $data['budgets'] = Budget::all();
                break;
            case 'analytics':
                $data['budgets'] = Budget::all();
                $data['expenses'] = Expense::with('budget')->get();
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
                    'amount' => 'required|numeric|min:0',
                    'period' => 'required|string|max:4',
                ]);
                
                $validated['remaining_amount'] = $validated['amount'];
                $validated['allocated'] = 0;
                
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

                DB::transaction(function () use ($validated) {
                    $budget = Budget::findOrFail($validated['budget_id']);
                    
                    if ($budget->remaining_amount < $validated['amount']) {
                        throw new \Exception('Insufficient budget remaining');
                    }

                    $budget->allocated += $validated['amount'];
                    $budget->remaining_amount -= $validated['amount'];
                    $budget->save();

                    Expense::create($validated);
                });
                break;
        }

        return redirect()->route('inventory.index', ['tab' => $type])->with('success', 'Item created successfully');
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
                    'amount' => 'required|numeric|min:0',
                    'period' => 'required|string|max:4',
                ]);
                
                $budget = Budget::findOrFail($id);
                $oldAmount = $budget->amount;
                $newAmount = $validated['amount'];
                $difference = $newAmount - $oldAmount;

                $budget->update([
                    'name' => $validated['name'],
                    'amount' => $newAmount,
                    'period' => $validated['period'],
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
                    $oldBudget = Budget::findOrFail($expense->budget_id);
                    $newBudget = Budget::findOrFail($validated['budget_id']);

                    // Revert old budget allocation
                    $oldBudget->allocated -= $expense->amount;
                    $oldBudget->remaining_amount += $expense->amount;
                    $oldBudget->save();

                    // Check if new budget has enough remaining
                    if ($newBudget->remaining_amount < $validated['amount']) {
                        throw new \Exception('Insufficient budget remaining');
                    }

                    // Update new budget allocation
                    $newBudget->allocated += $validated['amount'];
                    $newBudget->remaining_amount -= $validated['amount'];
                    $newBudget->save();

                    $expense->update($validated);
                });
                break;
        }

        return redirect()->route('inventory.index', ['tab' => $type])->with('success', 'Item updated successfully');
    }

    public function use(Request $request)
    {
        $validated = $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'quantity' => 'required|numeric|min:1',
            'purpose' => 'required|string|max:255'
        ]);

        DB::transaction(function () use ($validated) {
            $inventory = Inventory::findOrFail($validated['inventory_id']);
            
            if ($inventory->quantity < $validated['quantity']) {
                throw new \Exception('Insufficient inventory quantity');
            }

            $inventory->quantity -= $validated['quantity'];
            $inventory->save();

            // You might want to log this usage in a separate table
            // ActivityLog::create([
            //     'type' => 'inventory_use',
            //     'description' => sprintf('Used %d %s of %s for %s', 
            //         $validated['quantity'],
            //         $inventory->unit,
            //         $inventory->name,
            //         $validated['purpose']
            //     )
            // ]);
        });

        return redirect()->route('inventory.index')->with('success', 'Inventory used successfully');
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
                $item = Budget::findOrFail($id);
                $item->delete();
                break;

            case 'expense':
                $item = Expense::findOrFail($id);
                $item->delete();
                break;
        }

        return redirect()->route('inventory.index', ['tab' => $type])->with('success', 'Item deleted successfully');
    }
}