<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Barangay Cantil-E - Projects Management (Official View)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/official-dashboard.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <link href="{{ asset('css/secretary-dashboard.css') }}" rel="stylesheet">

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .print-content, .print-content * {
                visibility: visible;
            }
            .print-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
        .stepper-wrapper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            width: 100%;
            max-width: 600px;
        }
        .stepper-item {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
        }
        .stepper-item:not(:last-child):after {
            content: '';
            position: absolute;
            top: 20px;
            right: -50%;
            width: 100%;
            height: 2px;
            background-color: #e0e0e0;
            z-index: 1;
        }
        .stepper-item.active:not(:last-child):after {
            background-color: #0d6efd;
        }
        .stepper-button {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e0e0e0;
            border: none;
            color: #666;
            font-weight: bold;
            z-index: 2;
        }
        .stepper-button.active {
            background-color: #0d6efd;
            color: white;
        }
        .stepper-label {
            margin-top: 8px;
            font-size: 14px;
            color: #666;
        }
        .stepper-item.active .stepper-label {
            color: #0d6efd;
            font-weight: bold;
        }
        .badge {
            font-size: 0.85rem;
            font-weight: 500;
            letter-spacing: 0.3px;
        }
        
        .badge.bg-success {
            background-color: #198754 !important;
        }
        
        .badge.bg-primary {
            background-color: #0d6efd !important;
        }
        
        .badge.bg-info {
            background-color: #0dcaf0 !important;
        }
        
        .badge.bg-danger {
            background-color: #dc3545 !important;
        }
        
        .badge i {
            font-size: 0.9rem;
        }
        .info-item {
            margin-bottom: 1rem;
        }
        .info-item:last-child {
            margin-bottom: 0;
        }
        .badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
        .card {
            border: none;
            border-radius: 10px;
        }
        .modal-body {
            padding: 1.5rem;
        }
        .modal-header {
            border-radius: 10px 10px 0 0;
        }
        .modal-footer {
            border-radius: 0 0 10px 10px;
        }
        .text-primary {
            color: #0d6efd !important;
        }
        .custom-tabs .nav-link {
            color: #6c757d;
            font-weight: 500;
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            font-size: 1.1rem;
            padding: 0.5rem 1.5rem;
            transition: color 0.2s, border-bottom 0.2s;
        }
        .custom-tabs .nav-link.active {
            color: #600000;
            font-weight: bold;
            border-bottom: 3px solid #600000;
            background: none;
        }
        .custom-tabs .nav-link i {
            font-size: 1.1rem;
        }
        .custom-tabs .nav-link:focus {
            outline: none;
            box-shadow: none;
        }
    </style>
