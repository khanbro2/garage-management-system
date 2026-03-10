@extends('layouts.app')

@section('title', 'Vehicles')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Vehicles</h1>
    <div>
        <a href="{{ route('mot.index') }}" class="btn btn-outline-primary me-2">
            <i class="bi bi-search"></i> MOT Check
        </a>
        <a href="{{ route('vehicles.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Vehicle
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Registration</th>
                        <th>Vehicle</th>
                        <th>Customer</th>
                        <th>MOT Status</th>
                        <th>Service Due</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehicles as $vehicle)
                    <tr>
                        <td>
                            <strong>{{ $vehicle->registration_number }}</strong>
                        </td>
                        <td>{{ $vehicle->make }} {{ $vehicle->model }}</td>
                        <td>{{ $vehicle->customer->name }}</td>
                        <td>
                            @if($vehicle->mot_expiry)
                                <span class="status-badge status-{{ $vehicle->mot_status }}">
                                    {{ $vehicle->mot_expiry->format('M d, Y') }}
                                </span>
                            @else
                                <span class="text-muted">Unknown</span>
                            @endif
                        </td>
                        <td>
                            @if($vehicle->service_due)
                                <span class="status-badge status-{{ $vehicle->service_status }}">
                                    {{ $vehicle->service_due->format('M d, Y') }}
                                </span>
                            @else
                                <span class="text-muted">Not set</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <p class="text-muted mb-0">No vehicles found</p>
                            <a href="{{ route('vehicles.create') }}" class="btn btn-primary mt-2">Add your first vehicle</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $vehicles->links() }}
    </div>
</div>
@endsection