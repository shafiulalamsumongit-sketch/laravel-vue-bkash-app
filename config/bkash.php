<?php

// config/bkash.php
return [
    'agreement_callback_url' => env('BKASH_AGREEMENT_CALLBAK_URL'),
    'payment_callback_url' => env('BKASH_PAYMENT_CALLBAK_URL'),
    'base_url' => env('BKASH_BASE_URL'),
    'tokenaized_url' => env('BKASH_TOKENAIZED_URL'),
    'app_key' => env('BKASH_APP_KEY'),
    'app_secret' => env('BKASH_APP_SECRET'),
    'username' => env('BKASH_USERNAME'),
    'password' => env('BKASH_PASSWORD'),
];