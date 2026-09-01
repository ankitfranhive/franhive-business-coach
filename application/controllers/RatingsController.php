<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RatingsController extends CI_Controller
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
        $this->load->model('Ratings_model');
        // $this->load->model('Ticket_priority_model');
        $this->load->model('User_Model');
    }

    public function getAllRatings()
    {

        $data['all_rating_data'] = $this->Ratings_model->getAllRatings();
        // print_r($data['all_ticket_data']);
        // die;
        $this->load->view('leads/rating/list', $data);
    }

    public function viewRating($id)
    {
        $data['lead_data'] = $this->Ratings_model->get_rating($id);
        $this->load->view('leads/rating/view', $data);
    }

    public function createRating()
    {
        if ($this->input->post()) {
            $data = array(
                'RATING' => $this->input->post('RATING'),
            );
            $this->Ratings_model->insertRating($data);

            // Redirect to a success page or show a success message
            redirect('ratings-list');
        } else {
            $this->load->view('leads/rating/add');
        }
    }


    public function updateRating($id)
    {
        if ($this->input->post()) {
            $data = array(
                'RATING' => $this->input->post('RATING'),
            );

            $this->Ratings_model->updateRating($id, $data);

            redirect('ratings-list');
        } else {
            $data['lead_data'] = $this->Ratings_model->get_Rating($id);

            $this->load->view('leads/rating/edit', $data);
        }
    }

    public function deleteRating($id)
    {
        // Call the model function to update the is_del column
        $result = $this->Ratings_model->deleteRating($id);
        // Send response back to the AJAX call
        redirect('ratings-list');
    }
}
