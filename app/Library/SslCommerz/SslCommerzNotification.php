<?php

namespace App\Library\SslCommerz;

use Illuminate\Support\Facades\Log;

class SslCommerzNotification extends AbstractSslCommerz
{
    protected array $data = [];

    /**
     * Set payload parameters for SSLCommerz request.
     */
    public function setParams(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Initiate payment session with SSLCommerz.
     *
     * @param array $requestData
     * @param string $type 'checkout' | 'hosted'
     * @param string $pattern 'json'
     * @return array
     */
    public function makePayment(array $requestData, string $type = 'checkout', string $pattern = 'json'): array
    {
        $this->setParams($requestData);

        // Required credentials
        $this->data['store_id'] = $this->store_id;
        $this->data['store_passwd'] = $this->store_pass;

        // Ensure mandatory redirect URLs if not set
        $baseUrl = url('/');
        if (empty($this->data['success_url'])) {
            $this->data['success_url'] = $baseUrl . config('sslcommerz.success_url', '/sslcommerz/success');
        }
        if (empty($this->data['fail_url'])) {
            $this->data['fail_url'] = $baseUrl . config('sslcommerz.failed_url', '/sslcommerz/fail');
        }
        if (empty($this->data['cancel_url'])) {
            $this->data['cancel_url'] = $baseUrl . config('sslcommerz.cancel_url', '/sslcommerz/cancel');
        }
        if (empty($this->data['ipn_url'])) {
            $this->data['ipn_url'] = $baseUrl . config('sslcommerz.ipn_url', '/sslcommerz/ipn');
        }

        // Mandatory customer info defaults
        $this->data['cus_name']     = !empty($this->data['cus_name']) ? $this->data['cus_name'] : 'Customer';
        $this->data['cus_email']    = !empty($this->data['cus_email']) ? $this->data['cus_email'] : 'customer@modhu.com';
        $this->data['cus_add1']     = !empty($this->data['cus_add1']) ? $this->data['cus_add1'] : 'Dhaka';
        $this->data['cus_add2']     = $this->data['cus_add2'] ?? $this->data['cus_add1'];
        $this->data['cus_city']     = !empty($this->data['cus_city']) ? $this->data['cus_city'] : 'Dhaka';
        $this->data['cus_state']    = !empty($this->data['cus_state']) ? $this->data['cus_state'] : $this->data['cus_city'];
        $this->data['cus_postcode'] = !empty($this->data['cus_postcode']) ? $this->data['cus_postcode'] : '1000';
        $this->data['cus_country']  = !empty($this->data['cus_country']) ? $this->data['cus_country'] : 'Bangladesh';
        $this->data['cus_phone']    = !empty($this->data['cus_phone']) ? $this->data['cus_phone'] : '01711111111';
        $this->data['cus_fax']      = $this->data['cus_fax'] ?? $this->data['cus_phone'];

        // Mandatory shipping info defaults (strictly required by SSLCommerz)
        $this->data['shipping_method'] = !empty($this->data['shipping_method']) ? $this->data['shipping_method'] : 'YES';
        $this->data['num_of_item']     = !empty($this->data['num_of_item']) ? intval($this->data['num_of_item']) : 1;
        $this->data['ship_name']       = !empty($this->data['ship_name']) ? $this->data['ship_name'] : $this->data['cus_name'];
        $this->data['ship_add1']       = !empty($this->data['ship_add1']) ? $this->data['ship_add1'] : $this->data['cus_add1'];
        $this->data['ship_add2']       = $this->data['ship_add2'] ?? $this->data['ship_add1'];
        $this->data['ship_city']       = !empty($this->data['ship_city']) ? $this->data['ship_city'] : $this->data['cus_city'];
        $this->data['ship_state']      = !empty($this->data['ship_state']) ? $this->data['ship_state'] : $this->data['cus_state'];
        $this->data['ship_postcode']   = !empty($this->data['ship_postcode']) ? $this->data['ship_postcode'] : $this->data['cus_postcode'];
        $this->data['ship_country']    = !empty($this->data['ship_country']) ? $this->data['ship_country'] : $this->data['cus_country'];

        // Product info defaults
        $this->data['product_name']     = !empty($this->data['product_name']) ? $this->data['product_name'] : 'Honey & Food';
        $this->data['product_category'] = !empty($this->data['product_category']) ? $this->data['product_category'] : 'Organic Honey';
        $this->data['product_profile']  = !empty($this->data['product_profile']) ? $this->data['product_profile'] : 'physical-goods';
        $this->data['emi_option']       = $this->data['emi_option'] ?? '0';

        $response = $this->callApi($this->sslc_submit_url, $this->data);

        if (!$response) {
            return [
                'status'       => 'FAILED',
                'failedreason' => $this->error ?: 'Could not connect to SSLCommerz API gateway server.',
            ];
        }

        $decoded = json_decode($response, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        Log::error('SSLCommerz makePayment non-JSON response: ' . $response);

        return [
            'status'       => 'FAILED',
            'failedreason' => 'Invalid gateway response format from SSLCommerz.',
            'raw_response' => $response,
        ];
    }

    /**
     * Validate transaction with SSLCommerz Order Validation Server API.
     *
     * @param string $trx_id Order transaction ID
     * @param float $amount Expected total order amount
     * @param string $currency Expected currency (BDT)
     * @param array $post_data Data returned from gateway callback (contains val_id)
     * @return bool
     */
    public function orderValidate(string $trx_id, float $amount, string $currency, array $post_data): bool
    {
        $val_id = trim($post_data['val_id'] ?? '');

        if (empty($val_id)) {
            $this->error = 'Validation ID (val_id) is missing in gateway callback.';
            Log::warning('SSLCommerz validation failed: missing val_id for ' . $trx_id);
            return false;
        }

        $validateData = [
            'val_id'         => $val_id,
            'store_id'       => $this->store_id,
            'store_passwd'   => $this->store_pass,
            'v'              => '1',
            'format'         => 'json',
        ];

        $response = $this->callApi($this->sslc_validation_url, $validateData);

        if (!$response) {
            $this->error = 'Could not connect to SSLCommerz validation server.';
            return false;
        }

        $res = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($res)) {
            $this->error = 'Invalid JSON response from validation server.';
            Log::error('SSLCommerz validate non-JSON: ' . $response);
            return false;
        }

        // Check 1: Status must be VALID or VALIDATED
        $status = strtoupper($res['status'] ?? '');
        if ($status !== 'VALID' && $status !== 'VALIDATED') {
            $this->error = 'Transaction status is not valid: ' . ($res['status'] ?? 'Unknown');
            Log::warning("SSLCommerz validation error for {$trx_id}: status={$status}");
            return false;
        }

        // Check 2: Transaction ID matches
        $resTrxId = $res['tran_id'] ?? '';
        if ($resTrxId !== $trx_id) {
            $this->error = "Transaction ID mismatch. Expected: {$trx_id}, Gateway: {$resTrxId}";
            Log::warning("SSLCommerz transaction mismatch: {$trx_id} != {$resTrxId}");
            return false;
        }

        // Check 3: Amount verification (within 0.01 tolerance)
        $resAmount = floatval($res['amount'] ?? 0);
        if (abs($resAmount - $amount) > 0.05) {
            $this->error = "Amount mismatch. Expected: {$amount}, Gateway: {$resAmount}";
            Log::warning("SSLCommerz amount mismatch: expected {$amount}, got {$resAmount}");
            return false;
        }

        // Check 4: Currency verification
        $resCurrency = strtoupper($res['currency'] ?? 'BDT');
        if ($resCurrency !== strtoupper($currency)) {
            $this->error = "Currency mismatch. Expected: {$currency}, Gateway: {$resCurrency}";
            Log::warning("SSLCommerz currency mismatch: expected {$currency}, got {$resCurrency}");
            return false;
        }

        return true;
    }
}
