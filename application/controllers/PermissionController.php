<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PermissionController extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('AdminConsoleModel');
        $this->load->model('Permission_Model');
        $this->load->model('Module_Model');
        $this->load->library('session');
    }

    public function index()
    {
        $data['permissions'] = $this->Permission_Model->get_all_permissions($this->input);
        // print_r($data);exit;
        return $this->load->view('permissions/listing', $data);
    }

    public function show($permission_id)
    {
        $data['permission'] = $this->Permission_Model->find($permission_id);
        // print_r($data);exit;
        return $this->load->view('permissions/show', $data);
    }

    public function store()
    {
        $data['modules'] = $this->Module_Model->get_all_modules();
        $this->load->view('permissions/add_permission', $data);
    }

    public function save()
    {
        if ($this->session->userdata('logged_in')) {
            $id = $this->Permission_Model->save($this->input);
            if ($id) {
                $this->session->set_flashdata('success', 'permission Created!');
                redirect('permissions');
            } else {
                throw new \Exception('Error creating new permission', 404);
            }
        } else {
            throw new \Exception('You are not authorized to perform this action', 404);
        }
    }

    public function edit($permission_id)
    {
        $data['permission'] = $this->Permission_Model->find($permission_id);
        $data['modules'] = $this->Module_Model->get_all_modules();
        $this->load->view('permissions/edit_permission', $data);
    }

    public function update($permission_id)
    {
        try {
            if ($this->session->userdata('logged_in')) {
                $id = $this->Permission_Model->update($permission_id, $this->input);
                if ($id) {
                    $this->session->set_flashdata('success', 'permission Updated Successfully!');
                    redirect('permissions');
                } else {
                    throw new \Exception('Error updating the permission', 404);
                }
            } else {
                throw new \Exception('You are not authorized to perform this action', 404);
            }
        } catch (\Exception $e) {
            throw new \Exception('Error updating the permission', 404);
        }
    }

    public function delete($permission_id)
    {
        try {
            if ($this->session->userdata('logged_in')) {
                $id = $this->Permission_Model->delete($permission_id);
                if ($id) {
                    $this->session->set_flashdata('success', 'permission DELETED Successfully!');
                    redirect('permissions');
                } else {
                    throw new \Exception('Error deleting the permission', 404);
                }
            } else {
                throw new \Exception('You are not authorized to perform this action', 404);
            }
        } catch (\Exception $e) {
            throw new \Exception('Error deleting the permission', 404);
        }
    }

    public function activate($permission_id) {}

    public function deactivate($permission_id) {}

    public function assignPermission($permission_id) {}

    // public function revokePermission($permission_id, $permission_id) {

    // }





}
