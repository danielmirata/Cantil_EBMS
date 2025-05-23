<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Barangay Cantil-E - Edit Walk-in Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/secretary-dashboard.css') }}">
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
            <h1 class="dashboard-title">Edit Walk-in Record</h1>
            <div class="dashboard-subtitle">Update walk-in request or clearance record</div>
        </div>

        <!-- Edit Form -->
        <div class="content-card mt-4">
            <form action="{{ route('secretary.records.update', $record) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="resident_name" class="form-label">Resident Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('resident_name') is-invalid @enderror" 
                               id="resident_name" name="resident_name" 
                               value="{{ old('resident_name', $record->resident_name) }}" required>
                        @error('resident_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="request_type" class="form-label">Request Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('request_type') is-invalid @enderror" 
                                id="request_type" name="request_type" required>
                            <option value="">Select request type</option>
                            <option value="clearance" {{ old('request_type', $record->request_type) == 'clearance' ? 'selected' : '' }}>Clearance</option>
                            <option value="certification" {{ old('request_type', $record->request_type) == 'certification' ? 'selected' : '' }}>Certification</option>
                            <option value="other" {{ old('request_type', $record->request_type) == 'other' ? 'selected' : '' }}>Other Request</option>
                        </select>
                        @error('request_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="purpose" class="form-label">Purpose <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('purpose') is-invalid @enderror" 
                              id="purpose" name="purpose" rows="3" required>{{ old('purpose', $record->purpose) }}</textarea>
                    @error('purpose')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select @error('status') is-invalid @enderror" 
                            id="status" name="status" required>
                        <option value="">Select status</option>
                        <option value="pending" {{ old('status', $record->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ old('status', $record->status) == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ old('status', $record->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $record->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="remarks" class="form-label">Remarks</label>
                    <textarea class="form-control @error('remarks') is-invalid @enderror" 
                              id="remarks" name="remarks" rows="2">{{ old('remarks', $record->remarks) }}</textarea>
                    @error('remarks')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('secretary.records.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Record
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 