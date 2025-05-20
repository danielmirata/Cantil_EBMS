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
            <h1 class="dashboard-title">Official Document Requests</h1>
            <div class="dashboard-subtitle">Manage and process official document requests</div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="stats-card blue-card">
                    <div>
                        <div class="number">{{ $stats['total_requests'] }}</div>
                        <div class="label">Total Requests</div>
                    </div>
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card yellow-card">
                    <div>
                        <div class="number">{{ $stats['pending_requests'] }}</div>
                        <div class="label">Pending</div>
                    </div>
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card green-card">
                    <div>
                        <div class="number">{{ $stats['completed_requests'] }}</div>
                        <div class="label">Completed</div>
                    </div>
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card red-card">
                    <div>
                        <div class="number">{{ $stats['rejected_requests'] }}</div>
                        <div class="label">Rejected</div>
                    </div>
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>

        <!-- Document Requests Table -->
        <div class="content-card mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Document Requests</h2>
                <div class="d-flex">
                    <input type="text" id="searchInput" class="form-control me-2" placeholder="Search requests...">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newRequestModal">
                        <i class="fas fa-plus"></i> New Request
                    </button>
                </div>
            </div>
            
            <div class="mb-3">
                <div class="btn-group" role="group" aria-label="Request Type Filter">
                    <button type="button" class="btn btn-outline-primary active" id="btn-show-official">Official Requests</button>
                    <button type="button" class="btn btn-outline-secondary" id="btn-show-resident">Resident Requests</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Document Type</th>
                            <th>Requested By</th>
                            <th>Date Needed</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $request)
                        <tr data-request-type="official" class="request-row">
                            <td>#{{ $request->request_id }}</td>
                            <td>{{ $request->document_type }}</td>
                            <td>{{ $request->user->name }}</td>
                            <td>{{ $request->date_needed->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-{{ getStatusColor($request->status) }}">
                                    {{ $request->status }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info view-request" data-bs-toggle="modal" data-bs-target="#viewModal" data-request="{{ json_encode($request) }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-primary update-request" data-bs-toggle="modal" data-bs-target="#updateStatusModal" data-request="{{ json_encode($request) }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No official document requests found</td>
                            </tr>
                        @endforelse

                        @forelse($residentRequests as $request)
                        <tr data-request-type="resident" class="request-row" style="display: none;">
                            <td>#{{ $request->request_id }}</td>
                            <td>{{ $request->document_type }}</td>
                            <td>{{ $request->first_name . ' ' . $request->last_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($request->date_needed)->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-{{ getStatusColor($request->status) }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info view-resident-request" data-bs-toggle="modal" data-bs-target="#viewResidentModal" data-request="{{ json_encode($request) }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-primary update-request" data-bs-toggle="modal" data-bs-target="#updateStatusModal" data-request="{{ json_encode($request) }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                            <tr data-request-type="resident" style="display: none;">
                                <td colspan="6" class="text-center">No resident document requests found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- New Request Modal -->
    <div class="modal fade" id="newRequestModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Document Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('secretary.barangay.document.request.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Document Type</label>
                            <select name="document_type" class="form-select" required>
                                <option value="">Select Document Type</option>
                                <option value="Barangay Clearance">Barangay Clearance</option>
                                <option value="Certificate of Residency">Certificate of Residency</option>
                                <option value="Certificate of Indigency">Certificate of Indigency</option>
                                <option value="Barangay ID">Barangay ID</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Purpose</label>
                            <textarea name="purpose" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date Needed</label>
                            <input type="date" name="date_needed" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Additional Information</label>
                            <textarea name="additional_info" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Request Details Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Request Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-sm mb-0 text-secondary">Request ID:</p>
                            <p class="text-sm font-weight-bold mb-0" id="view-request-id"></p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-sm mb-0 text-secondary">Status:</p>
                            <p class="text-sm font-weight-bold mb-0" id="view-status"></p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-sm mb-0 text-secondary">Document Type:</p>
                            <p class="text-sm font-weight-bold mb-0" id="view-document-type"></p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-sm mb-0 text-secondary">Date Requested:</p>
                            <p class="text-sm font-weight-bold mb-0" id="view-date"></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p class="text-sm mb-0 text-secondary">Purpose:</p>
                            <p class="text-sm font-weight-bold mb-0" id="view-purpose"></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p class="text-sm mb-0 text-secondary">Additional Information:</p>
                            <p class="text-sm font-weight-bold mb-0" id="view-additional-info"></p>
                        </div>
                    </div>
                    <div class="row mb-3" id="remarks-section">
                        <div class="col-md-12">
                            <p class="text-sm mb-0 text-secondary">Remarks:</p>
                            <p class="text-sm font-weight-bold mb-0" id="view-remarks"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Request Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="updateStatusForm" action="/secretary/documents/request/status" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="request_id" id="update-request-id">
                    <div class="modal-body">
                        
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="update-status" name="status" required>
                                <option value="Pending">Pending</option>
                                <option value="Processing">Processing</option>
                                <option value="Ready for Pickup">Ready for Pickup</option>
                                <option value="Completed">Completed</option>
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
                        <button type="submit" class="btn btn-primary">Update Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Resident Details Modal -->
    <div class="modal fade" id="viewResidentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Resident Request Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Request ID:</strong> <span id="resident-request-id"></span></div>
                        <div class="col-md-6"><strong>Status:</strong> <span id="resident-status"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>First Name:</strong> <span id="resident-first-name"></span></div>
                        <div class="col-md-6"><strong>Last Name:</strong> <span id="resident-last-name"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Contact Number:</strong> <span id="resident-contact-number"></span></div>
                        <div class="col-md-6"><strong>Email:</strong> <span id="resident-email"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12"><strong>Address:</strong> <span id="resident-address"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Document Type:</strong> <span id="resident-document-type"></span></div>
                        <div class="col-md-6"><strong>Date Needed:</strong> <span id="resident-date-needed"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12"><strong>Purpose:</strong> <span id="resident-purpose"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12"><strong>Notes:</strong> <span id="resident-notes"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>ID Type:</strong> <span id="resident-id-type"></span></div>
                        <div class="col-md-6">
                            <strong>ID Photo:</strong>
                            <div id="resident-id-photo" class="mt-2">
                                <img src="" alt="ID Photo" class="img-thumbnail" style="max-width: 200px; display: none;">
                                <span class="no-photo">No photo available</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Declaration:</strong> <span id="resident-declaration"></span></div>
                        <div class="col-md-6"><strong>Remarks:</strong> <span id="resident-remarks"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12"><strong>Created At:</strong> <span id="resident-created-at"></span></div>
                    </div>
                    <input type="hidden" id="resident-document-type-value">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="printDocument">
                        <i class="fas fa-print"></i> Print Document
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
            $('.view-request').on('click', function() {
                var requestData = $(this).data('request');
                var isResident = $(this).closest('tr').data('request-type') === 'resident';
                
                if (isResident) {
                    // For resident requests
                    $('#view-request-id').text(requestData.id);
                    $('#view-document-type').text(requestData.document_type);
                    $('#view-date').text(moment(requestData.created_at).format('MMMM D, YYYY'));
                    $('#view-purpose').text(requestData.purpose);
                    $('#view-additional-info').text(requestData.notes || 'N/A');
                    $('#view-status').text(requestData.status);
                    
                    if (requestData.remarks) {
                        $('#view-remarks').text(requestData.remarks);
                        $('#remarks-section').show();
                    } else {
                        $('#remarks-section').hide();
                    }
                } else {
                    // For official requests (existing code)
                    $('#view-request-id').text(requestData.request_id);
                    $('#view-document-type').text(requestData.document_type);
                    $('#view-date').text(moment(requestData.created_at).format('MMMM D, YYYY'));
                    $('#view-purpose').text(requestData.purpose);
                    $('#view-additional-info').text(requestData.additional_info || 'N/A');
                    $('#view-status').text(requestData.status);
                    
                    if (requestData.remarks) {
                        $('#view-remarks').text(requestData.remarks);
                        $('#remarks-section').show();
                    } else {
                        $('#remarks-section').hide();
                    }
                }
            });
            
            // Handle update status modal data
            $('.update-request').on('click', function() {
                var requestData = $(this).data('request');
                var isResident = $(this).closest('tr').data('request-type') === 'resident';
                
                $('#update-request-id').val(requestData.id);
                $('#update-status').val(requestData.status);
                $('#update-remarks').val(requestData.remarks || '');
                
                // Update form action based on request type
                var form = $('#updateStatusForm');
                if (isResident) {
                    form.attr('action', '/resident/document-requests/' + requestData.id + '/status');
                } else {
                    form.attr('action', '/secretary/documents/request/status');
                }
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
                            alert(response.message || 'Error updating request. Please try again.');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseText);
                        var errorMessage = 'Error updating request. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                    }
                });
            });

            // Handle request type filtering
            $('#btn-show-official').on('click', function() {
                $(this).addClass('active btn-primary').removeClass('btn-outline-primary');
                $('#btn-show-resident').removeClass('active btn-primary').addClass('btn-outline-secondary');
                $('tr[data-request-type="official"]').show();
                $('tr[data-request-type="resident"]').hide();
            });

            $('#btn-show-resident').on('click', function() {
                $(this).addClass('active btn-primary').removeClass('btn-outline-secondary');
                $('#btn-show-official').removeClass('active btn-primary').addClass('btn-outline-primary');
                $('tr[data-request-type="resident"]').show();
                $('tr[data-request-type="official"]').hide();
            });

            // Show official requests by default
            $('#btn-show-official').click();

            // Handle view resident modal data
            $('.view-resident-request').on('click', function() {
                var requestData = $(this).data('request');
                $('#resident-request-id').text(requestData.request_id);
                $('#resident-status').text(requestData.status);
                $('#resident-first-name').text(requestData.first_name);
                $('#resident-last-name').text(requestData.last_name);
                $('#resident-contact-number').text(requestData.contact_number);
                $('#resident-email').text(requestData.email || 'N/A');
                $('#resident-address').text(requestData.address);
                $('#resident-document-type').text(requestData.document_type);
                $('#resident-date-needed').text(moment(requestData.date_needed).format('MMMM D, YYYY'));
                $('#resident-purpose').text(requestData.purpose);
                $('#resident-notes').text(requestData.notes || 'N/A');
                $('#resident-id-type').text(requestData.id_type);
                
                // Handle ID photo display
                var photoContainer = $('#resident-id-photo');
                var photoImg = photoContainer.find('img');
                var noPhotoSpan = photoContainer.find('.no-photo');
                
                if (requestData.id_photo) {
                    photoImg.attr('src', '/storage/' + requestData.id_photo);
                    photoImg.show();
                    noPhotoSpan.hide();
                } else {
                    photoImg.hide();
                    noPhotoSpan.show();
                }
                
                $('#resident-declaration').text(requestData.declaration ? 'Yes' : 'No');
                $('#resident-remarks').text(requestData.remarks || 'N/A');
                $('#resident-created-at').text(moment(requestData.created_at).format('MMMM D, YYYY h:mm A'));
            });

            // Handle print document button click
            $('#printDocument').on('click', function() {
                var documentType = $('#resident-document-type').text();
                var requestId = $('#resident-request-id').text();
                
                // Map document types to their corresponding routes
                var routeMap = {
                    'Barangay Clearance': '/secretary/documents/print/clearance/',
                    'Certificate of Residency': '/secretary/documents/print/residency/',
                    'Certificate of Indigency': '/secretary/documents/print/certification/'
                };

                if (routeMap[documentType]) {
                    var printWindow = window.open(routeMap[documentType] + requestId, '_blank');
                    if (printWindow) {
                        printWindow.onload = function() {
                            printWindow.print();
                        };
                    }
                } else {
                    alert('Printing is not available for this document type');
                }
            });
        });
    </script>
</body>
</html>

@php
function getStatusColor($status) {
    switch ($status) {
        case 'Pending':
            return 'warning';
        case 'Processing':
            return 'info';
        case 'Ready for Pickup':
            return 'primary';
        case 'Completed':
            return 'success';
        case 'Rejected':
            return 'danger';
        default:
            return 'secondary';
    }
}
@endphp