@extends('layouts.admin_layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/shared-dashboard.css') }}">
<style>
    .form-error {
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 0.25rem;
    }
    .password-requirements {
        font-size: 0.875em;
        color: #6c757d;
        margin-top: 0.5rem;
    }
    .password-requirements ul {
        padding-left: 1.2rem;
        margin-bottom: 0;
    }
    .password-requirements li {
        margin-bottom: 0.25rem;
    }
    .password-requirements li.valid {
        color: #198754;
    }
    .password-requirements li.invalid {
        color: #dc3545;
    }
    .filter-section {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }
    .filter-section .form-group {
        margin-bottom: 0.5rem;
    }
    .filter-section label {
        font-weight: 500;
        margin-bottom: 0.25rem;
    }
    .filter-buttons {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    .active-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }
    .filter-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        background: #e9ecef;
        border-radius: 0.25rem;
        font-size: 0.875rem;
    }
    .filter-badge .remove-filter {
        cursor: pointer;
        color: #6c757d;
    }
    .filter-badge .remove-filter:hover {
        color: #dc3545;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h1 class="dashboard-title">Account Management</h1>
        <div class="dashboard-subtitle">Manage all system accounts and permissions</div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="stats-card blue-card">
                <div>
                    <div class="number">{{ $users->count() }}</div>
                    <div class="label">Total Accounts</div>
                </div>
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card yellow-card">
                <div>
                    <div class="number">{{ $users->where('account_type', 'secretary')->count() }}</div>
                    <div class="label">Secretaries</div>
                </div>
                <i class="fas fa-user-tie"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card green-card">
                <div>
                    <div class="number">{{ $users->where('account_type', 'resident')->count() }}</div>
                    <div class="label">Residents</div>
                </div>
                <i class="fas fa-user"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card red-card">
                <div>
                    <div class="number">{{ $users->where('status', 'inactive')->count() }}</div>
                    <div class="label">Inactive Accounts</div>
                </div>
                <i class="fas fa-user-slash"></i>
            </div>
        </div>
    </div>

    <!-- Users Table Card -->
    <div class="content-card mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>User Accounts</h2>
            <div class="d-flex">
                <button type="button" class="btn btn-outline-primary me-2" data-bs-toggle="collapse" data-bs-target="#filterSection">
                    <i class="fas fa-filter"></i> Filters
                </button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fas fa-plus"></i> Add New User
                </button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="collapse" id="filterSection">
            <div class="filter-section">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="searchInput">Search</label>
                            <input type="text" id="searchInput" class="form-control" placeholder="Search by name, username, or email...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="accountTypeFilter">Account Type</label>
                            <select class="form-select" id="accountTypeFilter">
                                <option value="">All Account Types</option>
                                <option value="secretary">Secretary</option>
                                <option value="resident">Resident</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="statusFilter">Status</label>
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="filter-buttons">
                    <button type="button" class="btn btn-primary" onclick="applyFilters()">
                        <i class="fas fa-check"></i> Apply Filters
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="resetFilters()">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                </div>
                <div class="active-filters" id="activeFilters"></div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Account Type</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->fullname }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-{{ $user->account_type === 'secretary' ? 'primary' : 'success' }}">
                                {{ ucfirst($user->account_type) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="text-center action-buttons">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $user->id }}" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#passwordModal{{ $user->id }}" title="Change Password">
                                <i class="fas fa-key"></i>
                            </button>
                            <button type="button" class="btn btn-{{ $user->status === 'active' ? 'danger' : 'success' }} btn-sm" 
                                    onclick="toggleUserStatus({{ $user->id }}, '{{ $user->status }}')" 
                                    title="{{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                <i class="fas fa-{{ $user->status === 'active' ? 'ban' : 'check' }}"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            <div class="empty-state">
                                <i class="fas fa-users"></i>
                                <p>No users found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @include('auth.register', ['isModal' => true])
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
@foreach($users as $user)
<div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $user->id }}">Edit User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Full Name</label>
                        <input type="text" class="form-control @error('fullname') is-invalid @enderror" 
                               id="fullname" name="fullname" value="{{ old('fullname', $user->fullname) }}" required>
                        @error('fullname')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" 
                               id="username" name="username" value="{{ old('username', $user->username) }}" required>
                        @error('username')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="account_type" class="form-label">Account Type</label>
                        <select class="form-select @error('account_type') is-invalid @enderror" 
                                id="account_type" name="account_type" required>
                            <option value="secretary" {{ old('account_type', $user->account_type) == 'secretary' ? 'selected' : '' }}>Secretary</option>
                            <option value="resident" {{ old('account_type', $user->account_type) == 'resident' ? 'selected' : '' }}>Resident</option>
                        </select>
                        @error('account_type')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="passwordModal{{ $user->id }}" tabindex="-1" aria-labelledby="passwordModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordModalLabel{{ $user->id }}">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.users.change-password', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                               id="new_password" name="new_password" required>
                        @error('new_password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" 
                               id="new_password_confirmation" name="new_password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize filters
    let activeFilters = {
        search: '',
        accountType: '',
        status: ''
    };

    // Function to update active filters display
    function updateActiveFilters() {
        const activeFiltersContainer = $('#activeFilters');
        activeFiltersContainer.empty();

        if (activeFilters.search) {
            activeFiltersContainer.append(`
                <div class="filter-badge">
                    Search: ${activeFilters.search}
                    <span class="remove-filter" onclick="removeFilter('search')">&times;</span>
                </div>
            `);
        }

        if (activeFilters.accountType) {
            activeFiltersContainer.append(`
                <div class="filter-badge">
                    Account Type: ${activeFilters.accountType}
                    <span class="remove-filter" onclick="removeFilter('accountType')">&times;</span>
                </div>
            `);
        }

        if (activeFilters.status) {
            activeFiltersContainer.append(`
                <div class="filter-badge">
                    Status: ${activeFilters.status}
                    <span class="remove-filter" onclick="removeFilter('status')">&times;</span>
                </div>
            `);
        }
    }

    // Function to apply filters
    window.applyFilters = function() {
        activeFilters.search = $('#searchInput').val().toLowerCase();
        activeFilters.accountType = $('#accountTypeFilter').val();
        activeFilters.status = $('#statusFilter').val();

        $('table tbody tr').each(function() {
            const row = $(this);
            const fullname = row.find('td:eq(0)').text().toLowerCase();
            const username = row.find('td:eq(1)').text().toLowerCase();
            const email = row.find('td:eq(2)').text().toLowerCase();
            const accountType = row.find('td:eq(3) .badge').text().toLowerCase();
            const status = row.find('td:eq(4) .badge').text().toLowerCase();

            let show = true;

            // Search filter
            if (activeFilters.search) {
                show = show && (
                    fullname.includes(activeFilters.search) ||
                    username.includes(activeFilters.search) ||
                    email.includes(activeFilters.search)
                );
            }

            // Account type filter
            if (activeFilters.accountType) {
                show = show && accountType === activeFilters.accountType.toLowerCase();
            }

            // Status filter
            if (activeFilters.status) {
                show = show && status === activeFilters.status.toLowerCase();
            }

            row.toggle(show);
        });

        updateActiveFilters();
    };

    // Function to reset filters
    window.resetFilters = function() {
        $('#searchInput').val('');
        $('#accountTypeFilter').val('');
        $('#statusFilter').val('');
        activeFilters = {
            search: '',
            accountType: '',
            status: ''
        };
        $('table tbody tr').show();
        updateActiveFilters();
    };

    // Function to remove individual filters
    window.removeFilter = function(filterType) {
        switch(filterType) {
            case 'search':
                $('#searchInput').val('');
                activeFilters.search = '';
                break;
            case 'accountType':
                $('#accountTypeFilter').val('');
                activeFilters.accountType = '';
                break;
            case 'status':
                $('#statusFilter').val('');
                activeFilters.status = '';
                break;
        }
        applyFilters();
    };

    // Search input event
    $('#searchInput').on('keyup', function() {
        applyFilters();
    });

    // Filter select events
    $('#accountTypeFilter, #statusFilter').on('change', function() {
        applyFilters();
    });
});

// Toggle user status
function toggleUserStatus(userId, currentStatus) {
    if (confirm('Are you sure you want to ' + (currentStatus === 'active' ? 'deactivate' : 'activate') + ' this user?')) {
        $.ajax({
            url: '/admin/users/' + userId + '/toggle-status',
            type: 'PUT',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    location.reload();
                } else {
                    alert('Failed to update user status.');
                }
            },
            error: function(xhr) {
                alert('An error occurred while updating the user status.');
            }
        });
    }
}
</script>
@endsection 