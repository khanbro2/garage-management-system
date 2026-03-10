<?php

return [
    'mot' => [
        'api_url' => env('MOT_API_URL', 'https://api.mot.gov.uk/v1'),
        'api_key' => env('MOT_API_KEY'),
        'enabled' => env('MOT_API_ENABLED', true),
    ],
    'sms' => [
        'driver' => env('SMS_DRIVER', 'twilio'),
        'twilio' => [
            'sid' => env('TWILIO_SID'),
            'token' => env('TWILIO_TOKEN'),
            'from' => env('TWILIO_FROM'),
        ],
    ],
    'mail' => [
        'from_address' => env('MAIL_FROM_ADDRESS', 'noreply@garagemanagement.com'),
        'from_name' => env('MAIL_FROM_NAME', 'Garage Management System'),
    ],
];