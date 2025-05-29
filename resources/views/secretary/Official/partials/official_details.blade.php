<div class="container-fluid">
    <div class="row">
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
                <p class="text-muted">{{ $official->position->position_name ?? 'N/A' }}</p>
                
                <div class="d-flex justify-content-center gap-2 mt-3">
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#updatePhotoModal">
                        <i class="fas fa-edit"></i> Update Photo
                    </button>
                </div>
            </div>
        </div>
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
            </div>
        </div>
    </div>
</div> 