@extends('layouts.new_resident')

@section('content')
<div class="resident-form-container">
    <h2 class="mb-4">Barangay Cantil-E</h2>
    <p class="text-muted mb-4">New Resident</p>
    <form id="residentForm" method="POST" action="{{ route('secretary.residence.store') }}" enctype="multipart/form-data">
        @csrf
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div id="formErrors" class="alert alert-danger d-none">
            <ul id="errorList"></ul>
        </div>
        <div class="row">
            <!-- Left Column: Resident Status -->
            <div class="col-md-4">
                <div class="status-card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Resident Status</h5>
                        <div class="profile-picture-container mb-3" onclick="document.getElementById('profile_picture').click();">
                            <img src="{{ asset('img/default-profile.png') }}" alt="Profile Picture" id="profilePreview" />
                        </div>
                        <small class="text-muted d-block mb-3">Click on the image to select photo</small>
                        <input type="file" class="form-control d-none" id="profile_picture" name="profile_picture" accept="image/*" onchange="previewProfilePicture(event)">

                        <h6>Status Information</h6>
                        <div class="mb-3">
                            <label class="form-label">Residency Status</label>
                            <select class="form-select" name="residency_status" required>
                                <option value="Permanent">Permanent</option>
                                <option value="Present">Present</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Registered Voter</label>
                            <select class="form-select" name="voters" required>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">PWD Status</label>
                            <select class="form-select" name="pwd" required>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="pwd_type" class="form-label">PWD Type</label>
                            <input type="text" class="form-control" id="pwd_type" name="pwd_type" placeholder="Enter PWD Type (if applicable)">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Single Parent</label>
                            <select class="form-select" name="single_parent" required>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Tabs -->
            <div class="col-md-8">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" id="residentTab" role="tablist">
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
                                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="middle_name" class="form-label">Middle Name</label>
                                    <input type="text" class="form-control" id="middle_name" name="middle_name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="suffix" class="form-label">Suffix</label>
                                    <input type="text" class="form-control" id="suffix" name="suffix" placeholder="Jr., Sr., III, etc.">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="place_of_birth" class="form-label">Place of Birth <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="place_of_birth" name="place_of_birth" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                                    <select class="form-select" name="gender" required>
                                        <option value="">Select gender...</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Civil Status <span class="text-danger">*</span></label>
                                    <select class="form-select" name="civil_status" required>
                                        <option value="">Select civil status...</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Widowed">Widowed</option>
                                        <option value="Divorced">Divorced</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="nationality" class="form-label">Nationality <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nationality" name="nationality" value="Filipino" required>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="religion" class="form-label">Religion <span class="text-danger">*</span></label>
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
                                    <input type="email" class="form-control" id="email" name="email" placeholder="example@email.com">
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
                            
                            <!-- Household Section -->
                            <div class="household-section mb-4">
                                <h6 class="mb-3">Household Information</h6>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Household Type</label>
                                        <select class="form-select" name="household_type" id="household_type" required>
                                            <option value="new">New Household</option>
                                            <option value="existing">Existing Household</option>
                                        </select>
                                    </div>
                                    
                                    <!-- New Household Fields -->
                                    <div id="new_household_fields">
                                        <div class="col-md-12 mb-3">
                                            <label for="household_name" class="form-label">Household Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="household_name" name="household_name" placeholder="Enter household name (e.g., Family Name)" required>
                                        </div>
                                    </div>
                                    
                                    <!-- Existing Household Fields -->
                                    <div id="existing_household_fields" style="display: none;">
                                        <div class="col-md-12 mb-3">
                                            <label for="existing_household" class="form-label">Select Household <span class="text-danger">*</span></label>
                                            <select class="form-select" id="existing_household" name="existing_household" required>
                                                <option value="">Select a household...</option>
                                                @foreach($households as $household)
                                                    <option value="{{ $household->id }}" 
                                                        data-address="{{ $household->house_number }}, {{ $household->street }}, {{ $household->barangay }}">
                                                        {{ $household->name }} - {{ $household->house_number }}, {{ $household->street }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select a valid household.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="father_name" class="form-label">Father's Name</label>
                                    <input type="text" class="form-control" id="father_name" name="father_name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="mother_name" class="form-label">Mother's Name</label>
                                    <input type="text" class="form-control" id="mother_name" name="mother_name">
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
                                <button type="submit" class="btn btn-success" id="submitResident">Save Resident</button>
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

    // Form validation and submission
    document.addEventListener('DOMContentLoaded', function() {
        // Handle household type switching
        const householdType = document.getElementById('household_type');
        const newHouseholdFields = document.getElementById('new_household_fields');
        const existingHouseholdFields = document.getElementById('existing_household_fields');
        const householdName = document.getElementById('household_name');
        const existingHousehold = document.getElementById('existing_household');

        // Set initial state
        if (householdType.value === 'new') {
            newHouseholdFields.style.display = 'block';
            existingHouseholdFields.style.display = 'none';
            householdName.required = true;
            existingHousehold.required = false;
        } else {
            newHouseholdFields.style.display = 'none';
            existingHouseholdFields.style.display = 'block';
            householdName.required = false;
            existingHousehold.required = true;
        }

        householdType.addEventListener('change', function() {
            if (this.value === 'new') {
                newHouseholdFields.style.display = 'block';
                existingHouseholdFields.style.display = 'none';
                householdName.required = true;
                existingHousehold.required = false;
                existingHousehold.value = ''; // Clear existing household selection
            } else {
                newHouseholdFields.style.display = 'none';
                existingHouseholdFields.style.display = 'block';
                householdName.required = false;
                existingHousehold.required = true;
                householdName.value = ''; // Clear household name
            }
        });

        // Handle existing household selection
        existingHousehold.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const address = selectedOption.getAttribute('data-address');
                // Auto-fill address fields
                const addressParts = address.split(', ');
                document.getElementById('house_number').value = addressParts[0];
                document.getElementById('street').value = addressParts[1];
                document.getElementById('barangay').value = addressParts[2];
                
                // Log the selected household for debugging
                console.log('Selected household:', {
                    id: selectedOption.value,
                    name: selectedOption.text,
                    address: address
                });

                // Validate the selection
                if (!selectedOption.value) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            }
        });

        // Handle form submission
        const form = document.getElementById('residentForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate all required fields
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;
                let firstInvalidField = null;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('is-invalid');
                        if (!firstInvalidField) {
                            firstInvalidField = field;
                        }
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                // Validate household fields
                if (householdType.value === 'new' && !householdName.value.trim()) {
                    isValid = false;
                    householdName.classList.add('is-invalid');
                    if (!firstInvalidField) {
                        firstInvalidField = householdName;
                    }
                }
                
                if (householdType.value === 'existing') {
                    if (!existingHousehold.value) {
                        isValid = false;
                        existingHousehold.classList.add('is-invalid');
                        if (!firstInvalidField) {
                            firstInvalidField = existingHousehold;
                        }
                    } else {
                        // Verify the selected household exists
                        const selectedOption = existingHousehold.options[existingHousehold.selectedIndex];
                        if (!selectedOption || !selectedOption.value) {
                            isValid = false;
                            existingHousehold.classList.add('is-invalid');
                            if (!firstInvalidField) {
                                firstInvalidField = existingHousehold;
                            }
                        }
                    }
                }

                if (!isValid) {
                    Swal.fire({
                        title: 'Validation Error!',
                        text: 'Please fill in all required fields correctly.',
                        icon: 'error'
                    });
                    if (firstInvalidField) {
                        firstInvalidField.focus();
                    }
                    return;
                }

                // Log form data before submission
                console.log('Form data before submission:', {
                    household_type: householdType.value,
                    household_name: householdName.value,
                    existing_household: existingHousehold.value,
                    selected_household_text: existingHousehold.options[existingHousehold.selectedIndex]?.text
                });

                Swal.fire({
                    title: 'Add New Resident',
                    text: 'Are you sure you want to add this new resident?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, add it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        const submitButton = document.getElementById('submitResident');
                        const originalButtonText = submitButton.innerHTML;
                        submitButton.disabled = true;
                        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';

                        // Submit form using fetch API
                        const formData = new FormData(this);
                        
                        // Remove fields not needed for the selected household type
                        if (householdType.value === 'new') {
                            formData.delete('existing_household');
                        }
                        if (householdType.value === 'existing') {
                            formData.delete('household_name');
                        }

                        // Log form data for debugging
                        console.log('Form data being submitted:');
                        for (let pair of formData.entries()) {
                            console.log(pair[0] + ': ' + pair[1]);
                        }

                        fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            credentials: 'same-origin'
                        })
                        .then(response => {
                            const contentType = response.headers.get('content-type');
                            if (contentType && contentType.includes('application/json')) {
                                return response.json();
                            }
                            throw new Error('Server returned non-JSON response');
                        })
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: data.message,
                                    icon: 'success'
                                }).then(() => {
                                    window.location.href = '{{ route('secretary.residence.all') }}';
                                });
                            } else {
                                let errorMessage = data.message || 'An error occurred while adding the resident';
                                if (data.errors) {
                                    errorMessage = Object.values(data.errors).flat().join('\n');
                                }
                                Swal.fire({
                                    title: 'Error!',
                                    text: errorMessage,
                                    icon: 'error'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: error.message || 'An error occurred. Please try again.',
                                icon: 'error'
                            });
                        })
                        .finally(() => {
                            // Reset button state
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalButtonText;
                        });
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
