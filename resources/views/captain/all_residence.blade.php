@extends('layouts.cap_view_resident')

@section('content')
<h2 class="mb-4">Barangay Cantil-E</h2>
    <p class="text-muted mb-4">All Residents</p>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>All Residents</h4>
        <div class="d-flex gap-2">
            <div class="input-group">
                <select class="form-select" id="addressFilter">
                    <option value="">All Streets/Purok</option>
                    @foreach($residents->pluck('street')->unique() as $street)
                        <option value="{{ $street }}">{{ $street }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-success" onclick="printResidents()">
                <i class="fas fa-print me-1"></i>Print List
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="residentsTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Residency Status</th>
                        <th>Contact Number</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($residents as $resident)
                        <tr data-street="{{ $resident->street }}">
                            <td>{{ $resident->first_name }} {{ $resident->last_name }}</td>
                            <td>{{ $resident->residency_status }}</td>
                            <td>{{ $resident->contact_number }}</td>
                            <td>{{ $resident->street }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#residentModal{{ $resident->id }}">
                                        <i class="fas fa-eye me-1"></i>View
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
                                                    
                                                    <div class="d-flex justify-content-center gap-2 mt-3">
                                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#updatePhotoModal{{ $resident->id }}">
                                                            <i class="fas fa-edit"></i> Update Photo
                                                        </button>
                                                    </div>
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
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#updateInfoModal{{ $resident->id }}">
                                            <i class="fas fa-file-certificate me-1"></i> Update Information
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Update Photo Modal -->
                        <div class="modal fade" id="updatePhotoModal{{ $resident->id }}" tabindex="-1" aria-labelledby="updatePhotoModalLabel{{ $resident->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="updatePhotoModalLabel{{ $resident->id }}">Update Profile Picture</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('captain.residents.update-photo', $resident->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="profile_picture" class="form-label">Select New Profile Picture</label>
                                                <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*" required>
                                                <small class="text-muted">Allowed formats: JPEG, PNG, JPG. Max size: 2MB</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update Photo</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Update Information Modal -->
                        <div class="modal fade" id="updateInfoModal{{ $resident->id }}" tabindex="-1" aria-labelledby="updateInfoModalLabel{{ $resident->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="updateInfoModalLabel{{ $resident->id }}">Update Resident Information</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('captain.residents.update', $resident->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <!-- Personal Information -->
                                                <div class="col-md-6">
                                                    <label for="first_name" class="form-label">First Name</label>
                                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $resident->first_name }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="middle_name" class="form-label">Middle Name</label>
                                                    <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{ $resident->middle_name }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="last_name" class="form-label">Last Name</label>
                                                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $resident->last_name }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="suffix" class="form-label">Suffix</label>
                                                    <input type="text" class="form-control" id="suffix" name="suffix" value="{{ $resident->suffix }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ $resident->date_of_birth }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="place_of_birth" class="form-label">Place of Birth</label>
                                                    <input type="text" class="form-control" id="place_of_birth" name="place_of_birth" value="{{ $resident->place_of_birth }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="gender" class="form-label">Gender</label>
                                                    <select class="form-select" id="gender" name="gender" required>
                                                        <option value="Male" {{ $resident->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                                        <option value="Female" {{ $resident->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="civil_status" class="form-label">Civil Status</label>
                                                    <select class="form-select" id="civil_status" name="civil_status" required>
                                                        <option value="Single" {{ $resident->civil_status == 'Single' ? 'selected' : '' }}>Single</option>
                                                        <option value="Married" {{ $resident->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                                                        <option value="Widowed" {{ $resident->civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                                        <option value="Divorced" {{ $resident->civil_status == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="nationality" class="form-label">Nationality</label>
                                                    <input type="text" class="form-control" id="nationality" name="nationality" value="{{ $resident->nationality }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="religion" class="form-label">Religion</label>
                                                    <input type="text" class="form-control" id="religion" name="religion" value="{{ $resident->religion }}" required>
                                                </div>

                                                <!-- Contact Information -->
                                                <div class="col-md-6">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email" value="{{ $resident->email }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="contact_number" class="form-label">Contact Number</label>
                                                    <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{ $resident->contact_number }}" required>
                                                </div>

                                                <!-- Address Information -->
                                                <div class="col-md-6">
                                                    <label for="house_number" class="form-label">House Number</label>
                                                    <input type="text" class="form-control" id="house_number" name="house_number" value="{{ $resident->house_number }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="street" class="form-label">Street</label>
                                                    <input type="text" class="form-control" id="street" name="street" value="{{ $resident->street }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="barangay" class="form-label">Barangay</label>
                                                    <input type="text" class="form-control" id="barangay" name="barangay" value="{{ $resident->barangay }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="municipality" class="form-label">Municipality</label>
                                                    <input type="text" class="form-control" id="municipality" name="municipality" value="{{ $resident->municipality }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="zip" class="form-label">Zip Code</label>
                                                    <input type="text" class="form-control" id="zip" name="zip" value="{{ $resident->zip }}" required>
                                                </div>

                                                <!-- Family Information -->
                                                <div class="col-md-6">
                                                    <label for="father_name" class="form-label">Father's Name</label>
                                                    <input type="text" class="form-control" id="father_name" name="father_name" value="{{ $resident->father_name }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="mother_name" class="form-label">Mother's Name</label>
                                                    <input type="text" class="form-control" id="mother_name" name="mother_name" value="{{ $resident->mother_name }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="guardian_name" class="form-label">Guardian's Name</label>
                                                    <input type="text" class="form-control" id="guardian_name" name="guardian_name" value="{{ $resident->guardian_name }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="guardian_contact" class="form-label">Guardian's Contact</label>
                                                    <input type="text" class="form-control" id="guardian_contact" name="guardian_contact" value="{{ $resident->guardian_contact }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="guardian_relation" class="form-label">Relation to Guardian</label>
                                                    <select class="form-select" id="guardian_relation" name="guardian_relation" required>
                                                        <option value="Parent" {{ $resident->guardian_relation == 'Parent' ? 'selected' : '' }}>Parent</option>
                                                        <option value="Spouse" {{ $resident->guardian_relation == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                                                        <option value="Sibling" {{ $resident->guardian_relation == 'Sibling' ? 'selected' : '' }}>Sibling</option>
                                                        <option value="Relative" {{ $resident->guardian_relation == 'Relative' ? 'selected' : '' }}>Relative</option>
                                                        <option value="Friend" {{ $resident->guardian_relation == 'Friend' ? 'selected' : '' }}>Friend</option>
                                                    </select>
                                                </div>

                                                <!-- Status Information -->
                                                <div class="col-md-6">
                                                    <label for="residency_status" class="form-label">Residency Status</label>
                                                    <select class="form-select" id="residency_status" name="residency_status" required>
                                                        <option value="Permanent" {{ $resident->residency_status == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                                                        <option value="Temporary" {{ $resident->residency_status == 'Temporary' ? 'selected' : '' }}>Temporary</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="voters" class="form-label">Voter Status</label>
                                                    <select class="form-select" id="voters" name="voters" required>
                                                        <option value="Yes" {{ $resident->voters == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                        <option value="No" {{ $resident->voters == 'No' ? 'selected' : '' }}>No</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="pwd" class="form-label">PWD Status</label>
                                                    <select class="form-select" id="pwd" name="pwd" required>
                                                        <option value="Yes" {{ $resident->pwd == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                        <option value="No" {{ $resident->pwd == 'No' ? 'selected' : '' }}>No</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 pwd-type-field" style="display: {{ $resident->pwd == 'Yes' ? 'block' : 'none' }};">
                                                    <label for="pwd_type" class="form-label">PWD Type</label>
                                                    <input type="text" class="form-control" id="pwd_type" name="pwd_type" value="{{ $resident->pwd_type }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="single_parent" class="form-label">Single Parent Status</label>
                                                    <select class="form-select" id="single_parent" name="single_parent" required>
                                                        <option value="Yes" {{ $resident->single_parent == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                        <option value="No" {{ $resident->single_parent == 'No' ? 'selected' : '' }}>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update Information</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Show/hide PWD type field based on PWD status
                            const pwdSelect = document.getElementById('pwd');
                            const pwdTypeField = document.querySelector('.pwd-type-field');
                            
                            pwdSelect.addEventListener('change', function() {
                                pwdTypeField.style.display = this.value === 'Yes' ? 'block' : 'none';
                            });
                        });
                        </script>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No residents found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Address filter functionality
        const addressFilter = document.getElementById('addressFilter');
        const table = document.getElementById('residentsTable');
        
        addressFilter.addEventListener('change', function() {
            const selectedStreet = this.value;
            const rows = table.getElementsByTagName('tr');
            
            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const street = row.getAttribute('data-street');
                
                if (!selectedStreet || street === selectedStreet) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });

        // Print functionality
        window.printResidents = function() {
            const printWindow = window.open('', '_blank');
            const table = document.getElementById('residentsTable').cloneNode(true);
            
            // Remove action buttons from the cloned table
            const actionCells = table.querySelectorAll('td:last-child');
            actionCells.forEach(cell => cell.remove());
            
            const printContent = `
                <html>
                    <head>
                        <title>Residents List - Barangay Cantil-E</title>
                        <style>
                            body { font-family: Arial, sans-serif; }
                            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                            th { background-color: #f4f4f4; }
                            h1 { text-align: center; }
                            .header { text-align: center; margin-bottom: 20px; }
                        </style>
                    </head>
                    <body>
                        <div class="header">
                            <h1>Barangay Cantil-E</h1>
                            <h2>List of Residents</h2>
                            <p>Generated on: ${new Date().toLocaleDateString()}</p>
                        </div>
                        ${table.outerHTML}
                    </body>
                </html>
            `;
            
            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.print();
        };

        // Existing archive button functionality
        const archiveButtons = document.querySelectorAll('.archive-btn');
        archiveButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Archive Resident',
                    text: 'Are you sure you want to archive this resident?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, archive it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Show success/error messages
        @if(session('success'))
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif

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
@endsection