</head>
<body>
    @include('partials.captain-sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <div class="top-nav">
            <div class="dropdown official-dropdown">
                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle"></i> Captain
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
        <div class="dashboard-header">
            <h1 class="dashboard-title">Projects Management</h1>
            <div class="dashboard-subtitle">Monitor and review all development projects in your barangay</div>
        </div>

        @if(isset($project))
        <!-- Project Details View -->
        <div class="content-card mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Project Details</h2>
                <div class="btn-group">
                    <a href="{{ route('official.projects.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left mr-1"></i>Back to Projects
                    </a>
                    <button type="button" class="btn btn-warning" onclick="editProject({{ $project->id }})">
                        <i class="fas fa-edit mr-1"></i>Edit Project
                    </button>
                </div>
            </div>
            <!-- Project details content will be added here -->
        </div>
        @else
        <!-- Stats Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="stats-card blue-card">
                    <div>
                        <div class="number">{{ $totalProjects ?? 0 }}</div>
                        <div class="label">Total Projects</div>
                    </div>
                    <i class="fas fa-clipboard-list"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card yellow-card">
                    <div>
                        <div class="number">{{ $ongoingProjects ?? 0 }}</div>
                        <div class="label">Ongoing</div>
                    </div>
                    <i class="fas fa-spinner"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card green-card">
                    <div>
                        <div class="number">{{ $completedProjects ?? 0 }}</div>
                        <div class="label">Completed</div>
                    </div>
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card red-card">
                    <div>
                        <div class="number">{{ $pendingProjects ?? 0 }}</div>
                        <div class="label">Pending</div>
                    </div>
                    <i class="fas fa-exclamation-circle"></i>
                </div>
            </div>
        </div>

        <!-- Projects Table -->
        <div class="content-card mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Projects List</h2>
                <div class="d-flex">
                    <input type="text" id="searchProject" class="form-control me-2" placeholder="Search projects...">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                        <i class="fas fa-plus"></i> Add New Project
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0" id="projectsTable">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="align-middle">PROJECT NAME</th>
                            <th class="align-middle">LOCATION</th>
                            <th class="align-middle">START DATE</th>
                            <th class="align-middle">END DATE</th>
                            <th class="align-middle">BUDGET</th>
                            <th class="align-middle">PROGRESS</th>
                            <th class="align-middle">STATUS</th>
                            <th class="align-middle">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                        <tr>
                            <td class="font-weight-bold">{{ $project->project_name }}</td>
                            <td>{{ $project->location ?? '-' }}</td>
                            <td>{{ date('M d, Y', strtotime($project->start_date)) }}</td>
                            <td>{{ date('M d, Y', strtotime($project->end_date)) }}</td>
                            <td>₱{{ number_format($project->budget, 2) }}</td>
                            <td>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ isset($project->progress) ? $project->progress : 0 }}%;" aria-valuenow="{{ isset($project->progress) ? $project->progress : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="text-center small mt-1">{{ isset($project->progress) ? $project->progress : 0 }}%</div>
                            </td>
                            <td>
                                @php
                                    $statusClass = [
                                        'Completed' => 'success',
                                        'Ongoing' => 'primary',
                                        'Planning' => 'info',
                                        'On Hold' => 'danger'
                                    ][$project->status] ?? 'secondary';
                                    
                                    $statusIcon = [
                                        'Completed' => 'check-circle',
                                        'Ongoing' => 'spinner fa-spin',
                                        'Planning' => 'clipboard-list',
                                        'On Hold' => 'pause-circle'
                                    ][$project->status] ?? 'question-circle';
                                @endphp
                                <span class="badge bg-{{ $statusClass }} text-white px-3 py-2">
                                    <i class="fas fa-{{ $statusIcon }} me-1"></i>
                                    {{ $project->status }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-info" onclick="viewProject({{ $project->id }})" data-toggle="tooltip" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning" onclick="editProject({{ $project->id }})" data-toggle="tooltip" title="Edit Project">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteProject({{ $project->id }})" data-toggle="tooltip" title="Delete Project">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="my-3 text-muted">
                                    <i class="fas fa-folder-open fa-3x mb-3"></i>
                                    <p>No projects found. Click "Add New Project" to create one.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <!-- Add Project Modal -->
    <div class="modal fade" id="addProjectModal" tabindex="-1" aria-labelledby="addProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addProjectModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Add New Project
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('official.projects.store') }}" method="POST" id="addProjectForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <!-- Progress Stepper -->
                        <div class="d-flex justify-content-center mb-4">
                            <div class="stepper-wrapper">
                                <div class="stepper-item active" data-step="1">
                                    <button type="button" class="stepper-button active">1</button>
                                    <div class="stepper-label">Basic Info</div>
                                </div>
                                <div class="stepper-item" data-step="2">
                                    <button type="button" class="stepper-button">2</button>
                                    <div class="stepper-label">Details</div>
                                </div>
                                <div class="stepper-item" data-step="3">
                                    <button type="button" class="stepper-button">3</button>
                                    <div class="stepper-label">Financials</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Step 1: Basic Information -->
                        <div class="step-content active" data-step="1">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="project_name" class="form-label"><i class="fas fa-tag me-1"></i>Project Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="project_name" name="project_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="location" class="form-label"><i class="fas fa-map-marker-alt me-1"></i>Location <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="location" name="location" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="start_date" class="form-label"><i class="fas fa-calendar-alt me-1"></i>Start Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="end_date" class="form-label"><i class="fas fa-calendar-alt me-1"></i>End Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Project Details -->
                        <div class="step-content" data-step="2" style="display: none;">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="description" class="form-label"><i class="fas fa-align-left me-1"></i>Project Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="documents" class="form-label"><i class="fas fa-file-alt me-1"></i>Project Documents</label>
                                        <input type="file" class="form-control" id="documents" name="documents[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx">
                                        <small class="form-text text-muted">You can upload multiple files. Supported formats: PDF, DOC, DOCX, XLS, XLSX</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="status" class="form-label"><i class="fas fa-tasks me-1"></i>Status <span class="text-danger">*</span></label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="">Select Status</option>
                                            <option value="Planning">Planning</option>
                                            <option value="Ongoing">Ongoing</option>
                                            <option value="Completed">Completed</option>
                                            <option value="On Hold">On Hold</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="priority" class="form-label"><i class="fas fa-flag me-1"></i>Priority Level <span class="text-danger">*</span></label>
                                        <select class="form-select" id="priority" name="priority" required>
                                            <option value="">Select Priority</option>
                                            <option value="High">High</option>
                                            <option value="Medium">Medium</option>
                                            <option value="Low">Low</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- Progress (%) -->
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="progress" class="form-label"><i class="fas fa-percent me-1"></i>Progress (%)</label>
                                        <input type="range" class="form-range" id="progress" name="progress" min="0" max="100" value="0" oninput="$('#progressValue').text(this.value + '%'); $('#progressBar').css('width', this.value + '%').attr('aria-valuenow', this.value);">
                                        <div class="d-flex justify-content-between">
                                            <span id="progressValue">0%</span>
                                        </div>
                                        <div class="progress mt-2" style="height: 8px;">
                                            <div class="progress-bar bg-primary" id="progressBar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Financial Information -->
                        <div class="step-content" data-step="3" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="budget" class="form-label"><i class="fas fa-money-bill-wave me-1"></i>Budget (₱) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="budget" name="budget" step="0.01" min="0" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="funding_source" class="form-label"><i class="fas fa-hand-holding-usd me-1"></i>Funding Source <span class="text-danger">*</span></label>
                                        <select class="form-select" id="funding_source" name="funding_source" required>
                                            <option value="">Select Source</option>
                                            <option value="Barangay Fund">Barangay Fund</option>
                                            <option value="City Fund">City Fund</option>
                                            <option value="Provincial Fund">Provincial Fund</option>
                                            <option value="National Fund">National Fund</option>
                                            <option value="Private Donation">Private Donation</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="notes" class="form-label"><i class="fas fa-sticky-note me-1"></i>Additional Notes</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                        <button type="button" class="btn btn-primary" id="prevStep" style="display: none;">
                            <i class="fas fa-arrow-left me-1"></i>Previous
                        </button>
                        <button type="button" class="btn btn-primary" id="nextStep">
                            <i class="fas fa-arrow-right me-1"></i>Next
                        </button>
                        <button type="submit" class="btn btn-success" id="submitForm" style="display: none;">
                            <i class="fas fa-save me-1"></i>Save Project
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            let currentStep = 1;
            const totalSteps = 3;

            // Function to show step
            function showStep(step) {
                $('.step-content').hide();
                $(`.step-content[data-step="${step}"]`).show();
                
                // Update stepper UI
                $('.stepper-item').removeClass('active');
                $(`.stepper-item[data-step="${step}"]`).addClass('active');
                $('.stepper-button').removeClass('active');
                $(`.stepper-item[data-step="${step}"] .stepper-button`).addClass('active');

                // Show/hide navigation buttons
                $('#prevStep').toggle(step > 1);
                $('#nextStep').toggle(step < totalSteps);
                $('#submitForm').toggle(step === totalSteps);
            }

            // Next button click
            $('#nextStep').click(function() {
                if (validateStep(currentStep)) {
                    currentStep++;
                    showStep(currentStep);
                }
            });

            // Previous button click
            $('#prevStep').click(function() {
                currentStep--;
                showStep(currentStep);
            });

            // Validate current step
            function validateStep(step) {
                const currentStepContent = $(`.step-content[data-step="${step}"]`);
                const requiredFields = currentStepContent.find('[required]');
                let isValid = true;

                requiredFields.each(function() {
                    if (!$(this).val()) {
                        isValid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                return isValid;
            }

            // Form submission
            $('#addProjectForm').submit(function(e) {
                if (!validateStep(currentStep)) {
                    e.preventDefault();
                    return false;
                }
            });

            // Initialize modal
            const addProjectModal = new bootstrap.Modal(document.getElementById('addProjectModal'));

            // Reset form when modal is closed
            $('#addProjectModal').on('hidden.bs.modal', function() {
                $('#addProjectForm')[0].reset();
                currentStep = 1;
                showStep(currentStep);
                $('.is-invalid').removeClass('is-invalid');
            });

            // Add CSRF token to all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Handle search functionality
            $('#searchProject').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $("table tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Progress slider sync for Add/Edit modal
            $('#progress').on('input', function() {
                let val = $(this).val();
                $('#progressValue').text(val + '%');
                $('#progressBar').css('width', val + '%').attr('aria-valuenow', val);
            });
        });
    </script>
</body>
</html>
