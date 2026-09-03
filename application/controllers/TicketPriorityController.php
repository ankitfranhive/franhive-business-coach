<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TicketPriorityController extends CI_Controller
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
        // $this->load->model('Ticket_status_model');
        $this->load->model('Ticket_priority_model');
    }

    public function getAllTicketPriority()
    {
        $data['all_ticket_data'] = $this->Ticket_priority_model->get_all_prioritys();
        // print_r($data['all_ticket_data']);
        // die;
        $this->load->view('support-config/ticket-priority/list', $data);
    }

    public function viewTicketPriority($id)
    {
        $data['ticket_data'] = $this->Ticket_priority_model->get_priority($id);
        $this->load->view('support-config/ticket-priority/view', $data);
    }

    public function createTicketPriority()
    {
        if ($this->input->post()) {

            $data = array(
                'PRIORITY_NAME' => $this->input->post('PRIORITY_NAME'),
            );
            $this->Ticket_priority_model->insertTicketPriority($data);

            // Redirect to a success page or show a success message
            redirect('ticket-priority-list');
        } else {
            $this->load->view('support-config/ticket-priority/add');
        }
    }


    public function updateTicketPriority($id)
    {
        if ($this->input->post()) {
            $data = array(
                'PRIORITY_NAME' => $this->input->post('PRIORITY_NAME'),
            );

            $this->Ticket_priority_model->updateTicketPriority($id, $data);

            redirect('ticket-priority-list');
        } else {
            $data['ticket_data'] = $this->Ticket_priority_model->get_priority($id);

            $this->load->view('support-config/ticket-priority/edit', $data);
        }
    }

    public function deleteTicketPriority($id)
    {
        // Call the model function to update the is_del column
        $result = $this->Ticket_priority_model->deleteTicketPriority($id);
        // Send response back to the AJAX call
        redirect('ticket-priority-list');
    }
}
