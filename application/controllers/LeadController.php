<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LeadController extends CI_Controller
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
        $this->load->model('KnowledgeCenterModel');
        $this->load->model('LeadModel');
        $this->load->model('LeadTaskModel');
        $this->load->model('User_Model');
        $this->load->model('Template_Model');
        $this->load->model('BusinessTypeModel');
        $this->load->library('email');
    }

    public function leadDashboard()
    {
        // echo "jejrjej";die;

        $data['recently_leads'] = $this->LeadModel->get_recent_leads();
        $data['recently_task'] = $this->LeadModel->get_recent_tasks();
        // echo "<pre>";
        // print_r($data);die;
        $this->load->view('leads/lead_dashboard', $data);
    }

    public function getAllLeads()
    {

        $data['all_leads'] = $this->LeadModel->get_all_leads();
        $this->load->view('leads/leads_list', $data);
    }

    public function addLead()
    {
        $data['all_users'] = $this->User_Model->get_all_users();
        $data['all_countries'] = $this->User_Model->get_all_countries();
        $data['all_business_type'] = $this->BusinessTypeModel->getAllBusinessType();
        $this->load->view('leads/add_lead', $data);
    }


    public function createNewLead()
{
    if (!$this->input->post()) { redirect('leads'); }

    // 1) Validate required fields (server-side)
    $this->load->library('form_validation');
    $this->form_validation->set_rules('NAME',  'Name',  'trim|required');
    $this->form_validation->set_rules('EMAIL', 'Email', 'trim|required|valid_email');
    $this->form_validation->set_rules('PHONE', 'Phone', 'trim|required|min_length[7]|max_length[20]');

    if ($this->form_validation->run() === FALSE) {
        $this->session->set_flashdata('error', validation_errors(' ', ' '));
        redirect('leads'); // or reload the add form route if different
    }

    // Helper to trim, XSS clean, and convert empty/-1 to NULL
    $get = function($key) {
        $v = $this->input->post($key, TRUE); // TRUE = XSS filter
        if (is_string($v)) { $v = trim($v); }
        return ($v === '' || $v === '-1') ? NULL : $v;
    };

    // 2) Build payload (optional fields allowed to be NULL)
    $data = array(
        'TITLE'                    => $get('TITLE'),
        'NAME'                     => $get('NAME'),   // required by validation
        'EMAIL'                    => $get('EMAIL'),  // required by validation
        'PHONE'                    => $get('PHONE'),  // required by validation
        'BUISNESS'                 => $get('BUISNESS'),
        'PREF_MODE_CONTACT'        => $get('PREF_MODE_CONTACT'),
        'BEST_TIME_CONTACT'        => $get('BEST_TIME_CONTACT'),
        'HOME_PHONE'               => $get('HOME_PHONE'),
        'ADDRESS_LINE_1'           => $get('ADDRESS_LINE_1'),
        'ADDRESS_LINE_2'           => $get('ADDRESS_LINE_2'),
        'CITY'                     => $get('CITY'),
        'ZIP'                      => $get('ZIP'),
        'COUNTRY'                  => $get('COUNTRY'),
        'STATE'                    => $get('STATE'),
        'COMMENTS'                 => $get('COMMENTS'),
        'LEAD_STATUS'              => $get('LEAD_STATUS'),
        'LEAD_OWNER'               => $get('LEAD_OWNER'),
        'LEAD_SOURCE'              => $get('LEAD_SOURCE'),
        'LEAD_SOURCE_DETAIL'       => $get('LEAD_SOURCE_DETAIL'),
        'CURRENT_NET_WORTH'        => $get('CURRENT_NET_WORTH'),
        'CASH_AVAILABLE_INVESTMENT'=> $get('CASH_AVAILABLE_INVESTMENT'),
        'INVESTMENT_TIMEFRAME'     => $get('INVESTMENT_TIMEFRAME'),
        'BACKGROUND'               => $get('BACKGROUND'),
        'SOURCE_OF_INVESTMENT'     => $get('SOURCE_OF_INVESTMENT'),
        'NEXT_CALL_DATE'           => $get('NEXT_CALL_DATE'),
        'IS_LEAD'                  => 'Y',
        // 'created_at'             => date('Y-m-d H:i:s'), // optional
    );

    // 3) Insert
    $lead_id = $this->LeadModel->insertLead($data);

    if ($lead_id) {
        $this->session->set_flashdata('success', 'Lead created successfully.');
    } else {
        $this->session->set_flashdata('error', 'Failed to create lead.');
    }

    redirect('leads');
}



    public function editLead($id)
    {

        $data['all_users'] = $this->User_Model->get_all_users();
        $data['all_countries'] = $this->User_Model->get_all_countries();
        $data['lead'] = $this->LeadModel->getLeadById($id);
        $data['all_business_type'] = $this->BusinessTypeModel->getAllBusinessType();
        // echo "<pre>";
        // print_r($data['lead']);die;
        $this->load->view('leads/edit_lead_view', $data);
    }

    public function viewLead($id)
    {

        $data['all_users'] = $this->User_Model->get_all_users();
        $data['all_countries'] = $this->User_Model->get_all_countries();
        $data['lead'] = $this->LeadModel->getLeadById($id);
        // echo "<pre>";
        // print_r($data['lead']);die;
        $this->load->view('leads/view_lead', $data);
    }

    public function empAgreenentFrom($id)
    {

        $data['all_users'] = $this->User_Model->get_all_users();
        $data['all_countries'] = $this->User_Model->get_all_countries();
        $data['lead'] = $this->LeadModel->getLeadById($id);
        // echo "<pre>";
        // print_r($data['lead']);die;
        $this->load->view('leads/emp_agreement_view', $data);
    }


    public function updateLead()
    {
        $id = $this->input->post('id');
        $data = array(
            'TITLE' => $this->input->post('TITLE'),
            'NAME' => $this->input->post('NAME'),
            'EMAIL' => $this->input->post('EMAIL'),
            'PHONE' => $this->input->post('PHONE'),
            'BUISNESS' => $this->input->post('BUISNESS'),
            'PREF_MODE_CONTACT' => $this->input->post('PREF_MODE_CONTACT'),
            'BEST_TIME_CONTACT' => $this->input->post('BEST_TIME_CONTACT'),
            'HOME_PHONE' => $this->input->post('HOME_PHONE'),
            'ADDRESS_LINE_1' => $this->input->post('ADDRESS_LINE_1'),
            'ADDRESS_LINE_2' => $this->input->post('ADDRESS_LINE_2'),
            'CITY' => $this->input->post('CITY'),
            'ZIP' => $this->input->post('ZIP'),
            'COUNTRY' => $this->input->post('COUNTRY'),
            'STATE' => $this->input->post('STATE'),
            'COMMENTS' => $this->input->post('COMMENTS'),
            'LEAD_STATUS' => $this->input->post('LEAD_STATUS'),
            'LEAD_OWNER' => $this->input->post('LEAD_OWNER'),
            'LEAD_SOURCE' => $this->input->post('LEAD_SOURCE'),
            'LEAD_SOURCE_DETAIL' => $this->input->post('LEAD_SOURCE_DETAIL'),
            'CURRENT_NET_WORTH' => $this->input->post('CURRENT_NET_WORTH'),
            'CASH_AVAILABLE_INVESTMENT' => $this->input->post('CASH_AVAILABLE_INVESTMENT'),
            'INVESTMENT_TIMEFRAME' => $this->input->post('INVESTMENT_TIMEFRAME'),
            'BACKGROUND' => $this->input->post('BACKGROUND'),
            'SOURCE_OF_INVESTMENT' => $this->input->post('SOURCE_OF_INVESTMENT'),
            // 'NEXT_CALL_DATE' => $this->input->post('NEXT_CALL_DATE'),
            'MODIFIED_ON' => date('Y-m-d H:i:s'),
            'MODIFIED_BY' => $this->session->userdata('user')['USER_ID']
        );

        if($this->LeadModel->updateLead($id, $data)){
            $this->session->set_flashdata('success', 'Lead updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to update lead.');
        }
        // Redirect to a success page or show a success message
        redirect('leads');
    }

    public function moveLeadToClient($id)
    {
        $lead = $this->LeadModel->getLeadById($id);
        if($lead){
            if($this->LeadModel->updateLeadToClient($id)){
                $this->session->set_flashdata('success', 'Lead moved to client successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to move lead to client.');
            }
        }else{
            $this->session->set_flashdata('error', 'Lead not found.');
        }
        redirect('clients');
    }

    public function addTask($id)
    {
        $data['all_users'] = $this->User_Model->get_entity_by_type('N');
        $data['ENTITY_DATA'] = $this->LeadModel->getLeadById($id);
        $data['ENTITY_ID'] = $id;
        $this->load->view('leads/add_task', $data);
    }

    public function addFreshTask()
    {
        $data['all_users'] = $this->User_Model->get_entity_by_type('N');
        $data['ENTITY_DATA'] = [];
        $data['ENTITY_ID'] = 0;
        $this->load->view('leads/add_task', $data);
    }


    public function createTask()
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
                'RECORD_STATUS' =>  1,
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

            $this->LeadTaskModel->add_task_lead($task_data);
            redirect('tasks');
        }
    }

    public function editTask($id)
    {
        $data['all_users'] = $this->User_Model->get_entity_by_type('N');

        $data['task_data'] = $this->LeadTaskModel->get_lead_task_by_id($id);

        $userid = $this->session->userdata('user')['USER_ID'];
        $user_role = $this->User_Model->getUserRoles($userid);
        $data['user_role'] = $user_role['role_name'];

        // echos
        $this->load->view('leads/edit_task', $data);
    }

    public function updateTask()
    {
        // Get the current user's ID from session
        if ($this->input->post()) {
            $user_id = $this->session->userdata('user')['USER_ID'];
            $data = $this->input->post();


            // $id = $this->input->post('id');
            // print_r($id);
            // print_r($data);
            // die;
            // Prepare the data to be inserted
            $task_data = array(
                'MODIFIED_BY' => $user_id,
                'NODE_ID' => isset($data['NODE_ID']) ? $data['NODE_ID'] : null,
                'MODIFIED_ON' => date('Y-m-d H:i:s'),
                'LAST_VIEWED_ON' => null,
                'STATUS' => $data['STATUS'],
                'TASK_DESC' => $data['TASK_DESC']
            );

            $this->LeadTaskModel->update_lead_task($data['TASK_ID'], $task_data);
            redirect('tasks');
        }
    }

    public function changeStatus($id)
    {
        if ($this->input->post()) {
            $user_id = $this->session->userdata('user')['USER_ID'];

            // $id = $this->input->post('id');
            // print_r($id);
            // print_r($data);
            // die;
            // Prepare the data to be inserted
            $data = array(
                'LEAD_STATUS' => $this->input->post('LEAD_STATUS'),
                'MODIFIED_BY' => $user_id,
                'MODIFIED_ON' => date('Y-m-d H:i:s') // Set the current date and time
            );

            $this->LeadModel->updateLead($id, $data);
            redirect('leads');
        } else {
            $data['ENTITY_DATA'] = $this->LeadModel->getLeadById($id);
            $this->load->view('leads/edit_task_status', $data);
        }
    }
    public function delete_task($id)
    {
        $this->LeadTaskModel->delete_task($id);
        redirect('tasks');
    }

    public function deleteLead($id)
    {
        $this->LeadModel->deleteLead($id);
        redirect('leads');
    }

    public function getAllTasks()
    {
        // echo "here";die;
      
        // Attempt to retrieve the user ID from the session
        $userid = $this->session->userdata('user')['USER_ID'];

        // echo $userid;die;
        if (!$userid) {
            // Handle missing user ID, e.g., redirect to login page
            show_error('User not logged in. Please log in.', 401, 'Unauthorized');
            redirect('login');
            return;
        }

        // Initialize data array
        $data = array();
        $data['all_task'] = [];
        $data['user_role'] = [];

        // Attempt to retrieve the user's role
        $user_role = $this->User_Model->getUserRoles($userid);
        // PRINT_R ($user_role);die;
        if ($user_role) {
            // If user role is found, attempt to retrieve all tasks for the user
            $all_task = $this->LeadTaskModel->get_task_list($userid, $user_role['role_name']);
            $data['all_task'] = $all_task;
            $data['user_role'] = $user_role;
        }

        // echo "<pre>";
        // print_r($data);die;
        // Load the view with the data
        $this->load->view('leads/task_list', $data);
    }


    public function send_email_template($user_email, $subject, $template_string, $lead_email, $template_data = [])
    {
        // echo $template_string;die;
        // Replace placeholders with actual values in the subject
        foreach ($template_data as $key => $value) {
            $subject = str_replace('$' . strtoupper($key) . '$', $value, $subject);
            $template_string = str_replace('$' . strtoupper($key) . '$', $value, $template_string);
        }
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'smtp.hostinger.com';
        $config['smtp_user'] = 'nlp@empoweryourdestiny.com.au';
        $config['smtp_pass'] = 'Franh1ve@2024';
        $config['smtp_port'] = 587;
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
    
        $this->email->initialize($config);
    
        $this->email->set_newline("\r\n");
        $this->email->from($user_email, $subject);
        $this->email->to($lead_email); // Recipient's email address
        $this->email->subject($subject);
        $this->email->message($template_string);
    
        if ($this->email->send()) {
            return true;
        } else {
            show_error($this->email->print_debugger());
        }
    }
    


    public function sendLeadEmail($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $template_result = $this->Template_Model->get_template($data['EMAIL_TEMPLATE']);
            
            // Construct template data
            $template_data = [
                'name' => $data['LEAD_NAME'],
                'email_id' => $data['LEAD_EMAIL'],
                'user_name' => EMAIL_FROM,
                'unsubscribe_link' => 'https://www.franhive.com/eyd/unsubscribe'
            ];
    
            // Signature image and text (image on top)
            $signature_image_url = 'https://eyd.franhive.com/uploads/headshot4.png'; // Final hardcoded image
            $signature_content = $template_result['TEMPLATE_SIGN'];
    
            $signature_html = '<br><br><div style="display:inline-block;">';
            $signature_html .= '<img src="' . $signature_image_url . '" style="max-width:200px; max-height:100px; display:block;">';
            if (!empty($signature_content)) {
                $signature_html .= '<div style="margin-top:2px;">' . nl2br($signature_content) . '</div>';
            }
            $signature_html .= '</div>';
    
            // Combine email body with the updated signature
            $template_body_with_signature = $template_result['TEMPLATE_BODY'] . $signature_html;
    
            // Send the email
            if ($this->send_email_template(EMAIL_CONFIG_EMAIL, $template_result['TEMPLATE_SUBJECT'], $template_body_with_signature, $data['LEAD_EMAIL'], $template_data)) {
                $this->session->set_flashdata('success', 'Email sent successfully!');
                redirect('leads');
            } else {
                echo "Failed to send email.";
            }
        } else {
            $data['user'] = $this->LeadModel->getLeadById($id);
            $data['templates'] = $this->Template_Model->get_all_templates();
    
            $this->load->view('leads/send_email', $data);
        }
    }
    




