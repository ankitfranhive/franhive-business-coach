<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmailTrackingController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Campaign_Model');
    }

    public function open($token)
    {
        $token = trim((string)$token);
        if ($token !== '') {
            $this->Campaign_Model->record_email_open($token);
        }
        header('Content-Type: image/gif');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        echo base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        exit;
    }

    public function click($token)
    {
        $token = trim((string)$token);
        $dest = '';
        $raw = $this->input->get('u');
        if ($raw !== null && $raw !== '') {
            $decoded = base64_decode($raw, true);
            if ($decoded !== false && preg_match('#^https?://#i', $decoded)) {
                $dest = $decoded;
            }
        }
        if ($token !== '') {
            $this->Campaign_Model->record_email_click($token, $dest);
        }
        if ($dest === '') {
            show_404();
            return;
        }
        redirect($dest);
    }

    public function unsubscribe($token)
    {
        $token = trim((string)$token);
        $row = $this->Campaign_Model->get_log_by_tracking_token($token);
        if (empty($row)) {
            show_error('Invalid unsubscribe link.', 400);
            return;
        }
        $this->Campaign_Model->record_unsubscribe($row);
        $this->load->view('campaign/unsubscribe_confirm', [
            'email' => $row['TO_EMAIL'] ?? '',
        ]);
    }
}
