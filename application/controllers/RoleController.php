<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RoleController extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('AdminConsoleModel');
        $this->load->model('Role_Model');
        $this->load->model('Permission_Model');
        $this->load->library('session');
    }

    public function index()
    {
        $data['roles'] = $this->Role_Model->get_all_roles($this->input);
        // print_r($data);exit;
        return $this->load->view('role/listing', $data);
    }

    public function show($role_id)
    {
        $data['role'] = $this->Role_Model->find($role_id);
        // print_r($data);exit;
        return $this->load->view('role/show', $data);
    }

    public function store()
    {
        $data['module_with_permissions'] = $this->Permission_Model->get_modules_with_permissions();
        $this->load->view('role/add_role', $data);
    }

    public function save()
    {
        if ($this->session->userdata('logged_in')) {
            $module_permissions = json_decode($this->input->post('permissions'), true);
            $permissions = [];
            foreach ($module_permissions as $permission) {
                $permissions = array_merge($permissions, array_keys($permission));
            }
            $id = $this->Role_Model->save($this->input, $permissions);
            if ($id) {
                $this->session->set_flashdata('success', 'Role Created!');
                redirect('roles');
            } else {
                throw new \Exception('Error creating new role', 404);
            }
        } else {
            throw new \Exception('You are not authorized to perform this action', 404);
        }
    }

    public function edit($role_id)
    {
        $data['role'] = $this->Role_Model->find($role_id);
        $data['module_with_permissions'] = $this->Permission_Model->get_modules_with_permissions();
        $this->load->view('role/edit_role', $data);
    }

    public function update($role_id)
    {
        try {
            if ($this->session->userdata('logged_in')) {
                $module_permissions = json_decode($this->input->post('permissions'), true);

                // print_r( $module_permissions);die;
                $permissions = [];
                foreach ($module_permissions as $permission) {
                    $permissions = array_merge($permissions, array_keys($permission));
                }
                $id = $this->Role_Model->update($role_id, $this->input, $permissions);
                if ($id) {
                    $this->session->set_flashdata('success', 'Role Updated Successfully!');
                    redirect('roles');
                } else {
                    throw new \Exception('Error updating the role', 404);
                }
            } else {
                throw new \Exception('You are not authorized to perform this action', 404);
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 404);
        }
    }

    public function delete($role_id)
    {
        try {
            if ($this->session->userdata('logged_in')) {
                $id = $this->Role_Model->delete($role_id);
                if ($id) {
                    $this->session->set_flashdata('success', 'Role DELETED Successfully!');
                    redirect('roles');
                } else {
                    throw new \Exception('Error deleting the role', 404);
                }
            } else {
                throw new \Exception('You are not authorized to perform this action', 404);
            }
        } catch (\Exception $e) {
            throw new \Exception('Error deleting the role', 404);
        }
    }

    public function activate($role_id) {}

    public function deactivate($role_id) {}

    public function assignPermission($role_id) {}

    // public function revokePermission($role_id, $permission_id) {

    // }





}
