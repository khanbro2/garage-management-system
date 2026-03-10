@extends('layouts.app')

@section('title', 'Create Plan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Create Subscription Plan</h1>
    <a href="{{ route('superadmin.plans.index') }}" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('superadmin.plans.store') }}">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Plan Name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="slug" class="form-label">Slug *</label>
                    <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                           id="slug" name="slug" value="{{ old('slug') }}" required>
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="2">{{ old('description') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="price_monthly" class="form-label">Monthly Price ($) *</label>
                    <input type="number" step="0.01" class="form-control @error('price_monthly') is-invalid @enderror" 
                           id="price_monthly" name="price_monthly" value="{{ old('price_monthly') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="price_yearly" class="form-label">Yearly Price ($) *</label>
                    <input type="number" step="0.01" class="form-control @error('price_yearly') is-invalid @enderror" 
                           id="price_yearly" name="price_yearly" value="{{ old('price_yearly') }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="max_vehicles" class="form-label">Max Vehicles (leave empty for unlimited)</label>
                    <input type="number" class="form-control @error('max_vehicles') is-invalid @enderror" 
                           id="max_vehicles" name="max_vehicles" value="{{ old('max_vehicles') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="max_staff" class="form-label">Max Staff (leave empty for unlimited)</label>
                    <input type="number" class="form-control @error('max_staff') is-invalid @enderror" 
                           id="max_staff" name="max_staff" value="{{ old('max_staff') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="sms_reminders" name="sms_reminders" value="1" {{ old('sms_reminders') ? 'checked' : '' }}>
                        <label class="form-check-label" for="sms_reminders">SMS Reminders</label>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="api_access" name="api_access" value="1" {{ old('api_access') ? 'checked' : '' }}>
                        <label class="form-check-label" for="api_access">API Access</label>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="advanced_reporting" name="advanced_reporting" value="1" {{ old('advanced_reporting') ? 'checked' : '' }}>
                        <label class="form-check-label" for="advanced_reporting">Advanced Reporting</label>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="multiple_locations" name="multiple_locations" value="1" {{ old('multiple_locations') ? 'checked' : '' }}>
                        <label class="form-check-label" for="multiple_locations">Multiple Locations</label>
                    </div>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Create Plan</button>
            </div>
        </form>
    </div>
</div>
@endsection