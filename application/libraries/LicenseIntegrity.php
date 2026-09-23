<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LicenseIntegrity
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->config('license');
        $this->CI->load->model('License_model');
        $this->CI->load->library('LicenseVerifier');
        $this->CI->load->helper('email');
    }

    public function files()
    {
        $list = $this->CI->config->item('license_enforcement_files');
        return is_array($list) ? $list : array();
    }

    public function seal()
    {
        foreach ($this->files() as $rel) {
            $abs = FCPATH . $rel;
            $hash = $this->CI->licenseverifier->file_hash($abs);
            $this->CI->License_model->seal_baseline($rel, $hash);
        }
    }

    public function check()
    {
        $baselines = $this->CI->License_model->get_baselines();
        if (empty($baselines)) {
            $this->seal();
            $baselines = $this->CI->License_model->get_baselines();
        }
        $mismatch = false;
        $details = array();
        foreach ($this->files() as $rel) {
            $abs = FCPATH . $rel;
            $actual = $this->CI->licenseverifier->file_hash($abs);
            $expected = isset($baselines[$rel]['expected_hash']) ? $baselines[$rel]['expected_hash'] : '';
            $match = ($expected !== '' && hash_equals($expected, $actual));
            if (!$match) {
                $mismatch = true;
            }
            $alerted = false;
            if (!$match) {
                $alerted = $this->alert($rel, $expected, $actual);
            }
            $this->CI->License_model->log_integrity($rel, $expected, $actual, $match, $alerted);
            $details[] = array('file' => $rel, 'match' => $match, 'actual' => $actual);
        }
        $this->heartbeat($details, $mismatch);
        return !$mismatch;
    }

    protected function alert($file, $expected, $actual)
    {
        $sent = false;
        $email = (string)$this->CI->config->item('license_alert_email');
        $code = defined('SUBDOMAIN') ? SUBDOMAIN : 'unknown';
        $msg = 'Integrity mismatch for client ' . $code . ' file ' . $file . "\nexpected " . $expected . "\nactual " . $actual;
        if ($email !== '' && function_exists('send_email')) {
            $sent = (bool)send_email($email, 'License integrity mismatch: ' . $code, nl2br(htmlspecialchars($msg)));
        }
        $webhook = (string)$this->CI->config->item('license_alert_webhook');
        if ($webhook !== '') {
            $this->post_json($webhook, array(
                'type' => 'integrity_mismatch',
                'client_code' => $code,
                'file' => $file,
                'expected' => $expected,
                'actual' => $actual,
            ));
            $sent = true;
        }
        return $sent;
    }

    protected function heartbeat(array $details, $mismatch)
    {
        $row = $this->CI->License_model->current_install_license();
        $hash = hash('sha256', json_encode($details));
        $status = $row ? (string)$row['status'] : 'none';
        if ($mismatch) {
            $status = 'integrity_fail';
        }
        $this->CI->License_model->log_heartbeat($row ? $row['client_id'] : null, $status, $hash);
        $url = (string)$this->CI->config->item('license_heartbeat_url');
        if ($url === '') {
            return;
        }
        $this->post_json($url, array(
            'client_code' => $row ? $row['client_code'] : (defined('SUBDOMAIN') ? SUBDOMAIN : ''),
            'license_status' => $status,
            'file_hash' => $hash,
        ));
    }

    protected function post_json($url, array $payload)
    {
        $json = json_encode($payload);
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 8);
            curl_exec($ch);
            curl_close($ch);
            return;
        }
        @file_get_contents($url, false, stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\n",
                'content' => $json,
                'timeout' => 8,
            ),
        )));
    }
}
