@extends('layouts.app')

@section('title', 'Edit Plan: ' . $plan->name)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Subscription Plan</h1>
    <div>
        <a href="{{ route('superadmin.plans.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Plans
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Plan Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('superadmin.plans.update', $plan) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="name" class="form-label">Plan Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $plan->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="is_active" class="form-label">Status</label>
                            <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active">
                                <option value="1" {{ old('is_active', $plan->is_active) ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !old('is_active', $plan->is_active) ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="2">{{ old('description', $plan->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Price (£) *</label>
                            <div class="input-group">
                                <span class="input-group-text">£</span>
                                <input type="number" step="0.01" min="0" 
                                       class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price', $plan->price) }}" required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="billing_interval" class="form-label">Billing Interval *</label>
                            <select class="form-select @error('billing_interval') is-invalid @enderror" 
                                    id="billing_interval" name="billing_interval" required>
                                <option value="month" {{ old('billing_interval', $plan->billing_interval) == 'month' ? 'selected' : '' }}>Monthly</option>
                                <option value="year" {{ old('billing_interval', $plan->billing_interval) == 'year' ? 'selected' : '' }}>Yearly</option>
                            </select>
                            @error('billing_interval')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="mb-3">Usage Limits (leave empty for unlimited)</h6>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="max_customers" class="form-label">Max Customers</label>
                            <input type="number" min="1" 
                                   class="form-control @error('max_customers') is-invalid @enderror" 
                                   id="max_customers" name="max_customers" 
                                   value="{{ old('max_customers', $plan->max_customers) }}"
                                   placeholder="∞">
                            @error('max_customers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="max_vehicles" class="form-label">Max Vehicles</label>
                            <input type="number" min="1" 
                                   class="form-control @error('max_vehicles') is-invalid @enderror" 
                                   id="max_vehicles" name="max_vehicles" 
                                   value="{{ old('max_vehicles', $plan->max_vehicles) }}"
                                   placeholder="∞">
                            @error('max_vehicles')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="max_staff" class="form-label">Max Staff</label>
                            <input type="number" min="1" 
                                   class="form-control @error('max_staff') is-invalid @enderror" 
                                   id="max_staff" name="max_staff" 
                                   value="{{ old('max_staff', $plan->max_staff) }}"
                                   placeholder="∞">
                            @error('max_staff')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="mb-3">Features</h6>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="mot_api_access" name="mot_api_access" value="1"
                                       {{ old('mot_api_access', $plan->mot_api_access) ? 'checked' : '' }}>
                                <label class="form-check-label" for="mot_api_access">MOT API Access</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="sms_reminders" name="sms_reminders" value="1"
                                       {{ old('sms_reminders', $plan->sms_reminders) ? 'checked' : '' }}>
                                <label class="form-check-label" for="sms_reminders">SMS Reminders</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="{{ route('superadmin.plans.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Plan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="card mt-4 border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Danger Zone</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Deleting this plan will affect {{ $plan->subscriptions_count ?? 0 }} active subscriptions. They will continue with current settings but cannot renew to this plan.</p>
                <form action="{{ route('superadmin.plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete {{ $plan->name }}?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-trash"></i> Delete Plan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        {{-- Plan Preview Card --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Preview</h5>
            </div>
            <div class="card-body text-center">
                <h4 id="preview-name">{{ $plan->name }}</h4>
                <div class="my-3">
                    <span class="display-6">£<span id="preview-price">{{ number_format($plan->price, 2) }}</span></span>
                    <small class="text-muted">/<span id="preview-interval">{{ $plan->billing_interval }}</span></small>
                </div>
                <p class="text-muted small" id="preview-description">{{ $plan->description ?? 'No description' }}</p>
            </div>
        </div>

        {{-- Usage Stats --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Usage Statistics</h5>
            </div>
            <div class="list-group list-group-flush">
                <div class="list-group-item d-flex justify-content-between">
                    <span>Active Subscriptions</span>
                    <span class="badge bg-primary">{{ $plan->subscriptions_count ?? 0 }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between">
                    <span>Total Revenue</span>
                    <span class="fw-bold">£{{ number_format($plan->total_revenue ?? 0, 2) }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between">
                    <span>Created</span>
                    <span>{{ $plan->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Live preview updates
    document.getElementById('name').addEventListener('input', function() {
        document.getElementById('preview-name').textContent = this.value || 'Plan Name';
    });
    
    document.getElementById('price').addEventListener('input', function() {
        document.getElementById('preview-price').textContent = parseFloat(this.value || 0).toFixed(2);
    });
    
    document.getElementById('billing_interval').addEventListener('change', function() {
        document.getElementById('preview-interval').textContent = this.value;
    });
    
    document.getElementById('description').addEventListener('input', function() {
        document.getElementById('preview-description').textContent = this.value || 'No description';
    });
</script>
@endpush