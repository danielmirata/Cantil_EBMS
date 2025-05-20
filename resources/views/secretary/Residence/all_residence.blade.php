@extends('layouts.view_resident')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/shared-dashboard.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('content')
<div class="container-fluid">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h1 class="dashboard-title">Resident Management</h1>
        <div class="dashboard-subtitle">Manage and organize barangay residents</div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="stats-card blue-card">
                <div>
                    <div class="number">{{ $residents->count() }}</div>
                    <div class="label">Total Residents</div>
                </div>
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card yellow-card">
                <div>
                    <div class="number">{{ $residents->where('residency_status', 'Permanent')->count() }}</div>
                    <div class="label">Permanent Residents</div>
                </div>
                <i class="fas fa-home"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card green-card">
                <div>
                    <div class="number">{{ $residents->where('voters', 'Yes')->count() }}</div>
                    <div class="label">Registered Voters</div>
                </div>
                <i class="fas fa-vote-yea"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card red-card">
                <div>
                    <div class="number">{{ $residents->where('created_at', '>=', now()->subDays(7))->count() }}</div>
                    <div class="label">New This Week</div>
                </div>
                <i class="fas fa-user-plus"></i>
            </div>
        </div>
    </div>

    <!-- Residents Table Card -->
    <div class="content-card mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>All Residents</h2>
            <div class="d-flex">
                <input type="text" id="searchInput" class="form-control search-input me-2" placeholder="Search residents...">
                <a href="{{ route('secretary.residence.new') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Add New Resident
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Residency Status</th>
                        <th>Contact Number</th>
                        <th>Address</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($residents as $resident)
                        <tr>
                            <td>{{ $resident->first_name }} {{ $resident->last_name }}</td>
                            <td>
                                <span class="badge bg-{{ $resident->residency_status === 'Permanent' ? 'success' : 'warning' }}">
                                    {{ $resident->residency_status }}
                                </span>
                            </td>
                            <td>{{ $resident->contact_number }}</td>
                            <td>{{ $resident->house_number }}, {{ $resident->street }}</td>
                            <td class="text-center action-buttons">
                                <button type="button" class="btn btn-info btn-sm view-resident" data-id="{{ $resident->id }}" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <form action="{{ route('secretary.residence.archive', $resident->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-warning btn-sm archive-btn" data-id="{{ $resident->id }}" title="Archive">
                                        <i class="fas fa-archive"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-users"></i>
                                    <p>No residents found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Resident Modal -->
<div class="modal fade" id="residentModal" tabindex="-1" aria-labelledby="residentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="residentModalLabel">
                    <i class="fas fa-user-circle me-2"></i>Resident Information
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="updateInfoBtn">
                    <i class="fas fa-file-certificate me-1"></i> Update Information
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Update Photo Modal -->
<div class="modal fade" id="updatePhotoModal" tabindex="-1" aria-labelledby="updatePhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updatePhotoModalLabel">Update Profile Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updatePhotoForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="profile_picture" class="form-label">Select New Profile Picture</label>
                        <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*" required>
                        <small class="text-muted">Allowed formats: JPEG, PNG, JPG. Max size: 2MB</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Photo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Information Modal -->
<div class="modal fade" id="updateInfoModal" tabindex="-1" aria-labelledby="updateInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateInfoModalLabel">Update Resident Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateInfoForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <!-- Content will be loaded dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Information</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        $("#searchInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("table tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        // Handle view resident button clicks
        $(document).on('click', '.view-resident', function() {
            const residentId = $(this).data('id');
            // Load resident information via AJAX (fixed URL)
            $.get(`/secretary/residence/${residentId}`, function(data) {
                $('#residentModal .modal-body').html(data);
                $('#residentModal').modal('show');
            }).fail(function(xhr) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to load resident details. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        });

        // Handle update info button click
        $(document).on('click', '#updateInfoBtn', function() {
            const residentId = $(this).data('id');
            // Load update form via AJAX
            $.get(`/secretary/residents/${residentId}/edit`, function(data) {
                $('#updateInfoModal .modal-body').html(data);
                $('#updateInfoForm').attr('action', `/secretary/residents/${residentId}`);
                $('#updateInfoModal').modal('show');
            });
        });

        // Handle archive button clicks
        const archiveButtons = document.querySelectorAll('.archive-btn');
        archiveButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Archive Resident',
                    text: 'Are you sure you want to archive this resident?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, archive it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Show success message if exists
        @if(session('success'))
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif

        // Show error message if exists
        @if(session('error'))
            Swal.fire({
                title: 'Error!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        @endif
    });
</script>
@endpush
