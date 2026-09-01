<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BasicConfigController extends CI_Controller
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
        $this->load->model('BasicConfigModel');
    }

    public function getAllWelcomeNoteText()
    {
        $data['all_welcome_note_text'] = $this->BasicConfigModel->get_all_welcome_note_text();
        // print_r($data['all_ticket_data']);
        // die;
        $this->load->view('support-config/welcome-note-text/list', $data);
    }

    public function viewWelcomeNoteText($id)
    {
        $data['ticket_data'] = $this->BasicConfigModel->get_welcome_note_text($id);
        $this->load->view('support-config/welcome-note-text/view', $data);
    }

    public function createWelcomeNoteText()
    {
        if ($this->input->post()) {

            $data = array(
                'WELCOME_NOTE_TEXT' => $this->input->post('WELCOME_NOTE_TEXT'),
            );
            $this->BasicConfigModel->insertWelcomeNoteText($data);

            // Redirect to a success page or show a success message
            redirect('welcome-note-text-list');
        } else {
            $this->load->view('support-config/welcome-note-text/add');
        }
    }


    public function updateWelcomeNoteText($id)
    {
        if ($this->input->post()) {
            $data = array(
                'WELCOME_NOTE_TEXT' => $this->input->post('WELCOME_NOTE_TEXT'),
            );

            $this->BasicConfigModel->updateBasicConfig($id, $data);

            redirect('welcome-note-text-list');
        } else {
            $data['welcome_note_data'] = $this->BasicConfigModel->get_welcome_note_text($id);

            $this->load->view('support-config/welcome-note-text/edit', $data);
        }
    }

            public function deleteWelcomeNoteText($id)
    {
        // Call the model function to update the is_del column
        $result = $this->BasicConfigModel->deleteWelcomeNoteText($id);
        // Send response back to the AJAX call
        redirect('welcome-note-text-list');
    }
}
