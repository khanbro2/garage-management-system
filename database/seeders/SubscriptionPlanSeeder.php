<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'description' => 'Perfect for small garages just getting started',
                'price_monthly' => 29.99,
                'price_yearly' => 299.99,
                'max_vehicles' => 200,
                'max_staff' => 5,
                'sms_reminders' => false,
                'api_access' => false,
                'advanced_reporting' => false,
                'multiple_locations' => false,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'description' => 'For growing garages with advanced needs',
                'price_monthly' => 79.99,
                'price_yearly' => 799.99,
                'max_vehicles' => null,
                'max_staff' => null,
                'sms_reminders' => true,
                'api_access' => true,
                'advanced_reporting' => true,
                'multiple_locations' => false,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'For multi-location garage chains',
                'price_monthly' => 199.99,
                'price_yearly' => 1999.99,
                'max_vehicles' => null,
                'max_staff' => null,
                'sms_reminders' => true,
                'api_access' => true,
                'advanced_reporting' => true,
                'multiple_locations' => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}