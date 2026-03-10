@extends('layouts.app')

@section('title', 'Add Service Record')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Add Service Record</h1>
    <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('services.store') }}">
            @csrf

            <div class="mb-3">
                <label for="vehicle_id" class="form-label">Vehicle *</label>
                <select class="form-select @error('vehicle_id') is-invalid @enderror" 
                        id="vehicle_id" name="vehicle_id" required>
                    <option value="">Select Vehicle</option>
                    @foreach($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}" {{ old('vehicle_id', $preselectedVehicle) == $vehicle->id ? 'selected' : '' }}>
                            {{ $vehicle->registration_number }} - {{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->customer->name }})
                        </option>
                    @endforeach
                </select>
                @error('vehicle_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="service_date" class="form-label">Service Date *</label>
                    <input type="date" class="form-control @error('service_date') is-invalid @enderror" 
                           id="service_date" name="service_date" value="{{ old('service_date', date('Y-m-d')) }}" required>
                    @error('service_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="service_type" class="form-label">Service Type *</label>
                    <select class="form-select @error('service_type') is-invalid @enderror" 
                            id="service_type" name="service_type" required>
                        <option value="">Select Type</option>
                        <option value="interim" {{ old('service_type') == 'interim' ? 'selected' : '' }}>Interim Service</option>
                        <option value="full" {{ old('service_type') == 'full' ? 'selected' : '' }}>Full Service</option>
                        <option value="major" {{ old('service_type') == 'major' ? 'selected' : '' }}>Major Service</option>
                        <option value="repair" {{ old('service_type') == 'repair' ? 'selected' : '' }}>Repair</option>
                        <option value="diagnostic" {{ old('service_type') == 'diagnostic' ? 'selected' : '' }}>Diagnostic</option>
                    </select>
                    @error('service_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="technician" class="form-label">Technician *</label>
                    <input type="text" class="form-control @error('technician') is-invalid @enderror" 
                           id="technician" name="technician" value="{{ old('technician') }}" required>
                    @error('technician')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="cost" class="form-label">Cost ($)</label>
                    <input type="number" step="0.01" class="form-control @error('cost') is-invalid @enderror" 
                           id="cost" name="cost" value="{{ old('cost') }}">
                    @error('cost')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="mileage" class="form-label">Current Mileage</label>
                    <input type="number" class="form-control @error('mileage') is-invalid @enderror" 
                           id="mileage" name="mileage" value="{{ old('mileage') }}">
                    @error('mileage')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="next_service_due" class="form-label">Next Service Due</label>
                    <input type="date" class="form-control @error('next_service_due') is-invalid @enderror" 
                           id="next_service_due" name="next_service_due" value="{{ old('next_service_due') }}">
                    @error('next_service_due')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description *</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Additional Notes</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" 
                          id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Save Service Record</button>
            </div>
        </form>
    </div>
</div>
@endsection