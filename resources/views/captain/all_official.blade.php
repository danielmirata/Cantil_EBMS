@extends('layouts.cap_view_resident')

@section('content')
<h2 class="mb-4">Barangay Cantil-E</h2>

    <p class="text-muted mb-4">All Officials</p>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>All Officials</h4>
        <div class="d-flex gap-2">
            <div class="input-group">
                <select class="form-select" id="addressFilter">
                    <option value="">All Addresses</option>
                    @foreach($officials->pluck('barangay')->unique() as $barangay)
                        <option value="{{ $barangay }}">{{ $barangay }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-success" onclick="printOfficials()">
                <i class="fas fa-print me-1"></i>Print List
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="officialsTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Contact Number</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($officials as $official)
                        <tr data-barangay="{{ $official->barangay }}">
                            <td>{{ $official->first_name }} {{ $official->last_name }}</td>
                            <td>{{ $official->position->position_name }}</td>
                            <td>{{ $official->contact_number }}</td>
                            <td>{{ $official->barangay }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#officialModal{{ $official->id }}">
                                        <i class="fas fa-eye me-1"></i>View
                                    </button>
                                   
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
                                                    
                                                    <div class="d-flex justify-content-center gap-2 mt-3">
                                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#updatePhotoModal{{ $official->id }}">
                                                            <i class="fas fa-edit"></i> Update Photo
                                                        </button>
                                                    </div>
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
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#updateInfoModal{{ $official->id }}">
                                            <i class="fas fa-file-certificate me-1"></i> Update Information
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Update Photo Modal -->
                        <div class="modal fade" id="updatePhotoModal{{ $official->id }}" tabindex="-1" aria-labelledby="updatePhotoModalLabel{{ $official->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="updatePhotoModalLabel{{ $official->id }}">Update Profile Picture</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('captain.officials.update-photo', $official->id) }}" method="POST" enctype="multipart/form-data">
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
                        <div class="modal fade" id="updateInfoModal{{ $official->id }}" tabindex="-1" aria-labelledby="updateInfoModalLabel{{ $official->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="updateInfoModalLabel{{ $official->id }}">Update Official Information</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('captain.officials.update', $official->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <!-- Personal Information -->
                                                <div class="col-md-6">
                                                    <label for="first_name" class="form-label">First Name</label>
                                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $official->first_name }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="middle_name" class="form-label">Middle Name</label>
                                                    <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{ $official->middle_name }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="last_name" class="form-label">Last Name</label>
                                                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $official->last_name }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="suffix" class="form-label">Suffix</label>
                                                    <input type="text" class="form-control" id="suffix" name="suffix" value="{{ $official->suffix }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ $official->date_of_birth }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="place_of_birth" class="form-label">Place of Birth</label>
                                                    <input type="text" class="form-control" id="place_of_birth" name="place_of_birth" value="{{ $official->place_of_birth }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="gender" class="form-label">Gender</label>
                                                    <select class="form-select" id="gender" name="gender" required>
                                                        <option value="Male" {{ $official->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                                        <option value="Female" {{ $official->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="civil_status" class="form-label">Civil Status</label>
                                                    <select class="form-select" id="civil_status" name="civil_status" required>
                                                        <option value="Single" {{ $official->civil_status == 'Single' ? 'selected' : '' }}>Single</option>
                                                        <option value="Married" {{ $official->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                                                        <option value="Widowed" {{ $official->civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                                        <option value="Divorced" {{ $official->civil_status == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="nationality" class="form-label">Nationality</label>
                                                    <input type="text" class="form-control" id="nationality" name="nationality" value="{{ $official->nationality }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="religion" class="form-label">Religion</label>
                                                    <input type="text" class="form-control" id="religion" name="religion" value="{{ $official->religion }}" required>
                                                </div>

                                                <!-- Contact Information -->
                                                <div class="col-md-6">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email" value="{{ $official->email }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="contact_number" class="form-label">Contact Number</label>
                                                    <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{ $official->contact_number }}" required>
                                                </div>

                                                <!-- Address Information -->
                                                <div class="col-md-6">
                                                    <label for="house_number" class="form-label">House Number</label>
                                                    <input type="text" class="form-control" id="house_number" name="house_number" value="{{ $official->house_number }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="street" class="form-label">Street</label>
                                                    <input type="text" class="form-control" id="street" name="street" value="{{ $official->street }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="barangay" class="form-label">Barangay</label>
                                                    <input type="text" class="form-control" id="barangay" name="barangay" value="{{ $official->barangay }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="municipality" class="form-label">Municipality</label>
                                                    <input type="text" class="form-control" id="municipality" name="municipality" value="{{ $official->municipality }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="zip" class="form-label">Zip Code</label>
                                                    <input type="text" class="form-control" id="zip" name="zip" value="{{ $official->zip }}" required>
                                                </div>

                                                <!-- Family Information -->
                                                <div class="col-md-6">
                                                    <label for="father_name" class="form-label">Father's Name</label>
                                                    <input type="text" class="form-control" id="father_name" name="father_name" value="{{ $official->father_name }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="mother_name" class="form-label">Mother's Name</label>
                                                    <input type="text" class="form-control" id="mother_name" name="mother_name" value="{{ $official->mother_name }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="guardian_name" class="form-label">Guardian's Name</label>
                                                    <input type="text" class="form-control" id="guardian_name" name="guardian_name" value="{{ $official->guardian_name }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="guardian_contact" class="form-label">Guardian's Contact</label>
                                                    <input type="text" class="form-control" id="guardian_contact" name="guardian_contact" value="{{ $official->guardian_contact }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="guardian_relation" class="form-label">Relation to Guardian</label>
                                                    <select class="form-select" id="guardian_relation" name="guardian_relation" required>
                                                        <option value="Parent" {{ $official->guardian_relation == 'Parent' ? 'selected' : '' }}>Parent</option>
                                                        <option value="Spouse" {{ $official->guardian_relation == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                                                        <option value="Sibling" {{ $official->guardian_relation == 'Sibling' ? 'selected' : '' }}>Sibling</option>
                                                        <option value="Relative" {{ $official->guardian_relation == 'Relative' ? 'selected' : '' }}>Relative</option>
                                                        <option value="Friend" {{ $official->guardian_relation == 'Friend' ? 'selected' : '' }}>Friend</option>
                                                    </select>
                                                </div>

                                                <!-- Position Information -->
                                                <div class="col-md-6">
                                                    <label for="position_id" class="form-label">Position</label>
                                                    <input type="text" class="form-control" value="{{ $official->position->position_name }}" disabled>
                                                    <input type="hidden" name="position_id" value="{{ $official->position_id }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="term_start" class="form-label">Term Start</label>
                                                    <input type="date" class="form-control" id="term_start" name="term_start" value="{{ $official->term_start }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="term_end" class="form-label">Term End</label>
                                                    <input type="date" class="form-control" id="term_end" name="term_end" value="{{ $official->term_end }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="status" class="form-label">Status</label>
                                                    <select class="form-select" id="status" name="status" required>
                                                        <option value="Active" {{ $official->status == 'Active' ? 'selected' : '' }}>Active</option>
                                                        <option value="Inactive" {{ $official->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
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
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No officials found.</td>
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
        // Address filter functionality
        const addressFilter = document.getElementById('addressFilter');
        const table = document.getElementById('officialsTable');
        
        addressFilter.addEventListener('change', function() {
            const selectedBarangay = this.value;
            const rows = table.getElementsByTagName('tr');
            
            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const barangay = row.getAttribute('data-barangay');
                
                if (!selectedBarangay || barangay === selectedBarangay) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });

        // Print functionality
        window.printOfficials = function() {
            const printWindow = window.open('', '_blank');
            const table = document.getElementById('officialsTable').cloneNode(true);
            
            // Remove action buttons from the cloned table
            const actionCells = table.querySelectorAll('td:last-child');
            actionCells.forEach(cell => cell.remove());
            
            const printContent = `
                <html>
                    <head>
                        <title>Officials List - Barangay Cantil-E</title>
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
                            <h2>List of Officials</h2>
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
                    title: 'Archive Official',
                    text: 'Are you sure you want to archive this official?',
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
