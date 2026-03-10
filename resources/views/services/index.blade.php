@extends('layouts.app')

@section('title', 'Service Records')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Service Records</h1>
    <a href="{{ route('services.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Service
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Vehicle</th>
                        <th>Customer</th>
                        <th>Type</th>
                        <th>Technician</th>
                        <th>Cost</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                    <tr>
                        <td>{{ $service->service_date->format('M d, Y') }}</td>
                        <td>
                            <strong>{{ $service->vehicle->registration_number }}</strong><br>
                            <small class="text-muted">{{ $service->vehicle->make }} {{ $service->vehicle->model }}</small>
                        </td>
                        <td>{{ $service->vehicle->customer->name }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $service->service_type_label }}</span>
                        </td>
                        <td>{{ $service->technician }}</td>
                        <td>${{ number_format($service->cost, 2) }}</td>
                        <td>
                            <a href="{{ route('services.show', $service) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <p class="text-muted mb-0">No service records found</p>
                            <a href="{{ route('services.create') }}" class="btn btn-primary mt-2">Add your first service</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $services->links() }}
    </div>
</div>
@endsection