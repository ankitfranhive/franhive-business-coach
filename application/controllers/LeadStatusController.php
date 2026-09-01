<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LeadStatusController extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->model('AdminConsoleModel');
        // $this->load->model('Ticket_department_model');
        $this->load->model('Lead_Status_Model');
        // $this->load->model('Ticket_priority_model');
        $this->load->model('User_Model');
    }

    public function getAllLeadStatus()
    {
        $data['all_lead_data'] = $this->Lead_Status_Model->getAllLeadStatus();
        // print_r($data['all_ticket_data']);
        // die;
        $this->load->view('leads/lead-status/list', $data);
    }

    public function viewLeadStatus($id)
    {
        $data['lead_data'] = $this->Lead_Status_Model->get_LeadStatus($id);
        $this->load->view('leads/lead-status/view', $data);
    }

    public function createLeadtStatus()
    {
        if ($this->input->post()) {
            $data = array(
                'STATUS' => $this->input->post('STATUS'),
            );
            $this->Lead_Status_Model->insertLeadStatus($data);

            // Redirect to a success page or show a success message
            redirect('lead-status-list');
        } else {
            $this->load->view('leads/lead-status/add');
        }
    }


    public function updateLeadStatus($id)
    {
        if ($this->input->post()) {
            $data = array(
                'STATUS' => $this->input->post('STATUS'),
            );

            $this->Lead_Status_Model->updateLeadStatus($id, $data);

            redirect('lead-status-list');
        } else {
            $data['lead_data'] = $this->Lead_Status_Model->get_LeadStatus($id);

            $this->load->view('leads/lead-status/edit', $data);
        }
    }

    public function deleteLeadStatus($id)
    {
        // Call the model function to update the is_del column
        $result = $this->Lead_Status_Model->deleteLeadStatus($id);
        // Send response back to the AJAX call
        redirect('lead-status-list');
    }
}
