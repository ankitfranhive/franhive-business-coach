<?php
defined('BASEPATH') or exit('No direct script access allowed');

class WebFormController extends CI_Controller
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
        $this->load->model('WebForm_Model');
        $this->load->model('User_Model');
    }

    public function getAllWebForms()
    {
        $data['all_form_pages'] = $this->WebForm_Model->get_all_webforms();

        $this->load->view('web-forms/list-forms', $data);
    }

    public function viewWebForm($id)
    {
        $data['web_form_data'] = $this->WebForm_Model->get_webFromData($id);
        $this->load->view('web-forms/view-web-form', $data);
    }

    public function createNewWebForm()
    {
        if ($this->input->post()) {
            $upload_path = './uploads/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            $template_file = "";
            // Check if a file was selected
            if (!empty($_FILES['TEMPLETE_FILE']['name'])) {
                // File upload configuration
                $config['upload_path'] = $upload_path; // Specify the upload directory
                $config['allowed_types'] = 'gif|jpg|png'; // Specify allowed file types
                $config['max_size'] = 1024; // Specify max file size in KB

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('TEMPLETE_FILE')) {
                    // Handle upload failure
                    $error = array('error' => $this->upload->display_errors());
                    // var_dump( $error);die;
                    // You can handle the error as per your application's requirements
                } else {
                    // Upload successful, save the file URL to the database
                    $data = array('upload_data' => $this->upload->data());
                    $template_file = base_url() . 'uploads/' . $data['upload_data']['file_name'];

                    // Save the updated data to the database using your model

                }
            }

            $user_id = $this->session->userdata('user')['USER_ID'];

            $data = array(
                'WEB_FORM_FILE' => $template_file,
                'WEB_FORM_NAME' => $this->input->post('WEB_FORM_NAME'),
                'WEB_FORM_TITLE' => $this->input->post('WEB_FORM_TITLE'),
                'CREATED_BY' => $user_id,
                'RECORD_STATUS' =>  1,
                'CREATED_ON' => date('Y-m-d H:i:s'),
            );

            $this->WebForm_Model->insertWebPage($data);

            // Redirect to a success page or show a success message
            redirect('web-forms-list');
        } else {
            $this->load->view('web-forms/add-web-form');
        }
    }


    public function updateWebForm($id)
    {

        if ($this->input->post()) {
            $upload_path = './uploads/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            $template_file = "";
            // Check if a file was selected
            if (!empty($_FILES['TEMPLETE_FILE']['name'])) {
                // File upload configuration
                $config['upload_path'] = $upload_path; // Specify the upload directory
                $config['allowed_types'] = 'gif|jpg|png'; // Specify allowed file types
                $config['max_size'] = 1024; // Specify max file size in KB

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('TEMPLETE_FILE')) {
                    // Handle upload failure
                    $error = array('error' => $this->upload->display_errors());
                    // var_dump( $error);die;
                    // You can handle the error as per your application's requirements
                } else {
                    // Upload successful, save the file URL to the database
                    $data = array('upload_data' => $this->upload->data());
                    $template_file = base_url() . 'uploads/' . $data['upload_data']['file_name'];

                    // Save the updated data to the database using your model

                }
            }

            $data = array(
                'WEB_FORM_FILE' => $template_file,
                'WEB_FORM_NAME' => $this->input->post('WEB_FORM_NAME'),
                'WEB_FORM_TITLE' => $this->input->post('WEB_FORM_TITLE'),
                'MODIFIED_BY' => $user_id,
                'RECORD_STATUS' =>  1,
                'MODIFIED_ON' => date('Y-m-d H:i:s'),
            );

            $this->WebForm_Model->updateWebForm($id, $data);

            redirect('web-forms-list');
        } else {
            $data['web_form_data'] = $this->WebForm_Model->get_webFromData($id);
            $this->load->view('web-forms/edit-web-form', $data);
        }
    }

    public function deleteWebForm($id)
    {
        // Call the model function to update the is_del column
        $result = $this->WebForm_Model->deleteWebForm($id);
        // Send response back to the AJAX call
        redirect('web-forms-list');
    }
}
