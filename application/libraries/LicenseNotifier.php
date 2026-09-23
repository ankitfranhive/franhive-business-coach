<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LicenseNotifier
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('License_model');
        $this->CI->load->helper('email');
    }

    public function run_daily()
    {
        $licenses = $this->CI->License_model->active_licenses();
        $sent = 0;
        foreach ($licenses as $license) {
            $type = $this->threshold_type($license);
            if ($type === null) {
                continue;
            }
            $cycle_start = date('Y-m-d 00:00:00', strtotime('-1 day'));
            if ($this->CI->License_model->notification_exists($license['id'], $type, $cycle_start)) {
                continue;
            }
            if ($this->send_for_license($license, $type, false)) {
                $sent++;
            }
        }
        return $sent;
    }

    public function send_for_client($client_id, $type = 'test', $force = false)
    {
        $license = $this->CI->License_model->latest_license($client_id);
        $client = $this->CI->License_model->get_client($client_id);
        if (!$license || !$client) {
            return false;
        }
        $license['client_name'] = $client['client_name'];
        $license['client_code'] = $client['client_code'];
        return $this->send_for_license($license, $type, $force);
    }

    public function send_for_license(array $license, $type, $force)
    {
        $contacts = $this->CI->License_model->contacts($license['client_id'], true);
        if (empty($contacts)) {
            $this->CI->License_model->log_notification($license['id'], $type, 'email', '', 'no_contacts');
            return false;
        }
        $days = $this->days_left($license['expiry_date']);
        $vars = array(
            '[Client Name]' => $license['client_name'],
            '[Expiry Date]' => date('d-m-Y', strtotime($license['expiry_date'])),
            '[Days Left]' => (string)$days,
            '[Plan Name]' => (string)$license['plan_name'],
            '[Grace Period]' => (string)$license['grace_period_days'],
            '{{client_name}}' => $license['client_name'],
            '{{expiry_date}}' => date('d-m-Y', strtotime($license['expiry_date'])),
            '{{days_left}}' => (string)$days,
        );
        list($subject, $body) = $this->template($type, $vars);
        $ok_any = false;
        foreach ($contacts as $contact) {
            $email = trim((string)$contact['email']);
            if ($email === '') {
                continue;
            }
            $personalized = strtr($body, array('[Contact Name]' => $contact['name']));
            $status = send_email($email, $subject, $personalized) ? 'sent' : 'failed';
            $this->CI->License_model->log_notification($license['id'], $type, 'email', $email, $status);
            if ($status === 'sent') {
                $ok_any = true;
            }
            if (!empty($contact['phone'])) {
                $this->CI->License_model->log_notification($license['id'], $type, 'sms', $contact['phone'], 'not_configured');
            }
        }
        return $ok_any;
    }

    protected function threshold_type(array $license)
    {
        $days = $this->days_left($license['expiry_date']);
        if ($days < 0) {
            return 'expired';
        }
        $map = array(30 => '30d', 15 => '15d', 7 => '7d', 3 => '3d', 1 => '1d');
        return isset($map[$days]) ? $map[$days] : null;
    }

    protected function days_left($expiry)
    {
        $end = strtotime($expiry . ' 00:00:00');
        $today = strtotime(date('Y-m-d'));
        return (int)floor(($end - $today) / 86400);
    }

    protected function template($type, array $vars)
    {
        $subject = 'License reminder';
        $body = '<p>Hello [Client Name],</p><p>Your CRM AMC/subscription expires on [Expiry Date] ([Days Left] day(s) remaining).</p>';
        $tpl = null;
        if ($this->CI->db->table_exists('TEMPLATES')) {
            $tpl = $this->CI->db->query("
                SELECT TEMPLATE_SUBJECT, TEMPLATE_BODY, TEMPLATE_SIGN
                FROM TEMPLATES
                WHERE (RECORD_STATUS = 0 OR RECORD_STATUS IS NULL)
                  AND TRIM(MODULE_NAME) = 'License'
                ORDER BY TEMPLATE_ID DESC
                LIMIT 1
            ")->row_array();
        }
        if ($tpl) {
            $subject = $tpl['TEMPLATE_SUBJECT'] ?: $subject;
            $body = ($tpl['TEMPLATE_BODY'] ?? '') . '<br><br>' . ($tpl['TEMPLATE_SIGN'] ?? '');
        }
        if ($type === 'expired') {
            $subject = 'CRM access expired — ' . $vars['[Client Name]'];
        }
        return array(strtr($subject, $vars), strtr($body, $vars));
    }
}
