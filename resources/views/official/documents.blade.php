@extends('layouts.o_docs')

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
                    <div class="number">{{ $documents->count() }}</div>
                    <div class="label">Total Requests</div>
                </div>
                <i class="fas fa-file-alt"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card yellow-card">
                <div>
                    <div class="number">{{ $documents->where('status', 'Pending')->count() }}</div>
                    <div class="label">Pending</div>
                </div>
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card green-card">
                <div>
                    <div class="number">{{ $documents->where('status', 'Ready for Pickup')->count() }}</div>
                    <div class="label">Ready for Pickup</div>
                </div>
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card red-card">
                <div>
                    <div class="number">{{ $documents->where('status', 'Rejected')->count() }}</div>
                    <div class="label">Rejected</div>
                </div>
                <i class="fas fa-times-circle"></i>
            </div>
        </div>
    </div>    <!-- Document Requests Table -->
    <div class="content-card mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Document Requests</h2>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#requestModal">
                <i class="fa fa-plus"></i> New Request
            </button>
        </div>
        <div class="table-responsive">
                        <table class="table align-items-center mb-0">
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
                                @forelse($documents as $document)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $document->request_id }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $document->document_type }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $document->created_at->format('M d, Y') }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $document->purpose }}</p>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-{{ getStatusColor($document->status) }}">{{ $document->status }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <button class="btn btn-link text-secondary mb-0" 
                                                data-toggle="modal" 
                                                data-target="#viewModal" 
                                                data-request="{{ json_encode($document) }}">
                                            <i class="fa fa-eye text-xs"></i>
                                        </button>
                                        <button class="btn btn-link text-secondary mb-0"
                                                data-toggle="modal" 
                                                data-target="#updateStatusModal" 
                                                data-request="{{ json_encode($document) }}">
                                            <i class="fa fa-edit text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No document requests found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
            </div>            <form action="{{ route('official.document.request.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                     <div class="form-group">                        <label for="document_type">Document Type</label>
                        <input type="text" class="form-control" id="document_type" name="document_type" required>
                    </div>
                    <div class="form-group">
                        <label for="purpose">Purpose</label>
                        <textarea class="form-control" id="purpose" name="purpose" rows="2" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="date_needed">Date Needed</label>
                        <input type="date" class="form-control" id="date_needed" name="date_needed" required>
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
            </div>            <form id="updateStatusForm" action="{{ route('official.document.request.update.status') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="request_id" id="update-request-id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="document_type">Document Type</label>
                        <input type="text" class="form-control" id="update-document-type" name="document_type" required>
                    </div>
                    <div class="form-group">
                        <label for="purpose">Purpose</label>
                        <textarea class="form-control" id="update-purpose" name="purpose" rows="2" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="date_needed">Date Needed</label>
                        <input type="date" class="form-control" id="update-date-needed" name="date_needed" required>
                    </div>
                    <div class="form-group">
                        <label for="additional_info">Additional Information</label>
                        <textarea class="form-control" id="update-additional-info" name="additional_info" rows="2"></textarea>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Request</button>
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
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Handle view modal data
        $('#viewModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var requestData = button.data('request');
            
            $('#view-request-id').text(requestData.id);
            $('#view-document-type').text(requestData.document_type);
            $('#view-date').text(moment(requestData.created_at).format('MMMM D, YYYY'));
            $('#view-purpose').text(requestData.purpose);
            $('#view-additional-info').text(requestData.additional_info || 'N/A');
            $('#view-status').text(requestData.status);
            $('#view-date-needed').text(moment(requestData.date_needed).format('MMMM D, YYYY'));
            
            if (requestData.remarks) {
                $('#view-remarks').text(requestData.remarks);
                $('#remarks-section').show();
            } else {
                $('#remarks-section').hide();
            }
        });
        
        // Handle update status modal data
        $('#updateStatusModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var requestData = button.data('request');
            
            $('#update-request-id').val(requestData.id);
            $('#update-document-type').val(requestData.document_type);
            $('#update-purpose').val(requestData.purpose);
            $('#update-date-needed').val(requestData.date_needed);
            $('#update-additional-info').val(requestData.additional_info);
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

        // Handle print document button click
        $('#printDocument').on('click', function() {
            var documentType = $('#view-document-type').text();
            var requestId = $('#view-request-id').text();
            
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