<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Barangay Document Management System') }}</title>
    
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/logo.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
    
    <!-- Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    <!-- CSS Files -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link id="pagestyle" href="{{ asset('assets/css/dashboard.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/secretary-dashboard.css') }}" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        .bg-gradient-primary {
            background-image: linear-gradient(310deg, #5e72e4 0%, #825ee4 100%);
        }
        
        .navbar-vertical.navbar-expand-xs {
            z-index: 1000;
        }
        
        .navbar-vertical.navbar-expand-xs .navbar-collapse {
            height: calc(100vh - 150px);
        }
        
        .icon-shape {
            width: 48px;
            height: 48px;
            background-position: center;
            border-radius: 0.75rem;
        }
        
        .navbar-vertical .navbar-nav .nav-link {
            padding-left: 1rem;
            padding-right: 1rem;
            margin: 0.15rem 0;
        }
        
        .navbar-vertical .navbar-nav .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
        }
        
        .card {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-radius: 0.75rem;
            border: 0;
        }
        
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
        
        .badge {
            text-transform: uppercase;
            padding: 0.55em 0.9em;
        }
        
        .badge-sm {
            font-size: 0.65em;
            padding: 0.45em 0.775em;
        }
        
        .bg-success {
            background-color: #2dce89 !important;
        }
        
        .bg-warning {
            background-color: #fb6340 !important;
        }
        
        .bg-info {
            background-color: #11cdef !important;
        }
        
        .bg-primary {
            background-color: #5e72e4 !important;
        }
        
        .bg-danger {
            background-color: #f5365c !important;
        }
        
        .btn-primary {
            background-color: #5e72e4;
            border-color: #5e72e4;
        }
        
        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #4d61c9;
            border-color: #4d61c9;
        }
        
        .main-content {
            position: relative;
        }
        
        @media (min-width: 768px) {
            .main-content {
                margin-left: 17.125rem;
            }
        }
    </style>
    
    @yield('styles')
</head>

<body class="g-sidenav-show bg-gray-100">
    <!-- Sidebar -->
    @include('partials.captain-sidebar')
    
    <!-- Main content -->
    <main class="main-content position-relative border-radius-lg">
       
        <!-- End Navbar -->
        
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mx-4" role="alert">
                <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
                <span class="alert-text">{{ session('success') }}</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mx-4" role="alert">
                <span class="alert-icon"><i class="fas fa-exclamation-circle"></i></span>
                <span class="alert-text">{{ session('error') }}</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show mx-4" role="alert">
                <span class="alert-icon"><i class="fas fa-exclamation-triangle"></i></span>
                <span class="alert-text">{{ session('warning') }}</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show mx-4" role="alert">
                <span class="alert-icon"><i class="fas fa-info-circle"></i></span>
                <span class="alert-text">{{ session('info') }}</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        
        <!-- Content -->
        @yield('content')
        
        <!-- Footer -->
        <footer class="footer pt-3">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-lg-between">
                    <div class="col-lg-6 mb-lg-0 mb-4">
                        <div class="copyright text-center text-sm text-muted text-lg-start">
                            © <script>
                                document.write(new Date().getFullYear())
                            </script> Barangay Management Information System
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                            <li class="nav-item">
                                <a href="{{ route('official.dashboard') }}" class="nav-link text-muted">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link text-muted">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link text-muted">Contact</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </main>
    
    <!-- Core JS Files -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    
    <!-- App JS -->
    <script>
        $(document).ready(function() {
            // Toggle sidebar on small screens
            $('#iconSidenav').on('click', function() {
                $('body').toggleClass('g-sidenav-pinned');
                $('#sidenav-main').toggleClass('bg-transparent');
            });
            
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
            
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        });
    </script>
    
    @yield('scripts')
</body>
</html>