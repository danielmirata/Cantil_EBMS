<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantil E-System - Documents</title>
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
    <style>
        body {
            background: #f5f5f5;
        }
        .landing-card {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 2rem 1rem;
            margin-top: 2rem;
        }
        .main-logo {
            width: 120px;
            height: 120px;
            object-fit: contain;
            margin: 0 auto 1rem auto;
            display: block;
        }
        .main-btn {
            border-radius: 12px;
            margin-bottom: 1rem;
            font-size: 1.1rem;
            font-weight: 500;
            padding: 1rem 1.5rem;
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
        .contact-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            padding: 1.2rem 1rem;
            margin-top: 1.5rem;
        }
        .footer-text {
            font-size: 0.95rem;
            color: #888;
            margin-top: 1.5rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container d-flex flex-column align-items-center justify-content-center min-vh-100">
        <div class="landing-card w-100" style="max-width: 420px;">
            <img src="{{ asset('img/cantil-e-logo.png') }}" alt="Barangay Logo" class="main-logo">
            <h3 class="text-center mb-0" style="color:#8B0000; font-weight:700;">Cantil E e-Request</h3>
            <p class="text-center text-muted mb-4">Welcome to Barangay e-Request</p>
            <a href="{{ route('resident.documents.status') }}" class="btn main-btn main-btn-red w-100 mb-2">Requested Docs</a>
            <a href="{{ route('resident.complaints.status') }}" class="btn main-btn main-btn-outline w-100"> Complain</a>
            <div class="contact-card mt-4">
                <h5 class="text-center mb-2" style="color:#8B0000; font-weight:600;">Contact Information</h5>
                <p class="text-center mb-1">Need assistance? Contact your barangay office.</p>
                <p class="text-center mb-1">Operating Hours: 8:00 AM - 5:00 PM</p>
                <p class="text-center mb-1">Monday-Saturday</p>
                <p class="text-center mb-0">Contact: 09876543211</p>
            </div>
            <div class="footer-text mt-3">
                By continuing, you agree to our Terms of Service and Privacy Policy
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
