<?php

return [
    'default_plan' => env('DEFAULT_SUBSCRIPTION_PLAN', 'basic'),
    'grace_period_days' => env('SUBSCRIPTION_GRACE_PERIOD_DAYS', 7),
    'reminders' => [
        'days_before_mot' => [30, 7],
        'days_before_service' => [30, 7],
        'send_time' => '09:00',
    ],
    'limits' => [
        'basic' => [
            'max_vehicles' => 200,
            'max_staff' => 5,
        ],
        'pro' => [
            'max_vehicles' => null,
            'max_staff' => null,
        ],
    ],
];