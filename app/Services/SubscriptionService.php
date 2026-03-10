<?php

namespace App\Services;

use App\Models\Garage;
use App\Models\SubscriptionPlan;
use App\Models\GarageSubscription;

class SubscriptionService
{
    public function createSubscription(Garage $garage, string $planSlug, string $billingCycle = 'monthly'): GarageSubscription
    {
        $plan = SubscriptionPlan::where('slug', $planSlug)->firstOrFail();
        
        $startsAt = now();
        $endsAt = $billingCycle === 'yearly' 
            ? $startsAt->copy()->addYear()
            : $startsAt->copy()->addMonth();

        $garage->subscriptions()
            ->where('status', 'active')
            ->update(['status' => 'cancelled', 'cancelled_at' => now()]);

        return GarageSubscription::create([
            'garage_id' => $garage->id,
            'subscription_plan_id' => $plan->id,
            'billing_cycle' => $billingCycle,
            'status' => 'active',
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
        ]);
    }

    public function upgradePlan(GarageSubscription $subscription, string $newPlanSlug): GarageSubscription
    {
        $newPlan = SubscriptionPlan::where('slug', $newPlanSlug)->firstOrFail();
        
        $subscription->update([
            'subscription_plan_id' => $newPlan->id,
        ]);

        return $subscription->fresh();
    }

    public function cancelSubscription(GarageSubscription $subscription, bool $immediate = false): void
    {
        if ($immediate) {
            $subscription->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'ends_at' => now(),
            ]);
        } else {
            $subscription->update([
                'cancelled_at' => now(),
            ]);
        }
    }

    public function renewSubscription(GarageSubscription $subscription): GarageSubscription
    {
        $billingCycle = $subscription->billing_cycle;
        $months = $billingCycle === 'yearly' ? 12 : 1;
        
        $subscription->renew($months);
        
        return $subscription->fresh();
    }

    public function checkLimits(Garage $garage): array
    {
        $plan = $garage->current_plan;
        
        if (!$plan) {
            return ['has_exceeded' => true, 'reason' => 'No active subscription'];
        }

        $limits = [];
        
        if ($plan->max_vehicles !== null) {
            $vehicleCount = $garage->vehicles()->count();
            $limits['vehicles'] = [
                'used' => $vehicleCount,
                'limit' => $plan->max_vehicles,
                'remaining' => $plan->max_vehicles - $vehicleCount,
                'exceeded' => $vehicleCount >= $plan->max_vehicles,
            ];
        }
        
        if ($plan->max_staff !== null) {
            $staffCount = $garage->users()->where('role', 'garage_staff')->count();
            $limits['staff'] = [
                'used' => $staffCount,
                'limit' => $plan->max_staff,
                'remaining' => $plan->max_staff - $staffCount,
                'exceeded' => $staffCount >= $plan->max_staff,
            ];
        }

        $hasExceeded = collect($limits)->contains(fn($limit) => $limit['exceeded']);
        
        return [
            'has_exceeded' => $hasExceeded,
            'limits' => $limits,
        ];
    }

    public function getSubscriptionStatus(Garage $garage): array
    {
        $subscription = $garage->currentSubscription;
        
        if (!$subscription) {
            return [
                'status' => 'inactive',
                'message' => 'No active subscription',
                'grace_period' => false,
            ];
        }

        $daysUntilExpiry = $subscription->daysUntilExpiry();
        $inGracePeriod = $daysUntilExpiry < 0 && $daysUntilExpiry > -7;
        
        return [
            'status' => $subscription->status,
            'plan' => $subscription->plan->name,
            'billing_cycle' => $subscription->billing_cycle,
            'expires_at' => $subscription->ends_at,
            'days_until_expiry' => $daysUntilExpiry,
            'grace_period' => $inGracePeriod,
            'message' => $inGracePeriod 
                ? 'Subscription expired. Please renew to avoid service interruption.'
                : ($daysUntilExpiry <= 7 ? 'Subscription expiring soon' : 'Active'),
        ];
    }
}