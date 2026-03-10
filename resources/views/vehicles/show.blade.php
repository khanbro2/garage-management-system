@extends('layouts.app')

@section('title', 'Vehicle Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">{{ $vehicle->make }} {{ $vehicle->model }}</h1>
    <div>
        <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn btn-outline-primary">Edit</a>
        <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Vehicle Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Registration:</strong> {{ $vehicle->registration_number }}</p>
                <p><strong>Make:</strong> {{ $vehicle->make }}</p>
                <p><strong>Model:</strong> {{ $vehicle->model }}</p>
                <p><strong>Year:</strong> {{ $vehicle->year ?? 'N/A' }}</p>
                <p><strong>Color:</strong> {{ $vehicle->color ?? 'N/A' }}</p>
                <p><strong>VIN:</strong> {{ $vehicle->vin ?? 'N/A' }}</p>
                <p><strong>Mileage:</strong> {{ $vehicle->mileage ? number_format($vehicle->mileage) : 'N/A' }}</p>
                <hr>
                <p><strong>Customer:</strong> {{ $vehicle->customer->name }}</p>
                <p><strong>Phone:</strong> {{ $vehicle->customer->phone }}</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Important Dates</h5>
            </div>
            <div class="card-body">
                <p>
                    <strong>MOT Expiry:</strong><br>
                    @if($vehicle->mot_expiry)
                        <span class="status-badge status-{{ $vehicle->mot_status }}">
                            {{ $vehicle->mot_expiry->format('M d, Y') }}
                        </span>
                    @else
                        Not set
                    @endif
                </p>
                <p>
                    <strong>Service Due:</strong><br>
                    @if($vehicle->service_due)
                        <span class="status-badge status-{{ $vehicle->service_status }}">
                            {{ $vehicle->service_due->format('M d, Y') }}
                        </span>
                    @else
                        Not set
                    @endif
                </p>
            </div>
        </div>

        <form action="{{ route('vehicles.check-mot', $vehicle) }}" method="POST" class="mb-4">
            @csrf
            <button type="submit" class="btn btn-info w-100">
                <i class="bi bi-arrow-clockwise"></i> Check/Update MOT
            </button>
        </form>
    </div>

    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">MOT History</h5>
            </div>
            <div class="card-body">
                @if($vehicle->motRecords->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Result</th>
                                    <th>Mileage</th>
                                    <th>Expiry</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vehicle->motRecords as $record)
                                <tr>
                                    <td>{{ $record->test_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $record->result == 'pass' ? 'success' : 'danger' }}">
                                            {{ ucfirst($record->result) }}
                                        </span>
                                    </td>
                                    <td>{{ $record->mileage ? number_format($record->mileage) : 'N/A' }}</td>
                                    <td>{{ $record->expiry_date ? $record->expiry_date->format('M d, Y') : 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No MOT records found.</p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Service History</h5>
                <a href="{{ route('services.create', ['vehicle_id' => $vehicle->id]) }}" class="btn btn-sm btn-primary">Add Service</a>
            </div>
            <div class="card-body">
                @if($vehicle->serviceRecords->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Technician</th>
                                    <th>Cost</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vehicle->serviceRecords as $service)
                                <tr>
                                    <td>{{ $service->service_date->format('M d, Y') }}</td>
                                    <td>{{ $service->service_type_label }}</td>
                                    <td>{{ $service->technician }}</td>
                                    <td>${{ number_format($service->cost, 2) }}</td>
                                    <td>
                                        <a href="{{ route('services.show', $service) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No service records found.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection