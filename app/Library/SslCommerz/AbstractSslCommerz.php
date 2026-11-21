<?php

namespace App\Library\SslCommerz;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;

abstract class AbstractSslCommerz implements SslCommerzInterface
{
    protected string $sslc_submit_url;
    protected string $sslc_validation_url;
    protected string $sslc_mode;
    protected array $sslc_data = [];
    protected string $store_id;
    protected string $store_pass;
    public string $error = '';

    public function __construct()
    {
        // 1. Fetch credentials prioritizing database Settings, falling back to config
        $storeId = Setting::get('sslcommerz_store_id') ?: config('sslcommerz.apiCredentials.store_id', 'bongo6830d3864bcb8');
        $storePass = Setting::get('sslcommerz_store_password') ?: config('sslcommerz.apiCredentials.store_password', 'bongo6830d3864bcb8@ssl');
        $modeSetting = Setting::get('sslcommerz_mode', 'sandbox');
        $isSandbox = ($modeSetting === 'sandbox');

        $this->store_id = trim($storeId);
        $this->store_pass = trim($storePass);

        $this->setSSLCommerzMode($isSandbox);
    }

    /**
     * Set environment mode (Sandbox vs Live SecurePay).
     */
    public function setSSLCommerzMode(bool $isSandbox): void
    {
        if ($isSandbox) {
            $this->sslc_mode = 'sandbox';
            $this->sslc_submit_url = config('sslcommerz.apiUrl.make_payment.sandbox', 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php');
            $this->sslc_validation_url = config('sslcommerz.apiUrl.order_validate.sandbox', 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php');
        } else {
            $this->sslc_mode = 'live';
            $this->sslc_submit_url = config('sslcommerz.apiUrl.make_payment.live', 'https://securepay.sslcommerz.com/gwprocess/v4/api.php');
            $this->sslc_validation_url = config('sslcommerz.apiUrl.order_validate.live', 'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php');
        }
    }

    public function getStoreId(): string
    {
        return $this->store_id;
    }

    public function getStorePassword(): string
    {
        return $this->store_pass;
    }

    public function getMode(): string
    {
        return $this->sslc_mode;
    }

    /**
     * Low-level cURL call to SSLCommerz API.
     */
    protected function callApi(string $url, array $data = [], array $headers = []): string|false
    {
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        // Enforce strict SSL verification in live/production mode; allow local sandbox bypass
        $isLocalSandbox = ($this->sslc_mode === 'sandbox') && (app()->environment('local', 'testing') || config('sslcommerz.connect_from_localhost', false));
        if ($isLocalSandbox) {
            curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
        } else {
            curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 2);
        }

        if (!empty($headers)) {
            curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        }

        $content = curl_exec($handle);
        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if (curl_errno($handle)) {
            $this->error = curl_error($handle);
            Log::error('SSLCommerz cURL Error: ' . $this->error);
        }

        curl_close($handle);

        if ($code >= 200 && $code < 300) {
            return $content;
        }

        Log::warning("SSLCommerz API returned HTTP status {$code}: {$content}");
        return $content ?: false;
    }
}
