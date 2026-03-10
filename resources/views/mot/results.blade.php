@extends('layouts.app')

@section('title', 'MOT Results')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">MOT Check Results</h1>
    <a href="{{ route('mot.index') }}" class="btn btn-outline-secondary">Check Another</a>
</div>

@if($existingVehicle)
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> This vehicle is already in your database. 
        <a href="{{ route('vehicles.show', $existingVehicle) }}" class="alert-link">View Vehicle</a>
    </div>
@endif

<div class="card mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">{{ $motData['make'] }} {{ $motData['model'] }} - {{ $registration }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Make:</strong> {{ $motData['make'] }}</p>
                <p><strong>Model:</strong> {{ $motData['model'] }}</p>
                <p><strong>Year:</strong> {{ $motData['year'] ?? 'N/A' }}</p>
                <p><strong>Colour:</strong> {{ $motData['colour'] ?? 'N/A' }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Fuel Type:</strong> {{ $motData['fuelType'] ?? 'N/A' }}</p>
                <p><strong>Engine Size:</strong> {{ $motData['engineSize'] ?? 'N/A' }}</p>
                <p><strong>MOT Status:</strong> {{ $motData['motStatus'] ?? 'N/A' }}</p>
                <p><strong>MOT Expiry:</strong> {{ $motData['motExpiryDate'] ? date('M d, Y', strtotime($motData['motExpiryDate'])) : 'N/A' }}</p>
            </div>
        </div>

        @if(!$existingVehicle)
            <hr>
            <h6>Save this vehicle to your database:</h6>
            <form method="POST" action="{{ route('mot.save') }}">
                @csrf
                <input type="hidden" name="registration_number" value="{{ $registration }}">
                <input type="hidden" name="make" value="{{ $motData['make'] }}">
                <input type="hidden" name="model" value="{{ $motData['model'] }}">
                <input type="hidden" name="year" value="{{ $motData['year'] ?? '' }}">
                <input type="hidden" name="mot_expiry" value="{{ $motData['motExpiryDate'] ?? '' }}">

                <div class="row">
                    <div class="col-md-6">
                        <select class="form-select" name="customer_id" required>
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Save Vehicle</button>
                    </div>
                </div>
            </form>
        @endif
    </div>
</div>

@if(!empty($motData['motTests']))
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">MOT Test History</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Result</th>
                            <th>Mileage</th>
                            <th>Expiry Date</th>
                            <th>Defects</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($motData['motTests'] as $test)
                        <tr>
                            <td>{{ date('M d, Y', strtotime($test['completedDate'])) }}</td>
                            <td>
                                <span class="badge bg-{{ $test['testResult'] == 'PASSED' ? 'success' : 'danger' }}">
                                    {{ $test['testResult'] }}
                                </span>
                            </td>
                            <td>{{ number_format($test['odometerReading']) }} {{ $test['odometerUnit'] }}</td>
                            <td>{{ $test['expiryDate'] ? date('M d, Y', strtotime($test['expiryDate'])) : 'N/A' }}</td>
                            <td>
                                @if(!empty($test['defects']))
                                    <ul class="list-unstyled mb-0">
                                        @foreach($test['defects'] as $defect)
                                            <li><small>{{ $defect['type'] }}: {{ $defect['text'] }}</small></li>
                                        @endforeach
                                    </ul>
                                @else
                                    <small>None</small>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@endsection