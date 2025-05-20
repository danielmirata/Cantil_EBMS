@extends('layouts.docs')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>Barangay Document Requests</h6>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#requestModal">
                        <i class="fa fa-plus"></i> New Request
                    </button>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
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
                                        <button class="btn btn-link text-secondary mb-0" 
                                                data-toggle="modal" 
                                                data-target="#viewModal" 
                                                data-request="{{ json_encode($request) }}">
                                            <i class="fa fa-eye text-xs"></i>
                                        </button>
                                        <button class="btn btn-link text-secondary mb-0"
                                                data-toggle="modal" 
                                                data-target="#updateStatusModal" 
                                                data-request="{{ json_encode($request) }}">
                                            <i class="fa fa-edit text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No requests found</td>
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
            </div>
            <form action="{{ route('barangay.document.request.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                   
                    <div class="form-group">
                        <label for="document_type">Document Type</label>
                        <input type="text" class="form-control" id="document_type" name="document_type" required>
                        </select>
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
            </div>
            <form id="updateStatusForm" action="{{ route('barangay.document.request.update.status') }}" method="POST">
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