<?php

return [
    'mode'    => env('STRIPE_MODE', 'payment'),
    'secret_key' => env('STRIPE_SECRET_KEY'),
    'currency' => env('CURRENCY', 'usd')
];
