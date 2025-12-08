<?php

return [
    'base_url' => env('AFFILIATE_API_BASE_URL', 'https://stellarafi.com/api/v1'),
    'username' => env('AFFILIATE_API_USERNAME', ''),
    'password' => env('AFFILIATE_API_PASSWORD', ''),
    'initial_rate'   => (float) env('AFFILIATE_INITIAL_RATE', 1.0),
    'recurring_rate' => (float) env('AFFILIATE_RECURRING_RATE', 0.60),
];
