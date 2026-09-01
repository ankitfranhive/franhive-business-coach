<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RegisterController extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('AdminConsoleModel');
        $this->load->model('role_model');
        $this->load->library('session');
    }

    public function index()
    {
        return $this->role_model->get_all_roles($this->input);
    }

    public function store()
    {
        $this->load->view('role/add_role');
    }

    public function save()
    {
        if ($this->session->userdata('logged_in')) {
            $logged_in_user = $this->session->userdata('user_data');
            $id = $this->role_model->save($this->input, $logged_in_user);
            if ($id) {
                return $id;
            } else {
                throw new \Exception('Error creating new role', 404);
            }
        } else {
            throw new \Exception('You are not authorized to perform this action', 404);
        }
    }

    public function update($role_id) {}

    public function delete($role_id) {}

    public function activate($role_id) {}

    public function deactivate($role_id) {}

    public function assignPermission($role_id) {}

    // public function revokePermission($role_id, $permission_id) {

    // }





}
