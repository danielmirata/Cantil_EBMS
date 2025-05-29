<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Barangay Inventory Management')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Chart.js for Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <!-- Alpine.js for interactivity -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/secretary-dashboard.css') }}">
    <link id="pagestyle" href="{{ asset('assets/css/dashboard.css') }}" rel="stylesheet" />
    @stack('styles')
    <style>
        .main-content { margin-left: 250px; min-height: 100vh; }
    </style>
</head>
<body class="bg-light">
    @include('partials.official-sidebar')
    <div class="main-content">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="container-fluid py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 fw-bold text-dark mb-0">
                            @yield('header-title', 'Barangay Inventory Management')
                        </h1>
                        <div class="text-muted small">
                            @yield('header-subtitle', 'Manage inventory, budgets, and track expenses efficiently')
                        </div>
                    </div>
                    <div class="text-muted small">
                        {{ date('F j, Y') }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Navigation -->
        @hasSection('navigation')
            <nav class="bg-white border-bottom">
                <div class="container-fluid">
                    @yield('navigation')
                </div>
            </nav>
        @endif

        <!-- Main Content -->
        <main class="py-4 px-3">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-top mt-4 py-3">
            <div class="container-fluid text-center text-muted small">
                © {{ date('Y') }} Barangay Cantil-E. All rights reserved.
            </div>
        </footer>
    </div>
    @stack('scripts')
</body>
</html>