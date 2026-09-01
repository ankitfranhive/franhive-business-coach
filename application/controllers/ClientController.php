<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ClientController extends CI_Controller
{



    public function __construct()
    {
        parent::__construct();
        $this->load->model('AdminConsoleModel');
        $this->load->model('KnowledgeCenterModel');
        $this->load->model('LeadModel');
        $this->load->model('LeadTaskModel');
        $this->load->model('Client_Model');
        $this->load->model('Template_Model');
        $this->load->model('Ratings_model');
        $this->load->model('Course_Model');
        $this->load->model('BusinessTypeModel');
        $this->load->model('ClientTypeModel');
        $this->load->model('User_Model');
        $this->load->library('form_validation');
        $this->load->library('session');
    }

    public function clientDashboard()
    {
        $data['recently_leads'] = $this->LeadModel->get_recent_leads();
        $data['recently_task'] = $this->LeadModel->get_recent_tasks();
        $data['all_clients'] = $this->Client_Model->getRecentlyAddedClients();
        $data['total_clients'] = $this->Client_Model->getTotalClients();
        // echo "<pre>";
        // print_r($data);die;
        $this->load->view('client/client_dashboard', $data);
    }

    public function clientList()
    {
        $data['all_clients'] = $this->Client_Model->getAllClients();
        // echo "<pre>";
        // print_r($data['all_clients']);die;
        $this->load->view('client/list', $data);
    }

    public function createClient()
    {
        if ($this->input->post()) {
            // Prepare data for insertion
            // print_r($this->input->post());
            // die;
            $coursesAssigned = $this->input->post('COURSE_ID');

            // Check if the selection is an array and process it
            if (is_array($coursesAssigned)) {
                print_r($coursesAssigned); // Debugging: prints the array
                // Convert the array into a comma-separated string
                $coursesAssignedString = implode(',', $coursesAssigned);
            } else {
                // Fallback in case no course is selected or if it's not an array
                $coursesAssignedString = '';
            }

            $clientData = [
                'STORE_ID' => $this->input->post('STORE_ID'),
                'STORE_NAME' => $this->input->post('STORE_NAME'),
                'DIVISION' => implode(',', $this->input->post('DIVISION')),
                'STORE_TYPE' => $this->input->post('STORE_TYPE'),
                'LEAD_OWNER' => $this->input->post('LEAD_OWNER'),
                'FORECAST_RATING' => $this->input->post('FORECAST_RATING'),
                'ADDRESS_LINE_1' => $this->input->post('ADDRESS_LINE_1'),
                'ADDRESS_LINE_2' => $this->input->post('ADDRESS_LINE_2'),
                'CITY' => $this->input->post('CITY'),
                'ZIP' => $this->input->post('ZIP'),
                'COUNTRY' => $this->input->post('COUNTRY'),
                'STATE' => $this->input->post('STATE'),
                'PHONE' => $this->input->post('PHONE'),
                'EMAIL' => $this->input->post('EMAIL'),
                'START_DATE' => $this->input->post('START_DATE'),
                'END_DATE' => $this->input->post('END_DATE'),
                'BIRTH_DATE' => $this->input->post('BIRTH_DATE'),
                'WEBSITE' => $this->input->post('WEBSITE'),
                'LINKEDIN_LINK' => $this->input->post('LINKEDIN_LINK'),
                'COURSE_ID' => $coursesAssignedString,
                'IS_FRANCHISEE' => $this->input->post('CLIENT_TYPE'),
                'IS_LEAD' => 'N'
            ];

            // Insert client data
            $clientId = $this->Client_Model->insertClient($clientData);

            if ($clientId) {
                // Set a success message and redirect to the client list page
                $this->session->set_flashdata('success', 'Client added successfully.');
            } else {
                $this->session->set_flashdata('error', 'Client added successfully.');
            }
            redirect('clients');
        } else {
            // Load data for the form
            $data['all_business_type'] = $this->BusinessTypeModel->getAllBusinessType();
            $data['ratings'] = $this->Ratings_model->getAllRatings();
            $data['all_users'] = $this->User_Model->get_all_users();
            $data['courses'] = $this->Course_Model->getAllCourse();
            $data['client_types'] = $this->ClientTypeModel->getAllClientTypes();
            $this->load->view('client/add', $data);
        }
    }

    public function getAllTasks()
    {
        // Attempt to retrieve the user ID from the session
        $userid = $this->session->userdata('user')['USER_ID'];
        if (!$userid) {
            // Handle missing user ID, e.g., redirect to login page
            show_error('User not logged in. Please log in.', 401, 'Unauthorized');
            return;
        }

        // Initialize data array
        $data = array();
        $data['all_task'] = [];
        $data['user_role'] = [];

        // Attempt to retrieve the user's role
        $user_role = $this->User_Model->getUserRoles($userid);
        if ($user_role) {
            // If user role is found, attempt to retrieve all tasks for the user
            $all_task = $this->LeadTaskModel->get_client_task_list($userid, $user_role['role_name']);
            $data['all_task'] = $all_task;
            $data['user_role'] = $user_role;
        }

        // Load the view with the data
        $this->load->view('client/task_list', $data);
    }

    public function createTask($id)
    {
        // Get the current user's ID from session
        if ($this->input->post()) {
            $user_id = $this->session->userdata('user')['USER_ID'];
            $data = $this->input->post();

            // Prepare the data to be inserted
            $task_data = array(
                'CREATED_BY' => $user_id,
                'MODIFIED_BY' => $user_id,
                'DELETED_BY' => null,
                'RECORD_STATUS' => 0, // Changed from 1 to 0
                'NODE_ID' => isset($data['NODE_ID']) ? $data['NODE_ID'] : null,
                'CREATED_ON' => date('Y-m-d H:i:s'),
                'MODIFIED_ON' => date('Y-m-d H:i:s'),
                'LAST_VIEWED_ON' => null,
                'ENTITY_ID' => $data['ENTITY_ID'],
                'ASSIGN_TO' => $data['ASSIGN_TO'],
                'STATUS' => $data['STATUS'],
                'TASK_TYPE' => $data['TASK_TYPE'],
                'SUBJECT' => $data['SUBJECT'],
                'PRIORITY' => $data['PRIORITY'],
                'START_DATE' => $data['START_DATE'],
                'START_TIME' => $data['START_TIME'],
                'END_TIME' => $data['END_TIME'],
                'TASK_DESC' => $data['TASK_DESC']
            );

            $this->LeadTaskModel->add_task($task_data);
            redirect('tasks');
        } else {
            $data['all_users'] = $this->User_Model->get_entity_by_type('N');
            $data['ENTITY_DATA'] = $this->Client_Model->getClientById($id);
            $data['ENTITY_ID'] = $id;
            $this->load->view('client/add_task', $data);
        }
    }
    


    public function updateTask($taskId)
    {
        if ($this->input->post()) {
            $user_id = $this->session->userdata('user')['USER_ID'];
            $data = $this->input->post();

            $task_data = array(
                'MODIFIED_BY' => $user_id,
                'MODIFIED_ON' => date('Y-m-d H:i:s'),
                'ASSIGN_TO' => $data['ASSIGN_TO'],
                'STATUS' => $data['STATUS'],
                'TASK_TYPE' => $data['TASK_TYPE'],
                'SUBJECT' => $data['SUBJECT'],
                'PRIORITY' => $data['PRIORITY'],
                'START_DATE' => $data['START_DATE'],
                'START_TIME' => $data['START_TIME'],
                'END_TIME' => $data['END_TIME'],
                'TASK_DESC' => $data['TASK_DESC']
            );

            $this->LeadTaskModel->update_task($taskId, $task_data);
            redirect('tasks');
        } else {
            $data['task'] = $this->LeadTaskModel->get_task_by_id($taskId);
            $data['all_users'] = $this->User_Model->get_entity_by_type('N');
            $this->load->view('client/edit_task', $data);
        }
    }

// ... existing code ...
    public function clientView($id)
    {
        $data['client_data'] = $this->Client_Model->getClientById($id);
        $data['all_business_type'] = $this->BusinessTypeModel->getAllBusinessType();
        $data['ratings'] = $this->Ratings_model->getAllRatings();
        $data['all_users'] = $this->User_Model->get_all_users();
        $data['courses'] = $this->Course_Model->getAllCourse();
        $data['client_types'] = $this->ClientTypeModel->getAllClientTypes();
        $this->load->view('client/view', $data);
    }

    public function updateClient($clientId)
    {
        if ($this->input->post()) {
            // Prepare data for updating
            $coursesAssigned = $this->input->post('COURSE_ID');

            // Check if the selection is an array and process it
            if (is_array($coursesAssigned)) {
                // Convert the array into a comma-separated string
                $coursesAssignedString = implode(',', $coursesAssigned);
            } else {
                // Fallback in case no course is selected or if it's not an array
                $coursesAssignedString = '';
            }

            $clientData = [
                'STORE_ID' => $this->input->post('STORE_ID'),
                'STORE_NAME' => $this->input->post('STORE_NAME'),
                'DIVISION' => implode(',', $this->input->post('DIVISION')),
                'STORE_TYPE' => $this->input->post('STORE_TYPE'),
                'LEAD_OWNER' => $this->input->post('LEAD_OWNER'),
                'FORECAST_RATING' => $this->input->post('FORECAST_RATING'),
                'ADDRESS_LINE_1' => $this->input->post('ADDRESS_LINE_1'),
                'ADDRESS_LINE_2' => $this->input->post('ADDRESS_LINE_2'),
                'CITY' => $this->input->post('CITY'),
                'ZIP' => $this->input->post('ZIP'),
                'COUNTRY' => $this->input->post('COUNTRY'),
                'STATE' => $this->input->post('STATE'),
                'PHONE' => $this->input->post('PHONE'),
                'EMAIL' => $this->input->post('EMAIL'),
                'START_DATE' => $this->input->post('START_DATE'),
                'END_DATE' => $this->input->post('END_DATE'),
                'BIRTH_DATE' => $this->input->post('BIRTH_DATE'),
                'WEBSITE' => $this->input->post('WEBSITE'),
                'LINKEDIN_LINK' => $this->input->post('LINKEDIN_LINK'),
                'COURSE_ID' => $coursesAssignedString,
                'IS_FRANCHISEE' => $this->input->post('CLIENT_TYPE'),
                'IS_LEAD' => 'N'
            ];

            // Update client data
            $isUpdated = $this->Client_Model->updateClient($clientId, $clientData);

            if ($isUpdated) {
                // Set a success message and redirect to the client list page
                $this->session->set_flashdata('success', 'Client updated successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to update client.');
            }
            redirect('clients');
        } else {
            // Load the existing client data for the form
            $data['client_data'] = $this->Client_Model->getClientById($clientId);
            $data['all_business_type'] = $this->BusinessTypeModel->getAllBusinessType();
            $data['ratings'] = $this->Ratings_model->getAllRatings();
            $data['all_users'] = $this->User_Model->get_all_users();
            $data['courses'] = $this->Course_Model->getAllCourse();
            $data['client_types'] = $this->ClientTypeModel->getAllClientTypes();
            $this->load->view('client/edit', $data);
        }
    }

    public function deleteClient($id)
    {

        // Call the delete method from the model
        $result = $this->Client_Model->deleteClientById($id);

        if ($result) {
            // Set success message and redirect
            $this->session->set_flashdata('success', 'Client deleted successfully.');
        } else {
            // Set error message and redirect
            $this->session->set_flashdata('error', 'Client could not be deleted.');
        }

        // Redirect to the clients list or wherever appropriate
        redirect('clients');
    }
}
