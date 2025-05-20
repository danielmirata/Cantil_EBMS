<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantil E-System - Document Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="theme-color" content="#8B0000">
    <meta name="description" content="Cantil E-System - Barangay Document Request and Complaint System">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Cantil E">
    <link rel="apple-touch-icon" href="/images/icons/icon-192x192.png">
    <link rel="apple-touch-startup-image" href="/images/splash/splash-640x1136.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)">
    <link rel="apple-touch-startup-image" href="/images/splash/splash-750x1334.png" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)">
    <link rel="apple-touch-startup-image" href="/images/splash/splash-1242x2208.png" media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3)">
    <link rel="apple-touch-startup-image" href="/images/splash/splash-1125x2436.png" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)">
    <link rel="apple-touch-startup-image" href="/images/splash/splash-828x1792.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)">
    <link rel="apple-touch-startup-image" href="/images/splash/splash-1242x2688.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)">
    <link rel="apple-touch-startup-image" href="/images/splash/splash-1536x2048.png" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)">
    <link rel="apple-touch-startup-image" href="/images/splash/splash-1668x2224.png" media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)">
    <link rel="apple-touch-startup-image" href="/images/splash/splash-1668x2388.png" media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2)">
    <link rel="apple-touch-startup-image" href="/images/splash/splash-2048x2732.png" media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)">
    <link rel="icon" href="/favicon.ico">
    <style>
        body {
            background: #f5f5f5;
        }
        .request-card {
            background: rgba(255,255,255,0.97);
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 2rem 1rem 1rem 1rem;
            margin-top: 2rem;
        }
        .main-logo {
            width: 90px;
            height: 90px;
            object-fit: contain;
            margin: 0 auto 0.5rem auto;
            display: block;
        }
        .form-title {
            color: #8B0000;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1rem;
        }
        .form-label {
            font-weight: 600;
        }
        .btn-primary, .btn-danger {
            border-radius: 10px;
            font-weight: 600;
        }
        .btn-danger {
            background: #8B0000;
            border: none;
        }
        .btn-danger:active, .btn-danger:focus, .btn-danger:hover {
            background: #a80000;
        }
        .form-check-label {
            font-size: 0.97rem;
        }
        .return-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            color: #0d6efd;
            text-decoration: underline;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container d-flex flex-column align-items-center justify-content-center min-vh-100">
        <div class="request-card w-100" style="max-width: 480px;">
            <a href="{{ url()->previous() }}" class="text-dark mb-2" style="text-decoration:none;display:inline-block;">
                <i class="fas fa-arrow-left"></i> <span style="font-weight:500;">Request</span>
            </a>
            <img src="{{ asset('img/cantil-e-logo.png') }}" alt="Barangay Logo" class="main-logo">
            <h4 class="form-title">Barangay Document Request Form</h4>
            <form method="POST" action="{{ route('resident.requestdocs.submit') }}" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="mb-3">
                    <label for="document_type" class="form-label">Document Type *</label>
                    <select class="form-select" id="document_type" name="document_type" required>
                        <option value="">Select Document Type</option>
                        <option value="Barangay Clearance">Barangay Clearance</option>
                        <option value="Certificate of Residency">Certificate of Residency</option>
                        <option value="Barangay Certification">Barangay Certification</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="first_name" class="form-label">First Name *</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                    <div class="col">
                        <label for="last_name" class="form-label">Last Name *</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="contact_number" class="form-label">Contact Number *</label>
                        <input type="tel" class="form-control" id="contact_number" name="contact_number" required>
                    </div>
                    <div class="col">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Purok *</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="date_needed" class="form-label">Date Needed *</label>
                        <input type="date" class="form-control" id="date_needed" name="date_needed" required>
                    </div>
                    <div class="col">
                        <label for="purpose" class="form-label">Purpose of Request *</label>
                        <input type="text" class="form-control" id="purpose" name="purpose" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="notes" class="form-label">Additional Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Enter additional notes or special requests"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">ID Verification</label>
                    <div class="row">
                        <div class="col">
                            <select class="form-select" name="id_type" required>
                                <option value="">Select ID Type</option>
                                <option value="PhilHealth">PhilHealth</option>
                                <option value="SSS">SSS</option>
                                <option value="UMID">UMID</option>
                                <option value="Driver's License">Driver's License</option>
                                <option value="Voter's ID">Voter's ID</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col">
                            <input class="form-control" type="file" name="id_photo" accept="image/*" required>
                        </div>
                    </div>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="declaration" name="declaration" required>
                    <label class="form-check-label" for="declaration">I declare that all information provided above is true and correct</label>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('resident.dashboard') }}" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-danger">Submit Request</button>
                </div>
            </form>
            <a href="{{ route('resident.dashboard') }}" class="return-link">Return to Dashboard</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
