@extends('layouts.admin_layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-archive me-2"></i>Archived Residents
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Residency Status</th>
                                    <th>Contact Number</th>
                                    <th>Address</th>
                                    <th>Archived Date</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($archived_residents as $resident)
                                    <tr>
                                        <td>{{ $resident->first_name }} {{ $resident->last_name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $resident->residency_status === 'Permanent' ? 'success' : 'warning' }}">
                                                {{ $resident->residency_status }}
                                            </span>
                                        </td>
                                        <td>{{ $resident->contact_number }}</td>
                                        <td>{{ $resident->house_number }}, {{ $resident->street }}</td>
                                        <td>
                                            @if($resident->deleted_at)
                                                {{ \Carbon\Carbon::parse($resident->deleted_at)->format('M d, Y h:i A') }}
                                            @else
                                                {{ \Carbon\Carbon::parse($resident->archived_at)->format('M d, Y h:i A') }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#residentModal{{ $resident->id }}">
                                                    <i class="fas fa-eye me-1"></i>View
                                                </button>
                                                <button type="button" class="btn btn-sm btn-success restore-btn" data-id="{{ $resident->id }}">
                                                    <i class="fas fa-undo me-1"></i>Restore
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Resident Modal -->
                                    <div class="modal fade" id="residentModal{{ $resident->id }}" tabindex="-1" aria-labelledby="residentModalLabel{{ $resident->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title" id="residentModalLabel{{ $resident->id }}">
                                                        <i class="fas fa-user-circle me-2"></i>{{ $resident->first_name }} {{ $resident->last_name }}'s Information
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="alert alert-warning">
                                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                                        This resident was archived on {{ \Carbon\Carbon::parse($resident->archived_at)->format('F d, Y') }}
                                                    </div>
                                                    <div class="row">
                                                        <!-- Profile Section -->
                                                        <div class="col-md-4 text-center border-end mb-3">
                                                            <div class="mb-3">
                                                                @if ($resident->profile_picture)
                                                                    <img src="{{ route('profile.picture', ['filename' => $resident->profile_picture]) }}" 
                                                                         alt="Profile Picture" 
                                                                         class="img-thumbnail rounded-circle mb-2" 
                                                                         style="width: 150px; height: 150px; object-fit: cover;">
                                                                @else
                                                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2"
                                                                         style="width: 150px; height: 150px;">
                                                                        <i class="fas fa-user fa-4x text-secondary"></i>
                                                                    </div>
                                                                @endif
                                                                <h4 class="mt-2">{{ $resident->first_name }} {{ $resident->last_name }}</h4>
                                                                <p class="text-muted">{{ $resident->residency_status }}</p>
                                                            </div>
                                                        </div>
                                                        <!-- Information Tabs -->
                                                        <div class="col-md-8">
                                                            <ul class="nav nav-tabs" id="residentTabs{{ $resident->id }}" role="tablist">
                                                                <li class="nav-item" role="presentation">
                                                                    <button class="nav-link active" id="personal-tab{{ $resident->id }}" data-bs-toggle="tab" 
                                                                            data-bs-target="#personal{{ $resident->id }}" type="button" role="tab" 
                                                                            aria-controls="personal{{ $resident->id }}" aria-selected="true">
                                                                        <i class="fas fa-user me-1"></i> Personal
                                                                    </button>
                                                                </li>
                                                                <li class="nav-item" role="presentation">
                                                                    <button class="nav-link" id="contact-tab{{ $resident->id }}" data-bs-toggle="tab" 
                                                                            data-bs-target="#contact{{ $resident->id }}" type="button" role="tab" 
                                                                            aria-controls="contact{{ $resident->id }}" aria-selected="false">
                                                                        <i class="fas fa-address-card me-1"></i> Contact & Address
                                                                    </button>
                                                                </li>
                                                                <li class="nav-item" role="presentation">
                                                                    <button class="nav-link" id="family-tab{{ $resident->id }}" data-bs-toggle="tab" 
                                                                            data-bs-target="#family{{ $resident->id }}" type="button" role="tab" 
                                                                            aria-controls="family{{ $resident->id }}" aria-selected="false">
                                                                        <i class="fas fa-users me-1"></i> Family
                                                                    </button>
                                                                </li>
                                                                <li class="nav-item" role="presentation">
                                                                    <button class="nav-link" id="status-tab{{ $resident->id }}" data-bs-toggle="tab" 
                                                                            data-bs-target="#status{{ $resident->id }}" type="button" role="tab" 
                                                                            aria-controls="status{{ $resident->id }}" aria-selected="false">
                                                                        <i class="fas fa-info-circle me-1"></i> Status
                                                                    </button>
                                                                </li>
                                                            </ul>
                                                            <div class="tab-content p-3 border border-top-0 rounded-bottom" id="residentTabContent{{ $resident->id }}">
                                                                <!-- Personal Information Tab -->
                                                                <div class="tab-pane fade show active" id="personal{{ $resident->id }}" role="tabpanel" 
                                                                     aria-labelledby="personal-tab{{ $resident->id }}">
                                                                    <div class="row g-3">
                                                                        <div class="col-md-6">
                                                                            <div class="info-item">
                                                                                <span class="text-muted fs-6">Full Name</span>
                                                                                <p class="fw-bold mb-1">{{ $resident->first_name }} {{ $resident->middle_name }} {{ $resident->last_name }} {{ $resident->suffix }}</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="info-item">
                                                                                <span class="text-muted fs-6">Date of Birth</span>
                                                                                <p class="fw-bold mb-1">{{ \Carbon\Carbon::parse($resident->date_of_birth)->format('F d, Y') }}</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="info-item">
                                                                                <span class="text-muted fs-6">Gender</span>
                                                                                <p class="fw-bold mb-1">{{ $resident->gender }}</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="info-item">
                                                                                <span class="text-muted fs-6">Civil Status</span>
                                                                                <p class="fw-bold mb-1">{{ $resident->civil_status }}</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="info-item">
                                                                                <span class="text-muted fs-6">Nationality</span>
                                                                                <p class="fw-bold mb-1">{{ $resident->nationality }}</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="info-item">
                                                                                <span class="text-muted fs-6">Religion</span>
                                                                                <p class="fw-bold mb-1">{{ $resident->religion }}</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="info-item">
                                                                                <span class="text-muted fs-6">Place of Birth</span>
                                                                                <p class="fw-bold mb-1">{{ $resident->place_of_birth }}</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- Contact & Address Tab -->
                                                                <div class="tab-pane fade" id="contact{{ $resident->id }}" role="tabpanel" 
                                                                     aria-labelledby="contact-tab{{ $resident->id }}">
                                                                    <div class="row g-3">
                                                                        <div class="col-md-6">
                                                                            <div class="info-item">
                                                                                <span class="text-muted fs-6">Email Address</span>
                                                                                <p class="fw-bold mb-1">{{ $resident->email ?? 'N/A' }}</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="info-item">
                                                                                <span class="text-muted fs-6">Contact Number</span>
                                                                                <p class="fw-bold mb-1">{{ $resident->contact_number }}</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <hr class="text-muted my-2">
                                                                            <h6 class="text-primary mb-3"><i class="fas fa-map-marker-alt me-2"></i>Address</h6>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="info-item">
                                                                                <span class="text-muted fs-6">House Number</span>
                                                                                <p class="fw-bold mb-1">{{ $resident->house_number }}</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="info-item">
                                                                                <span class="text-muted fs-6">Street</span>
                                                                                <p class="fw-bold mb-1">{{ $resident->street }}</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="info-item">
                                                                                <span class="text-muted fs-6">Barangay</span>
                                                                                <p class="fw-bold mb-1">{{ $resident->barangay }}</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="info-item">
                                                                                <span class="text-muted fs-6">Municipality</span>
                                                                                <p class="fw-bold mb-1">{{ $resident->municipality }}</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="info-item">
                                                                                <span class="text-muted fs-6">Zip Code</span>
                                                                                <p class="fw-bold mb-1">{{ $resident->zip }}</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- Family Information Tab -->
                                                                <div class="tab-pane fade" id="family{{ $resident->id }}" role="tabpanel" 
                                                                     aria-labelledby="family-tab{{ $resident->id }}">
                                                                    <div class="row g-3">
                                                                        <div class="col-md-6">
                                                                            <div class="info-item">
                                                                                <span class="text-muted fs-6">Father's Name</span>
                                                                                <p class="fw-bold mb-1">{{ $resident->father_name ?? 'N/A' }}</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="info-item">
                                                                                <span class="text-muted fs-6">Mother's Name</span>
                                                                                <p class="fw-bold mb-1">{{ $resident->mother_name ?? 'N/A' }}</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <hr class="text-muted my-2">
                                                                            <h6 class="text-primary mb-3"><i class="fas fa-user-shield me-2"></i>Guardian Information</h6>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="info-item">
                                                                                <span class="text-muted fs-6">Guardian's Name</span>
                                                                                <p class="fw-bold mb-1">{{ $resident->guardian_name ?: 'N/A' }}</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="info-item">
                                                                                <span class="text-muted fs-6">Guardian's Contact</span>
                                                                                <p class="fw-bold mb-1">{{ $resident->guardian_contact ?: 'N/A' }}</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="info-item">
                                                                                <span class="text-muted fs-6">Relation to Guardian</span>
                                                                                <p class="fw-bold mb-1">{{ $resident->guardian_relation ?: 'N/A' }}</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- Status Information Tab -->
                                                                <div class="tab-pane fade" id="status{{ $resident->id }}" role="tabpanel" 
                                                                     aria-labelledby="status-tab{{ $resident->id }}">
                                                                    <div class="row g-3">
                                                                        <div class="col-md-6">
                                                                            <div class="card h-100">
                                                                                <div class="card-body">
                                                                                    <h6 class="card-title text-primary">
                                                                                        <i class="fas fa-home me-2"></i>Residency Status
                                                                                    </h6>
                                                                                    <p class="card-text fw-bold mb-0">{{ $resident->residency_status }}</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="card h-100">
                                                                                <div class="card-body">
                                                                                    <h6 class="card-title text-primary">
                                                                                        <i class="fas fa-vote-yea me-2"></i>Voter Status
                                                                                    </h6>
                                                                                    <p class="card-text fw-bold mb-0">{{ $resident->voters }}</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="card h-100">
                                                                                <div class="card-body">
                                                                                    <h6 class="card-title text-primary">
                                                                                        <i class="fas fa-wheelchair me-2"></i>PWD Status
                                                                                    </h6>
                                                                                    <p class="card-text fw-bold mb-0">
                                                                                        {{ $resident->pwd }}
                                                                                        @if($resident->pwd == 'Yes')
                                                                                            <span class="d-block text-muted small">Type: {{ $resident->pwd_type }}</span>
                                                                                        @endif
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="card h-100">
                                                                                <div class="card-body">
                                                                                    <h6 class="card-title text-primary">
                                                                                        <i class="fas fa-user-friends me-2"></i>Single Parent Status
                                                                                    </h6>
                                                                                    <p class="card-text fw-bold mb-0">{{ $resident->single_parent }}</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-success restore-btn" data-id="{{ $resident->id }}">
                                                        <i class="fas fa-undo me-1"></i>Restore Resident
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No archived residents found.</td>
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

@push('scripts')
<script>
    $(document).ready(function() {
        // Handle restore button clicks
        $('.restore-btn').click(function() {
            const residentId = $(this).data('id');
            
            Swal.fire({
                title: 'Restore Resident?',
                text: "Are you sure you want to restore this resident?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, restore it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create and submit the form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/residence/${residentId}/restore`;
                    
                    // Add CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    // Add method spoofing for PATCH
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'PATCH';
                    form.appendChild(methodField);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
@endsection 