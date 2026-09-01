<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LeadSourceController extends CI_Controller
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
        $this->load->model('Lead_Source_Model');
        // $this->load->model('Ticket_priority_model');
        $this->load->model('User_Model');
    }

    public function getAllLeadSource()
    {
        $data['all_lead_data'] = $this->Lead_Source_Model->getAllLeadSource();
        // print_r($data['all_ticket_data']);
        // die;
        $this->load->view('leads/lead-source/list', $data);
    }

    public function viewLeadSource($id)
    {
        $data['lead_data'] = $this->Lead_Source_Model->get_LeadSource($id);
        $this->load->view('leads/lead-source/view', $data);
    }

    public function createLeadtSource()
    {
        if ($this->input->post()) {
            $data = array(
                'SOURCE' => $this->input->post('SOURCE'),
            );
            $this->Lead_Source_Model->insertLeadSource($data);

            // Redirect to a success page or show a success message
            redirect('lead-source-list');
        } else {
            $this->load->view('leads/lead-source/add');
        }
    }


    public function updateLeadSource($id)
    {
        if ($this->input->post()) {
            $data = array(
                'SOURCE' => $this->input->post('SOURCE'),
            );

            $this->Lead_Source_Model->updateLeadSource($id, $data);

            redirect('lead-source-list');
        } else {
            $data['lead_data'] = $this->Lead_Source_Model->get_LeadSource($id);

            $this->load->view('leads/lead-source/edit', $data);
        }
    }

    public function deleteLeadSource($id)
    {
        // Call the model function to update the is_del column
        $result = $this->Lead_Source_Model->deleteLeadSource($id);
        // Send response back to the AJAX call
        redirect('lead-source-list');
    }
}
