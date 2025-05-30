@extends('layouts.user_layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/shared-dashboard.css') }}">
<style>
.action-buttons .action-btn {
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
    margin-right: 6px;
    padding: 4px 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.action-buttons .action-btn:last-child {
    margin-right: 0;
}
.action-buttons .action-btn:hover, .action-buttons .action-btn:focus {
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    opacity: 0.9;
}
.action-buttons .action-btn i {
    font-size: 1.1em;
    vertical-align: middle;
}
.action-buttons .btn-square {
    width: 40px;
    height: 40px;
    padding: 0;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.action-buttons .action-btn i {
    font-size: 1.3em;
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h1 class="dashboard-title">Administrator Management</h1>
        <div class="dashboard-subtitle">Manage administrators, officials, and captains</div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="stats-card blue-card">
                <div>
                    <div class="number">{{ $users->count() }}</div>
                    <div class="label">Total Accounts</div>
                </div>
                <i class="fas fa-users-cog"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card yellow-card">
                <div>
                    <div class="number">{{ $users->where('account_type', 'administrator')->count() }}</div>
                    <div class="label">Administrators</div>
                </div>
                <i class="fas fa-user-shield"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card green-card">
                <div>
                    <div class="number">{{ $users->where('account_type', 'official')->count() }}</div>
                    <div class="label">Officials</div>
                </div>
                <i class="fas fa-user-tie"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card red-card">
                <div>
                    <div class="number">{{ $users->where('account_type', 'captain')->count() }}</div>
                    <div class="label">Captains</div>
                </div>
                <i class="fas fa-user"></i>
            </div>
        </div>
    </div>

    <!-- Users Table Card -->
    <div class="content-card mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>User Accounts</h2>
            <div class="d-flex">
                <input type="text" id="searchInput" class="form-control search-input me-2" placeholder="Search users...">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fas fa-plus"></i> Add New User
                </button>
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
                            <span class="badge bg-{{ $user->account_type === 'administrator' ? 'primary' : ($user->account_type === 'official' ? 'success' : 'warning') }}">
                                {{ ucfirst($user->account_type) }}
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
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            <div class="empty-state">
                                <i class="fas fa-users-cog"></i>
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

<!-- Edit Modal -->
@foreach($users as $user)
<div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $user->id }}">Edit User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" value="{{ $user->fullname }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="account_type" class="form-label">Account Type</label>
                        <select class="form-select" id="account_type" name="account_type" required>
                            <option value="administrator" {{ $user->account_type == 'administrator' ? 'selected' : '' }}>Administrator</option>
                            <option value="official" {{ $user->account_type == 'official' ? 'selected' : '' }}>Official</option>
                            <option value="captain" {{ $user->account_type == 'captain' ? 'selected' : '' }}>Captain</option>
                        </select>
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
            <form action="{{ route('user.change-password', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
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

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" required>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <div class="mb-3">
                        <label for="account_type" class="form-label">Account Type</label>
                        <select class="form-select" id="account_type" name="account_type" required>
                            <option value="administrator">Administrator</option>
                            <option value="official">Official</option>
                            <option value="captain">Captain</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Search functionality
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("table tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // Handle Add User Form Submission
    $('#addUserModal form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const url = form.attr('action');
        const formData = new FormData(this);

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Close the modal
                $('#addUserModal').modal('hide');
                
                // Show success message
                Swal.fire({
                    title: 'Success!',
                    text: 'User created successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Reload the page to show the new user
                    window.location.reload();
                });
            },
            error: function(xhr) {
                console.error('Error creating user:', xhr.responseText);
                const errors = xhr.responseJSON.errors;
                let errorMessages = 'An error occurred while creating the user.';
                if (errors) {
                    errorMessages = Object.values(errors).join('<br>');
                }

                Swal.fire({
                    title: 'Error!',
                    html: errorMessages,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Enable Bootstrap tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endsection
