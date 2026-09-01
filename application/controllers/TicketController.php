<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TicketController extends CI_Controller
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
        $this->load->model('Ticket_Model');
        $this->load->model('Ticket_department_model');
        $this->load->model('Ticket_status_model');
        $this->load->model('Ticket_priority_model');



        $this->load->model('User_Model');
    }

    public function getAllTicketsData()
    {
        $data['all_ticket_data'] = $this->Ticket_Model->get_all_tickets();
        // print_r($data['all_ticket_data']);
        // die;
        $this->load->view('tickets/list-all-ticket', $data);
    }

    public function viewTicket($id)
    {
        $data['ticket_data'] = $this->Ticket_Model->get_ticketData($id);
        $this->load->view('tickets/view-ticket', $data);
    }

    public function createTicket()
    {
        if ($this->input->post()) {
            $upload_path = './uploads/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            $attachment = "";
            // Check if a file was selected
            if (!empty($_FILES['ATTACHMENT']['name'])) {
                // File upload configuration
                $config['upload_path'] = $upload_path; // Specify the upload directory
                $config['allowed_types'] = 'gif|jpg|png'; // Specify allowed file types
                $config['max_size'] = 1024; // Specify max file size in KB

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('ATTACHMENT')) {
                    // Handle upload failure
                    $error = array('error' => $this->upload->display_errors());
                    // var_dump( $error);die;
                    // You can handle the error as per your application's requirements
                } else {
                    // Upload successful, save the file URL to the database
                    $data = array('upload_data' => $this->upload->data());
                    $attachment = base_url() . 'uploads/' . $data['upload_data']['file_name'];

                    // Save the updated data to the database using your model

                }
            }

            $user_id = $this->session->userdata('user')['USER_ID'];

            $data = array(
                'DEPARTMENT' => $this->input->post('DEPARTMENT_ID'),
                'PRIORITY' => $this->input->post('PRIORITY'),
                'STORE_ID' => $this->input->post('STORE_ID'),
                'TICKET_STATUS' => $this->input->post('TICKET_STATUS'),
                'SUBJECT' => $this->input->post('SUBJECT'),
                'DESCRIPTION' => $this->input->post('DESCRIPTION'),
                'CREATED_BY' => $user_id,
                'RECORD_STATUS' =>  1,
                'CREATED_ON' => date('Y-m-d H:i:s'),
            );
            if ($this->input->post('ATTACHMENT')) {
                $data['ATTACHMENT'] =  $attachment;
            }

            $this->Ticket_Model->insertTicketData($data);

            // Redirect to a success page or show a success message
            redirect('ticket-list');
        } else {
            $data['departments_list'] = $this->Ticket_department_model->get_all_departments();
            $data['priority_list'] = $this->Ticket_priority_model->get_all_prioritys();
            $data['status_list'] = $this->Ticket_status_model->get_all_status();
            $data['user_list'] = $this->User_Model->get_all_users();


            $this->load->view('tickets/add-ticket', $data);
        }
    }


    public function updateTicket($id)
    {

        if ($this->input->post()) {
            $upload_path = './uploads/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            $attachment = "";
            // Check if a file was selected
            if (!empty($_FILES['ATTACHMENT']['name'])) {
                // File upload configuration
                $config['upload_path'] = $upload_path; // Specify the upload directory
                $config['allowed_types'] = 'gif|jpg|png'; // Specify allowed file types
                $config['max_size'] = 5120; // 5MB

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('ATTACHMENT')) {
                    // Handle upload failure
                    $error = array('error' => $this->upload->display_errors());
                    // var_dump( $error);die;
                    // You can handle the error as per your application's requirements
                } else {
                    // Upload successful, save the file URL to the database
                    $data = array('upload_data' => $this->upload->data());
                    $attachment = base_url() . 'uploads/' . $data['upload_data']['file_name'];

                    // Save the updated data to the database using your model

                }
            }

            $user_id = $this->session->userdata('user')['USER_ID'];

            $data = array(
                'ATTACHMENT' => $attachment,
                'DEPARTMENT' => $this->input->post('DEPARTMENT_ID'),
                'PRIORITY' => $this->input->post('PRIORITY'),
                'STORE_ID' => $this->input->post('STORE_ID'),
                'TICKET_STATUS' => $this->input->post('TICKET_STATUS'),
                'SUBJECT' => $this->input->post('SUBJECT'),
                'DESCRIPTION' => $this->input->post('DESCRIPTION'),


                'MODIFIED_BY' => $user_id,
                'RECORD_STATUS' =>  1,
                'MODIFIED_ON' => date('Y-m-d H:i:s'),
            );

            $this->Ticket_Model->updateTicket($id, $data);

            redirect('ticket-list');
        } else {
            $data['ticket_data'] = $this->Ticket_Model->get_ticketData($id);

            $data['departments_list'] = $this->Ticket_department_model->get_all_departments();
            $data['priority_list'] = $this->Ticket_priority_model->get_all_prioritys();
            $data['status_list'] = $this->Ticket_status_model->get_all_status();
            $data['user_list'] = $this->User_Model->get_all_users();

            $this->load->view('tickets/edit-ticket', $data);
        }
    }

    public function deleteTicket($id)
    {
        // Call the model function to update the is_del column
        $result = $this->Ticket_Model->deleteTicket($id);
        // Send response back to the AJAX call
        redirect('ticket-list');
    }
}
