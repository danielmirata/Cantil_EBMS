<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Barangay Cantil-E - Blotter Records</title>
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
            <h1 class="dashboard-title">Blotter Records</h1>
            <div class="dashboard-subtitle">Manage and process blotter records</div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="stats-card blue-card">
                    <div>
                        <div class="number">{{ $stats['total_blotters'] ?? 0 }}</div>
                        <div class="label">Total Records</div>
                    </div>
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card yellow-card">
                    <div>
                        <div class="number">{{ $stats['pending_blotters'] ?? 0 }}</div>
                        <div class="label">Pending</div>
                    </div>
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card green-card">
                    <div>
                        <div class="number">{{ $stats['resolved_blotters'] ?? 0 }}</div>
                        <div class="label">Resolved</div>
                    </div>
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card red-card">
                    <div>
                        <div class="number">{{ $stats['rejected_blotters'] ?? 0 }}</div>
                        <div class="label">Rejected</div>
                    </div>
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>

        <!-- Blotter Table -->
        <div class="content-card mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Blotter List</h2>
                <div class="d-flex">
                    <input type="text" id="searchInput" class="form-control me-2" placeholder="Search blotter records...">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBlotterModal">
                        <i class="fas fa-plus"></i> Create New Blotter
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Blotter ID</th>
                            <th>Complaint Type</th>
                            <th>Complainant</th>
                            <th>Incident Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($blotters as $blotter)
                        <tr>
                            <td>#{{ $blotter->id }}</td>
                            <td>{{ $blotter->complaint_type }}</td>
                            <td>{{ $blotter->first_name . ' ' . $blotter->last_name }}</td>
                            <td>{{ $blotter->incident_date->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-{{ getStatusColor($blotter->status) }}">
                                    {{ $blotter->status }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info view-blotter" data-bs-toggle="modal" data-bs-target="#viewModal" data-blotter="{{ json_encode($blotter) }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-primary update-blotter" data-bs-toggle="modal" data-bs-target="#updateStatusModal" data-blotter="{{ json_encode($blotter) }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No blotter records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- View Blotter Details Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Blotter Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body print-content">
                    <div class="text-center mb-4">
                        <h4>BARANGAY CANTIL-E</h4>
                        <h5>BLOTTER RECORD</h5>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Blotter ID:</strong> <span id="view-blotter-id"></span></div>
                        <div class="col-md-6"><strong>Status:</strong> <span id="view-status"></span></div>
                    </div>

                    <!-- Complainant Details -->
                    <h6 class="mt-4 mb-3">Complainant Details</h6>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Full Name:</strong> <span id="view-first-name"></span> <span id="view-last-name"></span></div>
                        <div class="col-md-6"><strong>Age:</strong> <span id="view-age"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Sex:</strong> <span id="view-sex"></span></div>
                        <div class="col-md-6"><strong>Civil Status:</strong> <span id="view-civil-status"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12"><strong>Address:</strong> <span id="view-address"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Contact Number:</strong> <span id="view-contact-number"></span></div>
                        <div class="col-md-6"><strong>Email:</strong> <span id="view-email"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Occupation:</strong> <span id="view-occupation"></span></div>
                        <div class="col-md-6"><strong>Relationship to Respondent:</strong> <span id="view-relationship"></span></div>
                    </div>

                    <!-- Respondent Details -->
                    <h6 class="mt-4 mb-3">Respondent Details</h6>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Full Name:</strong> <span id="view-respondent-name"></span></div>
                        <div class="col-md-6"><strong>Age:</strong> <span id="view-respondent-age"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Sex:</strong> <span id="view-respondent-sex"></span></div>
                        <div class="col-md-6"><strong>Civil Status:</strong> <span id="view-respondent-civil-status"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12"><strong>Address:</strong> <span id="view-respondent-address"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Contact Number:</strong> <span id="view-respondent-contact"></span></div>
                        <div class="col-md-6"><strong>Occupation:</strong> <span id="view-respondent-occupation"></span></div>
                    </div>

                    <!-- Incident Details -->
                    <h6 class="mt-4 mb-3">Incident Details</h6>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Complaint Type:</strong> <span id="view-complaint-type"></span></div>
                        <div class="col-md-6"><strong>Incident Date:</strong> <span id="view-incident-date"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Incident Time:</strong> <span id="view-incident-time"></span></div>
                        <div class="col-md-6"><strong>Incident Location:</strong> <span id="view-incident-location"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12"><strong>What Happened:</strong> <span id="view-what-happened"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12"><strong>Who Was Involved:</strong> <span id="view-who-involved"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12"><strong>How It Happened:</strong> <span id="view-how-happened"></span></div>
                    </div>

                    <!-- Witness Details -->
                    <h6 class="mt-4 mb-3">Witness Details</h6>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Name:</strong> <span id="view-witness-name"></span></div>
                        <div class="col-md-6"><strong>Contact:</strong> <span id="view-witness-contact"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12"><strong>Address:</strong> <span id="view-witness-address"></span></div>
                    </div>

                    <!-- Action Taken -->
                    <h6 class="mt-4 mb-3">Action Taken by Barangay</h6>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Initial Action:</strong> <span id="view-initial-action"></span></div>
                        <div class="col-md-6"><strong>Handling Officer:</strong> <span id="view-handling-officer"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Officer Position:</strong> <span id="view-officer-position"></span></div>
                        <div class="col-md-6"><strong>Mediation Date:</strong> <span id="view-mediation-date"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Action Result:</strong> <span id="view-action-result"></span></div>
                        <div class="col-md-6"><strong>Remarks:</strong> <span id="view-remarks"></span></div>
                    </div>

                    <!-- Evidence -->
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <strong>Evidence Photo:</strong>
                            <div id="view-evidence-photo" class="mt-2">
                                <img src="" alt="Evidence Photo" class="img-thumbnail" style="max-width: 200px; display: none;">
                                <span class="no-photo">No photo available</span>
                            </div>
                        </div>
                        <div class="col-md-6"><strong>Declaration:</strong> <span id="view-declaration"></span></div>
                    </div>
                </div>
                <div class="modal-footer no-print">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createSummonModal">
                        <i class="fas fa-file-alt"></i> Create Summon
                    </button>
                    <button type="button" class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print"></i> Print Blotter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Summon Modal -->
    <div class="modal fade" id="createSummonModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Summon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="summon-blotter-id">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Click the print button below to generate the summon for this blotter case.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="printSummon()">
                        <i class="fas fa-print"></i> Print Summon
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Blotter Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="updateStatusForm" action="{{ route('secretary.blotter.status') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="blotter_id" id="update-blotter-id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="update-status" name="status" required>
                                <option value="Pending">Pending</option>
                                <option value="Under Investigation">Under Investigation</option>
                                <option value="Resolved">Resolved</option>
                                <option value="Rejected">Rejected</option>
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

    <!-- Create Blotter Modal -->
    <div class="modal fade" id="createBlotterModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Blotter Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('secretary.blotter.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <!-- Complainant Details -->
                        <h5 class="mb-3">Complainant Details</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="first_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="last_name" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Age</label>
                                <input type="number" class="form-control" name="age" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sex</label>
                                <select class="form-select" name="sex" required>
                                    <option value="">Select Sex</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Civil Status</label>
                                <select class="form-select" name="civil_status" required>
                                    <option value="">Select Status</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Widowed">Widowed</option>
                                    <option value="Separated">Separated</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Complete Address</label>
                                <textarea class="form-control" name="complete_address" rows="2" required></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Contact Number</label>
                                <input type="text" class="form-control" name="contact_number" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Occupation</label>
                                <input type="text" class="form-control" name="occupation" required>
                            </div>
                            <div class="col-md-6">
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
                            <div class="col-md-4">
                                <label class="form-label">Age</label>
                                <input type="number" class="form-control" name="respondent_age" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sex</label>
                                <select class="form-select" name="respondent_sex" required>
                                    <option value="">Select Sex</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Civil Status</label>
                                <select class="form-select" name="respondent_civil_status" required>
                                    <option value="">Select Status</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Widowed">Widowed</option>
                                    <option value="Separated">Separated</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="respondent_address" rows="2" required></textarea>
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

                        <!-- Incident Details -->
                        <h5 class="mt-4 mb-3">Incident Details</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Complaint Type</label>
                                <input type="text" class="form-control" name="complaint_type" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Incident Location</label>
                                <input type="text" class="form-control" name="incident_location" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Complaint Description</label>
                                <textarea class="form-control" name="complaint_description" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Incident Date</label>
                                <input type="date" class="form-control" name="incident_date" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Incident Time</label>
                                <input type="time" class="form-control" name="incident_time" required>
                            </div>
                        </div>
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
                        <h5 class="mt-4 mb-3">Witness Details</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="witness_name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contact</label>
                                <input type="text" class="form-control" name="witness_contact">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="witness_address" rows="2"></textarea>
                            </div>
                        </div>

                        <!-- Action Taken -->
                        <h5 class="mt-4 mb-3">Action Taken by Barangay</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Initial Action</label>
                                <input type="text" class="form-control" name="initial_action_taken" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Handling Officer Name</label>
                                <input type="text" class="form-control" name="handling_officer_name" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Officer Position</label>
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

                        <!-- Evidence -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Evidence Photo</label>
                                <input type="file" class="form-control" name="evidence_photo" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="declaration" value="1" required>
                                    <label class="form-check-label">
                                        I declare that all information provided is true and correct to the best of my knowledge.
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Blotter Record</button>
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
            $('.view-blotter').on('click', function() {
                var blotterData = $(this).data('blotter');
                
                // Set blotter ID for summon form
                $('#summon-blotter-id').val(blotterData.id);
                
                // Basic Info
                $('#view-blotter-id').text(blotterData.id);
                $('#view-status').text(blotterData.status);

                // Complainant Details
                $('#view-first-name').text(blotterData.first_name);
                $('#view-last-name').text(blotterData.last_name);
                $('#view-age').text(blotterData.age);
                $('#view-sex').text(blotterData.sex);
                $('#view-civil-status').text(blotterData.civil_status);
                $('#view-address').text(blotterData.complete_address);
                $('#view-contact-number').text(blotterData.contact_number);
                $('#view-email').text(blotterData.email || 'N/A');
                $('#view-occupation').text(blotterData.occupation);
                $('#view-relationship').text(blotterData.relationship_to_respondent || 'N/A');

                // Respondent Details
                $('#view-respondent-name').text(blotterData.respondent_first_name + ' ' + blotterData.respondent_last_name);
                $('#view-respondent-age').text(blotterData.respondent_age);
                $('#view-respondent-sex').text(blotterData.respondent_sex);
                $('#view-respondent-civil-status').text(blotterData.respondent_civil_status);
                $('#view-respondent-address').text(blotterData.respondent_address);
                $('#view-respondent-contact').text(blotterData.respondent_contact_number);
                $('#view-respondent-occupation').text(blotterData.respondent_occupation);

                // Incident Details
                $('#view-complaint-type').text(blotterData.complaint_type);
                $('#view-incident-date').text(moment(blotterData.incident_date).format('MMMM D, YYYY'));
                $('#view-incident-time').text(moment(blotterData.incident_time).format('h:mm A'));
                $('#view-incident-location').text(blotterData.incident_location);
                $('#view-what-happened').text(blotterData.what_happened);
                $('#view-who-involved').text(blotterData.who_was_involved);
                $('#view-how-happened').text(blotterData.how_it_happened);

                // Witness Details
                $('#view-witness-name').text(blotterData.witness_name || 'N/A');
                $('#view-witness-address').text(blotterData.witness_address || 'N/A');
                $('#view-witness-contact').text(blotterData.witness_contact || 'N/A');

                // Action Taken
                $('#view-initial-action').text(blotterData.initial_action_taken);
                $('#view-handling-officer').text(blotterData.handling_officer_name);
                $('#view-officer-position').text(blotterData.handling_officer_position);
                $('#view-mediation-date').text(blotterData.mediation_date ? moment(blotterData.mediation_date).format('MMMM D, YYYY') : 'N/A');
                $('#view-action-result').text(blotterData.action_result);
                $('#view-remarks').text(blotterData.remarks || 'N/A');

                // Evidence
                $('#view-declaration').text(blotterData.declaration ? 'Yes' : 'No');
                
                // Handle evidence photo display
                var photoContainer = $('#view-evidence-photo');
                var photoImg = photoContainer.find('img');
                var noPhotoSpan = photoContainer.find('.no-photo');
                
                if (blotterData.evidence_photo) {
                    photoImg.attr('src', '/storage/' + blotterData.evidence_photo);
                    photoImg.show();
                    noPhotoSpan.hide();
                } else {
                    photoImg.hide();
                    noPhotoSpan.show();
                }
            });
            
            // Handle update status modal data
            $('.update-blotter').on('click', function() {
                var blotterData = $(this).data('blotter');
                
                $('#update-blotter-id').val(blotterData.id);
                $('#update-status').val(blotterData.status);
                $('#update-remarks').val(blotterData.remarks || '');
            });

            // Handle form submission
            $('#updateStatusForm').on('submit', function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'PUT',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#updateStatusModal').modal('hide');
                            location.reload();
                        } else {
                            alert(response.message || 'Error updating blotter. Please try again.');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseText);
                        var errorMessage = 'Error updating blotter. Please try again.';
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

            // Function to print summon
            window.printSummon = function() {
                var blotterId = $('#summon-blotter-id').val();
                
                if (!blotterId) {
                    alert('Error: Blotter ID is missing. Please try viewing the blotter details again.');
                    return;
                }

                var currentDate = new Date();
                
                // Create a new window for printing
                var printWindow = window.open('', '_blank');
                
                // Make an AJAX call to get the summon template
                $.ajax({
                    url: '{{ route("secretary.summon.print") }}',
                    method: 'GET',
                    data: {
                        blotter_id: blotterId,
                        date: moment(currentDate).add(7, 'days').format('MMMM D, YYYY'),
                        day: moment(currentDate).add(7, 'days').format('dddd'),
                        time: '9:00 AM',
                        issued_day: moment(currentDate).format('D'),
                        issued_month: moment(currentDate).format('MMMM')
                    },
                    success: function(response) {
                        if (response) {
                            printWindow.document.write(response);
                            printWindow.document.close();
                            printWindow.focus();
                            
                            // Wait for images to load before printing
                            setTimeout(function() {
                                printWindow.print();
                                printWindow.close();
                            }, 1000);
                        } else {
                            console.error('Empty response received');
                            alert('Error: Empty response received from server');
                            printWindow.close();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error details:', {
                            status: status,
                            error: error,
                            response: xhr.responseText
                        });
                        
                        var errorMessage = 'Error generating summon. ';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage += xhr.responseJSON.message;
                        } else {
                            errorMessage += 'Please try again.';
                        }
                        
                        alert(errorMessage);
                        printWindow.close();
                    }
                });
            };
        });
    </script>
</body>
</html>

@php
function getStatusColor($status) {
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
