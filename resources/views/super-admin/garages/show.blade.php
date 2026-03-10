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
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Garage Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Email</label>
                        <p class="mb-1">{{ $garage->email }}</p>
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
                            @if($garage->is_active)
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
        <div class="card mb-4">
            <div class="card-header">
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

        {{-- Recent Activity --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Activity</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Action</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Add activity log here if you have it --}}
                            <tr>
                                <td colspan="3" class="text-center text-muted">Activity log coming soon</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        {{-- Subscription Status --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Subscription</h5>
            </div>
            <div class="card-body">
                @if($garage->activeSubscription)
                    <h4 class="card-title">{{ $garage->activeSubscription->plan->name }}</h4>
                    <p class="card-text">
                        <span class="badge bg-success">Active</span>
                    </p>
                    <ul class="list-unstyled small">
                        <li><strong>Started:</strong> {{ $garage->activeSubscription->starts_at->format('d M Y') }}</li>
                        <li><strong>Expires:</strong> {{ $garage->activeSubscription->ends_at?->format('d M Y') ?? 'Never' }}</li>
                        <li><strong>Price:</strong> £{{ number_format($garage->activeSubscription->plan->price, 2) }}/{{ $garage->activeSubscription->plan->billing_interval }}</li>
                    </ul>
                    <a href="{{ route('superadmin.subscriptions.edit', $garage->activeSubscription) }}" class="btn btn-outline-primary btn-sm w-100">
                        Manage Subscription
                    </a>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-exclamation-circle text-warning fs-1"></i>
                        <p class="text-muted mt-2">No active subscription</p>
                        <a href="{{ route('superadmin.subscriptions.create', ['garage_id' => $garage->id]) }}" class="btn btn-success btn-sm">
                            Assign Subscription Plan
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Quick Stats --}}
        <div class="card mb-4">
            <div class="card-header">
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
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    MOT Checks (30 days)
                    <span class="badge bg-info rounded-pill">{{ $stats['recent_mots'] ?? 0 }}</span>
                </div>
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Danger Zone</h5>
            </div>
            <div class="card-body">
                <p class="small text-muted">Deleting this garage will remove all associated data including customers, vehicles, and service records.</p>
                <form action="{{ route('superadmin.garages.destroy', $garage) }}" method="POST" onsubmit="return confirm('WARNING: This will permanently delete {{ $garage->name }} and ALL associated data. Are you absolutely sure?');">
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