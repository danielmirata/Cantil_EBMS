<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Barangay Cantil-E - Projects Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/secretary-dashboard.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
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
    @include('partials.secretary-sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <div class="top-nav">
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
        <div class="dashboard-header">
            <h1 class="dashboard-title">Projects Management</h1>
            <div class="dashboard-subtitle">Manage and monitor all development projects in your barangay</div>
        </div>

        @if(isset($project))
        <!-- Project Details View -->
        <div class="content-card mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Project Details</h2>
                <div class="btn-group">
                    <a href="{{ route('projects.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left mr-1"></i>Back to Projects
                    </a>
                    <button type="button" class="btn btn-warning" onclick="editProject({{ $project->id }})">
                        <i class="fas fa-edit mr-1"></i>Edit Project
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <h2 class="mb-4">{{ $project->project_name }}</h2>
                    
                    <div class="mb-4">
                        <h5 class="text-muted mb-3">Project Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-map-marker-alt mr-2"></i>Location:</strong></p>
                                <p class="text-muted">{{ $project->location ?? 'Not specified' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-calendar-alt mr-2"></i>Timeline:</strong></p>
                                <p class="text-muted">
                                    {{ date('M d, Y', strtotime($project->start_date)) }} - 
                                    {{ date('M d, Y', strtotime($project->end_date)) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="text-muted mb-3">Description</h5>
                        <p class="text-muted">{{ $project->description }}</p>
                    </div>

                    <div class="mb-4">
                        <h5 class="text-muted mb-3">Financial Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-money-bill-wave mr-2"></i>Budget:</strong></p>
                                <p class="text-muted">₱{{ number_format($project->budget, 2) }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-hand-holding-usd mr-2"></i>Funding Source:</strong></p>
                                <p class="text-muted">{{ $project->funding_source ?? 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="text-muted mb-3">Project Status</h5>
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
                            
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-{{ $statusClass }} text-white px-3 py-2 mr-2">
                                    <i class="fas fa-{{ $statusIcon }} me-1"></i>
                                    {{ $project->status }}
                                </span>
                            </div>

                            <h5 class="text-muted mb-3">Priority Level</h5>
                            <p class="mb-0">{{ $project->priority ?? 'Not specified' }}</p>

                            @if($project->notes)
                                <h5 class="text-muted mb-3 mt-4">Additional Notes</h5>
                                <p class="mb-0">{{ $project->notes }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
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
                            <!--<th class="align-middle">ID</th>-->
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
                            <!--<td>{{ $project->id }}</td>-->
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
                <form action="{{ route('projects.store') }}" method="POST" id="addProjectForm" enctype="multipart/form-data">
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

    <!-- View Project Modal -->
    <div class="modal fade" id="viewProjectModal" tabindex="-1" aria-labelledby="viewProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="viewProjectModalLabel">
                        <i class="fas fa-project-diagram me-2"></i>Project Information
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs custom-tabs mb-4" id="projectDetailsTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab-info" data-bs-toggle="tab" data-bs-target="#tabInfoContent" type="button" role="tab" aria-controls="tabInfoContent" aria-selected="true">
                                <i class="fas fa-user me-1"></i> <span>Project Info</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-financial" data-bs-toggle="tab" data-bs-target="#tabFinancialContent" type="button" role="tab" aria-controls="tabFinancialContent" aria-selected="false">
                                <i class="fas fa-money-check-alt me-1"></i> <span>Financials</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-documents" data-bs-toggle="tab" data-bs-target="#tabDocumentsContent" type="button" role="tab" aria-controls="tabDocumentsContent" aria-selected="false">
                                <i class="fas fa-folder-open me-1"></i> <span>Documents & Notes</span>
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="projectDetailsTabsContent">
                        <!-- Project Info Tab -->
                        <div class="tab-pane fade show active" id="tabInfoContent" role="tabpanel" aria-labelledby="tab-info">
                            <div class="card shadow-sm mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h2 class="card-title mb-0" id="viewProjectName"></h2>
                                    </div>
                                    <div class="mb-4">
                                        <h5 class="text-muted mb-3 border-bottom pb-2">
                                            <i class="fas fa-info-circle me-2"></i>Project Information
                                        </h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <p class="mb-1"><strong><i class="fas fa-map-marker-alt me-2 text-primary"></i>Location:</strong></p>
                                                    <p class="text-muted ps-4" id="viewLocation"></p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <p class="mb-1"><strong><i class="fas fa-calendar-alt me-2 text-primary"></i>Timeline:</strong></p>
                                                    <p class="text-muted ps-4" id="viewTimeline"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <h5 class="text-muted mb-3 border-bottom pb-2">
                                            <i class="fas fa-align-left me-2"></i>Description
                                        </h5>
                                        <p class="text-muted ps-4" id="viewDescription"></p>
                                    </div>
                                    <div class="mb-4">
                                        <h5 class="text-muted mb-3 border-bottom pb-2">
                                            <i class="fas fa-tasks me-2"></i>Status & Priority
                                        </h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <p class="mb-1"><strong>Status:</strong></p>
                                                    <span class="badge" id="viewStatus"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <p class="mb-1"><strong>Priority:</strong></p>
                                                    <p class="mb-0 ps-4" id="viewPriority"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Financials Tab -->
                        <div class="tab-pane fade" id="tabFinancialContent" role="tabpanel" aria-labelledby="tab-financial">
                            <div class="card shadow-sm mb-4">
                                <div class="card-body">
                                    <div class="mb-4">
                                        <h5 class="text-muted mb-3 border-bottom pb-2">
                                            <i class="fas fa-money-bill-wave me-2"></i>Financial Information
                                        </h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <p class="mb-1"><strong><i class="fas fa-money-bill-wave me-2 text-primary"></i>Budget:</strong></p>
                                                    <p class="text-muted ps-4" id="viewBudget"></p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <p class="mb-1"><strong><i class="fas fa-hand-holding-usd me-2 text-primary"></i>Funding Source:</strong></p>
                                                    <p class="text-muted ps-4" id="viewFundingSource"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Documents & Notes Tab -->
                        <div class="tab-pane fade" id="tabDocumentsContent" role="tabpanel" aria-labelledby="tab-documents">
                            <div class="card shadow-sm mb-4">
                                <div class="card-body">
                                    <div class="mb-4">
                                        <h5 class="text-muted mb-3 border-bottom pb-2">
                                            <i class="fas fa-file-alt me-2"></i>Project Documents
                                        </h5>
                                        <div id="viewDocuments" class="text-muted ps-4">
                                            <!-- Documents will be populated here -->
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <h5 class="text-muted mb-3 border-bottom pb-2">
                                            <i class="fas fa-sticky-note me-2"></i>Additional Notes
                                        </h5>
                                        <p class="text-muted ps-4" id="viewNotes"></p>
                                    </div>
                                    <div class="mb-4">
                                        <h5 class="text-muted mb-3 border-bottom pb-2">
                                            <i class="fas fa-clock me-2"></i>Timestamps
                                        </h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <p class="mb-1"><strong><i class="fas fa-calendar-plus me-2 text-primary"></i>Created At:</strong></p>
                                                    <p class="text-muted ps-4" id="viewCreatedAt"></p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <p class="mb-1"><strong><i class="fas fa-calendar-edit me-2 text-primary"></i>Updated At:</strong></p>
                                                    <p class="text-muted ps-4" id="viewUpdatedAt"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Close
                    </button>
                    <button type="button" class="btn btn-warning" id="editFromViewBtn">
                        <i class="fas fa-edit me-1"></i>Edit Project
                    </button>
                </div>
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

            // Export to CSV
            $('#exportCSV').click(function() {
                // Add CSV export logic
            });

            // Print List
            $('#printList').click(function() {
                window.print();
            });

            // View Project Function
            window.viewProject = function(id) {
                $.ajax({
                    url: `/projects/${id}`,
                    type: 'GET',
                    success: function(response) {
                        const project = response.project;
                        
                        // Update modal content
                        $('#viewProjectName').text(project.project_name);
                        $('#viewLocation').text(project.location || 'Not specified');
                        $('#viewTimeline').text(`${formatDate(project.start_date)} - ${formatDate(project.end_date)}`);
                        $('#viewDescription').text(project.description);
                        $('#viewBudget').text(`₱${formatNumber(project.budget)}`);
                        $('#viewFundingSource').text(project.funding_source || 'Not specified');
                        $('#viewPriority').text(project.priority || 'Not specified');
                        $('#viewNotes').text(project.notes || 'No additional notes');
                        $('#viewCreatedAt').text(formatDate(project.created_at));
                        $('#viewUpdatedAt').text(formatDate(project.updated_at));
                        
                        // Handle documents
                        const documentsContainer = $('#viewDocuments');
                        documentsContainer.empty();
                        if (project.documents && project.documents.length > 0) {
                            const documentsList = $('<ul class="list-unstyled"></ul>');
                            project.documents.forEach(doc => {
                                let fileName = doc.path || doc.name || doc;
                                // Remove leading 'project-documents/' if present
                                fileName = fileName.replace(/^project-documents[\\/]/, '');
                                const docUrl = `/storage/project-documents/${fileName}`;
                                const docName = doc.name || doc.original_name || doc.path || doc;
                                documentsList.append(`
                                    <li class="mb-2">
                                        <i class="fas fa-file me-2 text-primary"></i>
                                        <a href="${docUrl}" target="_blank" class="text-primary text-decoration-underline">${docName}</a>
                                    </li>
                                `);
                            });
                            documentsContainer.append(documentsList);
                        } else {
                            documentsContainer.html('<p class="text-muted"><i class="fas fa-info-circle me-2"></i>No documents attached</p>');
                        }
                        
                        // Update status badge
                        const statusClass = {
                            'Completed': 'success',
                            'Ongoing': 'primary',
                            'Planning': 'info',
                            'On Hold': 'danger'
                        }[project.status] || 'secondary';
                        
                        const statusIcon = {
                            'Completed': 'check-circle',
                            'Ongoing': 'spinner fa-spin',
                            'Planning': 'clipboard-list',
                            'On Hold': 'pause-circle'
                        }[project.status] || 'question-circle';
                        
                        $('#viewStatus').html(`
                            <span class="badge bg-${statusClass} text-white">
                                <i class="fas fa-${statusIcon} me-1"></i>
                                ${project.status}
                            </span>
                        `);
                        
                        // Set edit button action
                        $('#editFromViewBtn').off('click').on('click', function() {
                            $('#viewProjectModal').modal('hide');
                            editProject(id);
                        });
                        
                        // Show modal
                        const viewModal = new bootstrap.Modal(document.getElementById('viewProjectModal'));
                        viewModal.show();
                    },
                    error: function(xhr) {
                        console.error('Error fetching project details:', xhr);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to load project details. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            };

            // Helper function to format dates
            function formatDate(dateString) {
                return new Date(dateString).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            }

            // Helper function to format numbers
            function formatNumber(number) {
                return new Intl.NumberFormat('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(number);
            }

            // Helper function to format dates for input[type=date]
            function toDateInputValue(date) {
                if (!date) return '';
                const d = new Date(date);
                const month = ('0' + (d.getMonth() + 1)).slice(-2);
                const day = ('0' + d.getDate()).slice(-2);
                return d.getFullYear() + '-' + month + '-' + day;
            }

            // Edit Project Function
            window.editProject = function(id) {
                $.ajax({
                    url: `/projects/${id}/edit`,
                    type: 'GET',
                    success: function(response) {
                        const project = response.project;
                        
                        // Populate form fields
                        $('#project_name').val(project.project_name);
                        $('#location').val(project.location);
                        $('#description').val(project.description);
                        $('#start_date').val(toDateInputValue(project.start_date));
                        $('#end_date').val(toDateInputValue(project.end_date));
                        $('#budget').val(project.budget);
                        $('#status').val(project.status);
                        $('#priority').val(project.priority);
                        $('#funding_source').val(project.funding_source);
                        $('#notes').val(project.notes);
                        $('#progress').val(project.progress || 0).trigger('input');
                        
                        // Update form action
                        $('#addProjectForm').attr('action', `/projects/${id}`);
                        $('#addProjectForm').append('<input type="hidden" name="_method" value="PUT">');
                        
                        // Update modal title
                        $('#addProjectModalLabel').html('<i class="fas fa-edit me-2"></i>Edit Project');
                        
                        // Show modal
                        const editModal = new bootstrap.Modal(document.getElementById('addProjectModal'));
                        editModal.show();
                    },
                    error: function(xhr) {
                        console.error('Error fetching project details:', xhr);
                        alert('Error loading project details. Please try again.');
                    }
                });
            };

            // Delete Project Function
            window.deleteProject = function(id) {
                if (confirm('Are you sure you want to delete this project?')) {
                    $.ajax({
                        url: `/projects/${id}`,
                        type: 'DELETE',
                        success: function(response) {
                            alert('Project deleted successfully');
                            location.reload();
                        },
                        error: function(xhr) {
                            console.error('Error deleting project:', xhr);
                            alert('Error deleting project. Please try again.');
                        }
                    });
                }
            };

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