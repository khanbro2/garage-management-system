@extends('layouts.app')

@section('title', 'Reminders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Upcoming Reminders</h1>
    <a href="{{ route('reminders.history') }}" class="btn btn-outline-secondary">History</a>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <h3 class="text-primary">{{ $stats['pending'] }}</h3>
                <p class="mb-0 text-muted">Pending</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <h3 class="text-success">{{ $stats['sent_today'] }}</h3>
                <p class="mb-0 text-muted">Sent Today</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <h3 class="text-danger">{{ $stats['mot_expiring_soon'] }}</h3>
                <p class="mb-0 text-muted">MOT Expiring Soon</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <h3 class="text-warning">{{ $stats['failed'] }}</h3>
                <p class="mb-0 text-muted">Failed</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Upcoming Reminders</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Type</th>
                        <th>Vehicle</th>
                        <th>Customer</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($upcomingReminders as $reminder)
                    <tr>
                        <td>
                            <span class="badge bg-{{ $reminder->type == 'mot_expiry' ? 'info' : 'warning' }}">
                                {{ $reminder->type_label }}
                            </span>
                        </td>
                        <td>{{ $reminder->vehicle->registration_number }}</td>
                        <td>{{ $reminder->customer->name }}</td>
                        <td>{{ $reminder->due_date->format('M d, Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $reminder->status == 'pending' ? 'secondary' : 'success' }}">
                                {{ ucfirst($reminder->status) }}
                            </span>
                        </td>
                        <td>
                            @if($reminder->status == 'pending')
                                <form action="{{ route('reminders.send', $reminder) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary">Send Now</button>
                                </form>
                                <form action="{{ route('reminders.cancel', $reminder) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Cancel</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No upcoming reminders</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $upcomingReminders->links() }}
    </div>
</div>
@endsection