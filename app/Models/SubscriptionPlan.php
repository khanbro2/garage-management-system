<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_monthly',
        'price_yearly',
        'max_vehicles',
        'max_staff',
        'sms_reminders',
        'api_access',
        'advanced_reporting',
        'multiple_locations',
        'is_active',
    ];

    protected $casts = [
        'price_monthly' => 'decimal:2',
        'price_yearly' => 'decimal:2',
        'sms_reminders' => 'boolean',
        'api_access' => 'boolean',
        'advanced_reporting' => 'boolean',
        'multiple_locations' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(GarageSubscription::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getPrice($billingCycle = 'monthly')
    {
        return $billingCycle === 'yearly' ? $this->price_yearly : $this->price_monthly;
    }

    public function getFeaturesAttribute()
    {
        $features = [];
        
        if ($this->max_vehicles) {
            $features[] = "Up to {$this->max_vehicles} vehicles";
        } else {
            $features[] = "Unlimited vehicles";
        }
        
        if ($this->max_staff) {
            $features[] = "Up to {$this->max_staff} staff members";
        } else {
            $features[] = "Unlimited staff";
        }
        
        if ($this->sms_reminders) $features[] = "SMS reminders";
        if ($this->api_access) $features[] = "API access";
        if ($this->advanced_reporting) $features[] = "Advanced reporting";
        if ($this->multiple_locations) $features[] = "Multiple locations";
        
        return $features;
    }
}