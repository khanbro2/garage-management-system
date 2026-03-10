@extends('layouts.app')

@section('title', 'Staff Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Staff Members</h1>
    <a href="{{ route('staff.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Staff
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staff as $member)
                    <tr>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->email }}</td>
                        <td>
                            <span class="badge bg-{{ $member->is_active ? 'success' : 'secondary' }}">
                                {{ $member->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $member->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('staff.edit', $member) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <p class="text-muted mb-0">No staff members found</p>
                            <a href="{{ route('staff.create') }}" class="btn btn-primary mt-2">Add your first staff member</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $staff->links() }}
    </div>
</div>
@endsection