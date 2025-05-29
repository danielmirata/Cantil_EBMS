@extends('layouts.docs')

@section('content')
<div class="container-fluid">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h1 class="dashboard-title">Document Requests</h1>
        <div class="dashboard-subtitle">View and manage barangay document requests</div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="stats-card blue-card">
                <div>
                    <div class="number">{{ $requests->count() }}</div>
                    <div class="label">Total Requests</div>
                </div>
                <i class="fas fa-file-alt"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card yellow-card">
                <div>
                    <div class="number">{{ $requests->where('status', 'Pending')->count() }}</div>
                    <div class="label">Pending</div>
                </div>
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card green-card">
                <div>
                    <div class="number">{{ $requests->where('status', 'Ready for Pickup')->count() }}</div>
                    <div class="label">Ready for Pickup</div>
                </div>
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card red-card">
                <div>
                    <div class="number">{{ $requests->where('status', 'Rejected')->count() }}</div>
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
            <div class="d-flex gap-2">
                <div class="search-box">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search requests...">
                    <i class="fas fa-search search-icon"></i>
                </div>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#requestModal">
                    <i class="fa fa-plus"></i> New Request
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-items-center mb-0" id="requestsTable">
                <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Request ID</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Document Type</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date Requested</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Purpose</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                        <th class="text-secondary opacity-7">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $request)
                    <tr>
                        <td>
                            <div class="d-flex px-2 py-1">
                                <div class="d-flex flex-column justify-content-center">
                                    <h6 class="mb-0 text-sm">{{ $request->request_id }}</h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p class="text-xs font-weight-bold mb-0">{{ $request->document_type }}</p>
                        </td>
                        <td>
                            <p class="text-xs font-weight-bold mb-0">{{ $request->created_at->format('M d, Y') }}</p>
                        </td>
                        <td>
                            <p class="text-xs font-weight-bold mb-0">{{ $request->purpose }}</p>
                        </td>
                        <td>
                            <span class="badge badge-sm bg-{{ getStatusColor($request->status) }}">{{ $request->status }}</span>
                        </td>
                        <td class="align-middle">
                            <div class="btn-group">
                                <button class="btn btn-link text-secondary mb-0" 
                                        data-toggle="modal" 
                                        data-target="#viewModal" 
                                        data-request="{{ json_encode($request) }}"
                                        title="View Details">
                                    <i class="fa fa-eye text-xs"></i>
                                </button>
                                <button class="btn btn-link text-secondary mb-0"
                                        data-toggle="modal" 
                                        data-target="#updateStatusModal" 
                                        data-request="{{ json_encode($request) }}"
                                        title="Update Status">
                                    <i class="fa fa-edit text-xs"></i>
                                </button>
                                @if($request->status === 'Ready for Pickup')
                                <button class="btn btn-link text-success mb-0"
                                        onclick="printDocument('{{ $request->document_type }}', '{{ $request->request_id }}')"
                                        title="Print Document">
                                    <i class="fa fa-print text-xs"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No requests found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- New Request Modal -->
<div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestModalLabel">Request Barangay Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('document.request.store') }}" method="POST" id="requestForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="document_type">Document Type</label>
                        <input type="text" class="form-control" id="document_type" name="document_type" required>
                    </div>
                    <div class="form-group">
                        <label for="purpose">Purpose</label>
                        <textarea class="form-control" id="purpose" name="purpose" rows="2" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="additional_info">Additional Information (Optional)</label>
                        <textarea class="form-control" id="additional_info" name="additional_info" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Request Details Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">Request Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="printDocument">
                    <i class="fa fa-print"></i> Print Document
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" role="dialog" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel">Update Request Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="updateStatusForm" action="{{ route('document.request.update.status') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="request_id" id="update-request-id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="update-status" name="status" required>
                            <option value="Pending">Pending</option>
                            <option value="Processing">Processing</option>
                            <option value="Ready for Pickup">Ready for Pickup</option>
                            <option value="Completed">Completed</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea class="form-control" id="update-remarks" name="remarks" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('styles')
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
        transition: transform 0.2s ease-in-out;
    }
    .stats-card:hover {
        transform: translateY(-5px);
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

    /* Search Box Styles */
    .search-box {
        position: relative;
        margin-right: 1rem;
    }
    .search-box input {
        padding-right: 2.5rem;
        border-radius: 20px;
    }
    .search-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    /* Table Styles */
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    .table td {
        vertical-align: middle;
    }
    .btn-group .btn-link {
        padding: 0.25rem 0.5rem;
    }
    .btn-group .btn-link:hover {
        background-color: rgba(0, 0, 0, 0.05);
        border-radius: 4px;
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 10px;
        border: none;
    }
    .modal-header {
        border-bottom: 1px solid #eee;
        padding: 1rem 1.5rem;
    }
    .modal-body {
        padding: 1.5rem;
    }
    .modal-footer {
        border-top: 1px solid #eee;
        padding: 1rem 1.5rem;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-group label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    .form-control {
        border-radius: 5px;
        border: 1px solid #ddd;
        padding: 0.5rem 1rem;
    }
    .form-control:focus {
        border-color: #2196F3;
        box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.25);
    }

    /* Badge Styles */
    .badge {
        padding: 0.5em 0.75em;
        font-weight: 500;
        border-radius: 4px;
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Handle view modal data
        $('#viewModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var requestData = button.data('request');
            
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

            // Show/hide print button based on status
            if (requestData.status === 'Ready for Pickup') {
                $('#printDocument').show();
            } else {
                $('#printDocument').hide();
            }
        });
        
        // Handle update status modal data
        $('#updateStatusModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var requestData = button.data('request');
            
            $('#update-request-id').val(requestData.id);
            $('#update-status').val(requestData.status);
            $('#update-remarks').val(requestData.remarks || '');
        });

        // Handle form submission
        $('#updateStatusForm').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                success: function(response) {
                    $('#updateStatusModal').modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error updating request. Please try again.');
                    console.error(xhr.responseText);
                }
            });
        });

        // Handle search functionality
        $('#searchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $("#requestsTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        // Handle print document button click
        $('#printDocument').on('click', function() {
            var documentType = $('#view-document-type').text();
            var requestId = $('#view-request-id').text();
            printDocument(documentType, requestId);
        });
    });

    // Print document function
    function printDocument(documentType, requestId) {
        var routeMap = {
            'Barangay Clearance': '/secretary/documents/print/clearance/',
            'Certificate of Residency': '/secretary/documents/print/residency/',
            'Certificate of Indigency': '/secretary/documents/print/certification/',
            'Barangay Business Permit': '/secretary/documents/print/business-permit/',
            'Certificate of Good Moral': '/secretary/documents/print/good-moral/'
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
    }
</script>
@endsection

@php
/**
 * Helper function to get the appropriate color for status badges
 */
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