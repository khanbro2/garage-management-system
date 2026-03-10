@extends('layouts.app')

@section('title', 'Reminder History')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Reminder History</h1>
    <a href="{{ route('reminders.index') }}" class="btn btn-outline-secondary">Back to Upcoming</a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Sent & Failed Reminders</h5>
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
                        <th>Sent At</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reminderHistory as $reminder)
                    <tr>
                        <td>
                            <span class="badge bg-{{ $reminder->type == 'mot_expiry' ? 'info' : 'warning' }}">
                                {{ $reminder->type_label }}
                            </span>
                        </td>
                        <td>{{ $reminder->vehicle->registration_number }}</td>
                        <td>{{ $reminder->customer->name }}</td>
                        <td>{{ $reminder->due_date->format('M d, Y') }}</td>
                        <td>{{ $reminder->sent_at ? $reminder->sent_at->format('M d, Y H:i') : 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $reminder->status == 'sent' ? 'success' : 'danger' }}">
                                {{ ucfirst($reminder->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No reminder history</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $reminderHistory->links() }}
    </div>
</div>
@endsection