//     public function sendLeadEmail($id = '')
// {
//     if ($this->input->post()) {
//         $data = $this->input->post();
//         $template_result = $this->Template_Model->get_template($data['EMAIL_TEMPLATE']);
        
//         // Construct template data
//         $template_data = [
//             'name' => $data['LEAD_NAME'],
//             'email_id' => $data['LEAD_EMAIL'],
//             'user_name' => EMAIL_FROM,
//             'unsubscribe_link' => 'https://www.franhive.com/eyd/unsubscribe'
//         ];

//         // Add an image and signature content side by side
//         $signature_image_url = 'https://eyd.franhive.com/uploads/rsz_1img_6729-removebg-preview.png'; // Replace with the actual URL
//         $signature_content = $template_result['TEMPLATE_SIGN']; // Signature text content
//         $signature_html = '
//             <br><br>
//             <table style="width:100%; border:none;">
//                 <tr>
//                     <td style="width:20%; vertical-align:top; text-align:left;">
//                         <img src="' . $signature_image_url . '" alt="Signature Image" style="width:100px; height:auto; border-radius:5px;">
//                     </td>
//                     <td style="width:80%; vertical-align:top; text-align:left; padding-left:2px;">
//                         ' . $signature_content . '
//                     </td>
//                 </tr>
//             </table>';

