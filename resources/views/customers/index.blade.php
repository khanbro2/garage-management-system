@extends('layouts.app')

@section('title', 'Customers')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Customers</h1>
    <a href="{{ route('customers.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Customer
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Vehicles</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->email ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $customer->vehicle_count }}</span>
                        </td>
                        <td>
                            <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <p class="text-muted mb-0">No customers found</p>
                            <a href="{{ route('customers.create') }}" class="btn btn-primary mt-2">Add your first customer</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $customers->links() }}
    </div>
</div>
@endsection