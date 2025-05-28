<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Cantil-E - Secretary Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/secretary-dashboard.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    @include('partials.secretary-sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <div class="top-nav">
            <div class="dropdown me-3" style="display:inline-block;">
                <button class="btn position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notification-count" style="display:none;">0</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" id="notification-list" style="width: 350px; max-height: 400px; overflow-y: auto;">
                    <li class="dropdown-header">Notifications</li>
                    <li><hr class="dropdown-divider"></li>
                    <li class="text-center text-muted" id="no-notifications">No notifications</li>
                </ul>
            </div>
            <div class="dropdown secretary-dropdown">
                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle"></i> Secretary
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="dropdown-item">
                            @csrf
                            <button type="submit" class="btn btn-link p-0"><i class="fas fa-sign-out-alt"></i> Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Dashboard Header -->
        <div class="dashboard-header mb-4">
            <h1 class="dashboard-title">Dashboard</h1>
            <div class="dashboard-subtitle">Secretary Dashboard</div>
        </div>

        <!-- Stat Cards Row -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <div class="fw-bold fs-2 text-primary">{{ $registeredResidents }}</div>
                        <div class="text-muted">Registered Residents</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <div class="fw-bold fs-2 text-success">{{ $currentOfficials }}</div>
                        <div class="text-muted">Current Officials</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <div class="fw-bold fs-2 text-warning">{{ $awaitingProcessing }}</div>
                        <div class="text-muted">Awaiting Processing</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <div class="fw-bold fs-2 text-danger">{{ $complaintsAndBlotters }}</div>
                        <div class="text-muted">Complaints & Blotters</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Chart and Donut Row -->
        <div class="row g-3">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Resident Demographics</h5>
                        <div style="height:320px;">
                            <canvas id="demographics-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <h5 class="card-title mb-3">Processed Requests</h5>
                        <div style="height:320px; width:100%; display:flex; align-items:center; justify-content:center;">
                            <canvas id="donut-chart"></canvas>
                        </div>
                        <div class="mt-3 text-center">
                            <span class="fw-bold fs-4" id="donut-percent">{{ $processedPercent }}%</span>
                            <div class="text-muted">of requests processed</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Demographics Pie Chart
        new Chart(document.getElementById('demographics-chart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: ['Male', 'Female', 'Senior Citizens', 'Youth'],
                datasets: [{
                    data: [{{ $male }}, {{ $female }}, {{ $seniorCitizens }}, {{ $youth }}],
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // Processed Requests Donut Chart
        new Chart(document.getElementById('donut-chart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Processed', 'Unprocessed'],
                datasets: [{
                    data: [{{ $processedPercent }}, {{ 100 - $processedPercent }}],
                    backgroundColor: ['#4e73df', '#e0e0e0'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '75%',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });

        function fetchNotifications() {
            fetch('/secretary/notifications')
                .then(response => response.json())
                .then(data => {
                    const list = document.getElementById('notification-list');
                    const count = document.getElementById('notification-count');
                    list.innerHTML = '<li class="dropdown-header">Notifications</li><li><hr class="dropdown-divider"></li>';
                    if (data.length === 0) {
                        list.innerHTML += '<li class="text-center text-muted" id="no-notifications">No notifications</li>';
                        count.style.display = 'none';
                    } else {
                        data.forEach(item => {
                            list.innerHTML += `<li>
                                <div class=\"dropdown-item\">
                                    <div><strong>${item.transaction_type}</strong> - ${item.description}</div>
                                    <small class=\"text-muted\">${new Date(item.created_at).toLocaleString()}</small>
                                </div>
                            </li>`;
                        });
                        count.textContent = data.length;
                        count.style.display = '';
                    }
                });
        }
        document.addEventListener('DOMContentLoaded', function() {
            fetchNotifications();
            setInterval(fetchNotifications, 60000); // Refresh every 60 seconds
        });
    </script>
</body>
</html>