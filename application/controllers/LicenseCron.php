<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LicenseCron extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->config('license');
        if (!is_cli()) {
            $token = (string)$this->input->get('token');
            $expected = (string)$this->config->item('license_cron_token');
            if ($expected === '' || !hash_equals($expected, $token)) {
                header('HTTP/1.0 404 Not Found');
                echo '404 Page Not Found';
                exit;
            }
        }
        $this->load->model('License_model');
        $this->load->library('LicenseNotifier');
        $this->load->library('LicenseIntegrity');
    }

    public function notify()
    {
        $n = $this->licensenotifier->run_daily();
        echo 'license notifications sent: ' . (int)$n . PHP_EOL;
    }

    public function integrity()
    {
        $ok = $this->licenseintegrity->check();
        echo 'license integrity: ' . ($ok ? 'ok' : 'mismatch') . PHP_EOL;
    }

    public function daily()
    {
        $this->notify();
        $this->integrity();
    }
}
