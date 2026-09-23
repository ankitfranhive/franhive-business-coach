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
        $this->secret = $this->resolve_secret($secret);
    }

    public function sign(array $payload)
    {
        $payload = $this->normalize_payload($payload);
        $json = json_encode($payload, JSON_UNESCAPED_SLASHES);
        return 'lic1.' . $this->b64($json) . '.' . hash_hmac('sha256', $json, $this->secret);
    }

    public function verify($token)
    {
        if (!is_string($token) || $token === '') {
            return false;
        }
        $payload = $this->verify_with_secret($token, $this->secret);
        if ($payload !== false) {
            return $payload;
        }
        // Production signed tokens before license_local.php existed used an empty HMAC key.
        if ($this->secret !== '') {
            return $this->verify_with_secret($token, '');
        }
        return false;
    }

    protected function verify_with_secret($token, $secret)
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3 || $parts[0] !== 'lic1') {
            return false;
        }
        $json = $this->b64_decode($parts[1]);
        if ($json === false || $json === '') {
            return false;
        }
        $calc = hash_hmac('sha256', $json, (string)$secret);
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
        $payload = $this->verify(trim((string)$token));
        if ($payload === false) {
            return false;
        }
        $code = strtolower(trim((string)($row['client_code'] ?? '')));
        $expiry = $this->normalize_date($row['expiry_date'] ?? '');
        $status = strtolower(trim((string)($row['status'] ?? '')));
        return hash_equals(strtolower(trim((string)$payload['client_code'])), $code)
            && hash_equals($this->normalize_date($payload['expiry_date']), $expiry)
            && hash_equals(strtolower(trim((string)$payload['status'])), $status);
    }

    public function file_hash($absolute_path)
    {
        if (!is_file($absolute_path)) {
            return '';
        }
        return hash_file('sha256', $absolute_path);
    }

    protected function resolve_secret($secret)
    {
        if (is_string($secret) && $secret !== '') {
            return $secret;
        }
        $CI =& get_instance();
        $CI->config->load('license', false);
        $found = (string)$CI->config->item('license_signing_secret');
        if ($found === '') {
            $section = $CI->config->item('license');
            if (is_array($section) && !empty($section['license_signing_secret'])) {
                $found = (string)$section['license_signing_secret'];
            }
        }
        if ($found === '' && defined('APPPATH')) {
            $local = APPPATH . 'config/license_local.php';
            if (is_file($local)) {
                $config = array();
                include $local;
                if (!empty($config['license_signing_secret'])) {
                    $found = (string)$config['license_signing_secret'];
                }
            }
        }
        if ($found === '') {
            $env = getenv('LICENSE_SIGNING_SECRET');
            if (is_string($env) && $env !== '') {
                $found = $env;
            }
        }
        return $found;
    }

    public function normalize_date($value)
    {
        $value = trim((string)$value);
        if ($value === '') {
            return '';
        }
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})/', $value, $m)) {
            return $m[1] . '-' . $m[2] . '-' . $m[3];
        }
        if (preg_match('/^(\d{2})[\/\-](\d{2})[\/\-](\d{4})$/', $value, $m)) {
            return $m[3] . '-' . $m[2] . '-' . $m[1];
        }
        $ts = strtotime($value);
        return $ts ? date('Y-m-d', $ts) : $value;
    }

    protected function normalize_payload(array $payload)
    {
        return array(
            'client_code' => (string)($payload['client_code'] ?? ''),
            'expiry_date' => $this->normalize_date($payload['expiry_date'] ?? ''),
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
