<!-- Personal Information -->
<form id="editResidentForm" method="POST" action="{{ route('secretary.residence.update', $resident->id) }}">
    @csrf
    @method('PATCH')
    <div class="row g-3">
        <div class="col-md-4">
            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $resident->first_name }}" required>
        </div>
        <div class="col-md-4">
            <label for="middle_name" class="form-label">Middle Name</label>
            <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{ $resident->middle_name }}">
        </div>
        <div class="col-md-4">
            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $resident->last_name }}" required>
        </div>
        <div class="col-md-4">
            <label for="suffix" class="form-label">Suffix</label>
            <input type="text" class="form-control" id="suffix" name="suffix" value="{{ $resident->suffix }}">
        </div>

        <div class="col-md-4">
            <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ $resident->date_of_birth }}" required>
        </div>
        <div class="col-md-4">
            <label for="place_of_birth" class="form-label">Place of Birth <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="place_of_birth" name="place_of_birth" value="{{ $resident->place_of_birth }}" required>
        </div>
        <div class="col-md-4">
            <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
            <select class="form-select" id="gender" name="gender" required>
                <option value="Male" {{ $resident->gender === 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ $resident->gender === 'Female' ? 'selected' : '' }}>Female</option>
            </select>
        </div>

        <div class="col-md-4">
            <label for="civil_status" class="form-label">Civil Status <span class="text-danger">*</span></label>
            <select class="form-select" id="civil_status" name="civil_status" required>
                <option value="Single" {{ $resident->civil_status === 'Single' ? 'selected' : '' }}>Single</option>
                <option value="Married" {{ $resident->civil_status === 'Married' ? 'selected' : '' }}>Married</option>
                <option value="Widowed" {{ $resident->civil_status === 'Widowed' ? 'selected' : '' }}>Widowed</option>
                <option value="Divorced" {{ $resident->civil_status === 'Divorced' ? 'selected' : '' }}>Divorced</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="nationality" class="form-label">Nationality <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nationality" name="nationality" value="{{ $resident->nationality }}" required>
        </div>
        <div class="col-md-4">
            <label for="religion" class="form-label">Religion <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="religion" name="religion" value="{{ $resident->religion }}" required>
        </div>
    </div>

    <hr class="my-4">

    <!-- Contact Information -->
    <div class="row g-3">
        <div class="col-md-6">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $resident->email }}">
        </div>
        <div class="col-md-6">
            <label for="contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{ $resident->contact_number }}" required>
        </div>
    </div>

    <hr class="my-4">

    <!-- Address Information -->
    <div class="row g-3">
        <div class="col-md-4">
            <label for="house_number" class="form-label">House Number <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="house_number" name="house_number" value="{{ $resident->house_number }}" required>
        </div>
        <div class="col-md-4">
            <label for="street" class="form-label">Street <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="street" name="street" value="{{ $resident->street }}" required>
        </div>
        <div class="col-md-4">
            <label for="barangay" class="form-label">Barangay <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="barangay" name="barangay" value="{{ $resident->barangay }}" required>
        </div>
        <div class="col-md-4">
            <label for="municipality" class="form-label">Municipality <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="municipality" name="municipality" value="{{ $resident->municipality }}" required>
        </div>
        <div class="col-md-4">
            <label for="zip" class="form-label">Zip Code <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="zip" name="zip" value="{{ $resident->zip }}" required>
        </div>
    </div>

    <hr class="my-4">

    <!-- Family Information -->
    <div class="row g-3">
        <div class="col-md-6">
            <label for="father_name" class="form-label">Father's Name</label>
            <input type="text" class="form-control" id="father_name" name="father_name" value="{{ $resident->father_name }}">
        </div>
        <div class="col-md-6">
            <label for="mother_name" class="form-label">Mother's Name</label>
            <input type="text" class="form-control" id="mother_name" name="mother_name" value="{{ $resident->mother_name }}">
        </div>
        <div class="col-md-4">
            <label for="guardian_name" class="form-label">Guardian's Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="guardian_name" name="guardian_name" value="{{ $resident->guardian_name }}" required>
        </div>
        <div class="col-md-4">
            <label for="guardian_contact" class="form-label">Guardian's Contact <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="guardian_contact" name="guardian_contact" value="{{ $resident->guardian_contact }}" required>
        </div>
        <div class="col-md-4">
            <label for="guardian_relation" class="form-label">Relation to Guardian <span class="text-danger">*</span></label>
            <select class="form-select" id="guardian_relation" name="guardian_relation" required>
                <option value="Parent" {{ $resident->guardian_relation === 'Parent' ? 'selected' : '' }}>Parent</option>
                <option value="Spouse" {{ $resident->guardian_relation === 'Spouse' ? 'selected' : '' }}>Spouse</option>
                <option value="Sibling" {{ $resident->guardian_relation === 'Sibling' ? 'selected' : '' }}>Sibling</option>
                <option value="Relative" {{ $resident->guardian_relation === 'Relative' ? 'selected' : '' }}>Relative</option>
                <option value="Friend" {{ $resident->guardian_relation === 'Friend' ? 'selected' : '' }}>Friend</option>
            </select>
        </div>
    </div>

    <hr class="my-4">

    <!-- Status Information -->
    <div class="row g-3">
        <div class="col-md-4">
            <label for="residency_status" class="form-label">Residency Status <span class="text-danger">*</span></label>
            <select class="form-select" id="residency_status" name="residency_status" required>
                <option value="Permanent" {{ $resident->residency_status === 'Permanent' ? 'selected' : '' }}>Permanent</option>
                <option value="Temporary" {{ $resident->residency_status === 'Temporary' ? 'selected' : '' }}>Temporary</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="voters" class="form-label">Registered Voter <span class="text-danger">*</span></label>
            <select class="form-select" id="voters" name="voters" required>
                <option value="Yes" {{ $resident->voters === 'Yes' ? 'selected' : '' }}>Yes</option>
                <option value="No" {{ $resident->voters === 'No' ? 'selected' : '' }}>No</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="pwd" class="form-label">PWD Status <span class="text-danger">*</span></label>
            <select class="form-select" id="pwd" name="pwd" required>
                <option value="Yes" {{ $resident->pwd === 'Yes' ? 'selected' : '' }}>Yes</option>
                <option value="No" {{ $resident->pwd === 'No' ? 'selected' : '' }}>No</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="pwd_type" class="form-label">PWD Type</label>
            <input type="text" class="form-control" id="pwd_type" name="pwd_type" value="{{ $resident->pwd_type }}" {{ $resident->pwd === 'No' ? 'disabled' : '' }}>
        </div>
        <div class="col-md-4">
            <label for="single_parent" class="form-label">Single Parent <span class="text-danger">*</span></label>
            <select class="form-select" id="single_parent" name="single_parent" required>
                <option value="Yes" {{ $resident->single_parent === 'Yes' ? 'selected' : '' }}>Yes</option>
                <option value="No" {{ $resident->single_parent === 'No' ? 'selected' : '' }}>No</option>
            </select>
        </div>
    </div>

    <div class="text-end mt-4">
        <button type="submit" class="btn btn-primary">Update Information</button>
    </div>
</form>
