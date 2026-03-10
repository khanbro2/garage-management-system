@extends('layouts.app')

@section('title', 'Edit Garage')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Garage: {{ $garage->name }}</h1>
    <div>
        <a href="{{ route('superadmin.garages.show', $garage) }}" class="btn btn-outline-info me-2">
            <i class="bi bi-eye"></i> View
        </a>
        <a href="{{ route('superadmin.garages.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Garage Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('superadmin.garages.update', $garage) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Garage Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $garage->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $garage->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $garage->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="2">{{ old('address', $garage->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="vat_number" class="form-label">VAT Number</label>
                        <input type="text" class="form-control @error('vat_number') is-invalid @enderror" 
                               id="vat_number" name="vat_number" value="{{ old('vat_number', $garage->vat_number) }}">
                        @error('vat_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                               {{ old('is_active', $garage->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('superadmin.garages.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Garage</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">Current Subscription</h6>
            </div>
            <div class="card-body">
                @if($garage->activeSubscription)
                    <h5 class="card-title">{{ $garage->activeSubscription->plan->name }}</h5>
                    <p class="card-text">
                        <small class="text-muted">Expires: {{ $garage->activeSubscription->ends_at?->format('d M Y') ?? 'Never' }}</small>
                    </p>
                    <a href="{{ route('superadmin.subscriptions.edit', $garage->activeSubscription) }}" class="btn btn-sm btn-outline-primary">
                        Manage Subscription
                    </a>
                @else
                    <p class="text-muted mb-2">No active subscription</p>
                    <a href="{{ route('superadmin.subscriptions.create', ['garage_id' => $garage->id]) }}" class="btn btn-sm btn-success">
                        Assign Plan
                    </a>
                @endif
            </div>
        </div>

        <div class="card bg-light">
            <div class="card-body">
                <h6 class="card-title">Statistics</h6>
                <ul class="list-unstyled small mb-0">
                    <li class="d-flex justify-content-between">
                        <span>Customers:</span>
                        <strong>{{ $garage->customers_count ?? 0 }}</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Vehicles:</span>
                        <strong>{{ $garage->vehicles_count ?? 0 }}</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Staff:</span>
                        <strong>{{ $garage->users_count ?? 0 }}</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Created:</span>
                        <strong>{{ $garage->created_at->format('d M Y') }}</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection