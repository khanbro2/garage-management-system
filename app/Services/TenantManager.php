<?php

namespace App\Services;

use App\Models\Garage;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TenantManager
{
    protected ?Garage $currentGarage = null;

    public function setGarage(Garage $garage): void
    {
        $this->currentGarage = $garage;
        session(['current_garage_id' => $garage->id]);
    }

    public function getGarage(): ?Garage
    {
        if ($this->currentGarage) {
            return $this->currentGarage;
        }

        $garageId = session('current_garage_id');
        if ($garageId) {
            $this->currentGarage = Garage::find($garageId);
            return $this->currentGarage;
        }

        if (auth()->check() && auth()->user()->garage_id) {
            $this->currentGarage = auth()->user()->garage;
            return $this->currentGarage;
        }

        return null;
    }

    public function clearGarage(): void
    {
        $this->currentGarage = null;
        session()->forget('current_garage_id');
    }

    public function hasGarage(): bool
    {
        return $this->getGarage() !== null;
    }

    public function createGarage(array $garageData, array $ownerData): Garage
    {
        return DB::transaction(function () use ($garageData, $ownerData) {
            $garage = Garage::create([
                'name' => $garageData['name'],
                'owner_name' => $garageData['owner_name'],
                'email' => $garageData['email'],
                'phone' => $garageData['phone'],
                'address' => $garageData['address'] ?? null,
                'password' => Hash::make($garageData['password']),
                'status' => 'pending',
            ]);

            $owner = User::create([
                'garage_id' => $garage->id,
                'name' => $ownerData['name'],
                'email' => $ownerData['email'],
                'password' => Hash::make($ownerData['password']),
                'role' => 'garage_owner',
                'is_active' => true,
            ]);

            $this->assignDefaultSubscription($garage);

            return $garage->fresh();
        });
    }

    protected function assignDefaultSubscription(Garage $garage): void
    {
        $defaultPlan = \App\Models\SubscriptionPlan::where('slug', 'basic')
            ->where('is_active', true)
            ->first();

        if ($defaultPlan) {
            \App\Models\GarageSubscription::create([
                'garage_id' => $garage->id,
                'subscription_plan_id' => $defaultPlan->id,
                'billing_cycle' => 'monthly',
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
            ]);
        }
    }

    public function canAccessGarage(int $garageId): bool
    {
        if (auth()->user()->isSuperAdmin()) {
            return true;
        }

        return auth()->user()->garage_id === $garageId;
    }

    public function getGarageStats(Garage $garage): array
    {
        return [
            'total_customers' => $garage->customers()->count(),
            'total_vehicles' => $garage->vehicles()->count(),
            'total_staff' => $garage->users()->where('role', 'garage_staff')->count(),
            'mot_expiring_soon' => $garage->vehicles()
                ->where('mot_expiry', '<=', now()->addDays(30))
                ->where('mot_expiry', '>=', now())
                ->count(),
            'service_due_soon' => $garage->vehicles()
                ->where('service_due', '<=', now()->addDays(30))
                ->where('service_due', '>=', now())
                ->count(),
            'subscription_status' => $garage->currentSubscription?->status ?? 'inactive',
            'subscription_ends' => $garage->currentSubscription?->ends_at,
        ];
    }
}