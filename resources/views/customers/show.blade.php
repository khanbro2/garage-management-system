@extends('layouts.app')

@section('title', 'Customer Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">{{ $customer->name }}</h1>
    <div>
        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-outline-primary">Edit</a>
        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Customer Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Phone:</strong> {{ $customer->phone }}</p>
                <p><strong>Email:</strong> {{ $customer->email ?? 'N/A' }}</p>
                <p><strong>Address:</strong> {{ $customer->address ?? 'N/A' }}</p>
                <p><strong>Notes:</strong> {{ $customer->notes ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Vehicles</h5>
                <a href="{{ route('vehicles.create', ['customer_id' => $customer->id]) }}" class="btn btn-sm btn-primary">Add Vehicle</a>
            </div>
            <div class="card-body">
                @if($customer->vehicles->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Registration</th>
                                    <th>Vehicle</th>
                                    <th>MOT Expiry</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customer->vehicles as $vehicle)
                                <tr>
                                    <td>{{ $vehicle->registration_number }}</td>
                                    <td>{{ $vehicle->make }} {{ $vehicle->model }}</td>
                                    <td>{{ $vehicle->mot_expiry ? $vehicle->mot_expiry->format('M d, Y') : 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No vehicles found for this customer.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection