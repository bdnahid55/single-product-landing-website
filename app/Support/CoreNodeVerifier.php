<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class CoreNodeVerifier
{
    /**
     * Node verification public key (Ed25519 32-byte hex).
     */
    public const NODE_PUBKEY = '724bfd2bc7c18ddd32c98ab5753af561ce514de7cba208701973d2b296dbd822';

    /**
     * Check runtime node environment state.
     * Evaluated and cached at most once every 24 hours per token hash.
     */
    public static function check(): bool
    {
        $raw = (string) config('app.node_sig', '');
        if (trim($raw) === '') {
            return false;
        }

        $cacheKey = 'sys_node_state_' . md5($raw);

        return (bool) Cache::remember($cacheKey, 86400, function () use ($raw) {
            return self::inspect($raw);
        });
    }

    /**
     * Cryptographically inspect signature and expiration of the node payload.
     */
    public static function inspect(string $token): bool
    {
        $parts = explode('.', trim($token));
        if (count($parts) !== 2) {
            return false;
        }

        [$encodedPayload, $encodedSignature] = $parts;

        $payloadJson = self::base64UrlDecode($encodedPayload);
        $signature = self::base64UrlDecode($encodedSignature);

        if ($payloadJson === false || $signature === false) {
            return false;
        }

        // Ed25519 signature is strictly 64 bytes
        if (strlen($signature) !== 64) {
            return false;
        }

        $pubKeyHex = self::NODE_PUBKEY;
        $pubKeyBin = @hex2bin($pubKeyHex);
        if ($pubKeyBin === false || strlen($pubKeyBin) !== 32) {
            return false;
        }

        if (!function_exists('sodium_crypto_sign_verify_detached')) {
            return false;
        }

        try {
            $isValid = sodium_crypto_sign_verify_detached($signature, $payloadJson, $pubKeyBin);
            if (!$isValid) {
                return false;
            }
        } catch (\Throwable) {
            return false;
        }

        $data = json_decode($payloadJson, true);
        if (!is_array($data)) {
            return false;
        }

        // Validate expiration (0 = perpetual / lifetime, timestamp = expiration time)
        if (isset($data['exp']) && is_numeric($data['exp']) && (int) $data['exp'] > 0) {
            if (time() >= (int) $data['exp']) {
                return false;
            }
        }

        return true;
    }

    /**
     * Base64URL decode helper.
     */
    public static function base64UrlDecode(string $data): string|false
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $data .= str_repeat('=', $padlen);
        }

        return base64_decode(strtr($data, '-_', '+/'), true);
    }

    /**
     * Base64URL encode helper.
     */
    public static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
