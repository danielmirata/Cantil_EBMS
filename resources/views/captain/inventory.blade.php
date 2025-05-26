@extends('layouts.cap_inventory')

@section('title', 'Barangay Cantil-E - Inventory & Expenses View')

@section('content')
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h1 class="dashboard-title">Inventory & Expenses View</h1>
        <div class="dashboard-subtitle">View and print barangay inventory and expenses</div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="stats-card blue-card">
                <div>
                    <div class="number">₱{{ number_format($totalBudget, 2) }}</div>
                    <div class="label">Total Budget</div>
                </div>
                <i class="fas fa-wallet"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card green-card">
                <div>
                    <div class="number">₱{{ number_format($remainingBudget, 2) }}</div>
                    <div class="label">Available Balance</div>
                </div>
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card yellow-card">
                <div>
                    <div class="number">₱{{ number_format($totalExpenses, 2) }}</div>
                    <div class="label">Total Expenses</div>
                </div>
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card red-card">
                <div>
                    <div class="number">₱{{ number_format($pendingExpenses, 2) }}</div>
                    <div class="label">Pending Expenses</div>
                </div>
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>

    <!-- Expenses List -->
    <div class="content-card mt-4 print-content">
        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
            <h2>Expenses List</h2>
            <div class="d-flex gap-2">
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search expenses...">
                </div>
                <button type="button" class="btn btn-primary d-flex align-items-center gap-2" onclick="window.print()">
                    <i class="fas fa-print"></i>
                    <span>Print Expenses</span>
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="expensesTable">
                <thead>
                    <tr class="bg-primary text-white">
                        <th class="align-middle">DATE</th>
                        <th class="align-middle">DESCRIPTION</th>
                        <th class="align-middle">CATEGORY</th>
                        <th class="align-middle">LOCATION</th>
                        <th class="align-middle">BENEFICIARY</th>
                        <th class="align-middle">BUDGET ALLOCATION</th>
                        <th class="align-middle">AMOUNT</th>
                        <th class="align-middle">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                    <tr>
                        <td>{{ date('M d, Y', strtotime($expense->date)) }}</td>
                        <td class="font-weight-bold">{{ Str::limit($expense->description, 50) }}</td>
                        <td>
                            <span class="badge badge-light text-capitalize px-3 py-2" style="font-size:0.95rem;">
                                {{ $expense->category }}
                            </span>
                        </td>
                        <td>{{ $expense->location }}</td>
                        <td>{{ $expense->beneficiary ?? 'N/A' }}</td>
                        <td>{{ $expense->budget_allocation ?? 'N/A' }}</td>
                        <td class="font-weight-bold">₱{{ number_format($expense->amount, 2) }}</td>
                        <td>
                            @php
                                $statusClass = [
                                    'Approved' => 'success',
                                    'Pending' => 'warning',
                                    'Rejected' => 'danger'
                                ][$expense->status] ?? 'secondary';
                                
                                $statusIcon = [
                                    'Approved' => 'check-circle',
                                    'Pending' => 'clock',
                                    'Rejected' => 'times-circle'
                                ][$expense->status] ?? 'question-circle';
                            @endphp
                            
                            <span class="badge bg-{{ $statusClass }}">
                                <i class="fas fa-{{ $statusIcon }} mr-1"></i>
                                {{ $expense->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="my-3 text-muted">
                                <i class="fas fa-receipt fa-3x mb-3"></i>
                                <p>No expenses found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white no-print">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted">Showing {{ $expenses->firstItem() ?? 0 }} to {{ $expenses->lastItem() ?? 0 }} of {{ $expenses->total() }} expenses</span>
                </div>
                <div>
                    {{ $expenses->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#expensesTable').DataTable({
            responsive: true,
            autoWidth: false,
            searching: false,
            paging: false,
            info: false
        });

        // Handle search functionality
        $('#searchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $("table tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
@endsection
