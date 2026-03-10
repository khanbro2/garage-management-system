<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Garage Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .hero-section {
            padding: 100px 0;
            color: white;
        }
        .feature-card {
            background: rgba(255,255,255,0.95);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">GarageMS</a>
            <div class="navbar-nav ms-auto">
                @auth
                    <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                @else
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container text-center">
            <h1 class="display-3 fw-bold mb-4">Garage Management System</h1>
            <p class="lead mb-5">Complete solution for automotive garages to manage customers, vehicles, MOT checks, and service records.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5">Get Started</a>
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-5">Sign In</a>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row">
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <i class="bi bi-people display-4 text-primary mb-3"></i>
                    <h4>Customer Management</h4>
                    <p>Keep track of all your customers and their vehicle history in one place.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <i class="bi bi-car-front display-4 text-success mb-3"></i>
                    <h4>Vehicle Tracking</h4>
                    <p>Manage vehicles with automatic MOT history integration.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <i class="bi bi-bell display-4 text-warning mb-3"></i>
                    <h4>Automated Reminders</h4>
                    <p>Send automatic reminders for MOT and service due dates.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>