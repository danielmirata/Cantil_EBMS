<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Barangay Cantil-E - Walk-in Records</title>
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
            <h1 class="dashboard-title">Walk-in Records</h1>
            <div class="dashboard-subtitle">Track and manage walk-in requests and clearances</div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="stats-card blue-card">
                    <div>
                        <div class="number">{{ $stats['total_requests'] ?? 0 }}</div>
                        <div class="label">Total Requests</div>
                    </div>
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card yellow-card">
                    <div>
                        <div class="number">{{ $stats['pending_requests'] ?? 0 }}</div>
                        <div class="label">Pending Requests</div>
                    </div>
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card green-card">
                    <div>
                        <div class="number">{{ $stats['completed_requests'] ?? 0 }}</div>
                        <div class="label">Completed Requests</div>
                    </div>
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card red-card">
                    <div>
                        <div class="number">{{ $stats['today_requests'] ?? 0 }}</div>
                        <div class="label">Today's Requests</div>
                    </div>
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>

        <!-- Records Table -->
        <div class="content-card mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Walk-in Records List</h2>
                <div class="d-flex">
                    <input type="text" id="searchInput" class="form-control me-2" placeholder="Search records...">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            Filter by Type
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" data-filter="all">All Records</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="clearance">Clearances</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="certification">Certifications</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="other">Other Requests</a></li>
                        </ul>
                    </div>
                    <a href="{{ route('secretary.records.create') }}" class="btn btn-success me-2">
                        <i class="fas fa-plus"></i> New Record
                    </a>
                    <button type="button" class="btn btn-primary" onclick="exportRecords()">
                        <i class="fas fa-download"></i> Export Records
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Request Type</th>
                            <th>Resident Name</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th>Processed By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $record)
                        <tr>
                            <td>{{ $record->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                <span class="badge bg-{{ getRequestTypeColor($record->request_type) }}">
                                    {{ ucfirst($record->request_type) }}
                                </span>
                            </td>
                            <td>{{ $record->resident_name }}</td>
                            <td>{{ $record->purpose }}</td>
                            <td>
                                <span class="badge bg-{{ getStatusColor($record->status) }}">
                                    {{ ucfirst($record->status) }}
                                </span>
                            </td>
                            <td>{{ $record->processed_by }}</td>
                            <td>
                                <button class="btn btn-sm btn-info view-record" data-bs-toggle="modal" data-bs-target="#viewModal" data-record="{{ json_encode($record) }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('secretary.records.edit', $record) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('secretary.records.destroy', $record) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $records->links() }}
            </div>
        </div>
    </div>

    <!-- View Record Details Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Record Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body print-content">
                    <div class="text-center mb-4">
                        <h4>BARANGAY CANTIL-E</h4>
                        <h5>REQUEST RECORD DETAILS</h5>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Date & Time:</strong> <span id="view-datetime"></span></div>
                        <div class="col-md-6"><strong>Request Type:</strong> <span id="view-type"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Resident Name:</strong> <span id="view-resident"></span></div>
                        <div class="col-md-6"><strong>Status:</strong> <span id="view-status"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12"><strong>Purpose:</strong> <span id="view-purpose"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12"><strong>Processed By:</strong> <span id="view-processed-by"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12"><strong>Remarks:</strong> <span id="view-remarks"></span></div>
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
            $('.view-record').on('click', function() {
                var recordData = $(this).data('record');
                
                $('#view-datetime').text(moment(recordData.created_at).format('MMMM D, YYYY h:mm A'));
                $('#view-type').text(recordData.request_type.charAt(0).toUpperCase() + recordData.request_type.slice(1));
                $('#view-resident').text(recordData.resident_name);
                $('#view-status').text(recordData.status.charAt(0).toUpperCase() + recordData.status.slice(1));
                $('#view-purpose').text(recordData.purpose);
                $('#view-processed-by').text(recordData.processed_by);
                $('#view-remarks').text(recordData.remarks || 'N/A');
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

        // Function to export records
        function exportRecords() {
            window.location.href = '{{ route("secretary.records.export") }}';
        }
    </script>
</body>
</html>

@php
function getRequestTypeColor($type) {
    switch (strtolower($type)) {
        case 'clearance':
            return 'primary';
        case 'certification':
            return 'info';
        case 'other':
            return 'secondary';
        default:
            return 'secondary';
    }
}

function getStatusColor($status) {
    switch (strtolower($status)) {
        case 'pending':
            return 'warning';
        case 'processing':
            return 'info';
        case 'completed':
            return 'success';
        case 'cancelled':
            return 'danger';
        default:
            return 'secondary';
    }
}
@endphp 