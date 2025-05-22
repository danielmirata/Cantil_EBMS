@extends('layouts.admin_layout')

@section('content')
<h2 class="mb-4">Barangay Cantil-E</h2>
    <p class="text-muted mb-4">Archive Officials</p>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Archived Officials</h4>
        <a href="{{ route('admin.officials.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to All Officials
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Contact Number</th>
                        <th>Status</th>
                        <th>Archive Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($archived_officials as $official)
                        <tr>
                            <td>{{ $official->first_name }} {{ $official->last_name }}</td>
                            <td>{{ $official->position->position_name }}</td>
                            <td>{{ $official->contact_number }}</td>
                            <td>{{ $official->status }}</td>
                            <td>
                                @if($official->archived_at)
                                    {{ \Carbon\Carbon::parse($official->archived_at)->format('M d, Y') }}
                                @elseif($official->deleted_at)
                                    {{ \Carbon\Carbon::parse($official->deleted_at)->format('M d, Y') }}
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#officialModal{{ $official->id }}">
                                        <i class="fas fa-eye me-1"></i>View
                                    </button>
                                    <form action="{{ route('admin.officials.restore', $official->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success restore-btn" data-id="{{ $official->id }}">
                                            <i class="fas fa-undo me-1"></i>Restore
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Official Modal -->
                        <div class="modal fade" id="officialModal{{ $official->id }}" tabindex="-1" aria-labelledby="officialModalLabel{{ $official->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="officialModalLabel{{ $official->id }}">
                                            <i class="fas fa-user-circle me-2"></i>{{ $official->first_name }} {{ $official->last_name }}'s Information
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            This official was archived on {{ \Carbon\Carbon::parse($official->archived_at)->format('F d, Y') }}
                                        </div>
                                        <div class="row">
                                            <!-- Profile Section -->
                                            <div class="col-md-4 text-center border-end mb-3">
                                                <div class="mb-3">
                                                    @if ($official->profile_picture)
                                                        <img src="{{ route('profile.picture', ['filename' => $official->profile_picture]) }}" 
                                                             alt="Profile Picture" 
                                                             class="img-thumbnail rounded-circle mb-2" 
                                                             style="width: 150px; height: 150px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2"
                                                             style="width: 150px; height: 150px;">
                                                            <i class="fas fa-user fa-4x text-secondary"></i>
                                                        </div>
                                                    @endif
                                                    <h4 class="mt-2">{{ $official->first_name }} {{ $official->last_name }}</h4>
                                                    <p class="text-muted">{{ $official->position->position_name }}</p>
                                                </div>
                                            </div>
                                            
                                            <!-- Information Tabs -->
                                            <div class="col-md-8">
                                                <ul class="nav nav-tabs" id="officialTabs{{ $official->id }}" role="tablist">
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link active" id="personal-tab{{ $official->id }}" data-bs-toggle="tab" 
                                                                data-bs-target="#personal{{ $official->id }}" type="button" role="tab" 
                                                                aria-controls="personal{{ $official->id }}" aria-selected="true">
                                                            <i class="fas fa-user me-1"></i> Personal
                                                        </button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link" id="contact-tab{{ $official->id }}" data-bs-toggle="tab" 
                                                                data-bs-target="#contact{{ $official->id }}" type="button" role="tab" 
                                                                aria-controls="contact{{ $official->id }}" aria-selected="false">
                                                            <i class="fas fa-address-card me-1"></i> Contact & Address
                                                        </button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link" id="family-tab{{ $official->id }}" data-bs-toggle="tab" 
                                                                data-bs-target="#family{{ $official->id }}" type="button" role="tab" 
                                                                aria-controls="family{{ $official->id }}" aria-selected="false">
                                                            <i class="fas fa-users me-1"></i> Family
                                                        </button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link" id="position-tab{{ $official->id }}" data-bs-toggle="tab" 
                                                                data-bs-target="#position{{ $official->id }}" type="button" role="tab" 
                                                                aria-controls="position{{ $official->id }}" aria-selected="false">
                                                            <i class="fas fa-briefcase me-1"></i> Position
                                                        </button>
                                                    </li>
                                                </ul>
                                                
                                                <div class="tab-content p-3 border border-top-0 rounded-bottom" id="officialTabContent{{ $official->id }}">
                                                    <!-- Personal Information Tab -->
                                                    <div class="tab-pane fade show active" id="personal{{ $official->id }}" role="tabpanel" 
                                                         aria-labelledby="personal-tab{{ $official->id }}">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <div class="info-item">
                                                                    <span class="text-muted fs-6">Full Name</span>
                                                                    <p class="fw-bold mb-1">{{ $official->first_name }} {{ $official->middle_name }} {{ $official->last_name }} {{ $official->suffix }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="info-item">
                                                                    <span class="text-muted fs-6">Date of Birth</span>
                                                                    <p class="fw-bold mb-1">{{ \Carbon\Carbon::parse($official->date_of_birth)->format('F d, Y') }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="info-item">
                                                                    <span class="text-muted fs-6">Gender</span>
                                                                    <p class="fw-bold mb-1">{{ $official->gender }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="info-item">
                                                                    <span class="text-muted fs-6">Civil Status</span>
                                                                    <p class="fw-bold mb-1">{{ $official->civil_status }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="info-item">
                                                                    <span class="text-muted fs-6">Nationality</span>
                                                                    <p class="fw-bold mb-1">{{ $official->nationality }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="info-item">
                                                                    <span class="text-muted fs-6">Religion</span>
                                                                    <p class="fw-bold mb-1">{{ $official->religion }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="info-item">
                                                                    <span class="text-muted fs-6">Place of Birth</span>
                                                                    <p class="fw-bold mb-1">{{ $official->place_of_birth }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Contact & Address Tab -->
                                                    <div class="tab-pane fade" id="contact{{ $official->id }}" role="tabpanel" 
                                                         aria-labelledby="contact-tab{{ $official->id }}">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <div class="info-item">
                                                                    <span class="text-muted fs-6">Email Address</span>
                                                                    <p class="fw-bold mb-1">{{ $official->email ?? 'N/A' }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="info-item">
                                                                    <span class="text-muted fs-6">Contact Number</span>
                                                                    <p class="fw-bold mb-1">{{ $official->contact_number }}</p>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-12">
                                                                <hr class="text-muted my-2">
                                                                <h6 class="text-primary mb-3"><i class="fas fa-map-marker-alt me-2"></i>Address</h6>
                                                            </div>
                                                            
                                                            <div class="col-md-6">
                                                                <div class="info-item">
                                                                    <span class="text-muted fs-6">House Number</span>
                                                                    <p class="fw-bold mb-1">{{ $official->house_number }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="info-item">
                                                                    <span class="text-muted fs-6">Street</span>
                                                                    <p class="fw-bold mb-1">{{ $official->street }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="info-item">
                                                                    <span class="text-muted fs-6">Barangay</span>
                                                                    <p class="fw-bold mb-1">{{ $official->barangay }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="info-item">
                                                                    <span class="text-muted fs-6">Municipality</span>
                                                                    <p class="fw-bold mb-1">{{ $official->municipality }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="info-item">
                                                                    <span class="text-muted fs-6">Zip Code</span>
                                                                    <p class="fw-bold mb-1">{{ $official->zip }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Family Information Tab -->
                                                    <div class="tab-pane fade" id="family{{ $official->id }}" role="tabpanel" 
                                                         aria-labelledby="family-tab{{ $official->id }}">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <div class="info-item">
                                                                    <span class="text-muted fs-6">Father's Name</span>
                                                                    <p class="fw-bold mb-1">{{ $official->father_name ?? 'N/A' }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="info-item">
                                                                    <span class="text-muted fs-6">Mother's Name</span>
                                                                    <p class="fw-bold mb-1">{{ $official->mother_name ?? 'N/A' }}</p>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-12">
                                                                <hr class="text-muted my-2">
                                                                <h6 class="text-primary mb-3"><i class="fas fa-user-shield me-2"></i>Guardian Information</h6>
                                                            </div>
                                                            
                                                            <div class="col-md-6">
                                                                <div class="info-item">
                                                                    <span class="text-muted fs-6">Guardian's Name</span>
                                                                    <p class="fw-bold mb-1">{{ $official->guardian_name ?: 'N/A' }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="info-item">
                                                                    <span class="text-muted fs-6">Guardian's Contact</span>
                                                                    <p class="fw-bold mb-1">{{ $official->guardian_contact ?: 'N/A' }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="info-item">
                                                                    <span class="text-muted fs-6">Relation to Guardian</span>
                                                                    <p class="fw-bold mb-1">{{ $official->guardian_relation ?: 'N/A' }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Position Information Tab -->
                                                    <div class="tab-pane fade" id="position{{ $official->id }}" role="tabpanel" 
                                                         aria-labelledby="position-tab{{ $official->id }}">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <div class="card h-100">
                                                                    <div class="card-body">
                                                                        <h6 class="card-title text-primary">
                                                                            <i class="fas fa-briefcase me-2"></i>Position
                                                                        </h6>
                                                                        <p class="card-text fw-bold mb-0">{{ $official->position->position_name }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="card h-100">
                                                                    <div class="card-body">
                                                                        <h6 class="card-title text-primary">
                                                                            <i class="fas fa-calendar me-2"></i>Term Period
                                                                        </h6>
                                                                        <p class="card-text fw-bold mb-0">
                                                                            {{ \Carbon\Carbon::parse($official->term_start)->format('M d, Y') }} - 
                                                                            {{ \Carbon\Carbon::parse($official->term_end)->format('M d, Y') }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="card h-100">
                                                                    <div class="card-body">
                                                                        <h6 class="card-title text-primary">
                                                                            <i class="fas fa-check-circle me-2"></i>Status
                                                                        </h6>
                                                                        <p class="card-text fw-bold mb-0">{{ $official->status }}</p>
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
                                        <form action="{{ route('admin.officials.restore', $official->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-undo me-1"></i>Restore Official
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No archived officials found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle restore button clicks
        const restoreButtons = document.querySelectorAll('.restore-btn');
        restoreButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Restore Official',
                    text: 'Are you sure you want to restore this official?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, restore it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Show success message if exists
        @if(session('success'))
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif

        // Show error message if exists
        @if(session('error'))
            Swal.fire({
                title: 'Error!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        @endif
    });
</script>
@endpush
