<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CallModuleController extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('AdminConsoleModel');
        $this->load->model('Role_Model');
        $this->load->model('Permission_Model');         
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('email');
        
    }

    public function callIBMApi() {
        // IBM API credentials
        $apiUrl = "https://api.youribmservice.com/resource";
        $apiKey = "YOUR_API_KEY";
        
        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $apiKey,
            'Accept: application/json',
        ]);

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for errors
        if(curl_errno($ch)) {
            $error_msg = curl_error($ch);
            // Handle the error
            log_message('error', 'cURL error: ' . $error_msg);
            show_error('An error occurred while connecting to the API.');
        } else {
            // Process the API response
            $data = json_decode($response, true);
            // Pass the data to your view or handle it as needed
            $this->load->view('your_view', ['data' => $data]);
        }

        // Close cURL session
        curl_close($ch);
    }


    public function sendEmail(){
        $this->email->set_newline("\r\n");
        $this->email->from('ankitsharma@letslearneasy.com', 'FranHive');
        $this->email->to('yesiamankitsharma@gmail.com'); // Recipient's email address
        $this->email->subject('Testing');
        $this->email->message('This is For Testing Sample Email');

        if ($this->email->send()) {
            echo 'Email sent successfully.';
        } else {
            show_error($this->email->print_debugger());
        }

    }

  

}
