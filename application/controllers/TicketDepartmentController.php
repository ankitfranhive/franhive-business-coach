<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TicketDepartmentController extends CI_Controller
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
        $this->load->model('Ticket_department_model');
        // $this->load->model('Ticket_status_model');
        // $this->load->model('Ticket_priority_model');

    }

    public function getAllTicketDepartmentData()
    {
        $data['all_ticket_data'] = $this->Ticket_department_model->get_all_departments();
        // print_r($data['all_ticket_data']);
        // die;
        $this->load->view('support-config/ticket-department/list', $data);
    }

    public function viewTicketDepartment($id)
    {
        $data['ticket_data'] = $this->Ticket_department_model->get_departmentData($id);
        $this->load->view('support-config/ticket-department/view', $data);
    }

    public function createTicketDepartment()
    {
        if ($this->input->post()) {

            $data = array(
                'DEPARTMENT_NAME' => $this->input->post('DEPARTMENT_NAME'),

            );
            $this->Ticket_department_model->insertTicketDepartmentData($data);

            // Redirect to a success page or show a success message
            redirect('ticket-department-list');
        } else {
            $this->load->view('support-config/ticket-department/add');
        }
    }


    public function updateTicketDepartment($id)
    {
        if ($this->input->post()) {
            $data = array(
                'DEPARTMENT_NAME' => $this->input->post('DEPARTMENT_NAME'),
            );

            $this->Ticket_department_model->updateTicketDepartment($id, $data);

            redirect('ticket-department-list');
        } else {
            $data['ticket_data'] = $this->Ticket_department_model->get_departmentData($id);

            $this->load->view('support-config/ticket-department/edit', $data);
        }
    }

    public function deleteTicketDepartment($id)
    {
        // Call the model function to update the is_del column
        $result = $this->Ticket_department_model->deleteTicketDepartment($id);
        // Send response back to the AJAX call
        redirect('ticket-department-list');
    }
}
