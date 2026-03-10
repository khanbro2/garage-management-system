@extends('layouts.app')

@section('title', 'Assign Subscription')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Assign Subscription to Garage</h1>
    <a href="{{ route('superadmin.subscriptions.index') }}" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('superadmin.subscriptions.store') }}">
            @csrf

            <div class="mb-3">
                <label for="garage_id" class="form-label">Garage *</label>
                <select class="form-select @error('garage_id') is-invalid @enderror" 
                        id="garage_id" name="garage_id" required>
                    <option value="">Select Garage</option>
                    @foreach($garages as $garage)
                        <option value="{{ $garage->id }}" {{ old('garage_id') == $garage->id ? 'selected' : '' }}>
                            {{ $garage->name }} ({{ $garage->email }})
                        </option>
                    @endforeach
                </select>
                @error('garage_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="plan_slug" class="form-label">Subscription Plan *</label>
                <select class="form-select @error('plan_slug') is-invalid @enderror" 
                        id="plan_slug" name="plan_slug" required>
                    <option value="">Select Plan</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->slug }}" {{ old('plan_slug') == $plan->slug ? 'selected' : '' }}>
                            {{ $plan->name }} - ${{ $plan->price_monthly }}/mo or ${{ $plan->price_yearly }}/yr
                        </option>
                    @endforeach
                </select>
                @error('plan_slug')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="billing_cycle" class="form-label">Billing Cycle *</label>
                <select class="form-select @error('billing_cycle') is-invalid @enderror" 
                        id="billing_cycle" name="billing_cycle" required>
                    <option value="monthly" {{ old('billing_cycle') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="yearly" {{ old('billing_cycle') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="starts_at" class="form-label">Start Date *</label>
                    <input type="date" class="form-control @error('starts_at') is-invalid @enderror" 
                           id="starts_at" name="starts_at" value="{{ old('starts_at', date('Y-m-d')) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="ends_at" class="form-label">End Date *</label>
                    <input type="date" class="form-control @error('ends_at') is-invalid @enderror" 
                           id="ends_at" name="ends_at" value="{{ old('ends_at', date('Y-m-d', strtotime('+1 month'))) }}" required>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Assign Subscription</button>
            </div>
        </form>
    </div>
</div>
@endsection