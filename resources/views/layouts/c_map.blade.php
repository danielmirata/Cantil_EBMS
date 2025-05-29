<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CANTIL E-SYSTEM')</title>
    
    <!-- Global CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="{{ asset('css/secretary-dashboard.css') }}" rel="stylesheet">
    
    <style>
        .wrapper {
            display: flex;
        }
        
        main {
            flex: 1;
            padding: 20px;
            margin-left: 250px; /* Width of the sidebar */
        }
        
        /* Sidebar base styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="wrapper">
        @include('partials.captain-sidebar')
        <!-- Main Content -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Global Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html> 