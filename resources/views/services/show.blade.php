@extends('layouts.app')

@section('title', 'Service Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Service Record</h1>
    <div>
        <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Service Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Service Date:</strong> {{ $service->service_date->format('M d, Y') }}</p>
                        <p><strong>Type:</strong> <span class="badge bg-secondary">{{ $service->service_type_label }}</span></p>
                        <p><strong>Technician:</strong> {{ $service->technician }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Cost:</strong> ${{ number_format($service->cost, 2) }}</p>
                        <p><strong>Mileage:</strong> {{ $service->mileage ? number_format($service->mileage) : 'N/A' }}</p>
                        <p><strong>Next Service Due:</strong> {{ $service->next_service_due ? $service->next_service_due->format('M d, Y') : 'N/A' }}</p>
                    </div>
                </div>
                
                <hr>
                
                <h6>Description:</h6>
                <p>{{ $service->description }}</p>
                
                @if($service->notes)
                    <h6>Additional Notes:</h6>
                    <p>{{ $service->notes }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Vehicle Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Registration:</strong> {{ $service->vehicle->registration_number }}</p>
                <p><strong>Vehicle:</strong> {{ $service->vehicle->make }} {{ $service->vehicle->model }}</p>
                <p><strong>Customer:</strong> {{ $service->vehicle->customer->name }}</p>
                <p><strong>Phone:</strong> {{ $service->vehicle->customer->phone }}</p>
                <hr>
                <a href="{{ route('vehicles.show', $service->vehicle) }}" class="btn btn-outline-primary btn-sm">View Vehicle</a>
            </div>
        </div>
    </div>
</div>
@endsection