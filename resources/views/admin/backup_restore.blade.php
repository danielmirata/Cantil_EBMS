@extends('layouts.admin_layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Database Backup & Restore</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Backup Section -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h4 class="mb-0">Create Backup</h4>
                                </div>
                                <div class="card-body">
                                    <p>Create a new backup of your database and files.</p>
                                    <form action="{{ route('admin.backup.create') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label>Backup Name (Optional)</label>
                                            <input type="text" name="backup_name" class="form-control" placeholder="Enter backup name">
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-download"></i> Create Backup
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Restore Section -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-warning text-white">
                                    <h4 class="mb-0">Restore Backup</h4>
                                </div>
                                <div class="card-body">
                                    <p>Restore your system from a previous backup.</p>
                                    <form action="{{ route('admin.backup.restore') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label>Select Backup File</label>
                                            <input type="file" name="backup_file" class="form-control" accept=".zip,.sql" required>
                                        </div>
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-upload"></i> Restore Backup
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Backup List -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h4 class="mb-0">Available Backups</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Backup Name</th>
                                                    <th>Date Created</th>
                                                    <th>Size</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($backups ?? [] as $backup)
                                                <tr>
                                                    <td>{{ $backup['name'] }}</td>
                                                    <td>{{ $backup['date'] }}</td>
                                                    <td>{{ $backup['size'] }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.backup.download', $backup['filename']) }}" class="btn btn-sm btn-success">
                                                            <i class="fas fa-download"></i> Download
                                                        </a>
                                                        <form action="{{ route('admin.backup.delete', $backup['filename']) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this backup?')">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">No backups found</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
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
