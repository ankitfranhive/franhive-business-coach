<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BusinessTypeController extends CI_Controller
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
        $this->load->model('BusinessTypeModel');
    }

    public function getAllBusinessType()
    {

        $data['all_data'] = $this->BusinessTypeModel->getAllBusinessType();
        // print_r($data['all_ticket_data']);
        // die;
        $this->load->view('leads/business-type/list', $data);
    }

    public function viewBusinessType($id)
    {
        $data['lead_data'] = $this->BusinessTypeModel->getBusinessType($id);
        $this->load->view('leads/business-type/view', $data);
    }

    public function createBusinessType()
    {
        if ($this->input->post()) {
            $data = array(
                'NAME' => $this->input->post('NAME'),
            );
            $this->BusinessTypeModel->insertBusinessType($data);

            // Redirect to a success page or show a success message
            redirect('business-type-list');
        } else {
            $this->load->view('leads/business-type/add');
        }
    }


    public function updateBusinessType($id)
    {
        if ($this->input->post()) {
            $data = array(
                'NAME' => $this->input->post('NAME'),
            );

            $this->BusinessTypeModel->updateBusinessType($id, $data);

            redirect('business-type-list');
        } else {
            $data['lead_data'] = $this->BusinessTypeModel->getBusinessType($id);

            $this->load->view('leads/business-type/edit', $data);
        }
    }

    public function deleteBusinessType($id)
    {
        // Call the model function to update the is_del column
        $result = $this->BusinessTypeModel->deleteBusinessType($id);
        // Send response back to the AJAX call
        redirect('business-type-list');
    }
}
