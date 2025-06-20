<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Barangay Cantil-E - Secretary Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/secretary-dashboard.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
</head>

<body>
    @include('partials.secretary-sidebar')

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
            <div class="dropdown secretary-dropdown">
                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="fas fa-user-circle"></i> Secretary
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
            <div class="dashboard-subtitle">Manage and process resident complaints</div>
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
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newComplaintModal">
                        <i class="fas fa-plus"></i> New Complaint
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Complaint ID</th>
                            <th>Full Name</th>
                            <th>Complainee</th>
                            <th>Complaint Type</th>
                            <th>Incident Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($complaints as $complaint)
                            <tr>
                                <td>#{{ $complaint->id }}</td>
                                <td>{{ $complaint->fullname }}</td>
                                <td>{{ $complaint->complainee_name }}</td>
                                <td>{{ $complaint->complaint_type }}</td>
                                <td>{{ \Carbon\Carbon::parse($complaint->incident_date)->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ getStatusColor($complaint->status ?? 'Pending') }}">
                                        {{ $complaint->status ?? 'Pending' }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info view-complaint" data-bs-toggle="modal"
                                        data-bs-target="#viewModal" data-complaint="{{ json_encode($complaint) }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-primary update-complaint" data-bs-toggle="modal"
                                        data-bs-target="#updateStatusModal"
                                        data-complaint="{{ json_encode($complaint) }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No complaints found</td>
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
                <div class="modal-header" style="background: #0d6efd; color: #fff; align-items: center;">
                    <i class="fas fa-exclamation-circle me-2" style="font-size: 1.5rem;"></i>
                    <h5 class="modal-title fw-bold flex-grow-1">Official Complaint Information</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="background: #f8f9fa;">
                    <div class="row">
                        <!-- Left Profile Section -->
                        <div class="col-md-4 d-flex flex-column align-items-center justify-content-start border-end">
                            <div class="mb-3 mt-3">
                                <div style="width:120px;height:120px;background:#e9ecef;border-radius:50%;display:flex;align-items:center;justify-content:center;overflow:hidden;">
                                    <i class="fas fa-user-circle" style="font-size:6rem;color:#adb5bd;"></i>
                                </div>
                            </div>
                            <div class="text-center mb-1">
                                <span class="fw-bold" id="view-fullname" style="font-size:1.2rem;"></span>
                            </div>
                            <div class="text-center text-muted mb-3" style="font-size:0.95rem;">
                                Complainant
                            </div>
                            <button class="btn btn-outline-primary btn-sm mb-3" disabled><i class="fas fa-id-badge me-1"></i> ID: <span id="view-complaint-id"></span></button>
                        </div>
                        <!-- Right Info Card Section -->
                        <div class="col-md-8">
                            <div class="card shadow-sm mt-3 mb-3">
                                <div class="card-header bg-white border-bottom-0 pb-0">
                                    <ul class="nav nav-tabs card-header-tabs" id="complaintTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="true">
                                                <i class="fas fa-user me-1"></i> Details
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="incident-tab" data-bs-toggle="tab" data-bs-target="#incident" type="button" role="tab" aria-controls="incident" aria-selected="false">
                                                <i class="fas fa-exclamation-triangle me-1"></i> Incident
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body tab-content">
                                    <!-- Details Tab -->
                                    <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                                        <div class="row mb-2">
                                            <div class="col-md-6 small text-muted">Contact Number</div>
                                            <div class="col-md-6 small text-muted">Email</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6 fw-bold" id="view-contact-number"></div>
                                            <div class="col-md-6 fw-bold" id="view-email"></div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6 small text-muted">Purok</div>
                                            <div class="col-md-6 small text-muted">Status</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6 fw-bold" id="view-purok"></div>
                                            <div class="col-md-6 fw-bold" id="view-status"></div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6 small text-muted">Complainee Name</div>
                                            <div class="col-md-6 small text-muted">Complainee Address</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6 fw-bold" id="view-complainee-name"></div>
                                            <div class="col-md-6 fw-bold" id="view-complainee-address"></div>
                                        </div>
                                    </div>
                                    <!-- Incident Tab -->
                                    <div class="tab-pane fade" id="incident" role="tabpanel" aria-labelledby="incident-tab">
                                        <div class="row mb-2">
                                            <div class="col-md-6 small text-muted">Complaint Type</div>
                                            <div class="col-md-6 small text-muted">Incident Date</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6 fw-bold" id="view-complaint-type"></div>
                                            <div class="col-md-6 fw-bold" id="view-incident-date"></div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6 small text-muted">Incident Time</div>
                                            <div class="col-md-6 small text-muted">Incident Location</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6 fw-bold" id="view-incident-time"></div>
                                            <div class="col-md-6 fw-bold" id="view-incident-location"></div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6 small text-muted">Declaration</div>
                                            <div class="col-md-6 small text-muted">Created At</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6 fw-bold" id="view-declaration"></div>
                                            <div class="col-md-6 fw-bold" id="view-created-at"></div>
                                        </div>
                                    </div>
                                    <!-- Description (always visible below tabs) -->
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div class="p-3" style="background:#e9ecef;border-radius:8px;border-left:4px solid #0d6efd;">
                                                <div class="small text-muted mb-1"><i class="fas fa-align-left me-1"></i>Description</div>
                                                <div id="view-description" class="fw-bold" style="white-space: pre-wrap;line-height:1.6;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Evidence Photo & Remarks -->
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="small text-muted mb-1">Evidence Photo</div>
                                            <div id="view-evidence-photo" class="mt-2">
                                                <img src="" alt="Evidence Photo" class="img-thumbnail" style="max-width: 180px; display: none; border-radius: 8px;">
                                                <span class="no-photo">No photo available</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="small text-muted mb-1">Remarks</div>
                                            <div id="view-remarks" class="fw-bold" style="white-space: pre-wrap;line-height:1.6;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8f9fa; border-top: 2px solid #dee2e6;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary transfer-to-blotter" data-bs-toggle="modal"
                        data-bs-target="#transferToBlotterModal">
                        <i class="fas fa-exchange-alt"></i> Transfer to Blotter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Transfer to Blotter Modal -->
    <div class="modal fade" id="transferToBlotterModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transfer to Blotter Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="transferToBlotterForm" action="{{ route('secretary.blotter.transfer') }}" method="POST">
                    @csrf
                    <input type="hidden" name="complaint_id" id="transfer-complaint-id">
                    <div class="modal-body">
                        <!-- Complainant Additional Details -->
                        <h5 class="mb-3">Complainant Additional Details</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Age</label>
                                <input type="number" class="form-control" name="age" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sex</label>
                                <select class="form-select" name="sex" required>
                                    <option value="">Select Sex</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Civil Status</label>
                                <select class="form-select" name="civil_status" required>
                                    <option value="">Select Civil Status</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Widowed">Widowed</option>
                                    <option value="Separated">Separated</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Occupation</label>
                                <input type="text" class="form-control" name="occupation" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Relationship to Respondent</label>
                                <input type="text" class="form-control" name="relationship_to_respondent">
                            </div>
                        </div>

                        <!-- Respondent Details -->
                        <h5 class="mt-4 mb-3">Respondent Details</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="respondent_first_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="respondent_last_name" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Age</label>
                                <input type="number" class="form-control" name="respondent_age" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sex</label>
                                <select class="form-select" name="respondent_sex" required>
                                    <option value="">Select Sex</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Civil Status</label>
                                <select class="form-select" name="respondent_civil_status" required>
                                    <option value="">Select Civil Status</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Widowed">Widowed</option>
                                    <option value="Separated">Separated</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="respondent_address" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Contact Number</label>
                                <input type="text" class="form-control" name="respondent_contact_number" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Occupation</label>
                                <input type="text" class="form-control" name="respondent_occupation" required>
                            </div>
                        </div>

                        <!-- Additional Details -->
                        <h5 class="mt-4 mb-3">Additional Details</h5>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">What Happened</label>
                                <textarea class="form-control" name="what_happened" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Who Was Involved</label>
                                <textarea class="form-control" name="who_was_involved" rows="2" required></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">How It Happened</label>
                                <textarea class="form-control" name="how_it_happened" rows="3" required></textarea>
                            </div>
                        </div>

                        <!-- Witness Details -->
                        <h5 class="mt-4 mb-3">Witness Details (Optional)</h5>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="witness_name">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="witness_address">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Contact</label>
                                <input type="text" class="form-control" name="witness_contact">
                            </div>
                        </div>

                        <!-- Action Details -->
                        <h5 class="mt-4 mb-3">Action Details</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Initial Action Taken</label>
                                <input type="text" class="form-control" name="initial_action_taken" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Handling Officer Name</label>
                                <input type="text" class="form-control" name="handling_officer_name" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Handling Officer Position</label>
                                <input type="text" class="form-control" name="handling_officer_position" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mediation Date</label>
                                <input type="date" class="form-control" name="mediation_date">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Action Result</label>
                                <input type="text" class="form-control" name="action_result" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Remarks</label>
                                <input type="text" class="form-control" name="remarks">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Transfer to Blotter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Complaint Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="updateStatusForm" action="/secretary/complaints/status" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="complaint_id" id="update-complaint-id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="update-status" name="status" required>
                                <option value="Pending">Pending</option>
                                <option value="Under Investigation">Under Investigation</option>
                                <option value="Resolved">Resolved</option>
                                <option value="Rejected">Rejected</option>
                                <option value="Transferred to Blotter">Transferred to Blotter</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea class="form-control" id="update-remarks" name="remarks" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- New Complaint Modal -->
    <div class="modal fade" id="newComplaintModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Complaint</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="newComplaintForm" action="{{ route('secretary.complaints.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <!-- Personal Information -->
                        <h5 class="mb-3">Personal Information</h5>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="fullname" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Contact Number</label>
                                <input type="tel" class="form-control" name="contact_number" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email">
                            </div>
                        </div>
                        <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Purok</label>
                            <select class="form-select" name="purok" required>
                                <option value="">Select Street/Purok...</option>
                                <option value="Mapahiyumon">Mapahiyumon</option>
                                <option value="Mauswagon">Mauswagon</option>
                                <option value="Madasigon">Madasigon</option>
                                <option value="Matinabangon">Matinabangon</option>
                                <option value="Malipayon">Malipayon</option>
                                <option value="Makugihon">Makugihon</option>
                                <option value="Maalagaron">Maalagaron</option>
                                <option value="Matinagdanon">Matinagdanon</option>
                                <option value="Maabi-abihon">Maabi-abihon</option>
                                <option value="Twin Heart">Twin Heart</option>
                            </select>
                        </div>
                    </div>

                        <!-- Complainee Information -->
                        <h5 class="mt-4 mb-3">Complainee Information</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Complainee Name</label>
                                <input type="text" class="form-control" name="complainee_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Complainee Address</label>
                                <input type="text" class="form-control" name="complainee_address" required>
                            </div>
                        </div>

                        <!-- Complaint Details -->
                        <h5 class="mt-4 mb-3">Complaint Details</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Complaint Type</label>
                                <select class="form-select" name="complaint_type" required>
                                    <option value="">Select Type</option>
                                    <option value="Noise Complaint">Noise Complaint</option>
                                    <option value="Property Dispute">Property Dispute</option>
                                    <option value="Public Disturbance">Public Disturbance</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Incident Date</label>
                                <input type="date" class="form-control" name="incident_date" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Incident Time</label>
                                <input type="time" class="form-control" name="incident_time" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Incident Location</label>
                                <input type="text" class="form-control" name="incident_location" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="complaint_description" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Evidence Photo</label>
                                <input type="file" class="form-control" name="evidence_photo" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="declaration" required>
                                    <label class="form-check-label">
                                        I declare that all information provided is true and accurate
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Complaint</button>
                    </div>
                </form>
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
                $('#view-fullname').text(complaintData.fullname);
                $('#view-contact-number').text(complaintData.contact_number);
                $('#view-email').text(complaintData.email || 'N/A');
                $('#view-purok').text(complaintData.purok);
                $('#view-complainee-name').text(complaintData.complainee_name);
                $('#view-complainee-address').text(complaintData.complainee_address);
                $('#view-complaint-type').text(complaintData.complaint_type);
                $('#view-incident-date').text(moment(complaintData.incident_date).format('MMMM D, YYYY'));
                $('#view-incident-time').text(complaintData.incident_time ? complaintData.incident_time.substring(11, 16) : 'N/A');
                $('#view-incident-location').text(complaintData.incident_location);
                $('#view-description').text(complaintData.complaint_description);
                $('#view-declaration').text(complaintData.declaration ? 'Yes' : 'No');
                $('#view-remarks').text(complaintData.remarks || 'N/A');
                $('#view-created-at').text(moment(complaintData.created_at).format('MMMM D, YYYY h:mm A'));

                // Set the complaint ID for the transfer form
                $('#transfer-complaint-id').val(complaintData.id);

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

            // Handle update status modal data
            $('.update-complaint').on('click', function() {
                var complaintData = $(this).data('complaint');

                $('#update-complaint-id').val(complaintData.id);
                $('#update-status').val(complaintData.status || 'Pending');
                $('#update-remarks').val(complaintData.remarks || '');
            });

            // Handle form submission
            $('#updateStatusForm').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                formData.append('_method', 'PUT');

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#updateStatusModal').modal('hide');
                            location.reload();
                        } else {
                            alert(response.message ||
                                'Error updating complaint. Please try again.');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseText);
                        var errorMessage = 'Error updating complaint. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                    }
                });
            });

            // Handle transfer to blotter form submission
            $('#transferToBlotterForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#transferToBlotterModal').modal('hide');
                            $('#viewModal').modal('hide');
                            location.reload();
                        } else {
                            alert(response.message ||
                                'Error transferring complaint. Please try again.');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseText);
                        var errorMessage = 'Error transferring complaint. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                    }
                });
            });

            // Handle search functionality
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $("table tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            // Handle new complaint form submission
            $('#newComplaintForm').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#newComplaintModal').modal('hide');
                            location.reload();
                        } else {
                            alert(response.message ||
                                'Error submitting complaint. Please try again.');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseText);
                        var errorMessage = 'Error submitting complaint. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                    }
                });
            });

            // Reset form when modal is closed
            $('#newComplaintModal').on('hidden.bs.modal', function() {
                $('#newComplaintForm')[0].reset();
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
