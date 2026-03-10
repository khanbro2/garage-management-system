@extends('layouts.app')

@section('title', 'Subscription Plans')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Subscription Plans</h1>
    <a href="{{ route('superadmin.plans.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Create Plan
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Price (Monthly)</th>
                        <th>Price (Yearly)</th>
                        <th>Features</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plans as $plan)
                    <tr>
                        <td>
                            <strong>{{ $plan->name }}</strong><br>
                            <small class="text-muted">{{ $plan->slug }}</small>
                        </td>
                        <td>${{ number_format($plan->price_monthly, 2) }}</td>
                        <td>${{ number_format($plan->price_yearly, 2) }}</td>
                        <td>
                            <small>
                                @if($plan->max_vehicles) Max {{ $plan->max_vehicles }} vehicles @else Unlimited vehicles @endif<br>
                                @if($plan->max_staff) Max {{ $plan->max_staff }} staff @else Unlimited staff @endif<br>
                                @if($plan->sms_reminders) <span class="badge bg-info">SMS</span> @endif
                                @if($plan->api_access) <span class="badge bg-primary">API</span> @endif
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-{{ $plan->is_active ? 'success' : 'secondary' }}">
                                {{ $plan->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('superadmin.plans.edit', $plan) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No plans found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection