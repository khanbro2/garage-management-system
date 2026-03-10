<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\MotController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\StaffController;
// ADD THESE IMPORTS
use App\Http\Controllers\SuperAdmin\GarageController;
use App\Http\Controllers\SuperAdmin\SubscriptionPlanController;
use App\Http\Controllers\SuperAdmin\SubscriptionController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/pricing', function () {
    $plans = \App\Models\SubscriptionPlan::active()->get();
    return view('pricing', compact('plans'));
})->name('pricing');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('superadmin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        $stats = [
            'total_garages' => \App\Models\Garage::count(),
            'active_garages' => \App\Models\Garage::where('status', 'active')->count(), // Uses 'status'
            'total_users' => \App\Models\User::where('role', '!=', 'super_admin')->count(),
            'total_plans' => \App\Models\SubscriptionPlan::count(),
            'active_subscriptions' => \App\Models\GarageSubscription::where('status', 'active')->where('ends_at', '>', now())->count(), // Uses 'status'
            'expired_subscriptions' => \App\Models\GarageSubscription::where('ends_at', '<', now())->count(),
        ];
        return view('super-admin.dashboard', compact('stats'));
    })->name('dashboard');

    // Garages - SINGLE RESOURCE ROUTE
    Route::resource('garages', GarageController::class);
    Route::post('garages/{garage}/toggle-status', [GarageController::class, 'toggleStatus'])
        ->name('garages.toggle-status');
    Route::get('garages/{garage}/impersonate', [GarageController::class, 'impersonate'])
        ->name('garages.impersonate');
        
    // Subscription Plans
    Route::resource('plans', SubscriptionPlanController::class)->names('plans');

    // Garage Subscriptions
    Route::resource('subscriptions', SubscriptionController::class)->names('subscriptions');
    Route::post('subscriptions/{subscription}/renew', [SubscriptionController::class, 'renew'])->name('subscriptions.renew');
    Route::post('subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
});

Route::middleware(['auth', 'ensure.garage.access', 'check.subscription'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('customers', CustomerController::class);
    Route::get('/customers/search/ajax', [CustomerController::class, 'search'])->name('customers.search.ajax');
    
    Route::resource('vehicles', VehicleController::class);
    Route::get('/vehicles/search/ajax', [VehicleController::class, 'search'])->name('vehicles.search.ajax');
    Route::post('/vehicles/{vehicle}/check-mot', [VehicleController::class, 'checkMot'])->name('vehicles.check-mot');
    
    Route::get('/mot-check', [MotController::class, 'index'])->name('mot.index');
    Route::post('/mot-check', [MotController::class, 'check'])->name('mot.check');
    Route::post('/mot-check/save', [MotController::class, 'save'])->name('mot.save');
    
    Route::resource('services', ServiceController::class);
    
    Route::get('/reminders', [ReminderController::class, 'index'])->name('reminders.index');
    Route::get('/reminders/history', [ReminderController::class, 'history'])->name('reminders.history');
    Route::post('/reminders/{reminder}/send', [ReminderController::class, 'sendNow'])->name('reminders.send');
    Route::post('/reminders/{reminder}/cancel', [ReminderController::class, 'cancel'])->name('reminders.cancel');
    
    Route::middleware(['role:garage_owner,super_admin'])->group(function () {
        Route::resource('staff', StaffController::class);
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('/subscription/required', function () {
        return view('subscription.required');
    })->name('subscription.required');
    
    Route::get('/subscription/expired', function () {
        return view('subscription.expired');
    })->name('subscription.expired');
});