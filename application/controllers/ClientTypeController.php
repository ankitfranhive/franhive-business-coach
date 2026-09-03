<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ClientTypeController extends CI_Controller
{



    public function __construct()
    {
        parent::__construct();
        $this->load->model('ClientTypeModel');
        $this->load->library('form_validation');
        $this->load->library('session');
    }

    public function clientDashboard()
    {
        $data['recently_leads'] = $this->LeadModel->get_recent_leads();
        $data['recently_task'] = $this->LeadModel->get_recent_tasks();
        // echo "<pre>";
        // print_r($data);die;
        $this->load->view('client/client_dashboard', $data);
    }

    public function clientTypeList()
    {
        $data['all_course'] = $this->ClientTypeModel->getAllClientTypes();
        $this->load->view('client/type/list', $data);
    }

    public function createType()
    {
        if ($this->input->post()) {
            $data = [
                'NAME' => $this->input->post('NAME'),

            ];

            // Insert client data
            $id = $this->ClientTypeModel->insertClientType($data);

            if ($id) {
                // Set a success message and redirect to the client list page
                $this->session->set_flashdata('success', 'Client Type added successfully.');
            } else {
                $this->session->set_flashdata('error', 'Client Type added successfully.');
            }
            redirect('client-type-list');
        } else {
            // Load data for the form

            $this->load->view('client/type/add');
        }
    }
    public function updateType($id)
    {
        if ($this->input->post()) {
            $data = [
                'NAME' => $this->input->post('NAME'),
            ];

            // Insert client data
            $id = $this->ClientTypeModel->updateClientType($id, $data);

            if ($id) {
                // Set a success message and redirect to the client list page
                $this->session->set_flashdata('success', 'Client Type updated successfully.');
            } else {
                $this->session->set_flashdata('error', 'Client Type updated successfully.');
            }
            redirect('client-type-list');
        } else {
            // Load data for the form
            $data['data'] = $this->ClientTypeModel->getClientTypeById($id);
            $this->load->view('client/type/edit', $data);
        }
    }

    public function clientTypeView($id)
    {
        $data['data'] = $this->ClientTypeModel->getClientTypeById($id);
        $this->load->view('client/type/view', $data);
    }

    // public function updateClient($clientId)
    // {
    //     if ($this->input->post()) {
    //         // Prepare data for updating
    //         $coursesAssigned = $this->input->post('COURSE_ID');

    //         // Check if the selection is an array and process it
    //         if (is_array($coursesAssigned)) {
    //             // Convert the array into a comma-separated string
    //             $coursesAssignedString = implode(',', $coursesAssigned);
    //         } else {
    //             // Fallback in case no course is selected or if it's not an array
    //             $coursesAssignedString = '';
    //         }

    //         $clientData = [
    //             'STORE_ID' => $this->input->post('STORE_ID'),
    //             'STORE_NAME' => $this->input->post('STORE_NAME'),
    //             'DIVISION' => implode(',', $this->input->post('DIVISION')),
    //             'STORE_TYPE' => $this->input->post('STORE_TYPE'),
    //             'LEAD_OWNER' => $this->input->post('LEAD_OWNER'),
    //             'FORECAST_RATING' => $this->input->post('FORECAST_RATING'),
    //             'ADDRESS_LINE_1' => $this->input->post('ADDRESS_LINE_1'),
    //             'ADDRESS_LINE_2' => $this->input->post('ADDRESS_LINE_2'),
    //             'CITY' => $this->input->post('CITY'),
    //             'ZIP' => $this->input->post('ZIP'),
    //             'COUNTRY' => $this->input->post('COUNTRY'),
    //             'STATE' => $this->input->post('STATE'),
    //             'PHONE' => $this->input->post('PHONE'),
    //             'EMAIL' => $this->input->post('EMAIL'),
    //             'START_DATE' => $this->input->post('START_DATE'),
    //             'END_DATE' => $this->input->post('END_DATE'),
    //             'BIRTH_DATE' => $this->input->post('BIRTH_DATE'),
    //             'WEBSITE' => $this->input->post('WEBSITE'),
    //             'LINKEDIN_LINK' => $this->input->post('LINKEDIN_LINK'),
    //             'COURSE_ID' => $coursesAssignedString,
    //             'IS_FRANCHISEE' => $this->input->post('CLIENT_TYPE') == '1' ? 'N' : 'Y',
    //             'IS_LEAD' => 'N'
    //         ];

    //         // Update client data
    //         $isUpdated = $this->Client_Model->updateClient($clientId, $clientData);

    //         if ($isUpdated) {
    //             // Set a success message and redirect to the client list page
    //             $this->session->set_flashdata('success', 'Client updated successfully.');
    //         } else {
    //             $this->session->set_flashdata('error', 'Failed to update client.');
    //         }
    //         redirect('clients');
    //     } else {
    //         // Load the existing client data for the form
    //         $data['client_data'] = $this->Client_Model->getClientById($clientId);
    //         $data['all_business_type'] = $this->BusinessTypeModel->getAllBusinessType();
    //         $data['ratings'] = $this->Ratings_model->getAllRatings();
    //         $data['all_users'] = $this->User_Model->get_all_users();
    //         $data['courses'] = $this->Course_Model->getAllCourse();
    //         $this->load->view('client/edit', $data);
    //     }
    // }

    public function deleteClientType($id)
    {
        // Call the delete method from the model
        $result = $this->ClientTypeModel->deleteClientTypeById($id);

        if ($result) {
            // Set success message and redirect
            $this->session->set_flashdata('success', 'Client Type deleted successfully.');
        } else {
            // Set error message and redirect
            $this->session->set_flashdata('error', 'Client Type could not be deleted.');
        }

        // Redirect to the clients list or wherever appropriate
        redirect('client-type-list');
    }
}
