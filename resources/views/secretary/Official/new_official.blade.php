@extends('layouts.new_resident')

@section('content')
<div class="resident-form-container">
    <h2 class="mb-4">Barangay Cantil-E</h2>
    <p class="text-muted mb-4">New Official</p>
    <form action="{{ route('officials.store') }}" method="POST" enctype="multipart/form-data" id="newOfficialForm">
        @csrf
        <div class="row">
            <!-- Left Column: Official Status -->
            <div class="col-md-4">
                <div class="status-card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Official Status</h5>
                        <div class="profile-picture-container mb-3" onclick="document.getElementById('profile_picture').click();">
                            <img src="{{ asset('img/default-profile.png') }}" alt="Profile Picture" id="profilePreview" />
                        </div>
                        <small class="text-muted d-block mb-3">Click on the image to select photo</small>
                        <input type="file" class="form-control d-none" id="profile_picture" name="profile_picture" accept="image/*" onchange="previewProfilePicture(event)">

                        <h6>Position Information</h6>
                        <div class="mb-3">
                            <label class="form-label">Position</label>
                            <select class="form-select" name="position_id" required>
                                <option value="">Select Position</option>
                                @foreach ($positions as $position)
                                    <option value="{{ $position->id }}">{{ $position->position_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Term Start</label>
                            <input type="date" class="form-control" name="term_start" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Term End</label>
                            <input type="date" class="form-control" name="term_end" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Tabs -->
            <div class="col-md-8">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" id="officialTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab" aria-controls="personal" aria-selected="true">
                            <i class="bi bi-person"></i> Personal Details
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">
                            <i class="bi bi-geo-alt"></i> Contact &amp; Address
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="family-tab" data-bs-toggle="tab" data-bs-target="#family" type="button" role="tab" aria-controls="family" aria-selected="false">
                            <i class="bi bi-people"></i> Family Info
                        </button>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- Personal Details Tab -->
                    <div class="tab-pane fade show active form-card" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-person-badge"></i> Personal Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="middle_name" class="form-label">Middle Name</label>
                                    <input type="text" class="form-control" id="middle_name" name="middle_name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="suffix" class="form-label">Suffix</label>
                                    <input type="text" class="form-control" id="suffix" name="suffix" placeholder="Jr., Sr., III, etc.">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="place_of_birth" class="form-label">Place of Birth</label>
                                    <input type="text" class="form-control" id="place_of_birth" name="place_of_birth" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Gender</label>
                                    <select class="form-select" name="gender" required>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Civil Status</label>
                                    <select class="form-select" name="civil_status" required>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Widowed">Widowed</option>
                                        <option value="Divorced">Divorced</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="nationality" class="form-label">Nationality</label>
                                    <input type="text" class="form-control" id="nationality" name="nationality" value="Filipino" required>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="religion" class="form-label">Religion</label>
                                    <input type="text" class="form-control" id="religion" name="religion" required>
                                </div>
                            </div>
                            <div class="form-navigation">
                                <div></div>
                                <button type="button" class="btn btn-primary" id="toContact">Next &rarr;</button>
                            </div>
                        </div>
                    </div>

                    <!-- Contact & Address Tab -->
                    <div class="tab-pane fade form-card" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-telephone"></i> Contact &amp; Address Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="example@email.com" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="contact_number" class="form-label">Contact Number</label>
                                    <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="09XX XXX XXXX" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="house_number" class="form-label">House/Lot/Unit No.</label>
                                    <input type="text" class="form-control" id="house_number" name="house_number" placeholder="House number" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="street" class="form-label">Street/Purok</label>
                                    <select class="form-select" id="street" name="street" required>
                                        <option value="">Select Street/Purok...</option>
                                        <option value="Mapahiyumon">Mapahiyumon</option>
                                        <option value="Mauswagon">Mauswagon</option>
                                        <option value="Madasigon">Madasigon</option>
                                        <option value="Matinabangon">Matinabangon</option>
                                        <option value="Malipayon">Malipayon</option>
                                        <option value="Makugihon">Makugihon</option>
                                        <option value="Maalagaron">Maalagaron</option>
                                        <option value="Matinagdanon">Matinagdanon</option>
                                        <option value="Maabi-abihon">Maabi-abihon</option>
                                        <option value="Twin Heart">Twin Heart</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="barangay" class="form-label">Barangay</label>
                                    <input type="text" class="form-control" id="barangay" name="barangay" value="Cantil-E" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="municipality" class="form-label">Municipality/City</label>
                                    <input type="text" class="form-control" id="municipality" name="municipality" value="Dumaguete City" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="zip" class="form-label">Postal/ZIP Code</label>
                                    <input type="text" class="form-control" id="zip" name="zip" value="6200" required>
                                </div>
                            </div>
                            <div class="form-navigation">
                                <button type="button" class="btn btn-secondary" id="toPersonal">&larr; Previous</button>
                                <button type="button" class="btn btn-primary" id="toFamily">Next &rarr;</button>
                            </div>
                        </div>
                    </div>

                    <!-- Family Info Tab -->
                    <div class="tab-pane fade form-card" id="family" role="tabpanel" aria-labelledby="family-tab">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-house-door"></i> Family Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="father_name" class="form-label">Father's Name</label>
                                    <input type="text" class="form-control" id="father_name" name="father_name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="mother_name" class="form-label">Mother's Name</label>
                                    <input type="text" class="form-control" id="mother_name" name="mother_name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="guardian_name" class="form-label">Guardian's Name</label>
                                    <input type="text" class="form-control" id="guardian_name" name="guardian_name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="guardian_contact" class="form-label">Guardian's Contact</label>
                                    <input type="text" class="form-control" id="guardian_contact" name="guardian_contact" required>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Guardian's Relation</label>
                                    <select class="form-select" name="guardian_relation" required>
                                        <option value="Parent">Parent</option>
                                        <option value="Spouse">Spouse</option>
                                        <option value="Sibling">Sibling</option>
                                        <option value="Relative">Relative</option>
                                        <option value="Friend">Friend</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-navigation">
                                <button type="button" class="btn btn-secondary" id="toContact">&larr; Previous</button>
                                <button type="submit" class="btn btn-success">Save Official</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Preview profile picture on file select
    function previewProfilePicture(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profilePreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Tab navigation buttons
    document.getElementById('toContact').addEventListener('click', function() {
        var tabTrigger = new bootstrap.Tab(document.querySelector('#contact-tab'));
        tabTrigger.show();
    });

    document.getElementById('toFamily').addEventListener('click', function() {
        var tabTrigger = new bootstrap.Tab(document.querySelector('#family-tab'));
        tabTrigger.show();
    });

    document.getElementById('toPersonal').addEventListener('click', function() {
        var tabTrigger = new bootstrap.Tab(document.querySelector('#personal-tab'));
        tabTrigger.show();
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Handle form submission
        const form = document.getElementById('newOfficialForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Add New Official',
                    text: 'Are you sure you want to add this new official?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, add it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }

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

        // Show validation errors if any
        @if($errors->any())
            Swal.fire({
                title: 'Validation Error!',
                html: `
                    <ul class="text-start">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        @endif
    });
</script>
@endsection
