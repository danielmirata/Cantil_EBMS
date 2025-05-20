@extends('layouts.resident')

@section('breadcrumb')
<li class="breadcrumb-item active">Document Status</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2>My Documents</h2>
            <p class="text-muted">Track the status of your document requests</p>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-3">
        <div class="btn-group" role="group" id="filterTabs">
            <button class="btn btn-success active" data-status="all">All</button>
            <button class="btn btn-outline-secondary" data-status="submitted">Submitted</button>
            <button class="btn btn-outline-secondary" data-status="under_review">Under Review</button>
            <button class="btn btn-outline-secondary" data-status="needs_action">Needs Action</button>
        </div>
    </div>

    <!-- Tracking Number Search -->
    <div class="mb-4">
        <label for="trackingNumber" class="form-label fw-bold">Enter Tracking Number:</label>
        <input type="text" id="trackingNumber" class="form-control mb-2" placeholder="Enter your tracking number here">
        <button class="btn btn-success w-100">Track Document</button>
    </div>

    <!-- Document Cards -->
    <div class="row g-3" id="documentCards">
        @forelse($documents as $document)
        <div class="col-12 document-card" data-status="{{ $document->status }}">
            <div class="card shadow-sm rounded-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <span class="fw-bold text-secondary">{{ $document->request_id }}</span>
                        </div>
                        <div>
                            @if($document->status == 'completed' || $document->status == 'verified')
                                <span class="badge bg-success">Verified</span>
                            @elseif($document->status == 'processing' || $document->status == 'under_review')
                                <span class="badge bg-warning text-dark">Under Review</span>
                            @elseif($document->status == 'pending')
                                <span class="badge bg-secondary">Submitted</span>
                            @else
                                <span class="badge bg-info text-dark">{{ ucfirst($document->status) }}</span>
                            @endif
                        </div>
                    </div>
                    <h5 class="mb-1">{{ $document->document_type }}</h5>
                    <div class="text-muted mb-1" style="font-size: 0.95em;">
                        Submitted: {{ $document->created_at->format('M d, Y, g:i A') }}
                    </div>
                    <div class="mb-2">{{ $document->purpose }}</div>
                    <div class="mb-2 text-muted" style="font-size: 0.95em;">
                        PDF &middot; 2.4 MB &nbsp; <span class="text-success">2 updates</span>
                    </div>
                    <button type="button" class="btn btn-link p-0 fw-bold" data-bs-toggle="modal" data-bs-target="#viewModal{{ $document->request_id }}">
                        View Details <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Document Details Modal -->
        <div class="modal fade" id="viewModal{{ $document->request_id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content rounded-4">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title text-success fw-bold">Document Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <span class="text-muted">Reference Number</span>
                            <span class="fw-bold float-end">{{ $document->request_id }}</span>
                        </div>
                        <div class="mb-3 p-2 rounded-3" style="background: #eaf7ea;">
                            <span class="fw-bold">Current Status:</span>
                            @if($document->status == 'completed' || $document->status == 'verified')
                                <span class="badge bg-success ms-2">Verified</span>
                            @elseif($document->status == 'processing' || $document->status == 'under_review')
                                <span class="badge bg-warning text-dark ms-2">Under Review</span>
                            @elseif($document->status == 'pending')
                                <span class="badge bg-secondary ms-2">Submitted</span>
                            @else
                                <span class="badge bg-info text-dark ms-2">{{ ucfirst($document->status) }}</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold">Document Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Type:</strong> {{ $document->document_type }}</p>
                                    <p class="mb-1"><strong>Date Submitted:</strong> {{ $document->created_at->format('M d, Y, g:i A') }}</p>
                                    <p class="mb-1"><strong>Related To:</strong> Property Registration</p>
                                    <p class="mb-1"><strong>File Type:</strong> PDF</p>
                                    <p class="mb-1"><strong>File Size:</strong> 2.4 MB</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Date Needed:</strong> {{ $document->date_needed->format('M d, Y') }}</p>
                                    <p class="mb-1"><strong>Purpose:</strong> {{ $document->purpose }}</p>
                                    @if($document->additional_notes)
                                    <p class="mb-1"><strong>Additional Notes:</strong> {{ $document->additional_notes }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 p-2 rounded-3" style="background: #f6f6f6;">
                            <strong>Description:</strong>
                            <div>{{ $document->purpose }}</div>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold">ID Photo Preview</h6>
                            <div class="p-3 rounded-3 border d-flex align-items-center justify-content-center" style="background: #f6f6f6; min-height: 120px;">
                                @if($document->id_photo)
                                    <img src="{{ asset('storage/' . $document->id_photo) }}" alt="ID Photo" class="img-fluid rounded border" style="max-height: 120px; max-width: 100%;">
                                @else
                                    <span class="text-muted">No ID photo uploaded.</span>
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
            <div class="alert alert-info text-center">No document requests found</div>
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
        $('#filterTabs button').on('click', function() {
            var status = $(this).data('status');
            $('#filterTabs button').removeClass('btn-success active').addClass('btn-outline-secondary');
            $(this).addClass('btn-success active').removeClass('btn-outline-secondary');
            if (status === 'all') {
                $('.document-card').show();
            } else if (status === 'under_review') {
                $('.document-card').hide();
                $('.document-card[data-status="processing"], .document-card[data-status="under_review"]').show();
            } else if (status === 'needs_action') {
                $('.document-card').hide();
                $('.document-card[data-status="needs_action"]').show();
            } else {
                $('.document-card').hide();
                $('.document-card[data-status="' + status + '"]').show();
            }
        });
    });
</script>
@endpush 