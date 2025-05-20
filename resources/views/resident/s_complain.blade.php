@extends('layouts.resident')

@section('breadcrumb')
<li class="breadcrumb-item active">Complaint Status</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2>My Complaints</h2>
            <p class="text-muted">Track the status of your filed complaints</p>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-3">
        <div class="btn-group" role="group" id="complaintFilterTabs">
            <button class="btn btn-success active" data-status="all">All</button>
            <button class="btn btn-outline-secondary" data-status="pending">Pending</button>
            <button class="btn btn-outline-secondary" data-status="in_progress">In Progress</button>
            <button class="btn btn-outline-secondary" data-status="resolved">Resolved</button>
        </div>
    </div>

    <!-- Tracking Number Search -->
    <div class="mb-4">
        <label for="complaintTrackingNumber" class="form-label fw-bold">Enter Complaint ID:</label>
        <input type="text" id="complaintTrackingNumber" class="form-control mb-2" placeholder="Enter your complaint ID here">
        <button class="btn btn-success w-100" id="trackComplaintBtn">Track Complaint</button>
    </div>

    <!-- Complaint Cards -->
    <div class="row g-3" id="complaintCards">
        @forelse($complaints as $complaint)
        <div class="col-12 complaint-card" data-status="{{ $complaint->status }}" data-complaint-id="{{ $complaint->complaint_id }}">
            <div class="card shadow-sm rounded-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <span class="fw-bold text-secondary">{{ $complaint->complaint_id }}</span>
                        </div>
                        <div>
                            @if($complaint->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($complaint->status == 'in_progress')
                                <span class="badge bg-info text-dark">In Progress</span>
                            @elseif($complaint->status == 'resolved')
                                <span class="badge bg-success">Resolved</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($complaint->status) }}</span>
                            @endif
                        </div>
                    </div>
                    <h5 class="mb-1">{{ $complaint->complaint_type }}</h5>
                    <div class="text-muted mb-1" style="font-size: 0.95em;">
                        Filed: {{ $complaint->created_at->format('M d, Y, g:i A') }}
                    </div>
                    <div class="mb-2">{{ $complaint->description }}</div>
                    <button type="button" class="btn btn-link p-0 fw-bold" data-bs-toggle="modal" data-bs-target="#viewComplaintModal{{ $complaint->complaint_id }}">
                        View Details <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Complaint Details Modal -->
        <div class="modal fade" id="viewComplaintModal{{ $complaint->complaint_id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content rounded-4">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title text-success fw-bold">Complaint Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <span class="text-muted">Complaint ID</span>
                            <span class="fw-bold float-end">{{ $complaint->complaint_id }}</span>
                        </div>
                        <div class="mb-3 p-2 rounded-3" style="background: #fffbe6;">
                            <span class="fw-bold">Current Status:</span>
                            @if($complaint->status == 'pending')
                                <span class="badge bg-warning text-dark ms-2">Pending</span>
                            @elseif($complaint->status == 'in_progress')
                                <span class="badge bg-info text-dark ms-2">In Progress</span>
                            @elseif($complaint->status == 'resolved')
                                <span class="badge bg-success ms-2">Resolved</span>
                            @else
                                <span class="badge bg-secondary ms-2">{{ ucfirst($complaint->status) }}</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold">Complaint Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Type:</strong> {{ $complaint->complaint_type }}</p>
                                    <p class="mb-1"><strong>Date Filed:</strong> {{ $complaint->created_at->format('M d, Y, g:i A') }}</p>
                                    <p class="mb-1"><strong>Incident Date:</strong> {{ $complaint->incident_date }}</p>
                                    <p class="mb-1"><strong>Incident Time:</strong> {{ $complaint->incident_time }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Location:</strong> {{ $complaint->location }}</p>
                                    @if($complaint->remarks)
                                    <p class="mb-1"><strong>Remarks:</strong> {{ $complaint->remarks }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 p-2 rounded-3" style="background: #f6f6f6;">
                            <strong>Description:</strong>
                            <div>{{ $complaint->description }}</div>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold">Evidence Photo Preview</h6>
                            <div class="p-3 rounded-3 border d-flex align-items-center justify-content-center" style="background: #f6f6f6; min-height: 120px;">
                                @if($complaint->evidence_photo)
                                    <img src="{{ asset('storage/' . $complaint->evidence_photo) }}" alt="Evidence Photo" class="img-fluid rounded border" style="max-height: 120px; max-width: 100%;">
                                @else
                                    <span class="text-muted">No evidence photo uploaded.</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">No complaints found</div>
        </div>
        @endforelse
    </div>

    <div class="mt-5 text-center">
        <a href="{{ route('resident.dashboard') }}" class="btn btn-link">Return to Dashboard</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Filter functionality
        $('#complaintFilterTabs button').on('click', function() {
            var status = $(this).data('status');
            $('#complaintFilterTabs button').removeClass('btn-success active').addClass('btn-outline-secondary');
            $(this).addClass('btn-success active').removeClass('btn-outline-secondary');
            if (status === 'all') {
                $('.complaint-card').show();
            } else {
                $('.complaint-card').hide();
                $('.complaint-card[data-status="' + status + '"]').show();
            }
        });
        // Tracking number search
        $('#trackComplaintBtn').on('click', function() {
            var searchId = $('#complaintTrackingNumber').val().trim();
            if (searchId === '') {
                $('.complaint-card').show();
                return;
            }
            $('.complaint-card').hide();
            $('.complaint-card[data-complaint-id="' + searchId + '"]').show();
        });
    });
</script>
@endpush
