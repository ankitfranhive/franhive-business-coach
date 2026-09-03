<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LeadSourceDetailsController extends CI_Controller
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
        $this->load->model('Lead_Source_Details_Model');
        // $this->load->model('Ticket_priority_model');

    }

    public function getAllLeadSourceDetails()
    {

        $data['all_lead_data'] = $this->Lead_Source_Details_Model->getAllLeadSourceDetails();
        // print_r($data['all_ticket_data']);
        // die;
        $this->load->view('leads/lead-source-details/list', $data);
    }

    public function viewLeadSourceDetails($id)
    {
        $data['lead_data'] = $this->Lead_Source_Details_Model->get_LeadSourceDetails($id);
        $this->load->view('leads/lead-source-details/view', $data);
    }

    public function createLeadtSourceDetails()
    {
        if ($this->input->post()) {
            $data = array(
                'SOURCE_DETAIL' => $this->input->post('SOURCE_DETAIL'),
            );
            $this->Lead_Source_Details_Model->insertLeadSourceDetails($data);

            // Redirect to a success page or show a success message
            redirect('lead-source-details-list');
        } else {
            $this->load->view('leads/lead-source-details/add');
        }
    }


    public function updateLeadSourceDetails($id)
    {
        if ($this->input->post()) {
            $data = array(
                'SOURCE_DETAIL' => $this->input->post('SOURCE_DETAIL'),
            );

            $this->Lead_Source_Details_Model->updateLeadSourceDetails($id, $data);

            redirect('lead-source-details-list');
        } else {
            $data['lead_data'] = $this->Lead_Source_Details_Model->get_LeadSourceDetails($id);

            $this->load->view('leads/lead-source-details/edit', $data);
        }
    }

    public function deleteLeadSourceDetails($id)
    {
        // Call the model function to update the is_del column
        $result = $this->Lead_Source_Details_Model->deleteLeadSourceDetails($id);
        // Send response back to the AJAX call
        redirect('lead-source-details-list');
    }
}
