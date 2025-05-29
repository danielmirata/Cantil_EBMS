<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Barangay Cantil-E - System Activity Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    @include('partials.admin-sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <div class="top-nav">
            <div class="dropdown admin-dropdown">
                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle"></i> Admin
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
            <h1 class="dashboard-title">System Activity Logs</h1>
            <div class="dashboard-subtitle">Track and monitor all system activities and transactions</div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Activities</h5>
                        <h2 class="card-text">{{ $stats['total_activities'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Today's Activities</h5>
                        <h2 class="card-text">{{ $stats['today_activities'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">User Activities</h5>
                        <h2 class="card-text">{{ $stats['user_activities'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">System Activities</h5>
                        <h2 class="card-text">{{ $stats['system_activities'] }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Logs Table -->
        <div class="content-card mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Activity Logs</h2>
                <div class="d-flex">
                    <input type="text" id="searchInput" class="form-control me-2" placeholder="Search activities...">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            Filter by Type
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" data-filter="all">All Activities</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="user">User Activities</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="system">System Activities</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="error">Errors</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="warning">Warnings</a></li>
                        </ul>
                    </div>
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
                            <th>Type</th>
                            <th>Description</th>
                            <th>User/Resident</th>
                            <th>Status</th>
                            <th>Processed By</th>
                            <th>IP Address</th>
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
                            <td>{{ $activity->description }}</td>
                            <td>{{ $activity->resident_name }}</td>
                            <td>
                                <span class="badge bg-{{ getStatusColor($activity->status) }}">
                                    {{ $activity->status }}
                                </span>
                            </td>
                            <td>{{ $activity->processed_by }}</td>
                            <td>{{ $activity->ip_address }}</td>
                            <td>
                                <button class="btn btn-sm btn-info view-activity" data-bs-toggle="modal" data-bs-target="#viewModal" data-activity="{{ json_encode($activity) }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No activities found</td>
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

    <!-- View Activity Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">Activity Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Date & Time:</strong> <span id="modalDateTime"></span></p>
                            <p><strong>Type:</strong> <span id="modalType"></span></p>
                            <p><strong>Description:</strong> <span id="modalDescription"></span></p>
                            <p><strong>User/Resident:</strong> <span id="modalResident"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                            <p><strong>Processed By:</strong> <span id="modalProcessedBy"></span></p>
                            <p><strong>IP Address:</strong> <span id="modalIpAddress"></span></p>
                            <p><strong>User Agent:</strong> <span id="modalUserAgent"></span></p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Additional Data:</h6>
                            <pre id="modalAdditionalData" class="bg-light p-3 rounded"></pre>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Search functionality
            $("#searchInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("table tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            // Filter functionality
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

            // View activity details
            $('.view-activity').on('click', function() {
                var activity = $(this).data('activity');
                $('#modalDateTime').text(new Date(activity.created_at).toLocaleString());
                $('#modalType').html(`<span class="badge bg-${getActivityTypeColor(activity.transaction_type)}">${activity.transaction_type}</span>`);
                $('#modalDescription').text(activity.description);
                $('#modalResident').text(activity.resident_name);
                $('#modalStatus').html(`<span class="badge bg-${getStatusColor(activity.status)}">${activity.status}</span>`);
                $('#modalProcessedBy').text(activity.processed_by);
                $('#modalIpAddress').text(activity.ip_address || 'N/A');
                $('#modalUserAgent').text(activity.user_agent || 'N/A');
                $('#modalAdditionalData').text(JSON.stringify(activity.additional_data || {}, null, 2));
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
            return 'info';
        case 'system':
            return 'primary';
        case 'error':
            return 'danger';
        case 'warning':
            return 'warning';
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
        case 'processing':
            return 'info';
        default:
            return 'secondary';
    }
}
@endphp
