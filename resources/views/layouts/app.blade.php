<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Garage Management System')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
        }
        
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: #94a3b8;
            padding: 0.875rem 1.25rem;
            border-radius: 0.5rem;
            margin: 0.25rem 0.75rem;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,0.1);
        }
        
        .sidebar .nav-link i {
            width: 24px;
            margin-right: 0.75rem;
        }
        
        .main-content {
            padding: 2rem;
        }
        
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .stat-card {
            background: linear-gradient(135deg, #fff 0%, #f8fafc 100%);
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .bg-primary-light { background: rgba(37, 99, 235, 0.1); color: var(--primary-color); }
        .bg-success-light { background: rgba(16, 185, 129, 0.1); color: var(--success-color); }
        .bg-warning-light { background: rgba(245, 158, 11, 0.1); color: var(--warning-color); }
        .bg-danger-light { background: rgba(239, 68, 68, 0.1); color: var(--danger-color); }
        
        .status-badge {
            padding: 0.35em 0.65em;
            border-radius: 9999px;
            font-size: 0.75em;
            font-weight: 600;
        }
        
        .status-valid { background: #d1fae5; color: #065f46; }
        .status-expiring { background: #fef3c7; color: #92400e; }
        .status-expiring-soon { background: #fee2e2; color: #991b1b; }
        .status-expired { background: #fecaca; color: #7f1d1d; }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            @auth
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-4">
                    
                    @if(auth()->user()->isSuperAdmin())
                        {{-- SUPER ADMIN MENU --}}
                        <div class="px-4 mb-4">
                            <h5 class="text-white mb-0">Super Admin</h5>
                            <small class="text-muted">System Administrator</small>
                        </div>
                        
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}" href="{{ route('superadmin.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('superadmin.garages.*') ? 'active' : '' }}" href="{{ route('superadmin.garages.index') }}">
                                    <i class="bi bi-building"></i> Garages
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('superadmin.plans.*') ? 'active' : '' }}" href="{{ route('superadmin.plans.index') }}">
                                    <i class="bi bi-tag"></i> Subscription Plans
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('superadmin.subscriptions.*') ? 'active' : '' }}" href="{{ route('superadmin.subscriptions.index') }}">
                                    <i class="bi bi-credit-card"></i> Subscriptions
                                </a>
                            </li>
                        </ul>
                        
                    @else
                        {{-- GARAGE USER MENU --}}
                        <div class="px-4 mb-4">
                            <h5 class="text-white mb-0">{{ auth()->user()->garage->name ?? 'No Garage' }}</h5>
                            <small class="text-muted">{{ auth()->user()->role }}</small>
                        </div>
                        
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                                    <i class="bi bi-people"></i> Customers
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('vehicles.*') ? 'active' : '' }}" href="{{ route('vehicles.index') }}">
                                    <i class="bi bi-car-front"></i> Vehicles
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('mot.*') ? 'active' : '' }}" href="{{ route('mot.index') }}">
                                    <i class="bi bi-clipboard-check"></i> MOT Check
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}" href="{{ route('services.index') }}">
                                    <i class="bi bi-wrench"></i> Services
                                </a>
                            </li>
                            
                            @if(auth()->user()->garage_id)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('reminders.*') ? 'active' : '' }}" href="{{ route('reminders.index') }}">
                                    <i class="bi bi-bell"></i> Reminders
                                </a>
                            </li>
                            @endif
                            
                            @can('manage-staff')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('staff.*') ? 'active' : '' }}" href="{{ route('staff.index') }}">
                                    <i class="bi bi-person-badge"></i> Staff
                                </a>
                            </li>
                            @endcan
                        </ul>
                    @endif
                    
                    <hr class="text-secondary mx-3">
                    
                    {{-- LOGOUT (Common to both) --}}
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-left"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
            @endauth
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 {{ auth()->check() ? 'main-content' : '' }}">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>