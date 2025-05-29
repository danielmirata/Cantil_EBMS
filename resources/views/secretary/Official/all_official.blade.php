@extends('layouts.view_resident')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/shared-dashboard.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h1 class="dashboard-title">Official Management</h1>
        <div class="dashboard-subtitle">Manage and organize barangay officials</div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="stats-card blue-card">
                <div>
                    <div class="number">{{ $officials->count() }}</div>
                    <div class="label">Total Officials</div>
                </div>
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card yellow-card">
                <div>
                    <div class="number">{{ $officials->where('status', 'Active')->count() }}</div>
                    <div class="label">Active Officials</div>
                </div>
                <i class="fas fa-user-check"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card green-card">
                <div>
                    <div class="number">{{ $officials->where('status', 'Inactive')->count() }}</div>
                    <div class="label">Inactive Officials</div>
                </div>
                <i class="fas fa-user-times"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card red-card">
                <div>
                    <div class="number">{{ $officials->where('created_at', '>=', now()->subDays(7))->count() }}</div>
                    <div class="label">New This Week</div>
                </div>
                <i class="fas fa-user-plus"></i>
            </div>
        </div>
    </div>

    <!-- Officials Table Card -->
    <div class="content-card mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>All Officials</h2>
            <div class="d-flex">
                <input type="text" id="searchInput" class="form-control search-input me-2" placeholder="Search officials...">
                <a href="{{ route('officials.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Add New Official
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Status</th>
                        <th>Contact Number</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($officials as $official)
                        <tr>
                            <td>{{ $official->first_name }} {{ $official->last_name }}</td>
                            <td>{{ $official->position->position_name }}</td>
                            <td>
                                <span class="badge bg-{{ $official->status === 'Active' ? 'success' : 'warning' }}">
                                    {{ $official->status }}
                                </span>
                            </td>
                            <td>{{ $official->contact_number }}</td>
                            <td class="text-center action-buttons">
                                <button type="button" class="btn btn-info btn-sm view-official" data-id="{{ $official->id }}" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <form action="{{ route('officials.archive', $official->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-warning btn-sm archive-btn" data-id="{{ $official->id }}" title="Archive">
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
                                    <p>No officials found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Official Modal -->
<div class="modal fade" id="officialModal" tabindex="-1" aria-labelledby="officialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="officialModalLabel">
                    <i class="fas fa-user-circle me-2"></i>Official Information
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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateInfoModalLabel">Update Official Information</h5>
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
        });        // Handle view official button clicks
        $('.view-official').on('click', function() {
            const officialId = $(this).data('id');
            // Load official information via AJAX
            $.get(`/secretary/officials/${officialId}`, function(data) {
                $('#officialModal').data('official-id', officialId);  // Store the ID in the modal
                $('#officialModal .modal-body').html(data);
                $('#officialModal').modal('show');
            });
        });

        // Handle update info button click
        $('#updateInfoBtn').on('click', function() {
            const officialId = $('#officialModal').data('official-id');  // Get the ID from the modal
            if (!officialId) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Could not find official information.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }
            // Load update form via AJAX
            $.ajax({
                url: `/secretary/officials/${officialId}/edit`,
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    $('#updateInfoModal .modal-body').html(data);
                    $('#updateInfoForm').attr('action', `/secretary/officials/${officialId}/update-info`);
                    $('#updateInfoModal').modal('show');
                },
                error: function(xhr) {
                    console.error('Error loading form:', xhr.responseText);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to load update form. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Handle update info form submission via AJAX
        $('#updateInfoForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const url = form.attr('action');
            const method = form.attr('method');
            const formData = new FormData(form[0]);

            $.ajax({
                url: url,
                method: method,
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Close the update modal
                    $('#updateInfoModal').modal('hide');

                    // Show success notification
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });

                    // Reload the official details in the view modal
                    const officialId = $('#officialModal').data('official-id');
                    if (officialId) {
                        $.get(`/secretary/officials/${officialId}`, function(data) {
                            $('#officialModal .modal-body').html(data);
                            $('#officialModal').modal('show');
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Error updating official info:', xhr.responseText);
                    const errors = xhr.responseJSON.errors;
                    let errorMessages = 'An error occurred.';
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

        // Handle update photo form submission via AJAX
        $('#updatePhotoForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const officialId = $('#officialModal').data('official-id');
            
            if (!officialId) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Could not find official information to update photo.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

            const formData = new FormData(form[0]);
            
            $.ajax({
                url: '{{ route('officials.update-profile-picture', ':officialId') }}'.replace(':officialId', officialId),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Close the photo update modal
                    $('#updatePhotoModal').modal('hide');

                    // Show success notification
                    Swal.fire({
                        title: 'Success!',
                        text: response.message || 'Profile picture updated successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });

                    // Reload the official details in the view modal
                    $.get(`/secretary/officials/${officialId}`, function(data) {
                        $('#officialModal .modal-body').html(data);
                        $('#officialModal').modal('show');
                    });
                },
                error: function(xhr) {
                    console.error('Error updating profile picture:', xhr.responseText);
                    const errors = xhr.responseJSON.errors;
                    let errorMessages = 'An error occurred while updating the profile picture.';
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

        // Handle archive button clicks
        const archiveButtons = document.querySelectorAll('.archive-btn');
        archiveButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Archive Official',
                    text: 'Are you sure you want to archive this official?',
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