<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GarageSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'garage_id',
        'subscription_plan_id',
        'billing_cycle',
        'status',
        'starts_at',
        'ends_at',
        'cancelled_at',
        'payment_method',
        'payment_id',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function garage()
    {
        return $this->belongsTo(Garage::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where('ends_at', '>', now());
    }

    public function isActive()
    {
        return $this->status === 'active' && $this->ends_at->isFuture();
    }

    public function isExpired()
    {
        return $this->ends_at->isPast();
    }

    public function daysUntilExpiry()
    {
        return Carbon::now()->diffInDays($this->ends_at, false);
    }

    public function cancel()
    {
        $this->update([
            'cancelled_at' => now(),
            'status' => 'cancelled'
        ]);
    }

    public function renew($months = 1)
    {
        $newEndDate = $this->ends_at->isFuture() 
            ? $this->ends_at->addMonths($months)
            : now()->addMonths($months);
            
        $this->update([
            'ends_at' => $newEndDate,
            'status' => 'active',
            'cancelled_at' => null
        ]);
    }
}