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
            body, html {
                background: #fff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            /* Hide everything except .print-content */
            body * {
                visibility: hidden !important;
            }
            .print-content, .print-content * {
                visibility: visible !important;
            }
            .print-content {
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 100% !important;
                min-height: 100vh !important;
                background: #fff !important;
                box-shadow: none !important;
                margin: 0 !important;
                z-index: 9999 !important;
            }
            @page {
                size: A4;
                margin: 0.3in;
            }
            html, body {
                width: 100%;
                min-height: 100vh;
                margin: 0;
                padding: 0;
                background: #fff;
            }
            .print-content {
                font-family: Arial, sans-serif;
                color: #000;
                background: #fff;
                padding: 32px 48px;
                max-width: 700px;
                margin: 0 auto;
                font-size: 12pt;
            }
            .blotter-logo {
                display: block;
                margin: 0 auto 8px auto;
                width: 80px;
                height: 80px;
                object-fit: contain;
            }
            .gov-details {
                text-align: center;
                font-size: 1em;
                line-height: 1.3;
                margin-bottom: 8px;
            }
            .header-line {
                border: none;
                border-top: 2px solid #000;
                margin: 12px 0 18px 0;
            }
            .center-title {
                text-align: center;
                font-size: 1.3em;
                font-weight: bold;
                margin: 0 0 18px 0;
            }
            .section-title {
                font-weight: bold;
                margin-top: 18px;
                margin-bottom: 6px;
                font-size: 1.1em;
                text-align: left;
            }
            .incident-intro {
                margin-bottom: 10px;
            }
            .blotter-table {
                width: 100%;
                margin-bottom: 16px;
            }
            .blotter-table td {
                padding: 2px 8px 2px 0;
                font-size: 1em;
                vertical-align: top;
            }
            .facts-box {
                border: 1px solid #000;
                min-height: 100px;
                margin-bottom: 18px;
                padding: 10px;
                font-size: 1em;
                width: 100%;
                box-sizing: border-box;
                background: #fff;
            }
            .footer-section {
                margin-top: 24px;
                font-size: 1em;
            }
            .signatures {
                display: flex;
                justify-content: space-between;
                margin-top: 32px;
                width: 100%;
            }
            .prepared, .certified {
                width: 45%;
                text-align: left;
            }
            .sig-label {
                margin-bottom: 40px;
            }
            .sig-name {
                font-weight: bold;
                text-decoration: underline;
                display: inline-block;
                margin-bottom: 2px;
            }
            .footer {
                margin-top: 32px;
                font-size: 0.95em;
                color: #555;
                text-align: left;
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
                       

                        <hr class="header-line">
                        <h3 class="center-title mb-4">BARANGAY BLOTTER</h3>

                        <div class="blotter-content">
                            <!-- Complainant Details -->
                            <div class="section-container mb-4">
                                <h4 class="section-title bg-primary text-white p-2 rounded">COMPLAINANT DETAILS</h4>
                                <div class="section-content p-3 border rounded">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="blotter-table">
                                                <tr><td class="fw-bold">Name:</td><td><span id="view-first-name"></span> <span id="view-last-name"></span></td></tr>
                                                <tr><td class="fw-bold">Age:</td><td><span id="view-age"></span></td></tr>
                                                <tr><td class="fw-bold">Sex:</td><td><span id="view-sex"></span></td></tr>
                                                <tr><td class="fw-bold">Civil Status:</td><td><span id="view-civil-status"></span></td></tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="blotter-table">
                                                <tr><td class="fw-bold">Contact Number:</td><td><span id="view-contact-number"></span></td></tr>
                                                <tr><td class="fw-bold">Email:</td><td><span id="view-email"></span></td></tr>
                                                <tr><td class="fw-bold">Occupation:</td><td><span id="view-occupation"></span></td></tr>
                                                <tr><td class="fw-bold">Relationship to Respondent:</td><td><span id="view-relationship"></span></td></tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <strong>Complete Address:</strong>
                                        <p class="mb-0"><span id="view-address"></span></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Respondent Details -->
                            <div class="section-container mb-4">
                                <h4 class="section-title bg-primary text-white p-2 rounded">RESPONDENT DETAILS</h4>
                                <div class="section-content p-3 border rounded">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="blotter-table">
                                                <tr><td class="fw-bold">Name:</td><td><span id="view-respondent-name"></span></td></tr>
                                                <tr><td class="fw-bold">Age:</td><td><span id="view-respondent-age"></span></td></tr>
                                                <tr><td class="fw-bold">Sex:</td><td><span id="view-respondent-sex"></span></td></tr>
                                                <tr><td class="fw-bold">Civil Status:</td><td><span id="view-respondent-civil-status"></span></td></tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="blotter-table">
                                                <tr><td class="fw-bold">Contact Number:</td><td><span id="view-respondent-contact"></span></td></tr>
                                                <tr><td class="fw-bold">Occupation:</td><td><span id="view-respondent-occupation"></span></td></tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <strong>Address:</strong>
                                        <p class="mb-0"><span id="view-respondent-address"></span></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Incident Details -->
                            <div class="section-container mb-4">
                                <h4 class="section-title bg-primary text-white p-2 rounded">INCIDENT DETAILS</h4>
                                <div class="section-content p-3 border rounded">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="blotter-table">
                                                <tr><td class="fw-bold">Blotter ID:</td><td><span id="view-blotter-id"></span></td></tr>
                                                <tr><td class="fw-bold">Complaint Type:</td><td><span id="view-complaint-type"></span></td></tr>
                                                <tr><td class="fw-bold">Incident Date:</td><td><span id="view-incident-date"></span></td></tr>
                                                <tr><td class="fw-bold">Incident Time:</td><td><span id="view-incident-time"></span></td></tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mt-2">
                                                <strong>Incident Location:</strong>
                                                <p class="mb-0"><span id="view-incident-location"></span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Complaint Description -->
                            <div class="section-container mb-4">
                                <h4 class="section-title bg-primary text-white p-2 rounded">COMPLAINT DESCRIPTION</h4>
                                <div class="section-content p-3 border rounded">
                                    <p class="mb-0"><span id="view-complaint-description"></span></p>
                                </div>
                            </div>

                            <!-- What Happened -->
                            <div class="section-container mb-4">
                                <h4 class="section-title bg-primary text-white p-2 rounded">WHAT HAPPENED</h4>
                                <div class="section-content p-3 border rounded">
                                    <p class="mb-0"><span id="view-what-happened"></span></p>
                                </div>
                            </div>

                            <!-- Who Was Involved -->
                            <div class="section-container mb-4">
                                <h4 class="section-title bg-primary text-white p-2 rounded">WHO WAS INVOLVED</h4>
                                <div class="section-content p-3 border rounded">
                                    <p class="mb-0"><span id="view-who-involved"></span></p>
                                </div>
                            </div>

                            <!-- How It Happened -->
                            <div class="section-container mb-4">
                                <h4 class="section-title bg-primary text-white p-2 rounded">HOW IT HAPPENED</h4>
                                <div class="section-content p-3 border rounded">
                                    <p class="mb-0"><span id="view-how-happened"></span></p>
                                </div>
                            </div>

                            <!-- Witness Details -->
                            <div class="section-container mb-4">
                                <h4 class="section-title bg-primary text-white p-2 rounded">WITNESS DETAILS</h4>
                                <div class="section-content p-3 border rounded">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="blotter-table">
                                                <tr><td class="fw-bold">Name:</td><td><span id="view-witness-name"></span></td></tr>
                                                <tr><td class="fw-bold">Contact:</td><td><span id="view-witness-contact"></span></td></tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mt-2">
                                                <strong>Address:</strong>
                                                <p class="mb-0"><span id="view-witness-address"></span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Taken -->
                            <div class="section-container mb-4">
                                <h4 class="section-title bg-primary text-white p-2 rounded">ACTION TAKEN BY BARANGAY</h4>
                                <div class="section-content p-3 border rounded">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="blotter-table">
                                                <tr><td class="fw-bold">Initial Action:</td><td><span id="view-initial-action"></span></td></tr>
                                                <tr><td class="fw-bold">Handling Officer:</td><td><span id="view-handling-officer"></span></td></tr>
                                                <tr><td class="fw-bold">Officer Position:</td><td><span id="view-officer-position"></span></td></tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="blotter-table">
                                                <tr><td class="fw-bold">Mediation Date:</td><td><span id="view-mediation-date"></span></td></tr>
                                                <tr><td class="fw-bold">Action Result:</td><td><span id="view-action-result"></span></td></tr>
                                                <tr><td class="fw-bold">Remarks:</td><td><span id="view-remarks"></span></td></tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Evidence -->
                            <div class="section-container mb-4">
                                <h4 class="section-title bg-primary text-white p-2 rounded">EVIDENCE</h4>
                                <div class="section-content p-3 border rounded">
                                    <div id="view-evidence-photo" class="mb-3">
                                        <img src="" alt="Evidence Photo" class="img-fluid rounded" style="max-width: 100%; display: none;">
                                        <span class="no-photo">No photo evidence provided</span>
                                    </div>
                                    <div class="mt-2">
                                        <strong>Declaration:</strong> <span id="view-declaration"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Section -->
                            <div class="footer-section mt-5">
                                
                               
                            </div>

                            
                        </div>
                    </div>
                    <div class="modal-footer no-print">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createSummonModal">
                            <i class="fas fa-file-alt"></i> Create Summon
                        </button>
                        <button type="button" class="btn btn-primary" onclick="printBlotter()">
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
                    $('#view-complaint-description').text(blotterData.complaint_description);
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

                // Function to print blotter
                window.printBlotter = function() {
                    var blotterData = $('.view-blotter').data('blotter');
                    
                    if (!blotterData) {
                        alert('Error: Blotter data is missing. Please try viewing the blotter details again.');
                        return;
                    }

                    // Create a new window for printing
                    var printWindow = window.open('', '_blank');
                    
                    // Make an AJAX call to get the blotter template
                    $.ajax({
                        url: '{{ route("secretary.blotter.print") }}',
                        method: 'GET',
                        data: {
                            blotter_id: blotterData.id,
                            incident_date: moment(blotterData.incident_date).format('MMMM D, YYYY'),
                            incident_time: moment(blotterData.incident_time).format('h:mm A'),
                            what_happened: blotterData.what_happened,
                            who_was_involved: blotterData.who_was_involved,
                            how_it_happened: blotterData.how_it_happened,
                            secretary_name: '{{ Auth::user() && Auth::user()->user_type == "secretary" ? Auth::user()->name : "Secretary Name" }}',
                            barangay_captain_name: 'HON. DEMOSTHENES P. PATINGA'
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
                            
                            var errorMessage = 'Error generating blotter. ';
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
