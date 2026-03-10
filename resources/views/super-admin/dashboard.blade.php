@extends('layouts.app')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
    <h1 class="h2">Super Admin Dashboard</h1>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-primary-light me-3">
                    <i class="bi bi-building"></i>
                </div>
                <div>
                    <h6 class="card-subtitle text-muted mb-1">Total Garages</h6>
                    <h3 class="card-title mb-0">{{ $stats['total_garages'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-success-light me-3">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <h6 class="card-subtitle text-muted mb-1">Active Garages</h6>
                    <h3 class="card-title mb-0">{{ $stats['active_garages'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-info-light me-3">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <h6 class="card-subtitle text-muted mb-1">Total Users</h6>
                    <h3 class="card-title mb-0">{{ $stats['total_users'] }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Welcome Super Admin</h5>
    </div>
    <div class="card-body">
        <p>This is the admin panel. You can manage all garages and users from here.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Go to Homepage</a>
    </div>
</div>
@endsection