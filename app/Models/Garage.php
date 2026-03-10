<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Garage extends Authenticatable
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'owner_name',
        'email',
        'phone',
        'address',
        'password',
        'status',
        'email_verified_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function owner()
    {
        return $this->hasOne(User::class)->where('role', 'garage_owner');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(GarageSubscription::class);
    }

    public function currentSubscription()
    {
        return $this->hasOne(GarageSubscription::class)
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->latest();
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function getCurrentPlanAttribute()
    {
        return $this->currentSubscription?->plan;
    }

    public function canAddVehicle()
    {
        $plan = $this->current_plan;
        if (!$plan) return false;
        if (is_null($plan->max_vehicles)) return true;
        return $this->vehicles()->count() < $plan->max_vehicles;
    }

    public function canAddStaff()
    {
        $plan = $this->current_plan;
        if (!$plan) return false;
        if (is_null($plan->max_staff)) return true;
        return $this->users()->where('role', 'garage_staff')->count() < $plan->max_staff;
    }

    public function hasSmsReminders()
    {
        return $this->current_plan?->sms_reminders ?? false;
    }

    public function hasApiAccess()
    {
        return $this->current_plan?->api_access ?? false;
    }
}