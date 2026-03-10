<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\ServiceRecord;
use App\Policies\CustomerPolicy;
use App\Policies\VehiclePolicy;
use App\Policies\ServiceRecordPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Customer::class => CustomerPolicy::class,
        Vehicle::class => VehiclePolicy::class,
        ServiceRecord::class => ServiceRecordPolicy::class,
    ];

    public function boot(): void
    {
        Gate::define('manage-staff', function ($user) {
            return $user->canManageStaff();
        });

        Gate::define('manage-subscription', function ($user) {
            return $user->canManageSubscription();
        });
    }
}