@extends('layouts.app')

@section('title', 'Dashboard - Garage Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
    <h1 class="h2">Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('vehicles.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add Vehicle
            </a>
            <a href="{{ route('customers.create') }}" class="btn btn-outline-primary">
                <i class="bi bi-plus-lg"></i> Add Customer
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-primary-light me-3">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <h6 class="card-subtitle text-muted mb-1">Total Customers</h6>
                    <h3 class="card-title mb-0">{{ $stats['total_customers'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-success-light me-3">
                    <i class="bi bi-car-front"></i>
                </div>
                <div>
                    <h6 class="card-subtitle text-muted mb-1">Total Vehicles</h6>
                    <h3 class="card-title mb-0">{{ $stats['total_vehicles'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-warning-light me-3">
                    <i class="bi bi-wrench"></i>
                </div>
                <div>
                    <h6 class="card-subtitle text-muted mb-1">Services This Month</h6>
                    <h3 class="card-title mb-0">{{ $stats['total_services_this_month'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-danger-light me-3">
                    <i class="bi bi-bell"></i>
                </div>
                <div>
                    <h6 class="card-subtitle text-muted mb-1">MOT Expiring Soon</h6>
                    <h3 class="card-title mb-0">{{ $motExpiringSoon->count() }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-exclamation-triangle text-warning"></i> MOT Expiring Soon</h5>
                <a href="{{ route('vehicles.index', ['status' => 'mot_expiring']) }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Vehicle</th>
                                <th>Customer</th>
                                <th>Expires</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($motExpiringSoon as $vehicle)
                            <tr>
                                <td>
                                    <strong>{{ $vehicle->make }} {{ $vehicle->model }}</strong><br>
                                    <small class="text-muted">{{ $vehicle->registration_number }}</small>
                                </td>
                                <td>{{ $vehicle->customer->name }}</td>
                                <td>
                                    <span class="status-badge status-{{ $vehicle->mot_status }}">
                                        {{ $vehicle->mot_expiry->diffInDays(now()) }} days
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">No vehicles with MOT expiring soon</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-wrench text-info"></i> Service Due Soon</h5>
                <a href="{{ route('vehicles.index', ['status' => 'service_due']) }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Vehicle</th>
                                <th>Customer</th>
                                <th>Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($serviceDueSoon as $vehicle)
                            <tr>
                                <td>
                                    <strong>{{ $vehicle->make }} {{ $vehicle->model }}</strong><br>
                                    <small class="text-muted">{{ $vehicle->registration_number }}</small>
                                </td>
                                <td>{{ $vehicle->customer->name }}</td>
                                <td>
                                    <span class="status-badge status-{{ $vehicle->service_status }}">
                                        {{ $vehicle->service_due->format('M d, Y') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">No services due soon</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection