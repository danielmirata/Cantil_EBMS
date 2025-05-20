<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantil E-System - Dashboard</title>
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
            border-radius: 16px;
            margin-bottom: 1rem;
            font-size: 1.2rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
        }
        .main-btn i {
            font-size: 1.7rem;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container d-flex flex-column align-items-center justify-content-center min-vh-100">
        <div class="landing-card w-100" style="max-width: 420px;">
            <img src="{{ asset('img/cantil-e-logo.png') }}" alt="Barangay Logo" class="main-logo">
            <h5 class="text-center mb-0">WELCOME, {{ strtoupper(auth()->user()->fullname) }}</h5>
            <p class="text-center text-muted mb-4">What would you like to do?</p>
            <a href="{{ route('resident.services') }}" class="btn btn-light border main-btn text-start"><i class="fas fa-tools text-danger"></i> <span class="text-danger">SERVICES</span></a>
            <a href="{{ route('resident.documents') }}" class="btn btn-light border main-btn text-start"><i class="fas fa-file-alt text-danger"></i> <span class="text-danger">DOCUMENTS</span></a>
            <a href="#" class="btn btn-light border main-btn text-start"><i class="fas fa-user text-danger"></i> <span class="text-danger">PROFILE</span></a>
            <a href="#" class="btn btn-light border main-btn text-start"><i class="fas fa-info-circle text-danger"></i> <span class="text-danger">ABOUT US</span></a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 