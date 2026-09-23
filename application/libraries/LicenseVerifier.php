<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * HMAC-SHA256 license token helper.
 * Isolated so it can later be swapped for public-key verification.
 */
class LicenseVerifier
{
    protected $secret;

    public function __construct($secret = null)
    {
        if ($secret === null || $secret === '') {
            $CI =& get_instance();
            $CI->config->load('license', true);
            $secret = (string)$CI->config->item('license_signing_secret', 'license');
            if ($secret === '') {
                $secret = (string)$CI->config->item('license_signing_secret');
            }
        }
        $this->secret = (string)$secret;
    }

    public function sign(array $payload)
    {
        $payload = $this->normalize_payload($payload);
        $json = json_encode($payload, JSON_UNESCAPED_SLASHES);
        return 'lic1.' . $this->b64($json) . '.' . hash_hmac('sha256', $json, $this->secret);
    }

    public function verify($token)
    {
        if ($this->secret === '' || !is_string($token) || $token === '') {
            return false;
        }
        $parts = explode('.', $token);
        if (count($parts) !== 3 || $parts[0] !== 'lic1') {
            return false;
        }
        $json = $this->b64_decode($parts[1]);
        if ($json === false || $json === '') {
            return false;
        }
        $calc = hash_hmac('sha256', $json, $this->secret);
        if (!hash_equals($calc, $parts[2])) {
            return false;
        }
        $payload = json_decode($json, true);
        if (!is_array($payload)) {
            return false;
        }
        foreach (array('client_code', 'expiry_date', 'issued_at', 'status') as $key) {
            if (!isset($payload[$key]) || $payload[$key] === '') {
                return false;
            }
        }
        return $payload;
    }

    public function matches_row($token, array $row)
    {
        $payload = $this->verify($token);
        if ($payload === false) {
            return false;
        }
        $code = (string)($row['client_code'] ?? '');
        $expiry = (string)($row['expiry_date'] ?? '');
        $status = (string)($row['status'] ?? '');
        return hash_equals((string)$payload['client_code'], $code)
            && hash_equals((string)$payload['expiry_date'], $expiry)
            && hash_equals((string)$payload['status'], $status);
    }

    public function file_hash($absolute_path)
    {
        if (!is_file($absolute_path)) {
            return '';
        }
        return hash_file('sha256', $absolute_path);
    }

    protected function normalize_payload(array $payload)
    {
        return array(
            'client_code' => (string)($payload['client_code'] ?? ''),
            'expiry_date' => (string)($payload['expiry_date'] ?? ''),
            'issued_at'   => (string)($payload['issued_at'] ?? date('c')),
            'status'      => (string)($payload['status'] ?? 'active'),
        );
    }

    protected function b64($raw)
    {
        return rtrim(strtr(base64_encode($raw), '+/', '-_'), '=');
    }

    protected function b64_decode($b64)
    {
        $pad = strlen($b64) % 4;
        if ($pad) {
            $b64 .= str_repeat('=', 4 - $pad);
        }
        return base64_decode(strtr($b64, '-_', '+/'));
    }
}
