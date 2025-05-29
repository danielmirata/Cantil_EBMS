@extends('layouts.c_inve_layout')

@section('title', 'Inventory Management')

@section('header-title', 'Inventory Management')
@section('header-subtitle', 'Manage inventory, budgets, and track expenses efficiently')

@section('navigation')
<div class="border-bottom mb-4">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link{{ request()->query('tab', 'inventory') == 'inventory' ? ' active' : '' }}" href="{{ route('captain.inventory.index', ['tab' => 'inventory']) }}">
                <i class="fas fa-box me-2"></i>Inventory
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ request()->query('tab') == 'budget' ? ' active' : '' }}" href="{{ route('captain.inventory.index', ['tab' => 'budget']) }}">
                <i class="fas fa-wallet me-2"></i>Budget Management
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ request()->query('tab') == 'expenses' ? ' active' : '' }}" href="{{ route('captain.inventory.index', ['tab' => 'expenses']) }}">
                <i class="fas fa-receipt me-2"></i>Expenses
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ request()->query('tab') == 'analytics' ? ' active' : '' }}" href="{{ route('captain.inventory.index', ['tab' => 'analytics']) }}">
                <i class="fas fa-chart-bar me-2"></i>Analytics
            </a>
        </li>
    </ul>
</div>
@endsection

@section('styles')
<style>
.bg-primary {
    background-color: #90caf9 !important;
    /* Optionally, adjust text color for contrast */
    color:rgb(52, 52, 54) !important;
}

.budget-blue {
    background-color: #90caf9 !important;
    color: rgb(52, 52, 54) !important;
}
</style>
@endsection

