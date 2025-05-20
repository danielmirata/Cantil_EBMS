@extends('layouts.bp_layout')

@section('content')
<div class="container-fluid pb-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb shadow-sm rounded-lg">
            <li class="breadcrumb-item"><a href="/dashboard"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
            <li class="breadcrumb-item active" aria-current="page">Project Details</li>
        </ol>
    </nav>

    <!-- Project Details Card -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 font-weight-bold text-primary">
                            <i class="fas fa-project-diagram mr-2"></i>Project Details
                        </h5>
                        <div class="btn-group">
                            <a href="{{ route('projects.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left mr-1"></i>Back to Projects
                            </a>
                            <button type="button" class="btn btn-warning" onclick="editProject({{ $project->id }})">
                                <i class="fas fa-edit mr-1"></i>Edit Project
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
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
                                            'Ongoing' => 'warning',
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
                                        <span class="badge badge-pill badge-{{ $statusClass }} px-3 py-2 mr-2">
                                            <i class="fas fa-{{ $statusIcon }} mr-1"></i>
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
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function editProject(id) {
        // Add edit project logic here
        window.location.href = `/projects/${id}/edit`;
    }
</script>
@endpush 