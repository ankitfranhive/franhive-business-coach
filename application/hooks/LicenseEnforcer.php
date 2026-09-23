<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LicenseEnforcer
{
    public function enforce()
    {
        $CI =& get_instance();
        if (!$CI) {
            return;
        }

        $class = strtolower((string)$CI->router->fetch_class());
        if (in_array($class, array('licensevendor', 'licensecron'), true)) {
            return;
        }

        if (is_cli()) {
            return;
        }

        if (!$CI->session->userdata('logged_in')) {
            return;
        }

        $method = strtolower((string)$CI->router->fetch_method());
        if ($class === 'admin' && in_array($method, array('logout', 'userlogin', 'login'), true)) {
            return;
        }

        $CI->load->config('license');
        $CI->load->model('License_model');
        $CI->load->library('LicenseVerifier');

        $fingerprint = $CI->License_model->current_install_fingerprint();
        $cached = $CI->session->userdata('lic_enforcement_cache');
        $ttl = (int)$CI->config->item('license_session_ttl');
        if ($ttl <= 0) {
            $ttl = 20;
        }
        // Only cache an "ok" result. A blocked result must be re-checked so a
        // vendor save (active + future expiry) unlocks the CRM on the next request.
        $cache_valid = is_array($cached)
            && ($cached['state'] ?? '') === 'ok'
            && !empty($cached['until'])
            && (int)$cached['until'] > time()
            && (($cached['fingerprint'] ?? '') === $fingerprint);
        if ($cache_valid) {
            return;
        }

        $result = $this->evaluate($CI);
        $CI->session->set_userdata('lic_enforcement_cache', array(
            'state' => $result['state'],
            'variant' => $result['variant'],
            'reason' => $result['reason'],
            'expiry_date' => $result['expiry_date'],
            'fingerprint' => $fingerprint,
            'until' => time() + ($ttl * 60),
        ));

        if ($result['state'] === 'blocked') {
            $this->render_blocker($CI, $result);
        }
    }

    protected function evaluate($CI)
    {
        $out = array(
            'state' => 'ok',
            'variant' => $this->is_super_admin($CI) ? 'admin' : 'user',
            'reason' => '',
            'expiry_date' => '',
        );

        $row = $CI->License_model->current_install_license();
        if (!$row) {
            // No license issued yet — do not lock a fresh install.
            return $out;
        }

        $out['expiry_date'] = $CI->licenseverifier->normalize_date($row['expiry_date'] ?? '');
        $token_ok = $CI->licenseverifier->matches_row($row['signed_token'] ?? '', $row);
        if (!$token_ok) {
            $out['state'] = 'blocked';
            $out['reason'] = 'invalid';
            return $out;
        }

        if (in_array($row['status'], array('suspended', 'cancelled'), true)
            || in_array($row['client_status'] ?? '', array('suspended', 'cancelled'), true)
        ) {
            $out['state'] = 'blocked';
            $out['reason'] = 'suspended';
            return $out;
        }

        $grace = (int)$row['grace_period_days'];
        if ($grace < 0) {
            $grace = 0;
        }
        $end = strtotime($out['expiry_date'] . ' 23:59:59');
        if ($end === false) {
            $out['state'] = 'blocked';
            $out['reason'] = 'invalid';
            return $out;
        }
        $end += ($grace * 86400);
        if (time() > $end) {
            $out['state'] = 'blocked';
            $out['reason'] = 'expired';
        }
        return $out;
    }

    protected function is_super_admin($CI)
    {
        $perm_id = (string)$CI->config->item('license_super_admin_permission_id');
        if ($perm_id === '') {
            $perm_id = '9';
        }
        $permissions = $CI->session->userdata('permissions');
        if (!is_array($permissions)) {
            $permissions = array_filter(array_map('trim', explode(',', (string)$permissions)));
        } else {
            $flat = array();
            foreach ($permissions as $p) {
                if (is_array($p) && isset($p['PERMISSION_ID'])) {
                    $flat[] = (string)$p['PERMISSION_ID'];
                } else {
                    $flat[] = (string)$p;
                }
            }
            $permissions = $flat;
        }
        return in_array($perm_id, $permissions, true) || in_array((int)$perm_id, array_map('intval', $permissions), true);
    }

    protected function render_blocker($CI, $result)
    {
        $CI->load->config('license');
        $data = array(
            'variant' => ($result['variant'] ?? 'user') === 'admin' ? 'admin' : 'user',
            'reason' => $result['reason'] ?? 'invalid',
            'expiry_date' => $result['expiry_date'] ?? '',
            'support_email' => (string)$CI->config->item('license_support_email'),
            'renew_url' => (string)$CI->config->item('license_renew_url'),
        );
        $CI->output->set_status_header(403);
        $CI->load->view('license/blocker', $data);
        echo $CI->output->get_output();
        exit;
    }
}
