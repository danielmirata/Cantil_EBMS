@extends('layouts.schd_layout')

@section('title', 'Schedule Management')

@section('content')
<div class="container-fluid">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h1 class="dashboard-title">Schedule Management</h1>
        <div class="dashboard-subtitle">Manage and organize barangay schedules and events</div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="stats-card blue-card">
                <div>
                    <div class="number">{{ $schedules->count() }}</div>
                    <div class="label">Total Schedules</div>
                </div>
                <i class="fas fa-calendar"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card yellow-card">
                <div>
                    <div class="number">{{ $schedules->where('date', '>=', now()->format('Y-m-d'))->count() }}</div>
                    <div class="label">Upcoming Events</div>
                </div>
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card green-card">
                <div>
                    <div class="number">{{ $schedules->where('date', '<', now()->format('Y-m-d'))->count() }}</div>
                    <div class="label">Past Events</div>
                </div>
                <i class="fas fa-history"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card red-card">
                <div>
                    <div class="number">{{ $schedules->where('date', now()->format('Y-m-d'))->count() }}</div>
                    <div class="label">Today's Events</div>
                </div>
                <i class="fas fa-calendar-day"></i>
            </div>
        </div>
    </div>

    <!-- Schedule Table Card -->
    <div class="content-card mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Schedules</h2>
            <div class="d-flex">
                <input type="text" id="searchInput" class="form-control me-2" placeholder="Search schedules...">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                    <i class="fas fa-plus"></i> Add New Schedule
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Venue</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($schedules as $schedule)
                        <tr>
                            <td class="align-middle">{{ $schedule->title }}</td>
                            <td class="align-middle">{{ Str::limit($schedule->description, 50) }}</td>
                            <td class="align-middle">{{ \Carbon\Carbon::parse($schedule->date)->format('F d, Y') }}</td>
                            <td class="align-middle">{{ \Carbon\Carbon::parse($schedule->time)->format('h:i A') }}</td>
                            <td class="align-middle">{{ $schedule->venue }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-warning" title="Edit" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editScheduleModal"
                                        data-schedule="{{ json_encode($schedule) }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger delete-schedule" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-calendar-times fa-2x mb-3"></i>
                                    <p class="mb-0">No schedules available.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="content-card mt-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Schedule Calendar</h5>
        </div>
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
</div>

<!-- Add Schedule Modal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1" aria-labelledby="addScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addScheduleModalLabel">Add New Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('schedules.store') }}" method="POST" id="addScheduleForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                        <div class="col-md-6">
                            <label for="time" class="form-label">Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="time" name="time" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="venue" class="form-label">Venue <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="venue" name="venue" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Schedule Modal -->
<div class="modal fade" id="editScheduleModal" tabindex="-1" aria-labelledby="editScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editScheduleModalLabel">Edit Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editScheduleForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_date" class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="edit_date" name="date" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_time" class="form-label">Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="edit_time" name="time" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_venue" class="form-label">Venue <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_venue" name="venue" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
<style>
    /* Dashboard Header Styles */
    .dashboard-header {
        margin-bottom: 2rem;
    }
    .dashboard-title {
        font-size: 1.75rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #333;
    }
    .dashboard-subtitle {
        color: #6c757d;
        font-size: 1rem;
    }

    /* Stats Cards Styles */
    .stats-card {
        padding: 1.5rem;
        border-radius: 10px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .stats-card .number {
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    .stats-card .label {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    .stats-card i {
        font-size: 2.5rem;
        opacity: 0.8;
    }
    .blue-card {
        background: linear-gradient(45deg, #2196F3, #1976D2);
    }
    .yellow-card {
        background: linear-gradient(45deg, #FFC107, #FFA000);
    }
    .green-card {
        background: linear-gradient(45deg, #4CAF50, #388E3C);
    }
    .red-card {
        background: linear-gradient(45deg, #F44336, #D32F2F);
    }

    /* Content Card Styles */
    .content-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .content-card h2 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #333;
    }

    /* Table Styles */
    .table {
        margin-bottom: 0;
    }
    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }
    .table td {
        vertical-align: middle;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(33, 150, 243, 0.05);
    }

    /* Button Styles */
    .btn-group .btn {
        margin: 0 2px;
    }
    .btn-warning {
        color: #fff;
    }
    .btn-warning:hover {
        color: #fff;
    }

    /* Modal Styles */
    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
    .modal-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }

    /* Calendar Styles */
    #calendar {
        margin: 20px 0;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .fc-event {
        cursor: pointer;
        padding: 2px 4px;
    }
    .fc-event-title {
        font-weight: 500;
    }
    .event-details-modal .modal-body {
        padding: 20px;
    }
    .event-details-modal .event-info {
        margin-bottom: 15px;
    }
    .event-details-modal .event-info label {
        font-weight: 600;
        color: #555;
        margin-bottom: 5px;
    }
    .event-details-modal .event-info p {
        margin-bottom: 0;
        color: #333;
    }

    /* Search Input Styles */
    #searchInput {
        border-radius: 20px;
        padding-left: 1rem;
        padding-right: 1rem;
        border: 1px solid #dee2e6;
    }
    #searchInput:focus {
        border-color: #2196F3;
        box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.25);
    }

    /* Responsive Calendar */
    #calendar {
        height: 500px;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        touch-action: pan-y;
        overscroll-behavior: contain;
    }
</style>
@endsection

@section('scripts')
<!-- jQuery (required for Bootstrap and tooltips) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date to today for new schedule
    const dateInput = document.getElementById('date');
    const today = new Date().toISOString().split('T')[0];
    dateInput.min = today;

    // Setup CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize FullCalendar
    const calendarEl = document.getElementById('calendar');
    const calendar = new window.FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: [
            @foreach($schedules as $schedule)
            {
                id: '{{ $schedule->id }}',
                title: '{{ $schedule->title }}',
                start: '{{ $schedule->date }}T{{ $schedule->time }}',
                description: '{{ $schedule->description }}',
                venue: '{{ $schedule->venue }}',
                backgroundColor: '#3788d8',
                borderColor: '#3788d8',
                textColor: '#ffffff'
            },
            @endforeach
        ],
        eventClick: function(info) {
            // Create and show event details modal
            const modalHtml = `
                <div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">${info.event.title}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="event-info">
                                    <label>Description:</label>
                                    <p>${info.event.extendedProps.description || 'No description available'}</p>
                                </div>
                                <div class="event-info">
                                    <label>Date & Time:</label>
                                    <p>${info.event.start.toLocaleDateString()} at ${info.event.start.toLocaleTimeString()}</p>
                                </div>
                                <div class="event-info">
                                    <label>Venue:</label>
                                    <p>${info.event.extendedProps.venue || 'No venue specified'}</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-warning edit-event" data-event-id="${info.event.id}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Remove existing modal if any
            const existingModal = document.getElementById('eventDetailsModal');
            if (existingModal) {
                existingModal.remove();
            }

            // Add new modal to body
            document.body.insertAdjacentHTML('beforeend', modalHtml);

            // Initialize and show the modal
            const modal = new bootstrap.Modal(document.getElementById('eventDetailsModal'));
            modal.show();

            // Handle edit button click
            document.querySelector('.edit-event').addEventListener('click', function() {
                modal.hide();
                const editModal = new bootstrap.Modal(document.getElementById('editScheduleModal'));
                const schedule = @json($schedules);
                const event = schedule.find(s => s.id == info.event.id);
                
                if (event) {
                    document.getElementById('edit_title').value = event.title;
                    document.getElementById('edit_description').value = event.description;
                    document.getElementById('edit_date').value = event.date;
                    document.getElementById('edit_time').value = event.time;
                    document.getElementById('edit_venue').value = event.venue;
                    document.getElementById('editScheduleForm').action = `/schedules/${event.id}`;
                    editModal.show();
                }
            });
        },
        eventDidMount: function(info) {
            // Add tooltip
            info.el.title = info.event.title;
        }
    });

    calendar.render();

    // Handle add schedule form submission
    $('#addScheduleForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#addScheduleModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.reload();
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = 'An error occurred while creating the schedule.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage
                });
            }
        });
    });

    // Handle edit schedule form submission
    $('#editScheduleForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#editScheduleModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.reload();
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = 'An error occurred while updating the schedule.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage
                });
            }
        });
    });

    // Handle delete schedule
    $('.delete-schedule').on('click', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: form.attr('action'),
                    method: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred while deleting the schedule.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
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
    });

    // Reset form when modal is closed
    $('#addScheduleModal').on('hidden.bs.modal', function() {
        $('#addScheduleForm')[0].reset();
        $('#addScheduleForm').removeClass('was-validated');
    });

    $('#editScheduleModal').on('hidden.bs.modal', function() {
        $('#editScheduleForm')[0].reset();
        $('#editScheduleForm').removeClass('was-validated');
    });
});
</script>
@endsection
