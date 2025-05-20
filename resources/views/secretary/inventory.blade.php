<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Barangay Cantil-E - Inventory & Expenses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/secretary-dashboard.css') }}">
    <!-- Add SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
    <!-- Add jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Add SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .print-content, .print-content * {
                visibility: visible;
            }
            .print-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
        .btn {
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .btn-group .btn {
            padding: 0.375rem 0.75rem;
        }

        .btn-group .btn:hover {
            transform: none;
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }

        .btn-outline-primary {
            color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-outline-primary:hover {
            background-color: #0d6efd;
            color: white;
        }

        .btn-info {
            background-color: #0dcaf0;
            border-color: #0dcaf0;
        }

        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .input-group {
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .input-group-text {
            border-right: none;
        }

        .input-group .form-control {
            border-left: none;
        }

        .input-group .form-control:focus {
            box-shadow: none;
            border-color: #ced4da;
        }

        .gap-2 {
            gap: 0.5rem;
        }

        .badge {
            font-weight: 500;
            padding: 0.5em 0.75em;
        }

        .badge-pill {
            border-radius: 50rem;
        }

        .badge-success {
            background-color: #198754;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #000;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border-radius: 0.375rem;
            border: 1px solid #ced4da;
            padding: 0.5rem 0.75rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus, .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
        }

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .modal-content {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }

        .modal-footer {
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
        }

        .g-3 {
            --bs-gutter-y: 1rem;
        }
    </style>
</head>
<body>
    @include('partials.secretary-sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <div class="top-nav">
            <div class="dropdown secretary-dropdown">
                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle"></i> Secretary
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="dropdown-item">
                            @csrf
                            <button type="submit" class="btn btn-link p-0"><i class="fas fa-sign-out-alt"></i> Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h1 class="dashboard-title">Inventory & Expenses</h1>
            <div class="dashboard-subtitle">Manage and track barangay inventory and expenses</div>
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

      
        <!-- Filter & Search Section -->
        <div class="content-card mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Expenses List</h2>
                <div class="d-flex gap-2">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Search expenses...">
                    </div>
                    <button type="button" class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                        <i class="fas fa-plus"></i>
                        <span>Add New Expense</span>
                    </button>
                    <button type="button" class="btn btn-outline-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addBudgetModal">
                        <i class="fas fa-wallet"></i>
                        <span>New Budget Entry</span>
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
                            <th class="align-middle">ACTIONS</th>
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
                                
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-{{ $statusClass }} dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-{{ $statusIcon }} mr-1"></i>
                                        {{ $expense->status }}
                                    </button>
                                    <ul class="dropdown-menu">
                                        @if($expense->status !== 'Approved')
                                            <li><a class="dropdown-item" href="#" onclick="updateExpenseStatus({{ $expense->id }}, 'Approved')">
                                                <i class="fas fa-check-circle text-success"></i> Approve
                                            </a></li>
                                        @endif
                                        @if($expense->status !== 'Pending')
                                            <li><a class="dropdown-item" href="#" onclick="updateExpenseStatus({{ $expense->id }}, 'Pending')">
                                                <i class="fas fa-clock text-warning"></i> Set Pending
                                            </a></li>
                                        @endif
                                        @if($expense->status !== 'Rejected')
                                            <li><a class="dropdown-item" href="#" onclick="updateExpenseStatus({{ $expense->id }}, 'Rejected')">
                                                <i class="fas fa-times-circle text-danger"></i> Reject
                                            </a></li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-info text-white" onclick="viewExpense({{ $expense->id }})" data-bs-toggle="tooltip" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning text-white" onclick="editExpense({{ $expense->id }})" data-bs-toggle="tooltip" title="Edit Expense">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger text-white" onclick="deleteExpense({{ $expense->id }})" data-bs-toggle="tooltip" title="Delete Expense">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="my-3 text-muted">
                                    <i class="fas fa-receipt fa-3x mb-3"></i>
                                    <p>No expenses found. Click "Add New Expense" to create one.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white">
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
    </div>

    <!-- Add Expense Modal -->
    <div class="modal fade" id="addExpenseModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        <i class="fas fa-plus-circle"></i>
                        <span>Add New Expense</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('expenses.store') }}" method="POST" id="addExpenseForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label d-flex align-items-center gap-2">
                                        <i class="fas fa-project-diagram"></i>
                                        <span>Project (Optional)</span>
                                    </label>
                                    <select class="form-select" name="project_id">
                                        <option value="">No Project</option>
                                        @foreach($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label d-flex align-items-center gap-2">
                                        <i class="fas fa-calendar"></i>
                                        <span>Date</span>
                                    </label>
                                    <input type="date" class="form-control" name="date" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label d-flex align-items-center gap-2">
                                        <i class="fas fa-align-left"></i>
                                        <span>Description</span>
                                    </label>
                                    <textarea class="form-control" name="description" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label d-flex align-items-center gap-2">
                                        <i class="fas fa-tag"></i>
                                        <span>Category</span>
                                    </label>
                                    <select class="form-select" name="category" required>
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
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label d-flex align-items-center gap-2">
                                        <i class="fas fa-money-bill"></i>
                                        <span>Amount</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" class="form-control" name="amount" step="0.01" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label d-flex align-items-center gap-2">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>Location</span>
                                    </label>
                                    <input type="text" class="form-control" name="location" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label d-flex align-items-center gap-2">
                                        <i class="fas fa-users"></i>
                                        <span>Beneficiary</span>
                                    </label>
                                    <input type="text" class="form-control" name="beneficiary">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label d-flex align-items-center gap-2">
                                        <i class="fas fa-wallet"></i>
                                        <span>Budget Allocation</span>
                                    </label>
                                    <select class="form-select" name="budget_allocation">
                                        <option value="">Select Budget</option>
                                        @foreach($budgets as $budget)
                                        <option value="{{ $budget->category }}">{{ $budget->category }} (₱{{ number_format($budget->remaining_amount, 2) }} remaining)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label d-flex align-items-center gap-2">
                                        <i class="fas fa-file-invoice"></i>
                                        <span>Receipt Number</span>
                                    </label>
                                    <input type="text" class="form-control" name="receipt_number">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label d-flex align-items-center gap-2">
                                        <i class="fas fa-file-upload"></i>
                                        <span>Receipt File</span>
                                    </label>
                                    <input type="file" class="form-control" name="receipt_file" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted">Accepted formats: PDF, JPG, JPEG, PNG</small>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label d-flex align-items-center gap-2">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Status</span>
                                    </label>
                                    <select class="form-select" name="status" required>
                                        <option value="Pending">Pending</option>
                                        <option value="Approved">Approved</option>
                                        <option value="Rejected">Rejected</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary d-flex align-items-center gap-2" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i>
                            <span>Cancel</span>
                        </button>
                        <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                            <i class="fas fa-save"></i>
                            <span>Save Expense</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Budget Modal -->
    <div class="modal fade" id="addBudgetModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        <i class="fas fa-wallet"></i>
                        <span>Add New Budget Entry</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('budget.store') }}" method="POST" id="addBudgetForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label d-flex align-items-center gap-2">
                                        <i class="fas fa-money-bill"></i>
                                        <span>Budget Amount</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" class="form-control" name="amount" step="0.01" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label d-flex align-items-center gap-2">
                                        <i class="fas fa-calendar"></i>
                                        <span>Budget Period</span>
                                    </label>
                                    <select class="form-select" name="period" required>
                                        <option value="">Select Period</option>
                                        <option value="Q1">Q1 (Jan-Mar)</option>
                                        <option value="Q2">Q2 (Apr-Jun)</option>
                                        <option value="Q3">Q3 (Jul-Sep)</option>
                                        <option value="Q4">Q4 (Oct-Dec)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label d-flex align-items-center gap-2">
                                        <i class="fas fa-align-left"></i>
                                        <span>Description</span>
                                    </label>
                                    <textarea class="form-control" name="description" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary d-flex align-items-center gap-2" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i>
                            <span>Cancel</span>
                        </button>
                        <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                            <i class="fas fa-save"></i>
                            <span>Save Budget</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Handle search functionality
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $("table tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });

        // Update Expense Status
        function updateExpenseStatus(expenseId, newStatus) {
            console.log('Updating status:', { expenseId, newStatus });
            
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to change the status to ${newStatus}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/expenses/${expenseId}/status`,
                        type: 'PATCH',
                        data: {
                            status: newStatus,
                            _token: '{{ csrf_token() }}'
                        },
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log('Status update response:', response);
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Updated!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response.message || 'Failed to update status'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Status update error:', {
                                status: status,
                                error: error,
                                response: xhr.responseText
                            });
                            
                            let errorMessage = 'Failed to update status';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                                errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                            }
                            
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: errorMessage
                            });
                        }
                    });
                }
            });
        }

        // View Expense
        function viewExpense(id) {
            $.ajax({
                url: `/expenses/${id}`,
                type: 'GET',
                success: function(response) {
                    Swal.fire({
                        title: 'Expense Details',
                        html: `
                            <div class="text-start">
                                <p><strong>Date:</strong> ${moment(response.date).format('MMMM D, YYYY')}</p>
                                <p><strong>Description:</strong> ${response.description}</p>
                                <p><strong>Category:</strong> ${response.category}</p>
                                <p><strong>Amount:</strong> ₱${parseFloat(response.amount).toFixed(2)}</p>
                                <p><strong>Location:</strong> ${response.location}</p>
                                <p><strong>Beneficiary:</strong> ${response.beneficiary || 'N/A'}</p>
                                <p><strong>Budget Allocation:</strong> ${response.budget_allocation || 'N/A'}</p>
                                <p><strong>Status:</strong> ${response.status}</p>
                                <p><strong>Receipt Number:</strong> ${response.receipt_number || 'N/A'}</p>
                            </div>
                        `,
                        width: '600px'
                    });
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error!',
                        'Failed to load expense details',
                        'error'
                    );
                }
            });
        }

        // Edit Expense
        function editExpense(id) {
            $.ajax({
                url: `/expenses/${id}`,
                type: 'GET',
                success: function(response) {
                    $('#addExpenseModal').modal('show');
                    const form = $('#addExpenseForm');
                    form.attr('action', `/expenses/${id}`);
                    form.find('input[name="_method"]').remove();
                    form.append('<input type="hidden" name="_method" value="PUT">');

                    // Convert date to YYYY-MM-DD if needed
                    let dateValue = response.date;
                    if (dateValue) {
                        dateValue = moment(dateValue).format('YYYY-MM-DD');
                    }

                    form.find('select[name="project_id"]').val(response.project_id);
                    form.find('input[name="date"]').val(dateValue);
                    form.find('textarea[name="description"]').val(response.description);
                    form.find('select[name="category"]').val(response.category);
                    form.find('input[name="amount"]').val(response.amount);
                    form.find('input[name="location"]').val(response.location);
                    form.find('input[name="beneficiary"]').val(response.beneficiary);
                    form.find('select[name="budget_allocation"]').val(response.budget_allocation);
                    form.find('input[name="receipt_number"]').val(response.receipt_number);
                    form.find('select[name="status"]').val(response.status);

                    $('.modal-title span').text('Edit Expense');

                    // Log all form field values after setting them
                    console.log('--- Form values after populating for edit ---');
                    console.log('project_id:', form.find('select[name="project_id"]').val());
                    console.log('date:', form.find('input[name="date"]').val());
                    console.log('description:', form.find('textarea[name="description"]').val());
                    console.log('category:', form.find('select[name="category"]').val());
                    console.log('amount:', form.find('input[name="amount"]').val());
                    console.log('location:', form.find('input[name="location"]').val());
                    console.log('beneficiary:', form.find('input[name="beneficiary"]').val());
                    console.log('budget_allocation:', form.find('select[name="budget_allocation"]').val());
                    console.log('receipt_number:', form.find('input[name="receipt_number"]').val());
                    console.log('status:', form.find('select[name="status"]').val());
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error!',
                        'Failed to load expense details',
                        'error'
                    );
                }
            });
        }

        // Delete Expense
        function deleteExpense(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/expenses/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Deleted!',
                                    'The expense has been deleted.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'Failed to delete expense',
                                'error'
                            );
                        }
                    });
                }
            });
        }

        // Handle form submission for both create and update
        $('#addExpenseForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const url = form.attr('action');
            const method = form.find('input[name="_method"]').val() || 'POST';
            const formData = new FormData(this);

            // Add CSRF token
            formData.append('_token', '{{ csrf_token() }}');

            // Log the form data for debugging
            console.log('Submitting form to:', url);
            console.log('Method:', method);
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            $.ajax({
                url: url,
                type: method,
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Success response:', response);
                    if (response.success) {
                        $('#addExpenseModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message || 'Expense has been saved successfully.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to save expense'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error response:', xhr.responseText);
                    console.error('Status:', status);
                    console.error('Error:', error);
                    
                    let errorMessage = 'Failed to save expense';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMessage
                    });
                }
            });
        });

        // Reset form when modal is closed
        $('#addExpenseModal').on('hidden.bs.modal', function() {
            const form = $('#addExpenseForm');
            form.attr('action', '{{ route("expenses.store") }}');
            form.find('input[name="_method"]').remove();
            form[0].reset();
        });
    </script>
</body>
</html>
