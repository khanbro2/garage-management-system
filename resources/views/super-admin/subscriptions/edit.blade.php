@extends('layouts.app')

@section('title', 'Edit Subscription')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Subscription</h1>
    <div>
        <a href="{{ route('superadmin.garages.show', $subscription->garage) }}" class="btn btn-outline-info me-2">
            <i class="bi bi-building"></i> View Garage
        </a>
        <a href="{{ route('superadmin.subscriptions.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Subscription Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('superadmin.subscriptions.update', $subscription) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Garage</label>
                        <input type="text" class="form-control" value="{{ $subscription->garage->name }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="subscription_plan_id" class="form-label">Subscription Plan *</label>
                        <select class="form-select @error('subscription_plan_id') is-invalid @enderror" 
                                id="subscription_plan_id" name="subscription_plan_id" required>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" 
                                        {{ old('subscription_plan_id', $subscription->subscription_plan_id) == $plan->id ? 'selected' : '' }}
                                        data-price="{{ $plan->price }}"
                                        data-interval="{{ $plan->billing_interval }}">
                                    {{ $plan->name }} - £{{ number_format($plan->price, 2) }}/{{ $plan->billing_interval }}
                                </option>
                            @endforeach
                        </select>
                        @error('subscription_plan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="starts_at" class="form-label">Start Date *</label>
                            <input type="date" class="form-control @error('starts_at') is-invalid @enderror" 
                                   id="starts_at" name="starts_at" 
                                   value="{{ old('starts_at', $subscription->starts_at->format('Y-m-d')) }}" required>
                            @error('starts_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ends_at" class="form-label">End Date</label>
                            <input type="date" class="form-control @error('ends_at') is-invalid @enderror" 
                                   id="ends_at" name="ends_at" 
                                   value="{{ old('ends_at', $subscription->ends_at?->format('Y-m-d')) }}">
                            <div class="form-text">Leave empty for unlimited duration</div>
                            @error('ends_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="trial_ends_at" class="form-label">Trial Ends At</label>
                            <input type="date" class="form-control @error('trial_ends_at') is-invalid @enderror" 
                                   id="trial_ends_at" name="trial_ends_at" 
                                   value="{{ old('trial_ends_at', $subscription->trial_ends_at?->format('Y-m-d')) }}">
                            @error('trial_ends_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method">
                                <option value="">Select...</option>
                                <option value="stripe" {{ old('payment_method', $subscription->payment_method) == 'stripe' ? 'selected' : '' }}>Stripe</option>
                                <option value="paypal" {{ old('payment_method', $subscription->payment_method) == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                <option value="bank_transfer" {{ old('payment_method', $subscription->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="cash" {{ old('payment_method', $subscription->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="complimentary" {{ old('payment_method', $subscription->payment_method) == 'complimentary' ? 'selected' : '' }}>Complimentary</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="external_subscription_id" class="form-label">External Subscription ID</label>
                        <input type="text" class="form-control @error('external_subscription_id') is-invalid @enderror" 
                               id="external_subscription_id" name="external_subscription_id" 
                               value="{{ old('external_subscription_id', $subscription->external_subscription_id) }}" 
                               placeholder="e.g., Stripe subscription ID">
                        @error('external_subscription_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="cancellation_reason" class="form-label">Cancellation Reason</label>
                        <textarea class="form-control @error('cancellation_reason') is-invalid @enderror" 
                                  id="cancellation_reason" name="cancellation_reason" rows="2">{{ old('cancellation_reason', $subscription->cancellation_reason) }}</textarea>
                        @error('cancellation_reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                               {{ old('is_active', $subscription->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('superadmin.subscriptions.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Subscription</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Cancel Subscription Form --}}
        @if($subscription->is_active)
        <div class="card mt-4 border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Cancel Subscription</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Cancelling will prevent auto-renewal. The garage will retain access until the end date.</p>
                <form action="{{ route('superadmin.subscriptions.cancel', $subscription) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="cancel_reason" class="form-label">Reason for Cancellation</label>
                        <textarea class="form-control" id="cancel_reason" name="cancellation_reason" rows="2" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-x-circle"></i> Cancel Subscription
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
      {{-- Current Status Card --}}
<div class="card mb-4">
    {{-- Use the isActive() method from your model --}}
    <div class="card-header {{ $subscription->status === 'active' ? 'bg-success' : 'bg-secondary' }} text-white">
        <h5 class="mb-0">Current Status</h5>
    </div>
    <div class="card-body text-center">
        @if($subscription->status === 'active')
            <i class="bi bi-check-circle-fill text-success fs-1"></i>
            <h5 class="mt-2">Active</h5>
        @else
            <i class="bi bi-x-circle-fill text-secondary fs-1"></i>
            <h5 class="mt-2 text-capitalize">{{ $subscription->status }}</h5>
            @if($subscription->ends_at)
                <p class="small text-muted">Ended on {{ $subscription->ends_at->format('d M Y') }}</p>
            @endif
        @endif
    </div>
</div>

        {{-- Plan Details --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Plan Features</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li><i class="bi bi-check text-success"></i> Max Customers: {{ $subscription->plan->max_customers ?? 'Unlimited' }}</li>
                    <li><i class="bi bi-check text-success"></i> Max Vehicles: {{ $subscription->plan->max_vehicles ?? 'Unlimited' }}</li>
                    <li><i class="bi bi-check text-success"></i> Max Staff: {{ $subscription->plan->max_staff ?? 'Unlimited' }}</li>
                    <li><i class="bi bi-check text-success"></i> MOT API Access: {{ $subscription->plan->mot_api_access ? 'Yes' : 'No' }}</li>
                    <li><i class="bi bi-check text-success"></i> SMS Reminders: {{ $subscription->plan->sms_reminders ? 'Yes' : 'No' }}</li>
                </ul>
            </div>
        </div>

        {{-- Billing History --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Billing History</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-0">Billing history feature coming soon...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-calculate end date based on billing interval when plan changes
    document.getElementById('subscription_plan_id').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const interval = selected.getAttribute('data-interval');
        const startDate = new Date(document.getElementById('starts_at').value);
        
        if (startDate && interval) {
            const endDate = new Date(startDate);
            if (interval === 'month') {
                endDate.setMonth(endDate.getMonth() + 1);
            } else if (interval === 'year') {
                endDate.setFullYear(endDate.getFullYear() + 1);
            }
            document.getElementById('ends_at').value = endDate.toISOString().split('T')[0];
        }
    });
</script>
@endpush