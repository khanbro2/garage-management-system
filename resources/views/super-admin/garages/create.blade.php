@extends('layouts.app')

@section('title', 'Create Garage')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Create New Garage</h1>
    <a href="{{ route('superadmin.garages.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Garage Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('superadmin.garages.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Garage Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="2">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="vat_number" class="form-label">VAT Number</label>
                        <input type="text" class="form-control @error('vat_number') is-invalid @enderror" 
                               id="vat_number" name="vat_number" value="{{ old('vat_number') }}">
                        @error('vat_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <h6 class="mb-3">Owner Account</h6>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="owner_name" class="form-label">Owner Name *</label>
                            <input type="text" class="form-control @error('owner_name') is-invalid @enderror" 
                                   id="owner_name" name="owner_name" value="{{ old('owner_name') }}" required>
                            @error('owner_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="owner_email" class="form-label">Owner Email *</label>
                            <input type="email" class="form-control @error('owner_email') is-invalid @enderror" 
                                   id="owner_email" name="owner_email" value="{{ old('owner_email') }}" required>
                            @error('owner_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="owner_password" class="form-label">Owner Password *</label>
                        <input type="password" class="form-control @error('owner_password') is-invalid @enderror" 
                               id="owner_password" name="owner_password" required>
                        @error('owner_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('superadmin.garages.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Garage</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="card-title">Quick Tips</h6>
                <ul class="small text-muted mb-0">
                    <li>Owner account will be created automatically</li>
                    <li>Garage owner can login immediately</li>
                    <li>Remember to assign a subscription plan after creation</li>
                    <li>Inactive garages cannot be accessed by users</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection