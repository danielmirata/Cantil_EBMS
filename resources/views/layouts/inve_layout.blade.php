<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Barangay Management System') }}</title>
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.3/sweetalert2.min.css">
    <link href="{{ asset('css/secretary-dashboard.css') }}" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #22324a;
            --secondary-color: #2196f3;
            --accent-color: #00bcd4;
            --success-color: #43a047;
            --warning-color: #ffb300;
            --danger-color: #e53935;
            --light-bg: #f6f7fb;
            --sidebar-width: 250px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .wrapper {
            min-height: 100vh;
            width: 100%;
        }

        .inve-sidebar {
            width: var(--sidebar-width);
            background: var(--primary-color);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
            transition: all 0.3s;
            overflow-y: auto;
        }

        .inve-sidebar.active {
            margin-left: calc(-1 * var(--sidebar-width));
        }

        .inve-main {
            width: calc(100% - var(--sidebar-width));
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s;
            padding: 20px;
            background: var(--light-bg);
        }

        .inve-main.shifted {
            margin-left: 0;
            width: 100%;
        }

        /* Navbar Styles */
        .inve-navbar {
            background: white;
            padding: 1rem;
            border-radius: 0.75rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        }

        .inve-navbar .btn {
            color: var(--primary-color);
            padding: 0.5rem 1rem;
        }

        .inve-navbar .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
            border-radius: 0.5rem;
        }

        .inve-navbar .dropdown-item {
            padding: 0.5rem 1.5rem;
        }

        .inve-navbar .dropdown-item:hover {
            background-color: var(--light-bg);
        }

        /* Content Area */
        .content {
            padding: 1rem 0;
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .card-header {
            border-radius: 15px 15px 0 0 !important;
            padding: 20px;
            background: var(--primary-color);
            color: white;
            border-bottom: none;
        }

        /* Table Styles */
        .table {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            border-top: none;
            background: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 15px;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #eee;
        }

        .table tbody tr:hover {
            background-color: rgba(33, 150, 243, 0.05);
        }

        /* Button Styles */
        .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(33, 150, 243, 0.3);
        }

        /* Form Control Styles */
        .form-control {
            border-radius: 8px;
            border: 2px solid #eee;
            padding: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 188, 212, 0.15);
        }

        /* Badge Styles */
        .badge {
            padding: 8px 12px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Responsive Adjustments */
        @media (max-width: 991.98px) {
            .inve-sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            
            .inve-sidebar.active {
                margin-left: 0;
            }
            
            .inve-main {
                width: 100%;
                margin-left: 0;
            }
            
            .inve-main.shifted {
                margin-left: var(--sidebar-width);
            }
        }

        /* Loading Spinner */
        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--accent-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--accent-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }
    </style>

    @stack('styles')
</head>
<body>
    @include('partials.secretary-sidebar')
    
    <div class="wrapper d-flex">
        <!-- Page Content -->
        <div class="inve-main">
            <!-- Top Navbar -->
            <nav class="inve-navbar d-flex justify-content-between align-items-center mb-4">
                <div>
                    <button id="sidebarCollapse" class="btn">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <button class="btn dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="mr-2 d-none d-md-block text-right">
                                <div class="font-weight-bold">{{ auth()->user()->name ?? 'Admin User' }}</div>
                                <div class="small text-muted">{{ auth()->user()->role ?? 'Administrator' }}</div>
                            </div>
                            <div style="width:40px;height:40px;border-radius:50%;background:#e9ecef;display:flex;align-items:center;justify-content:center">
                                <i class="fas fa-user"></i>
                            </div>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="/profile">
                                <i class="fas fa-user-circle mr-2"></i> Profile
                            </a>
                            <a class="dropdown-item" href="/settings">
                                <i class="fas fa-cog mr-2"></i> Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="content">
                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="mt-5 text-center text-muted small py-3">
                <p class="mb-0">&copy; {{ date('Y') }} Barangay Management System. All rights reserved.</p>
            </footer>
        </div>
    </div>

    <!-- Core Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.3/sweetalert2.all.min.js"></script>

    <!-- Sidebar Toggle Script -->
    <script>
        $(document).ready(function() {
            $('#sidebarCollapse').on('click', function() {
                $('.inve-sidebar').toggleClass('active');
                $('.inve-main').toggleClass('shifted');
            });
            
            // Close sidebar when clicking outside on mobile
            $(document).click(function(e) {
                const container = $(".inve-sidebar, #sidebarCollapse");
                if (!container.is(e.target) && container.has(e.target).length === 0) {
                    if (window.innerWidth < 992 && $('.inve-sidebar').hasClass('active')) {
                        $('.inve-sidebar').removeClass('active');
                        $('.inve-main').removeClass('shifted');
                    }
                }
            });
            
            // Handle AJAX CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Add loading spinner for AJAX requests
            $(document).ajaxStart(function() {
                $('body').append('<div class="loading-spinner" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;"></div>');
            }).ajaxStop(function() {
                $('.loading-spinner').remove();
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
