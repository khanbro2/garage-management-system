@extends('layouts.app')

@section('title', $garage->name)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ $garage->name }}</h1>
    <div>
        <a href="{{ route('superadmin.garages.edit', $garage) }}" class="btn btn-warning me-2">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('superadmin.garages.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        {{-- Garage Info Card --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Garage Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Email</label>
                        <p class="mb-1 fw-bold">{{ $garage->email }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Phone</label>
                        <p class="mb-1">{{ $garage->phone ?? 'Not set' }}</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small">VAT Number</label>
                        <p class="mb-1">{{ $garage->vat_number ?? 'Not set' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Status</label>
                        <p class="mb-1">
                            {{-- Corrected to use the method from your Model --}}
                            @if($garage->isActive())
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label class="text-muted small">Address</label>
                        <p class="mb-1">{{ $garage->address ?? 'Not set' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Owner Info --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Owner Details</h5>
            </div>
            <div class="card-body">
                @if($garage->owner)
                <div class="row">
                    <div class="col-md-6">
                        <label class="text-muted small">Name</label>
                        <p class="mb-1">{{ $garage->owner->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Email</label>
                        <p class="mb-1">{{ $garage->owner->email }}</p>
                    </div>
                </div>
                @else
                <p class="text-muted mb-0">No owner assigned</p>
                @endif
            </div>
        </div>

        {{-- Activity Log Placeholder --}}
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Recent Activity</h5>
            </div>
            <div class="card-body text-center py-4">
                <p class="text-muted mb-0 italic">Activity log coming soon</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        {{-- Subscription Status --}}
        <div class="card mb-4 shadow-sm border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Subscription Plan</h5>
            </div>
            <div class="card-body">
                {{-- Corrected to match your Model relationship name --}}
                @if($garage->currentSubscription)
                    <h4 class="card-title text-primary">{{ $garage->currentSubscription->plan->name }}</h4>
                    <p class="card-text">
                        <span class="badge bg-success">Active Plan</span>
                    </p>
                    <ul class="list-unstyled small border-top pt-2">
                        <li class="mb-1"><strong>Started:</strong> {{ $garage->currentSubscription->starts_at->format('d M Y') }}</li>
                        <li class="mb-1"><strong>Expires:</strong> {{ $garage->currentSubscription->ends_at->format('d M Y') }}</li>
                        <li class="mb-1"><strong>Billing:</strong> <span class="text-capitalize">{{ $garage->currentSubscription->billing_cycle }}</span></li>
                    </ul>
                    <a href="{{ route('superadmin.subscriptions.edit', $garage->currentSubscription) }}" class="btn btn-outline-primary btn-sm w-100 mt-2">
                        Manage Subscription
                    </a>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-exclamation-triangle text-warning fs-1"></i>
                        <p class="text-muted mt-2">No active subscription found.</p>
                        <a href="{{ route('superadmin.subscriptions.create', ['garage_id' => $garage->id]) }}" class="btn btn-success btn-sm w-100">
                            Assign Subscription Plan
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Quick Stats --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Statistics</h5>
            </div>
            <div class="list-group list-group-flush">
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    Total Customers
                    <span class="badge bg-primary rounded-pill">{{ $stats['customers'] ?? 0 }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    Total Vehicles
                    <span class="badge bg-primary rounded-pill">{{ $stats['vehicles'] ?? 0 }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    Staff Members
                    <span class="badge bg-primary rounded-pill">{{ $stats['staff'] ?? 0 }}</span>
                </div>
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="card border-danger shadow-sm">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Danger Zone</h5>
            </div>
            <div class="card-body">
                <p class="small text-muted">Permanently remove this garage and all its data.</p>
                <form action="{{ route('superadmin.garages.destroy', $garage) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this garage?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                        <i class="bi bi-trash"></i> Delete Garage
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection