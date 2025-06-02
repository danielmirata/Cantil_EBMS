<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Barangay Cantil-E - Captain Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/secretary-dashboard.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
</head>

<body>
    @include('partials.captain-sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <div class="top-nav">
            <div class="dropdown me-3" style="display:inline-block;">
                <button class="btn position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notification-count" style="display:none;">0</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" id="notification-list" style="width: 350px; max-height: 400px; overflow-y: auto;">
                    <li class="dropdown-header">Notifications</li>
                    <li><hr class="dropdown-divider"></li>
                    <li class="text-center text-muted" id="no-notifications">No notifications</li>
                </ul>
            </div>
            <div class="dropdown captain-dropdown">
                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="fas fa-user-circle"></i> Captain
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Settings</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="dropdown-item">
                            @csrf
                            <button type="submit" class="btn btn-link p-0"><i class="fas fa-sign-out-alt"></i>
                                Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h1 class="dashboard-title">Complaints Management</h1>
            <div class="dashboard-subtitle">View resident complaints</div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="stats-card blue-card">
                    <div>
                        <div class="number">{{ $stats['total_complaints'] ?? 0 }}</div>
                        <div class="label">Total Complaints</div>
                    </div>
                    <i class="fas fa-exclamation-circle"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card yellow-card">
                    <div>
                        <div class="number">{{ $stats['pending_complaints'] ?? 0 }}</div>
                        <div class="label">Pending</div>
                    </div>
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card green-card">
                    <div>
                        <div class="number">{{ $stats['resolved_complaints'] ?? 0 }}</div>
                        <div class="label">Resolved</div>
                    </div>
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card red-card">
                    <div>
                        <div class="number">{{ $stats['rejected_complaints'] ?? 0 }}</div>
                        <div class="label">Rejected</div>
                    </div>
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>

        <!-- Complaints Table -->
        <div class="content-card mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Complaints List</h2>
                <div class="d-flex">
                    <input type="text" id="searchInput" class="form-control me-2" placeholder="Search complaints...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Complaint ID</th>
                            <th>Complaint Type</th>
                            <th>Complainant</th>
                            <th>Incident Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($complaints as $complaint)
                            <tr>
                                <td>#{{ $complaint->id }}</td>
                                <td>{{ $complaint->complaint_type }}</td>
                                <td>{{ $complaint->first_name . ' ' . $complaint->last_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($complaint->incident_date)->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ getStatusColor($complaint->status ?? 'Pending') }}">
                                        {{ $complaint->status ?? 'Pending' }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info view-complaint" data-bs-toggle="modal"
                                        data-bs-target="#viewModal" data-complaint="{{ json_encode($complaint) }}">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No complaints found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- View Complaint Details Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Complaint Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Complaint ID:</strong> <span id="view-complaint-id"></span></div>
                        <div class="col-md-6"><strong>Status:</strong> <span id="view-status"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>First Name:</strong> <span id="view-first-name"></span></div>
                        <div class="col-md-6"><strong>Last Name:</strong> <span id="view-last-name"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Contact Number:</strong> <span id="view-contact-number"></span>
                        </div>
                        <div class="col-md-6"><strong>Email:</strong> <span id="view-email"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12"><strong>Address:</strong> <span id="view-address"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Complaint Type:</strong> <span id="view-complaint-type"></span>
                        </div>
                        <div class="col-md-6"><strong>Incident Date:</strong> <span id="view-incident-date"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Incident Time:</strong> <span id="view-incident-time"></span>
                        </div>
                        <div class="col-md-6"><strong>Incident Location:</strong> <span
                                id="view-incident-location"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12"><strong>Description:</strong> <span id="view-description"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <strong>Evidence Photo:</strong>
                            <div id="view-evidence-photo" class="mt-2">
                                <img src="" alt="Evidence Photo" class="img-thumbnail"
                                    style="max-width: 200px; display: none;">
                                <span class="no-photo">No photo available</span>
                            </div>
                        </div>
                        <div class="col-md-6"><strong>Declaration:</strong> <span id="view-declaration"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12"><strong>Created At:</strong> <span id="view-created-at"></span></div>
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
            // Add CSRF token to all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Handle view modal data
            $('.view-complaint').on('click', function() {
                var complaintData = $(this).data('complaint');

                $('#view-complaint-id').text(complaintData.id);
                $('#view-status').text(complaintData.status || 'Pending');
                $('#view-first-name').text(complaintData.first_name);
                $('#view-last-name').text(complaintData.last_name);
                $('#view-contact-number').text(complaintData.contact_number);
                $('#view-email').text(complaintData.email || 'N/A');
                $('#view-address').text(complaintData.complete_address);
                $('#view-complaint-type').text(complaintData.complaint_type);
                $('#view-incident-date').text(moment(complaintData.incident_date).format('MMMM D, YYYY'));
                $('#view-incident-time').text(
                    complaintData.incident_time ?
                    complaintData.incident_time.substring(11, 16) :
                    'N/A'
                );
                $('#view-incident-location').text(complaintData.incident_location);
                $('#view-description').text(complaintData.complaint_description);
                $('#view-declaration').text(complaintData.declaration ? 'Yes' : 'No');
                $('#view-created-at').text(moment(complaintData.created_at).format('MMMM D, YYYY h:mm A'));

                // Handle evidence photo display
                var photoContainer = $('#view-evidence-photo');
                var photoImg = photoContainer.find('img');
                var noPhotoSpan = photoContainer.find('.no-photo');

                if (complaintData.evidence_photo) {
                    var imagePath = complaintData.evidence_photo.includes('uploads/evidence_photos') ?
                        '/' + complaintData.evidence_photo.replace(/^\/+/, '') :
                        '/uploads/evidence_photos/' + complaintData.evidence_photo.replace(/^.*[\\\/]/, '');
                    photoImg.attr('src', imagePath);
                    photoImg.show();
                    noPhotoSpan.hide();
                } else {
                    photoImg.hide();
                    noPhotoSpan.show();
                }
            });

            // Handle search functionality
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $("table tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });

        function formatTime(timeStr) {
            if (!timeStr) return 'N/A';

            // Handle time string from database (HH:mm:ss format)
            const time = moment(timeStr, 'HH:mm:ss');
            if (time.isValid()) {
                return time.format('h:mm A');
            }

            // Fallback for other formats
            return timeStr;
        }
    </script>
</body>

</html>

@php
    function getStatusColor($status)
    {
        switch ($status) {
            case 'Pending':
                return 'warning';
            case 'Under Investigation':
                return 'info';
            case 'Resolved':
                return 'success';
            case 'Rejected':
                return 'danger';
            default:
                return 'secondary';
        }
    }
@endphp
