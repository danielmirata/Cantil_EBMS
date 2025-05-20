<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantil E-System - Complaint Form</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f5f5;
        }
        .form-card {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 2rem;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }
        .main-logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin: 0 auto 1rem auto;
            display: block;
        }
        .form-control, .form-select {
            border-radius: 12px;
            padding: 0.7rem 1rem;
            border: 1px solid #ddd;
            margin-bottom: 0.5rem;
        }
        .form-label {
            font-weight: 500;
            color: #444;
            margin-bottom: 0.3rem;
        }
        .section-title {
            color: #8B0000;
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(139, 0, 0, 0.1);
        }
        .main-btn {
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 500;
            padding: 0.8rem 1.5rem;
        }
        .main-btn-red {
            background: #8B0000;
            color: #fff;
            border: none;
        }
        .main-btn-outline {
            border: 2px solid #8B0000;
            color: #8B0000;
            background: #fff;
        }
        .footer-text {
            font-size: 0.95rem;
            color: #888;
            margin-top: 1.5rem;
            text-align: center;
        }
        .form-check-input:checked {
            background-color: #8B0000;
            border-color: #8B0000;
        }
    </style>
</head>
<body>
    <div class="container d-flex flex-column align-items-center justify-content-center">
        <div class="form-card w-100" style="max-width: 700px;">
            <img src="{{ asset('img/cantil-e-logo.png') }}" alt="Barangay Logo" class="main-logo">
            <h3 class="text-center mb-0" style="color:#8B0000; font-weight:700;">Barangay Complaint Form</h3>
            <p class="text-center text-muted mb-4">Submit your complaint to the barangay office</p>
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            <form method="POST" action="{{ route('resident.complain.store') }}" enctype="multipart/form-data">
                @csrf
                <h5 class="section-title">Complainant Information</h5>
                <div class="row mb-3">
                    <div class="col-md-6 mb-2">
                        <label class="form-label">First Name *</label>
                        <input type="text" name="first_name" class="form-control" required value="{{ old('first_name') }}">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Last Name *</label>
                        <input type="text" name="last_name" class="form-control" required value="{{ old('last_name') }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Contact Number *</label>
                        <input type="text" name="contact_number" class="form-control" required value="{{ old('contact_number') }}">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Complete Address *</label>
                    <input type="text" name="complete_address" class="form-control" required value="{{ old('complete_address') }}">
                </div>
                
                <h5 class="section-title">Complaint Details</h5>
                <div class="mb-3">
                    <label class="form-label">Complaint Type *</label>
                    <select name="complaint_type" class="form-select" required>
                        <option value="">Select Complaint Type</option>
                        <option value="Noise">Noise</option>
                        <option value="Vandalism">Vandalism</option>
                        <option value="Dispute">Dispute</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Incident Date *</label>
                        <input type="date" name="incident_date" class="form-control" required value="{{ old('incident_date') }}">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Incident Time *</label>
                        <input type="time" name="incident_time" class="form-control" required value="{{ old('incident_time') }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Incident Location *</label>
                    <input type="text" name="incident_location" class="form-control" required value="{{ old('incident_location') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Complaint Description *</label>
                    <textarea name="complaint_description" class="form-control" rows="3" required>{{ old('complaint_description') }}</textarea>
                </div>
                
                <h5 class="section-title">Evidence</h5>
                <div class="mb-3">
                    <label class="form-label">Upload Evidence Photo</label>
                    <input type="file" name="evidence_photo" class="form-control">
                </div>
                
                <h5 class="section-title">Declaration</h5>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="declaration" id="declaration" required>
                    <label class="form-check-label" for="declaration">
                        I declare that all information provided above is true and correct to the best of my knowledge
                    </label>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('resident.dashboard') }}" class="btn main-btn main-btn-outline">Cancel</a>
                    <button type="submit" class="btn main-btn main-btn-red">Submit Complaint</button>
                </div>
            </form>
            
            <div class="footer-text mt-4">
                By submitting this form, you agree to our Terms of Service and Privacy Policy
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>