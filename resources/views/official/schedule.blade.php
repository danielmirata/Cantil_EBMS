@extends('layouts.official')

@section('title', 'Schedule Management')

@section('content')
<div class="container-fluid">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h1 class="dashboard-title">Schedule Management</h1>
        <div class="dashboard-subtitle">View and manage barangay schedules and events</div>
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
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

<!-- Event Details Modal -->
<div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Event Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="event-info">
                    <label>Title:</label>
                    <p id="eventTitle"></p>
                </div>
                <div class="event-info">
                    <label>Description:</label>
                    <p id="eventDescription"></p>
                </div>
                <div class="event-info">
                    <label>Date & Time:</label>
                    <p id="eventDateTime"></p>
                </div>
                <div class="event-info">
                    <label>Venue:</label>
                    <p id="eventVenue"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
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
            // Update modal content
            document.getElementById('eventTitle').textContent = info.event.title;
            document.getElementById('eventDescription').textContent = info.event.extendedProps.description || 'No description available';
            document.getElementById('eventDateTime').textContent = `${info.event.start.toLocaleDateString()} at ${info.event.start.toLocaleTimeString()}`;
            document.getElementById('eventVenue').textContent = info.event.extendedProps.venue || 'No venue specified';

            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('eventDetailsModal'));
            modal.show();
        },
        eventDidMount: function(info) {
            // Add tooltip
            info.el.title = info.event.title;
        }
    });

    calendar.render();

    // Search functionality
    $('#searchInput').on('keyup', function() {
        const searchText = $(this).val().toLowerCase();
        $('table tbody tr').each(function() {
            const rowText = $(this).text().toLowerCase();
            $(this).toggle(rowText.indexOf(searchText) > -1);
        });
    });
});
</script>
@endsection