//         // Combine template body with the signature
//         $template_body_with_signature = $template_result['TEMPLATE_BODY'] . $signature_html;

//         // Send the email
//         if ($this->send_email_template(EMAIL_CONFIG_EMAIL, $template_result['TEMPLATE_SUBJECT'], $template_body_with_signature, $data['LEAD_EMAIL'], $template_data)) {
//             $this->session->set_flashdata('success', 'Email sent successfully!');
//             redirect('leads');
//         } else {
//             echo "Failed to send email.";
//         }
//     } else {
//         $data['user'] = $this->LeadModel->getLeadById($id);
//         $data['templates'] = $this->Template_Model->get_all_templates();

//         $this->load->view('leads/send_email', $data);
//     }
// }

    


    public function addQuestion()
    {
        $this->load->view('add_question');
    }

    public function editQuestion()
    {
        $question_id = $this->uri->segment(2);

        $data['questionData'] = $this->AdminConsoleModel->get_question_details($question_id);
        // echo "<pre>";
        // print_r( $questions_details);die;
        $this->load->view('edit_question_page', $data);
    }


    public function update_question()
    {

        // Check if the form is submitted
        if ($this->input->post()) {
            // Get form data
            $question_id = $this->input->post('question_id');
            $question_name = $this->input->post('question_name');
            $question_marks = $this->input->post('question_marks');
            $question_type = $this->input->post('question_type');

            // Validate and update the question
            $data = array(
                'QUESTION_NAME' => $question_name,
                'QUESTION_MARKS' => $question_marks,
                'QUESTION_TYPE' => $question_type
                // Add other fields as needed
            );

            $this->AdminConsoleModel->update_question($question_id, $data);

            // Redirect to the question listing page or show a success message
            redirect('questions-listing'); // Change this URL as needed
        } else {
            // If the form is not submitted, you may want to handle it accordingly
            // For example, load the form view or redirect to the form page
            redirect('edit-question/' . $question_id); // Change this URL as needed
        }
    }


    public function delete_question($question_id)
    {

        // Check if the question ID is provided
        if ($question_id) {
            // Call the model function to delete the question
            $this->AdminConsoleModel->delete_question($question_id);

            // Redirect to the question listing page or show a success message
            redirect('questions-listing'); // Change this URL as needed
        } else {
            // If the question ID is not provided, you may want to handle it accordingly
            // For example, redirect to the question listing page with an error message
            redirect('questions-listing'); // Change this URL as needed
        }
    }

    public function save_question()
    {
        // Retrieve form data using $this->input->post('field_name');
        $question_name = $this->input->post('question_name');
        $total_marks = $this->input->post('question_marks');
        $question_type = $this->input->post('question_type');

        // Save the data to the database using your model
        $data = array(
            'QUESTION_NAME' => $question_name,
            'QUESTION_MARKS' => $total_marks,
            'QUESTION_TYPE' => $question_type
        );

        $this->AdminConsoleModel->save_question($data);

        redirect('questions-listing');
    }



    public function getAllTests()
    {

        $data['tests'] = $this->AdminConsoleModel->get_tests();

        $this->load->view('test_listing', $data);
    }


    public function getAllTestsReports()
    {

        $data['tests'] = $this->AdminConsoleModel->get_tests_reports();

        // echo "<pre>";
        // print_r(  $data['tests'] );die;

        $this->load->view('test_reports_listings', $data);
    }


    public function getAllTestsAllocation()
    {
        $user_id = $this->uri->segment(2);
        $data['tests'] = $this->AdminConsoleModel->get_userassigned_tests($user_id);
        // $data['assigned_tests'] = $this->AdminConsoleModel->get_tests();

        // echo "pre";
        // print_r(  $data['tests'] );die;

        $this->load->view('test_allocation_listing', $data);
    }

    public function addTest()
    {
        $this->load->view('add_test');
    }

    public function editTest()
    {
        $test_id = $this->uri->segment(2);

        $data['testData'] = $this->AdminConsoleModel->get_test_details($test_id);
        // echo "<pre>";
        // print_r(   $data['testData']);die;

        $this->load->view('edit_test_page', $data);
    }


    public function save_test()
    {
        // Retrieve form data using $this->input->post('field_name');
        $test_name = $this->input->post('test_title');
        $total_marks = $this->input->post('total_marks');
        $total_questions = $this->input->post('total_questions');
        $question_type = $this->input->post('question_type');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');



        // Save the data to the database using your model
        $data = array(
            'TEST_NAME' => $test_name,
            'TOTAL_QUESTIONS' => $total_questions,
            'TOTAL_MARKS' => $total_marks,
            'TEST_START_DATE' => $start_date,
            'END_DATE' => $end_date
        );

        $this->AdminConsoleModel->save_test($data);

        redirect('test');
    }

    public function update_test()
    {
        // Retrieve form data using $this->input->post('field_name');
        $test_name = $this->input->post('test_title');
        $total_marks = $this->input->post('total_marks');
        $total_questions = $this->input->post('total_questions');
        $question_type = $this->input->post('question_type');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');



        // Save the data to the database using your model
        $data = array(
            'TEST_NAME' => $test_name,
            'TOTAL_QUESTIONS' => $total_questions,
            'TOTAL_MARKS' => $total_marks,
            'TEST_START_DATE' => $start_date,
            'END_DATE' => $end_date
        );
        $test_id =  $this->input->post('test_id');
        $this->AdminConsoleModel->update_test_details($data, $test_id);

        redirect('test');
    }

    public function questionsList()
    {

        $data['questions'] = $this->AdminConsoleModel->get_questions();

        $this->load->view('add-test-question', $data);
    }



    public function addQuestionTest()
    {

        $question_id = $this->input->post('question_id');
        $test_id = $this->input->post('test_id');

        // Save the data to the database using your model
        $data = array(
            'QUESTION_ID' => $question_id,
            'TEST_ID' => $test_id,
        );

        $add_question = $this->AdminConsoleModel->save_test_questing_mapping($data);

        if ($add_question) {
            echo "Added Question in test";
        }

        // redirect('test');
    }


    public function testAllocation()
    {

        $data['users'] = $this->AdminConsoleModel->get_all_users();
        // echo "<pre>";
        // print_r(  $data['users']);die;

        $this->load->view('test_user_allocation', $data);
    }

    public function testEval()
    {

        $test_id =  $this->uri->segment(2);

        $data['user_response'] = $this->AdminConsoleModel->getTestResponses($test_id);
        // echo "<pre>";
        // print_r(  $data['user_response'] );die;
        $this->load->view('test_eval', $data);
    }

    public function saveUserTestEval()
    {
        $data['MARKS_DATA'] = json_encode($this->input->post('marksData'));
        $data['MARKS_OBTAINED'] = $this->input->post('totalMarks');
        $data['TOTAL_MARKS']  = $this->input->post('totalTestMarks');
        $data['ID']  = $this->input->post('response_id');
        $data['IS_EVAL']  = 1;
        // $data['USER_ID']  = $this->input->post('userId');
        // $data['TEST_ID'] = $this->input->post('testId');

        // print_r($data);die;

        $save_test_eval_data = $this->AdminConsoleModel->update_user_test_eval($data);

        if ($save_test_eval_data) {
            echo "Done";
        }




        // Process the data as needed, update the database, etc.

        // You can return a response if needed
        // echo json_encode(['success' => true]);
    }


    public function addTestUser()
    {

        $test_id = $this->input->post('test_id');
        $user_id = $this->input->post('user_id');

        // Save the data to the database using your model
        $data = array(
            'TEST_ID' => $test_id,
            'USER_ID' => $user_id,
        );

        //  print_r($data);die;
        $add_test = $this->AdminConsoleModel->save_test_user_mapping($data);

        if ($add_test) {
            echo "Assigned test to user";
        }

        // redirect('test');
    }

    public function removeTestUser()
    {

        $mapping_id = $this->input->post('mapping_id');
        $user_id = $this->input->post('user_id');
        $is_removed = $this->input->post('is_removed');

        // Save the data to the database using your model
        $data = array(
            'MAPPING_ID' => $mapping_id,
            'USER_ID' => $user_id,
        );

        //  print_r($data);die;
        $remove_test = $this->AdminConsoleModel->remove_user_test_mapping($mapping_id, $is_removed);

        if ($remove_test) {
            echo "Removed test to user";
        }

        // redirect('test');
    }

    public function deleteTest()
    {
        // Get the test_id from the POST data
        $test_id = $this->input->post('test_id');

        // Call the model function to update the is_del column
        $result = $this->AdminConsoleModel->delete_test($test_id);

        // Send response back to the AJAX call
        echo json_encode($result);
    }
}
