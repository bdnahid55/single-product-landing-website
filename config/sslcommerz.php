<?php

// SSLCommerz Official Configuration
// Based on https://github.com/sslcommerz/SSLCommerz-PHP

return [
    'apiCredentials' => [
        'store_id'       => env('SSLCOMMERZ_STORE_ID', 'bongo6830d3864bcb8'),
        'store_password' => env('SSLCOMMERZ_STORE_PASSWORD', 'bongo6830d3864bcb8@ssl'),
    ],
    'apiUrl' => [
        'make_payment' => [
            'sandbox' => 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php',
            'live'    => 'https://securepay.sslcommerz.com/gwprocess/v4/api.php',
        ],
        'transaction_status' => [
            'sandbox' => 'https://sandbox.sslcommerz.com/validator/api/merchantTransIDvalidationAPI.php',
            'live'    => 'https://securepay.sslcommerz.com/validator/api/merchantTransIDvalidationAPI.php',
        ],
        'order_validate' => [
            'sandbox' => 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php',
            'live'    => 'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php',
        ],
        'refund_payment' => [
            'sandbox' => 'https://sandbox.sslcommerz.com/validator/api/merchantTransIDvalidationAPI.php',
            'live'    => 'https://securepay.sslcommerz.com/validator/api/merchantTransIDvalidationAPI.php',
        ],
        'refund_status' => [
            'sandbox' => 'https://sandbox.sslcommerz.com/validator/api/merchantTransIDvalidationAPI.php',
            'live'    => 'https://securepay.sslcommerz.com/validator/api/merchantTransIDvalidationAPI.php',
        ],
    ],
    'connect_from_localhost' => env('SSLCOMMERZ_IS_LOCALHOST', true),
    'success_url'            => '/sslcommerz/success',
    'failed_url'             => '/sslcommerz/fail',
    'cancel_url'             => '/sslcommerz/cancel',
    'ipn_url'                => '/sslcommerz/ipn',
];
