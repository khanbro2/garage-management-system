@extends('layouts.app')

@section('title', 'Manage Garages')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Garages</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('superadmin.garages.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Garage
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Owner</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Subscription</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($garages as $garage)
                    <tr>
                        <td>
                            <strong>{{ $garage->name }}</strong>
                            <br><small class="text-muted">{{ $garage->address }}</small>
                        </td>
                        <td>{{ $garage->owner?->name ?? 'N/A' }}</td>
                        <td>{{ $garage->email }}</td>
                        <td>{{ $garage->phone }}</td>
                        <td>
                            @if($garage->activeSubscription)
                                <span class="badge bg-success">{{ $garage->activeSubscription->plan->name }}</span>
                                <br><small class="text-muted">Expires: {{ $garage->activeSubscription->ends_at?->format('d/m/Y') ?? 'Never' }}</small>
                            @else
                                <span class="badge bg-danger">No Active Plan</span>
                            @endif
                        </td>
                        <td>
                            @if($garage->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $garage->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('superadmin.garages.show', $garage) }}" class="btn btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('superadmin.garages.edit', $garage) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('superadmin.garages.destroy', $garage) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure? This will delete all garage data.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No garages found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $garages->links() }}
    </div>
</div>
@endsection