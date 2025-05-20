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

        .bp-sidebar {
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

        .bp-sidebar.active {
            margin-left: calc(-1 * var(--sidebar-width));
        }

        .bp-main {
            width: calc(100% - var(--sidebar-width));
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s;
            padding: 20px;
            background: var(--light-bg);
        }

        .bp-main.shifted {
            margin-left: 0;
            width: 100%;
        }

        /* Navbar Styles */
        .bp-navbar {
            background: white;
            padding: 1rem;
            border-radius: 0.75rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        }

        .bp-navbar .btn {
            color: var(--primary-color);
            padding: 0.5rem 1rem;
        }

        .bp-navbar .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
            border-radius: 0.5rem;
        }

        .bp-navbar .dropdown-item {
            padding: 0.5rem 1.5rem;
        }

        .bp-navbar .dropdown-item:hover {
            background-color: var(--light-bg);
        }

        /* Content Area */
        .content {
            padding: 1rem 0;
        }

        /* General Utility Classes */
        .shadow-sm {
            box-shadow: 0 .125rem .25rem rgba(0,0,0,.075) !important;
        }
        
        .rounded-lg {
            border-radius: 0.75rem !important;
        }
        
        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Responsive Adjustments */
        @media (max-width: 991.98px) {
            .bp-sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            
            .bp-sidebar.active {
                margin-left: 0;
            }
            
            .bp-main {
                width: 100%;
                margin-left: 0;
            }
            
            .bp-main.shifted {
                margin-left: var(--sidebar-width);
            }
        }

        /* Pagination Styles */
        .pagination {
            margin-bottom: 0;
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .page-link {
            color: var(--primary-color);
        }

        .page-link:hover {
            color: var(--primary-color);
            background-color: var(--light-bg);
        }
    </style>

    <!-- Additional Styles -->
    @stack('styles')
</head>
<body>
@include('partials.secretary-sidebar')
    <div class="wrapper d-flex">
        <!-- Sidebar -->
      

        <!-- Page Content -->
        <div class="bp-main">
            <!-- Top Navbar -->
            <nav class="bp-navbar d-flex justify-content-between align-items-center mb-4">
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
                $('.bp-sidebar').toggleClass('active');
                $('.bp-main').toggleClass('shifted');
            });
            
            // Close sidebar when clicking outside on mobile
            $(document).click(function(e) {
                const container = $(".bp-sidebar, #sidebarCollapse");
                if (!container.is(e.target) && container.has(e.target).length === 0) {
                    if (window.innerWidth < 992 && $('.bp-sidebar').hasClass('active')) {
                        $('.bp-sidebar').removeClass('active');
                        $('.bp-main').removeClass('shifted');
                    }
                }
            });
            
            // Handle AJAX CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>

    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>