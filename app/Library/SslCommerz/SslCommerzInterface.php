<?php

namespace App\Library\SslCommerz;

interface SslCommerzInterface
{
    public function makePayment(array $requestData, string $type = 'checkout', string $pattern = 'json');

    public function orderValidate(string $trx_id, float $amount, string $currency, array $post_data);
}
