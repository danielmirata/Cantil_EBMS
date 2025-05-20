<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Barangay Cantil-E - Activity Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/secretary-dashboard.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            <h1 class="dashboard-title">Activity Logs</h1>
            <div class="dashboard-subtitle">Track and monitor system activities</div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="stats-card blue-card">
                    <div>
                        <div class="number">{{ $stats['total_activities'] ?? 0 }}</div>
                        <div class="label">Total Activities</div>
                    </div>
                    <i class="fas fa-history"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card yellow-card">
                    <div>
                        <div class="number">{{ $stats['today_activities'] ?? 0 }}</div>
                        <div class="label">Today's Activities</div>
                    </div>
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card green-card">
                    <div>
                        <div class="number">{{ $stats['user_activities'] ?? 0 }}</div>
                        <div class="label">User Activities</div>
                    </div>
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card red-card">
                    <div>
                        <div class="number">{{ $stats['system_activities'] ?? 0 }}</div>
                        <div class="label">System Activities</div>
                    </div>
                    <i class="fas fa-cogs"></i>
                </div>
            </div>
        </div>

        <!-- Activity Logs Table -->
        <div class="content-card mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Activity Logs List</h2>
                <div class="d-flex">
                    <input type="text" id="searchInput" class="form-control me-2" placeholder="Search activity logs...">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            Filter by Type
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" data-filter="all">All Activities</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="user">User Activities</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="system">System Activities</a></li>
                        </ul>
                    </div>
                    <a href="{{ route('secretary.activity-logs.create') }}" class="btn btn-success me-2">
                        <i class="fas fa-plus"></i> Create Log
                    </a>
                    <button type="button" class="btn btn-primary" onclick="exportLogs()">
                        <i class="fas fa-download"></i> Export Logs
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Transaction Type</th>
                            <th>Resident Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Processed By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $activity)
                        <tr>
                            <td>{{ $activity->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                <span class="badge bg-{{ getActivityTypeColor($activity->transaction_type) }}">
                                    {{ $activity->transaction_type }}
                                </span>
                            </td>
                            <td>{{ $activity->resident_name }}</td>
                            <td>{{ $activity->description }}</td>
                            <td>
                                <span class="badge bg-{{ getStatusColor($activity->status) }}">
                                    {{ $activity->status }}
                                </span>
                            </td>
                            <td>{{ $activity->processed_by }}</td>
                            <td>
                                <button class="btn btn-sm btn-info view-activity" data-bs-toggle="modal" data-bs-target="#viewModal" data-activity="{{ json_encode($activity) }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No activity logs found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $activities->links() }}
            </div>
        </div>
    </div>

    <!-- View Activity Details Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Activity Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body print-content">
                    <div class="text-center mb-4">
                        <h4>BARANGAY CANTIL-E</h4>
                        <h5>ACTIVITY LOG DETAILS</h5>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Date & Time:</strong> <span id="view-datetime"></span></div>
                        <div class="col-md-6"><strong>Transaction Type:</strong> <span id="view-type"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Resident Name:</strong> <span id="view-resident"></span></div>
                        <div class="col-md-6"><strong>Status:</strong> <span id="view-status"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12"><strong>Description:</strong> <span id="view-description"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12"><strong>Processed By:</strong> <span id="view-processed-by"></span></div>
                    </div>
                </div>
                <div class="modal-footer no-print">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print"></i> Print Details
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Add CSRF token to all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Handle view modal data
            $('.view-activity').on('click', function() {
                var activityData = $(this).data('activity');
                
                $('#view-datetime').text(moment(activityData.created_at).format('MMMM D, YYYY h:mm A'));
                $('#view-type').text(activityData.transaction_type);
                $('#view-resident').text(activityData.resident_name);
                $('#view-status').text(activityData.status);
                $('#view-description').text(activityData.description);
                $('#view-processed-by').text(activityData.processed_by);
            });

            // Handle search functionality
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $("table tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            // Handle filter functionality
            $('.dropdown-item[data-filter]').on('click', function(e) {
                e.preventDefault();
                var filter = $(this).data('filter');
                
                if (filter === 'all') {
                    $("table tbody tr").show();
                } else {
                    $("table tbody tr").hide();
                    $("table tbody tr").each(function() {
                        var type = $(this).find('td:nth-child(2)').text().trim().toLowerCase();
                        if (type === filter) {
                            $(this).show();
                        }
                    });
                }
            });
        });

        // Function to export logs
        function exportLogs() {
            window.location.href = '{{ route("secretary.activity-logs.export") }}';
        }
    </script>
</body>
</html>

@php
function getActivityTypeColor($type) {
    switch (strtolower($type)) {
        case 'user':
            return 'primary';
        case 'system':
            return 'info';
        case 'error':
            return 'danger';
        case 'warning':
            return 'warning';
        case 'success':
            return 'success';
        default:
            return 'secondary';
    }
}

function getStatusColor($status) {
    switch (strtolower($status)) {
        case 'pending':
            return 'warning';
        case 'completed':
            return 'success';
        case 'cancelled':
            return 'danger';
        default:
            return 'secondary';
    }
}
@endphp
