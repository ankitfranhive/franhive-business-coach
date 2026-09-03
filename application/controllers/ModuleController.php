<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ModuleController extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('AdminConsoleModel');
        $this->load->model('Module_Model');
        $this->load->library('session');
    }

    public function index()
    {
        $data['modules'] = $this->Module_Model->get_all_modules($this->input);
        // print_r($data);exit;
        return $this->load->view('modules/listing', $data);
    }

    public function show($module_id)
    {
        $data['module'] = $this->Module_Model->find($module_id);
        // print_r($data);exit;
        return $this->load->view('modules/show', $data);
    }

    public function store()
    {
        $this->load->view('modules/add_module');
    }

    public function save()
    {
        if ($this->session->userdata('logged_in')) {
            $id = $this->Module_Model->save($this->input);
            if ($id) {
                $this->session->set_flashdata('success', 'module Created!');
                redirect('modules');
            } else {
                throw new \Exception('Error creating new module', 404);
            }
        } else {
            throw new \Exception('You are not authorized to perform this action', 404);
        }
    }

    public function edit($module_id)
    {
        $data['module'] = $this->Module_Model->find($module_id);
        $this->load->view('modules/edit_module', $data);
    }

    public function update($module_id)
    {
        try {
            if ($this->session->userdata('logged_in')) {
                $id = $this->Module_Model->update($module_id, $this->input);
                if ($id) {
                    $this->session->set_flashdata('success', 'module Updated Successfully!');
                    redirect('modules');
                } else {
                    throw new \Exception('Error updating the module', 404);
                }
            } else {
                throw new \Exception('You are not authorized to perform this action', 404);
            }
        } catch (\Exception $e) {
            throw new \Exception('Error updating the module', 404);
        }
    }

    public function delete($module_id)
    {
        try {
            if ($this->session->userdata('logged_in')) {
                $id = $this->Module_Model->delete($module_id);
                if ($id) {
                    $this->session->set_flashdata('success', 'module DELETED Successfully!');
                    redirect('modules');
                } else {
                    throw new \Exception('Error deleting the module', 404);
                }
            } else {
                throw new \Exception('You are not authorized to perform this action', 404);
            }
        } catch (\Exception $e) {
            throw new \Exception('Error deleting the module', 404);
        }
    }

    public function activate($module_id) {}

    public function deactivate($module_id) {}
}
