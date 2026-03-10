@extends('layouts.app')

@section('title', 'Add Vehicle')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Add Vehicle</h1>
    <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('vehicles.store') }}">
            @csrf

            <div class="mb-3">
                <label for="customer_id" class="form-label">Customer *</label>
                <select class="form-select @error('customer_id') is-invalid @enderror" 
                        id="customer_id" name="customer_id" required>
                    <option value="">Select Customer</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id', $preselectedCustomer) == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }} ({{ $customer->phone }})
                        </option>
                    @endforeach
                </select>
                @error('customer_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="registration_number" class="form-label">Registration Number *</label>
                <input type="text" class="form-control @error('registration_number') is-invalid @enderror" 
                       id="registration_number" name="registration_number" value="{{ old('registration_number') }}" required>
                @error('registration_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="make" class="form-label">Make *</label>
                    <input type="text" class="form-control @error('make') is-invalid @enderror" 
                           id="make" name="make" value="{{ old('make') }}" required>
                    @error('make')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="model" class="form-label">Model *</label>
                    <input type="text" class="form-control @error('model') is-invalid @enderror" 
                           id="model" name="model" value="{{ old('model') }}" required>
                    @error('model')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="year" class="form-label">Year</label>
                    <input type="number" class="form-control @error('year') is-invalid @enderror" 
                           id="year" name="year" value="{{ old('year') }}">
                    @error('year')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="color" class="form-label">Color</label>
                    <input type="text" class="form-control @error('color') is-invalid @enderror" 
                           id="color" name="color" value="{{ old('color') }}">
                    @error('color')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="mileage" class="form-label">Mileage</label>
                    <input type="number" class="form-control @error('mileage') is-invalid @enderror" 
                           id="mileage" name="mileage" value="{{ old('mileage') }}">
                    @error('mileage')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="mot_expiry" class="form-label">MOT Expiry Date</label>
                    <input type="date" class="form-control @error('mot_expiry') is-invalid @enderror" 
                           id="mot_expiry" name="mot_expiry" value="{{ old('mot_expiry') }}">
                    @error('mot_expiry')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="service_due" class="form-label">Service Due Date</label>
                    <input type="date" class="form-control @error('service_due') is-invalid @enderror" 
                           id="service_due" name="service_due" value="{{ old('service_due') }}">
                    @error('service_due')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="vin" class="form-label">VIN Number</label>
                <input type="text" class="form-control @error('vin') is-invalid @enderror" 
                       id="vin" name="vin" value="{{ old('vin') }}">
                @error('vin')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Save Vehicle</button>
            </div>
        </form>
    </div>
</div>
@endsection