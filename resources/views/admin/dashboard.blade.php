@extends('layouts.admin_layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/shared-dashboard.css') }}">
<style>
    .stats-card {
        padding: 1.5rem;
        border-radius: 0.5rem;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .stats-card .number {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    .stats-card .label {
        font-size: 1rem;
        opacity: 0.9;
    }
    .stats-card i {
        font-size: 2.5rem;
        opacity: 0.8;
    }
    .blue-card {
        background: linear-gradient(45deg, #2196F3, #1976D2);
    }
    .yellow-card {
        background: linear-gradient(45deg, #FFC107, #FFA000);
    }
    .green-card {
        background: linear-gradient(45deg, #4CAF50, #388E3C);
    }
    .red-card {
        background: linear-gradient(45deg, #F44336, #D32F2F);
    }
    .content-card {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 1.5rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h1 class="dashboard-title">Admin Dashboard</h1>
        <div class="dashboard-subtitle">Welcome back, {{ auth()->user()->fullname }}</div>
    </div>

   
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Add any dashboard-specific JavaScript here
});
</script>
@endsection 