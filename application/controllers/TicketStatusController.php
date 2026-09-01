<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TicketStatusController extends CI_Controller
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
        $this->load->model('Ticket_status_model');
        // $this->load->model('Ticket_priority_model');



        $this->load->model('User_Model');
    }

    public function getAllTicketStatus()
    {
        $data['all_ticket_data'] = $this->Ticket_status_model->get_all_status();
        // print_r($data['all_ticket_data']);
        // die;
        $this->load->view('support-config/ticket-status/list', $data);
    }

    public function viewTicketStatus($id)
    {
        $data['ticket_data'] = $this->Ticket_status_model->get_status($id);
        $this->load->view('support-config/ticket-status/view', $data);
    }

    public function createTicketStatus()
    {
        if ($this->input->post()) {

            $data = array(
                'STATUS' => $this->input->post('STATUS'),
            );
            $this->Ticket_status_model->insertTicketStatus($data);

            // Redirect to a success page or show a success message
            redirect('ticket-status-list');
        } else {
            $this->load->view('support-config/ticket-status/add');
        }
    }


    public function updateTicketStatus($id)
    {
        if ($this->input->post()) {
            $data = array(
                'STATUS' => $this->input->post('STATUS'),
            );

            $this->Ticket_status_model->updateTicketStatus($id, $data);

            redirect('ticket-status-list');
        } else {
            $data['ticket_data'] = $this->Ticket_status_model->get_status($id);

            $this->load->view('support-config/ticket-status/edit', $data);
        }
    }

    public function deleteTicketStatus($id)
    {
        // Call the model function to update the is_del column
        $result = $this->Ticket_status_model->deleteTicketStatus($id);
        // Send response back to the AJAX call
        redirect('ticket-status-list');
    }
}
