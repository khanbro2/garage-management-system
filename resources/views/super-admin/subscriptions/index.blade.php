@extends('layouts.app')

@section('title', 'Garage Subscriptions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Garage Subscriptions</h1>
    <a href="{{ route('superadmin.subscriptions.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Assign Subscription
    </a>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card stat-card text-center">
            <div class="card-body">
                <h3 class="text-success">{{ $stats['total_active'] }}</h3>
                <p class="mb-0 text-muted">Active Subscriptions</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card text-center">
            <div class="card-body">
                <h3 class="text-danger">{{ $stats['total_expired'] }}</h3>
                <p class="mb-0 text-muted">Expired Subscriptions</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card text-center">
            <div class="card-body">
                <h3 class="text-warning">{{ $stats['expiring_soon'] }}</h3>
                <p class="mb-0 text-muted">Expiring Soon (7 days)</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Garage</th>
                        <th>Plan</th>
                        <th>Billing</th>
                        <th>Starts</th>
                        <th>Ends</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $subscription)
                    <tr>
                        <td>
                            <strong>{{ $subscription->garage->name }}</strong><br>
                            <small class="text-muted">{{ $subscription->garage->email }}</small>
                        </td>
                        <td>{{ $subscription->plan->name }}</td>
                        <td>{{ ucfirst($subscription->billing_cycle) }}</td>
                        <td>{{ $subscription->starts_at->format('M d, Y') }}</td>
                        <td>
                            {{ $subscription->ends_at->format('M d, Y') }}<br>
                            @if($subscription->isActive() && $subscription->daysUntilExpiry() <= 7)
                                <small class="text-warning">{{ $subscription->daysUntilExpiry() }} days left</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $subscription->status == 'active' ? 'success' : ($subscription->status == 'cancelled' ? 'secondary' : 'danger') }}">
                                {{ ucfirst($subscription->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('superadmin.subscriptions.edit', $subscription) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($subscription->isActive())
                                <form action="{{ route('superadmin.subscriptions.renew', $subscription) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Renew this subscription?')">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>
                                </form>
                                <form action="{{ route('superadmin.subscriptions.cancel', $subscription) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Cancel this subscription?')">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No subscriptions found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $subscriptions->links() }}
    </div>
</div>
@endsection