<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LicenseVendor extends CI_Controller
{
    protected $slug;
    protected $vendor;

    public function __construct()
    {
        parent::__construct();
        $this->load->config('license');
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'LicenseVerifier'));
        $this->load->model('License_model');

        $this->slug = (string)$this->config->item('license_admin_slug');
        if ($this->uri->segment(1) !== $this->slug) {
            $this->plain_404();
        }
        if (!$this->ip_allowed()) {
            $this->plain_404();
        }
        $this->vendor = $this->session->userdata('lic_vendor_user');
    }

    public function index()
    {
        if (!$this->vendor) {
            $this->login_form();
            return;
        }
        $data = $this->base_data();
        $data['clients'] = $this->License_model->get_clients_with_license();
        $this->load->view('license/vendor_layout_open', $data);
        $this->load->view('license/vendor_clients', $data);
        $this->load->view('license/vendor_layout_close');
    }

    public function auth()
    {
        if (strtoupper($this->input->method(true)) !== 'POST') {
            $this->plain_404();
        }
        $email = trim((string)$this->input->post('email'));
        $password = (string)$this->input->post('password');
        $user = $this->License_model->find_vendor_by_email($email);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            $this->plain_404();
        }
        unset($user['password_hash']);
        $this->session->set_userdata('lic_vendor_user', $user);
        $this->License_model->audit($user['id'], 'login', null, array('ip' => $this->input->ip_address()));
        redirect($this->slug);
    }

    public function logout()
    {
        $this->require_vendor();
        $this->session->unset_userdata('lic_vendor_user');
        $this->plain_404();
    }

    public function client_save()
    {
        $this->require_vendor();
        $id = (int)$this->input->post('id');
        $payload = array(
            'client_name' => trim((string)$this->input->post('client_name')),
            'client_code' => trim((string)$this->input->post('client_code')),
            'status' => $this->input->post('status') ?: 'active',
            'notes' => trim((string)$this->input->post('notes')),
        );
        if ($payload['client_name'] === '' || $payload['client_code'] === '') {
            $this->session->set_flashdata('lic_error', 'Client name and code are required.');
            redirect($this->slug);
        }
        $saved = $this->License_model->save_client($payload, $id);
        $this->License_model->audit($this->vendor['id'], $id ? 'client_update' : 'client_create', $saved, $payload);
        $this->session->set_flashdata('lic_ok', 'Client saved.');
        redirect($this->slug);
    }

    public function license_save()
    {
        $this->require_vendor();
        $id = (int)$this->input->post('license_id');
        $client_id = (int)$this->input->post('client_id');
        $client = $this->License_model->get_client($client_id);
        if (!$client) {
            $this->session->set_flashdata('lic_error', 'Client not found.');
            redirect($this->slug);
        }
        $status = $this->input->post('status') ?: 'active';
        $row = array(
            'client_id' => $client_id,
            'plan_name' => trim((string)$this->input->post('plan_name')),
            'start_date' => $this->licenseverifier->normalize_date($this->input->post('start_date')),
            'expiry_date' => $this->licenseverifier->normalize_date($this->input->post('expiry_date')),
            'grace_period_days' => max(0, (int)$this->input->post('grace_period_days')),
            'status' => $status,
            'created_by' => (int)$this->vendor['id'],
        );
        $issued = date('c');
        $row['signed_token'] = $this->licenseverifier->sign(array(
            'client_code' => $client['client_code'],
            'expiry_date' => $row['expiry_date'],
            'issued_at' => $issued,
            'status' => $status,
        ));
        $row['token_issued_at'] = date('Y-m-d H:i:s');
        $saved = $this->License_model->save_license($row, $id);
        $this->License_model->audit($this->vendor['id'], 'license_save', $client_id, array('license_id' => $saved, 'expiry_date' => $row['expiry_date']));
        $this->session->unset_userdata('lic_enforcement_cache');
        $this->session->set_flashdata('lic_ok', 'License saved and token signed.');
        redirect($this->slug . '/edit/' . $client_id);
    }

    public function edit($client_id = 0)
    {
        $this->require_vendor();
        $client = $this->License_model->get_client($client_id);
        if (!$client) {
            $this->plain_404();
        }
        $data = $this->base_data();
        $data['client'] = $client;
        $data['license'] = $this->License_model->latest_license($client_id) ?: array();
        $data['contacts'] = $this->License_model->contacts($client_id);
        $this->load->view('license/vendor_layout_open', $data);
        $this->load->view('license/vendor_edit', $data);
        $this->load->view('license/vendor_layout_close');
    }

    public function contact_save()
    {
        $this->require_vendor();
        $client_id = (int)$this->input->post('client_id');
        $id = (int)$this->input->post('id');
        $this->License_model->save_contact(array(
            'client_id' => $client_id,
            'name' => trim((string)$this->input->post('name')),
            'email' => trim((string)$this->input->post('email')),
            'phone' => trim((string)$this->input->post('phone')),
            'role_label' => trim((string)$this->input->post('role_label')),
            'is_active' => $this->input->post('is_active') ? 1 : 0,
        ), $id);
        redirect($this->slug . '/edit/' . $client_id);
    }

    public function contact_delete($id = 0, $client_id = 0)
    {
        $this->require_vendor();
        $this->License_model->delete_contact($id);
        redirect($this->slug . '/edit/' . (int)$client_id);
    }

    public function logs()
    {
        $this->require_vendor();
        $data = $this->base_data();
        $data['notifications'] = $this->License_model->notification_logs(80);
        $data['integrity'] = $this->License_model->integrity_logs(80);
        $data['heartbeats'] = $this->License_model->heartbeat_logs(40);
        $this->load->view('license/vendor_layout_open', $data);
        $this->load->view('license/vendor_logs', $data);
        $this->load->view('license/vendor_layout_close');
    }

    public function notify_test($client_id = 0)
    {
        $this->require_vendor();
        $this->load->library('LicenseNotifier');
        $ok = $this->licensenotifier->send_for_client((int)$client_id, 'test', true);
        $this->session->set_flashdata('lic_ok', $ok ? 'Test notification sent.' : 'Test notification failed (check contacts and email).');
        redirect($this->slug . '/edit/' . (int)$client_id);
    }

    public function baseline()
    {
        $this->require_vendor();
        $this->load->library('LicenseIntegrity');
        $this->licenseintegrity->seal();
        $this->License_model->audit($this->vendor['id'], 'integrity_baseline', null, array());
        $this->session->set_flashdata('lic_ok', 'Integrity baseline sealed.');
        redirect($this->slug . '/logs');
    }

    protected function login_form()
    {
        $this->load->view('license/vendor_login', array('slug' => $this->slug));
    }

    protected function require_vendor()
    {
        if (!$this->vendor) {
            $this->plain_404();
        }
    }

    protected function ip_allowed()
    {
        $raw = trim((string)$this->config->item('license_admin_allowed_ips'));
        if ($raw === '') {
            return true;
        }
        $ips = array_filter(array_map('trim', explode(',', $raw)));
        return in_array($this->input->ip_address(), $ips, true);
    }

    protected function base_data()
    {
        return array(
            'slug' => $this->slug,
            'vendor' => $this->vendor,
            'ok' => $this->session->flashdata('lic_ok'),
            'err' => $this->session->flashdata('lic_error'),
        );
    }

    protected function plain_404()
    {
        header('HTTP/1.0 404 Not Found');
        echo '404 Page Not Found';
        exit;
    }
}
