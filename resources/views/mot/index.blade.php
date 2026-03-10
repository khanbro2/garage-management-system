@extends('layouts.app')

@section('title', 'MOT Check')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">MOT Check</h1>
    <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Check Vehicle MOT History</h5>
        <p class="text-muted">Enter a vehicle registration number to fetch MOT history from the DVLA database.</p>

        <form method="POST" action="{{ route('mot.check') }}">
            @csrf
            <div class="input-group mb-3">
                <input type="text" class="form-control form-control-lg @error('registration_number') is-invalid @enderror" 
                       name="registration_number" placeholder="Enter registration (e.g., AB12 CDE)" required>
                <button class="btn btn-primary" type="submit">Check MOT</button>
                @error('registration_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </form>
    </div>
</div>
@endsection