@section('content')
<!-- Overview Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6 class="card-title">Total Budget</h6>
                <h3 class="mb-0">₱{{ number_format($totalBudget, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6 class="card-title">Total Expenses</h6>
                <h3 class="mb-0">₱{{ number_format($totalExpenses, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6 class="card-title">Remaining Budget</h6>
                <h3 class="mb-0">₱{{ number_format($remainingBudget, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6 class="card-title">Pending Expenses</h6>
                <h3 class="mb-0">₱{{ number_format($pendingExpenses, 2) }}</h3>
            </div>
        </div>
    </div>
</div>

@php
    $activeTab = request()->query('tab', 'inventory');
@endphp

@if($activeTab === 'inventory')
    @if(($lowStockItems->count() ?? 0) > 0 || ($outOfStockItems->count() ?? 0) > 0)
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <div>
                @if(($outOfStockItems->count() ?? 0) > 0)
                    <strong>Out of Stock:</strong>
                    @foreach($outOfStockItems as $item)
                        <span class="badge bg-danger">{{ $item->name }}</span>
                    @endforeach
                    <br>
                @endif
                @if(($lowStockItems->count() ?? 0) > 0)
                    <strong>Low Stock (≤ 5):</strong>
                    @foreach($lowStockItems as $item)
                        <span class="badge bg-warning text-dark">{{ $item->name }} ({{ $item->quantity }})</span>
                    @endforeach
                @endif
            </div>
        </div>
    @endif
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Inventory Management</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inventoryModal">
                <i class="fas fa-plus me-2"></i>Add Item
            </button>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('captain.inventory.index') }}" class="row g-3 mb-3 align-items-end">
                <input type="hidden" name="tab" value="inventory">
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select class="form-select" name="category">
                        <option value="">All Categories</option>
                        @foreach(($inventory ?? collect([]))->pluck('category')->unique()->filter() as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Supplier</label>
                    <select class="form-select" name="supplier">
                        <option value="">All Suppliers</option>
                        @foreach(($inventory ?? collect([]))->pluck('supplier')->unique()->filter() as $sup)
                            <option value="{{ $sup }}" {{ request('supplier') == $sup ? 'selected' : '' }}>{{ $sup }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Item Name</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search by name">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Cost</th>
                            <th>Supplier</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventory ?? [] as $item)
                            <tr>
                                <td>{{ $item->name }}
                                    @if($item->quantity <= 0)
                                        <span class="badge bg-danger ms-1">Out of Stock</span>
                                    @elseif($item->quantity > 0 && $item->quantity <= 5)
                                        <span class="badge bg-warning text-dark ms-1">Low</span>
                                    @endif
                                </td>
                                <td>{{ $item->category }}</td>
                                <td>{{ $item->quantity }} {{ $item->unit }}</td>
                                <td>₱{{ number_format($item->cost, 2) }}</td>
                                <td>{{ $item->supplier }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-2" onclick="editInventoryItem({{ $item->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning me-2" onclick="useInventoryItem({{ $item->id }}, '{{ $item->name }}', {{ $item->quantity }})">
                                        <i class="fas fa-minus-circle"></i>
                                    </button>
                                    <form action="{{ route('captain.inventory.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="type" value="inventory">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this item?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No inventory items found. <button class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#inventoryModal">Add your first item</button></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

@if($activeTab === 'budget')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Budget Management</h4>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#budgetModal">
                <i class="fas fa-plus me-2"></i>Add Budget
            </button>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @forelse($budgets ?? [] as $budget)
                    <div class="col-md-12">
                        <div class="card budget-blue text-black mb-3 position-relative">
                            <div class="card-body">
                                <h4 class="fw-bold mb-3">{{ $budget->name }}</h4>
                                <p class="mb-1">Total Budget: <span class="fw-bold">₱{{ number_format($budget->amount, 0) }}</span></p>
                                <p class="mb-1">Allocated: <span class="fw-bold">₱{{ number_format($budget->allocated, 0) }}</span></p>
                                <p class="mb-2">Remaining: <span class="fw-bold">₱{{ number_format($budget->remaining_amount, 0) }}</span></p>
                                <div class="progress mb-2" style="height: 6px;">
                                    <div class="progress-bar bg-info" role="progressbar"
                                        style="width: {{ $budget->amount > 0 ? ($budget->allocated / $budget->amount) * 100 : 0 }}%;"
                                        aria-valuenow="{{ $budget->allocated }}" aria-valuemin="0" aria-valuemax="{{ $budget->amount }}">
                                    </div>
                                </div>
                                <div class="position-absolute top-0 end-0 m-2">
                                    <button class="btn btn-sm btn-outline-primary me-1" onclick="editBudget({{ $budget->id }})"><i class="fas fa-edit"></i></button>
                                    <form action="{{ route('captain.inventory.destroy', $budget->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="type" value="budget">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted">No budgets found. <button class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#budgetModal">Add your first budget</button></div>
                @endforelse
            </div>
        </div>
    </div>
@endif

@if($activeTab === 'expenses')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Expense Tracking</h4>
            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#expenseModal">
                <i class="fas fa-plus me-2"></i>Add Expense
            </button>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-2 gap-2">
                <button class="btn btn-outline-secondary" onclick="printExpensesTable()">
                    <i class="fas fa-print me-1"></i> Print
                </button>
                <button class="btn btn-outline-danger" onclick="exportExpensesPDF()">
                    <i class="fas fa-file-pdf me-1"></i> Export PDF
                </button>
            </div>
            <!-- Filter Form -->
            <form method="GET" action="{{ route('captain.inventory.index') }}" class="row g-3 mb-3 align-items-end">
                <input type="hidden" name="tab" value="expenses">
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select class="form-select" name="category">
                        <option value="">All Categories</option>
                        @foreach(($expenses ?? collect([]))->pluck('category')->unique()->filter() as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date</label>
                    <input type="date" class="form-control" name="date" value="{{ request('date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search by description or vendor">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-info w-100">Filter</button>
                </div>
            </form>
            <div class="table-responsive">
                <table id="expensesTable" class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Vendor</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses ?? [] as $expense)
                            <tr>
                                <td>{{ $expense->description }}</td>
                                <td>{{ $expense->category }}</td>
                                <td>₱{{ number_format($expense->amount, 2) }}</td>
                                <td>{{ $expense->vendor }}</td>
                                <td>{{ $expense->created_at ? $expense->created_at->format('Y-m-d') : '' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-2" onclick="editExpense({{ $expense->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('captain.inventory.destroy', $expense->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="type" value="expense">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this expense?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No expenses found. <button class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#expenseModal">Add your first expense</button></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

@if($activeTab === 'analytics')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Budget vs Allocation</h5>
                </div>
                <div class="card-body">
                    <canvas id="budgetAllocationChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Inventory Status</h5>
                </div>
                <div class="card-body">
                    <canvas id="inventoryStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Inventory Modal -->
<div class="modal fade" id="inventoryModal" tabindex="-1" aria-labelledby="inventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('captain.inventory.store') }}" id="inventoryForm">
            @csrf
            <input type="hidden" name="type" value="inventory">
            <input type="hidden" name="_method" value="POST" id="inventoryMethod">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="inventoryModalLabel">Add Inventory Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Item Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" class="form-control" name="category" required>
                    </div>
                    <div class="mb-3 row">
                        <div class="col">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control" name="quantity" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Unit</label>
                            <input type="text" class="form-control" name="unit" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cost</label>
                        <input type="number" class="form-control" name="cost" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Supplier</label>
                        <input type="text" class="form-control" name="supplier" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Item</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Budget Modal -->
<div class="modal fade" id="budgetModal" tabindex="-1" aria-labelledby="budgetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('captain.inventory.store') }}" id="budgetForm">
            @csrf
            <input type="hidden" name="type" value="budget">
            <input type="hidden" name="_method" value="POST" id="budgetMethod">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="budgetModalLabel">Add Budget</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Budget Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Amount</label>
                        <input type="number" class="form-control" name="amount" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Period</label>
                        <input type="text" class="form-control" name="period" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Budget</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Expense Modal -->
<div class="modal fade" id="expenseModal" tabindex="-1" aria-labelledby="expenseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('captain.inventory.store') }}" id="expenseForm">
            @csrf
            <input type="hidden" name="type" value="expense">
            <input type="hidden" name="_method" value="POST" id="expenseMethod">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="expenseModalLabel">Add Expense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Budget</label>
                        <select class="form-control" name="budget_id" required>
                            <option value="">Select Budget</option>
                            @foreach($budgets ?? [] as $budget)
                                <option value="{{ $budget->id }}">{{ $budget->name }} (₱{{ number_format($budget->remaining_amount, 2) }} remaining)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" class="form-control" name="description" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-control" name="category" required>
                            <option value="">Select Category</option>
                            <option value="Infrastructure">Infrastructure</option>
                            <option value="Health Services">Health Services</option>
                            <option value="Education">Education</option>
                            <option value="Social Services">Social Services</option>
                            <option value="Public Safety">Public Safety</option>
                            <option value="Environmental">Environmental</option>
                            <option value="Administrative">Administrative</option>
                            <option value="Events and Programs">Events and Programs</option>
                            <option value="Utilities">Utilities</option>
                            <option value="Maintenance">Maintenance</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" class="form-control" name="amount" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Vendor</label>
                        <input type="text" class="form-control" name="vendor" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Save Expense</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Use Item Modal -->
<div class="modal fade" id="useItemModal" tabindex="-1" aria-labelledby="useItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('captain.inventory.use') }}" id="useItemForm">
            @csrf
            <input type="hidden" name="item_id" id="useItemId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="useItemModalLabel">Use Inventory Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Item Name</label>
                        <input type="text" class="form-control" id="useItemName" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current Quantity</label>
                        <input type="text" class="form-control" id="currentQuantity" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity to Use</label>
                        <input type="number" class="form-control" name="quantity" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Purpose/Notes</label>
                        <textarea class="form-control" name="notes" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Use Item</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Hidden export table for PDF/CSV/Print -->
<div id="expensesExportWrapper" style="display:none;">
    <h3 class="text-center mb-3">Barangay Cantil-E Expenses</h3>
    <table id="expensesExportTable" class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Description</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Vendor</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses ?? [] as $expense)
                <tr>
                    <td>{{ $expense->description }}</td>
                    <td>{{ $expense->category }}</td>
                    <td>₱{{ number_format($expense->amount, 2) }}</td>
                    <td>{{ $expense->vendor }}</td>
                    <td>{{ $expense->created_at ? $expense->created_at->format('Y-m-d') : '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
// Edit Inventory Item
function editInventoryItem(id) {
    console.log('editInventoryItem called', id);
    // Fetch item data and populate form
    fetch(`/captain/inventory/${id}?type=inventory`)
        .then(response => response.json())
        .then(data => {
            const form = document.getElementById('inventoryForm');
            form.action = `/captain/inventory/${id}`;
            document.getElementById('inventoryMethod').value = 'PUT';
            form.querySelector('[name="name"]').value = data.name;
            form.querySelector('[name="category"]').value = data.category;
            form.querySelector('[name="quantity"]').value = data.quantity;
            form.querySelector('[name="unit"]').value = data.unit;
            form.querySelector('[name="cost"]').value = data.cost;
            form.querySelector('[name="supplier"]').value = data.supplier;
            document.getElementById('inventoryModalLabel').textContent = 'Edit Inventory Item';
            new bootstrap.Modal(document.getElementById('inventoryModal')).show();
        });
}

// Edit Budget
function editBudget(id) {
    // Fetch budget data and populate form
    fetch(`/captain/inventory/${id}`)
        .then(response => response.json())
        .then(data => {
            const form = document.getElementById('budgetForm');
            form.action = `/captain/inventory/${id}`;
            document.getElementById('budgetMethod').value = 'PUT';
            form.querySelector('[name="name"]').value = data.name;
            form.querySelector('[name="amount"]').value = data.amount;
            form.querySelector('[name="period"]').value = data.period;
            document.getElementById('budgetModalLabel').textContent = 'Edit Budget';
            new bootstrap.Modal(document.getElementById('budgetModal')).show();
        });
}

// Edit Expense
function editExpense(id) {
    // Fetch expense data and populate form
    fetch(`/captain/inventory/${id}`)
        .then(response => response.json())
        .then(data => {
            const form = document.getElementById('expenseForm');
            form.action = `/captain/inventory/${id}`;
            document.getElementById('expenseMethod').value = 'PUT';
            form.querySelector('[name="budget_id"]').value = data.budget_id;
            form.querySelector('[name="description"]').value = data.description;
            form.querySelector('[name="category"]').value = data.category;
            form.querySelector('[name="amount"]').value = data.amount;
            form.querySelector('[name="vendor"]').value = data.vendor;
            document.getElementById('expenseModalLabel').textContent = 'Edit Expense';
            new bootstrap.Modal(document.getElementById('expenseModal')).show();
        });
}

// Analytics Charts
@if($activeTab === 'analytics')
document.addEventListener('DOMContentLoaded', function() {
    // Budget vs Allocation Chart
    const ctx = document.getElementById('budgetAllocationChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(($budgets ?? collect([]))->pluck('name')) !!},
            datasets: [
                {
                    label: 'Allocated',
                    data: {!! json_encode(($budgets ?? collect([]))->map(function($b) { return $b->amount - $b->remaining_amount; })) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgb(54, 162, 235)',
                    borderWidth: 1
                },
                {
                    label: 'Remaining',
                    data: {!! json_encode(($budgets ?? collect([]))->pluck('remaining_amount')) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgb(75, 192, 192)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Inventory Status Chart
    const inventoryStatusCtx = document.getElementById('inventoryStatusChart').getContext('2d');
    new Chart(inventoryStatusCtx, {
        type: 'bar',
        data: {
            labels: ['In Stock', 'Low Stock', 'Out of Stock'],
            datasets: [{
                label: 'Number of Items',
                data: [
                    {{ $inStockCount ?? 0 }},
                    {{ $lowStockCount ?? 0 }},
                    {{ $outOfStockCount ?? 0 }}
                ],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(255, 99, 132, 0.7)'
                ],
                borderColor: [
                    'rgb(54, 162, 235)',
                    'rgb(255, 206, 86)',
                    'rgb(255, 99, 132)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        afterBody: function(context) {
                            const names = [
                                {!! json_encode($inStockNames ?? []) !!},
                                {!! json_encode($lowStockNames ?? []) !!},
                                {!! json_encode($outOfStockNames ?? []) !!}
                            ];
                            const idx = context[0].dataIndex;
                            if (names[idx].length === 0) return 'No items';
                            return 'Items: ' + names[idx].join(', ');
                        }
                    }
                }
            }
        }
    });
});
@endif

// Use Inventory Item
function useInventoryItem(id, name, currentQuantity) {
    document.getElementById('useItemId').value = id;
    document.getElementById('useItemName').value = name;
    document.getElementById('currentQuantity').value = currentQuantity;
    document.getElementById('useItemForm').querySelector('[name="quantity"]').max = currentQuantity;
    new bootstrap.Modal(document.getElementById('useItemModal')).show();
}

// Print Expenses Table
function printExpensesTable() {
    const wrapper = document.getElementById('expensesExportWrapper');
    if (!wrapper) return;
    const printWindow = window.open('', '', 'height=600,width=900');
    printWindow.document.write('<html><head><title>Cantil-E Expenses</title>');
    printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
    printWindow.document.write('</head><body>');
    printWindow.document.write(wrapper.innerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => printWindow.print(), 500);
}

// Export Expenses Table as PDF
function exportExpensesPDF() {
    const wrapper = document.getElementById('expensesExportWrapper');
    if (!wrapper) return;
    // Temporarily show the export table
    wrapper.style.display = 'block';
    const opt = {
        margin:       0.5,
        filename:     'cantil-e-expenses.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2 },
        jsPDF:        { unit: 'in', format: 'letter', orientation: 'landscape' }
    };
    html2pdf().from(wrapper).set(opt).save().then(() => {
        // Hide the export table again
        wrapper.style.display = 'none';
    });
}
</script>
<style>
@media print {
    body * { visibility: hidden !important; }
    .card-body, .card-body * { visibility: visible !important; }
    .sidebar, .navbar, .btn, .alert, .nav, .main-content > :not(.card) { display: none !important; }
    .card-body { position: absolute; left: 0; top: 0; width: 100vw; background: white; }
}
</style>
@endpush
