{{-- This is a partial view for the modal form --}}
@csrf
@method('PATCH')

<style>
:root {
    --primary-color: #2563eb;
    --secondary-color: #1e40af;
    --border-color: #e5e7eb;
    --text-color: #1f2937;
    --bg-light: #f9fafb;
}

.profile-upload {
    position: relative;
    width: 200px;
    height: 200px;
    margin: 0 auto 1.5rem;
    border-radius: 50%;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    transition: transform 0.3s ease;
}

.profile-upload:hover {
    transform: scale(1.02);
}

.profile-upload .profile-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border: 4px solid #fff;
}

.profile-upload .upload-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0, 0, 0, 0.7);
    padding: 10px;
    text-align: center;
    color: white;
    font-size: 0.9rem;
    cursor: pointer;
    opacity: 0;
    transition: all 0.3s ease;
}

.profile-upload:hover .upload-overlay {
    opacity: 1;
}

.form-section {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    padding: 2rem;
    margin-bottom: 1.5rem;
    border: 1px solid var(--border-color);
}

.form-section-title {
    color: var(--text-color);
    border-bottom: 2px solid var(--primary-color);
    padding-bottom: 0.75rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 600;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid var(--border-color);
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
    background-color: var(--bg-light);
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    background-color: #fff;
}

.form-label {
    font-weight: 500;
    color: var(--text-color);
    margin-bottom: 0.5rem;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.card-title {
    color: var(--text-color);
    font-weight: 600;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--primary-color);
    margin-bottom: 1.5rem;
}

.text-danger {
    color: #dc2626 !important;
}

.row {
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .form-section {
        padding: 1.5rem;
    }
    
    .profile-upload {
        width: 150px;
        height: 150px;
    }
}
</style>

<div class="container-fluid py-4">
    <div class="row">
        <!-- Profile Information -->
        <div class="col-md-4 mb-4">
            <div class="form-section">
                <h5 class="form-section-title">
                    <i class="fas fa-user-circle"></i> Profile Information
                </h5>
                <div class="mb-4">
                    <label for="position_id" class="form-label">Position <span class="text-danger">*</span></label>
                    <select class="form-select" id="position_id" name="position_id" required>
                        @foreach($positions as $position)
                            <option value="{{ $position->id }}" {{ $official->position_id == $position->id ? 'selected' : '' }}>
                                {{ $position->position_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="term_start" class="form-label">Term Start <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="term_start" name="term_start" value="{{ $official->term_start }}" required>
                </div>

                <div class="mb-4">
                    <label for="term_end" class="form-label">Term End <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="term_end" name="term_end" value="{{ $official->term_end }}" required>
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="card-title">Personal Information</h5>
                    
                    <!-- Name Information -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $official->first_name }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="middle_name" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{ $official->middle_name }}">
                        </div>
                        <div class="col-md-3">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $official->last_name }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="suffix" class="form-label">Suffix</label>
                            <input type="text" class="form-control" id="suffix" name="suffix" value="{{ $official->suffix }}">
                        </div>
                    </div>

                    <!-- Personal Details -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ $official->date_of_birth }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="place_of_birth" class="form-label">Place of Birth <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="place_of_birth" name="place_of_birth" value="{{ $official->place_of_birth }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="Male" {{ $official->gender === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ $official->gender === 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="civil_status" class="form-label">Civil Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="civil_status" name="civil_status" required>
                                <option value="Single" {{ $official->civil_status === 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="Married" {{ $official->civil_status === 'Married' ? 'selected' : '' }}>Married</option>
                                <option value="Widowed" {{ $official->civil_status === 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                <option value="Divorced" {{ $official->civil_status === 'Divorced' ? 'selected' : '' }}>Divorced</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="nationality" class="form-label">Nationality <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nationality" name="nationality" value="{{ $official->nationality }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="religion" class="form-label">Religion <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="religion" name="religion" value="{{ $official->religion }}" required>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{ $official->contact_number }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $official->email }}">
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="house_number" class="form-label">House Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="house_number" name="house_number" value="{{ $official->house_number }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="street" class="form-label">Street <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="street" name="street" value="{{ $official->street }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="barangay" class="form-label">Barangay <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="barangay" name="barangay" value="{{ $official->barangay }}" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label for="municipality" class="form-label">Municipality <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="municipality" name="municipality" value="{{ $official->municipality }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="zip" class="form-label">Zip Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="zip" name="zip" value="{{ $official->zip }}" required>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="Active" {{ $official->status === 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ $official->status === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <!-- Guardian Information -->
                    <div class="card-body p-4 mt-4">
                        <h5 class="card-title">Guardian Information</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label for="guardian_name" class="form-label">Guardian Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="guardian_name" name="guardian_name" value="{{ $official->guardian_name ?? '' }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="guardian_contact" class="form-label">Guardian Contact <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="guardian_contact" name="guardian_contact" value="{{ $official->guardian_contact ?? '' }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="guardian_relation" class="form-label">Guardian Relation <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="guardian_relation" name="guardian_relation" value="{{ $official->guardian_relation ?? '' }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('profile_picture').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePreview').src = e.target.result;
        };
        reader.readAsDataURL(e.target.files[0]);
    }
});
</script>
