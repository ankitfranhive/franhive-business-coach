`<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserController extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('AdminConsoleModel');
        $this->load->model('User_Model');
        $this->load->model('role_model');
        $this->load->model('Permission_Model');
        $this->load->library('session');
    }



    public function campaignDashboard()
    {

        $data['lead_campaign_history'] = $this->User_Model->get_lead_campaign_history();
        $data['client_campaign_history'] = $this->User_Model->get_client_campaign_history();

        $data['lead_campaign_schduled'] = $this->User_Model->get_lead_campaign_schduled();
        $data['client_campaign_schduled'] = $this->User_Model->get_client_campaign_schduled();

        $this->load->view('campaign/campaign_dashboard');
    }


    public function addCampaign()
    {
        $this->load->view('campaign/add_campaign');
    }

    public function getAllCampaign()
    {

        $data['all_campaign'] = $this->User_Model->get_all_campaigns();
        // echo "<pre>";
        // print_r($data['all_course']);die;

        $this->load->view('campaign/campaign_list', $data);
    }


    public function create_campaign()
    {
        // Retrieve form data using $this->input->post('field_name');
        $title = $this->input->post('title');
        $manager_name = $this->input->post('manager_name');
        $reply_address = $this->input->post('reply_address');
        $module_name = $this->input->post('module_name');

        // Save the data to the database using your model
        $data = array(
            'title' => $title,
            'manager_name' => $manager_name,
            'reply_address' => $reply_address,
            'module_name' => $module_name
        );

        $this->User_Model->create_new_campaign($data);

        redirect('/campaign-listing');
    }


    public function editCampaign($campaign_id)
    {
        // Load the campaign data based on the $course_id
        $campaign = $this->User_Model->get_campaign_by_id($campaign_id);

        // Pass the campaign data to your view for editing
        // echo "<pre>";
        // print_r($campaign);die;
        $data['campaign_data'] = $campaign;
        $this->load->view('campaign/edit_campaign', $data);
    }


    public function updateCampaign()
    {


        // Retrieve form data 
        $CAMPAIGN_ID = $this->input->post('CAMPAIGN_ID');
        $TITLE = $this->input->post('TITLE');
        $MANAGER_NAME = $this->input->post('MANAGER_NAME');
        $MODULE_NAME = $this->input->post('MODULE_NAME');

        // Save the updated data to the database using your model
        $data = array(
            'TITLE' => $TITLE,
            'MANAGER_NAME' => $MANAGER_NAME,
            'MODULE_NAME' => $MODULE_NAME

        );

        $this->User_Model->update_campaign($CAMPAIGN_ID, $data);



        redirect('campaign-listing');
    }



    public function getAllUsers()
    {
        $data['all_users'] = $this->User_Model->get_all_users();
    
        // If you still want to debug, log it instead of die():
        // log_message('debug', print_r($data['all_users'], true));
    
        $this->load->view('users/users_list', $data);
    }
    

    public function viewUser($user_id)
    {
        // Load the template data based on the $course_id
        $data['user_data'] = $this->User_Model->find($user_id);
        $data['roles'] = $this->role_model->get_all_roles([]);
        $data['permissions'] = $this->Permission_Model->get_all_permissions();
        $data['user_courses'] = $this->User_Model->get_user_courses($user_id);
        // echo "<pre>";
        // print_r($data);
        // die;
        $this->load->view('users/view_user', $data);
    }

    public function addUser()
    {
        if ($this->input->post()) {
            $data = $this->input->post();


            // Create the uploads directory if it doesn't exist
            $upload_path = './uploads/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            $user_image = "";
            $enrollment_doc = "";
            $payment_agreement = "";
            // Check if a file was selected

            // Check if a file was selected
            if (!empty($_FILES['PROFILE_PICTURE']['name'])) {
                // File upload configuration
                $config['upload_path'] = $upload_path; // Specify the upload directory
                $config['allowed_types'] = 'gif|jpg|png|jpeg'; // Specify allowed file types
                $config['max_size'] = 1024; // Specify max file size in KB
                $config['file_name'] = time() . '_' . $_FILES['PROFILE_PICTURE']['name']; // Optional: rename the file

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('PROFILE_PICTURE')) {
                    // Handle upload failure
                    $error = $this->upload->display_errors();
                    var_dump($error);
                    die;
                } else {
                    // Upload successful, save the file URL to the database
                    $upload_data = $this->upload->data();
                    $user_image = base_url() . 'uploads/' . $upload_data['file_name'];
                }
            }

            if (!empty($_FILES['ENROLLMENT_AGREEMENT']['name'])) {
                // File upload configuration
                $config['upload_path'] = $upload_path; // Specify the upload directory
                $config['allowed_types'] = 'gif|jpg|png|jpeg'; // Specify allowed file types
                $config['max_size'] = 1024; // Specify max file size in KB
                $config['file_name'] = time() . '_' . $_FILES['ENROLLMENT_AGREEMENT']['name']; // Optional: rename the file

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('ENROLLMENT_AGREEMENT')) {
                    // Handle upload failure
                    $error = $this->upload->display_errors();
                    var_dump($error);
                    die;
                } else {
                    // Upload successful, save the file URL to the database
                    $upload_data = $this->upload->data();
                    $enrollment_doc = base_url() . 'uploads/' . $upload_data['file_name'];
                }
            }

            if (!empty($_FILES['PAYMENT_AGREEMENT']['name'])) {
                // File upload configuration
                $config['upload_path'] = $upload_path; // Specify the upload directory
                $config['allowed_types'] = 'gif|jpg|png|jpeg'; // Specify allowed file types
                $config['max_size'] = 1024; // Specify max file size in KB
                $config['file_name'] = time() . '_' . $_FILES['PAYMENT_AGREEMENT']['name']; // Optional: rename the file

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('PAYMENT_AGREEMENT')) {
                    // Handle upload failure
                    $error = $this->upload->display_errors();
                    var_dump($error);
                    die;
                } else {
                    // Upload successful, save the file URL to the database
                    $upload_data = $this->upload->data();
                    $payment_agreement = base_url() . 'uploads/' . $upload_data['file_name'];
                }
            }


            $data = array(
                'JOB_TITLE' => $this->input->post('JOB_TITLE'),
                'NAME' => $this->input->post('NAME'),
                'EMAIL' => $this->input->post('EMAIL'),
                'PROFILE_PICTURE' => $user_image,
                'MOBILE' => $this->input->post('MOBILE'),
                'USERS_TYPE_ID' => $this->input->post('USERS_TYPE_ID'),
                'ENROLLMENT_AGREEMENT' => $enrollment_doc,
                'PAYMENT_AGREEMENT' => $payment_agreement,
                // 'CLIENT_TYPE_ID' => $this->input->post('CLIENT'),
                'PERMISSION_ID' => !empty($permission_ids) && is_array($permission_ids)
                ? implode(',', $permission_ids)
                : '',
                // 'DIVISION' => $this->input->post('DIVISION'),
                'ADDRESS_LINE_1' => $this->input->post('ADDRESS_LINE_1'),
                'ADDRESS_LINE_2' => $this->input->post('ADDRESS_LINE_2'),
                'CITY' => $this->input->post('CITY'),
                'ZIP' => $this->input->post('ZIP'),
                'COUNTRY' => $this->input->post('COUNTRY'),
                'STATE' => $this->input->post('USERS_STATE'),
                'NOTE' => $this->input->post('NOTE')

            );

            $this->User_Model->save($data);

            // Redirect to a success page or show a success message
            redirect('users');
        } else {
            $data['roles'] = $this->role_model->get_all_roles([]);
            $data['permissions'] = $this->Permission_Model->get_all_permissions();
            $this->load->view('users/add_user', $data);
        }
    }

    public function save()
    {
        // Retrieve form data using $this->input->post('field_name');
        $NAME = $this->input->post('NAME');
        $EMAIL = $this->input->post('EMAIL');
        $ROLE = $this->input->post('role');

        $this->User_Model->save($this->input);

        redirect('users');
    }

    public function editUser($user_id)
    {
        // Load the template data based on the $course_id
        $data['user_data'] = $this->User_Model->find($user_id);
        $data['roles'] = $this->role_model->get_all_roles([]);
        $data['permissions'] = $this->Permission_Model->get_all_permissions();
        // echo "<pre>";
        // print_r($data);
        // die;
        $this->load->view('users/edit_user', $data);
    }

    public function updateUser()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            // Create the uploads directory if it doesn't exist
            $upload_path = './uploads/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            $user_image = "";
            $enrollment_doc = "";
            $payment_agreement = "";
            // Check if a file was selected

            // Check if a file was selected
            if (!empty($_FILES['PROFILE_PICTURE']['name'])) {
                // File upload configuration
                $config['upload_path'] = $upload_path; // Specify the upload directory
                $config['allowed_types'] = 'gif|jpg|png|jpeg'; // Specify allowed file types
                $config['max_size'] = 5120; // Specify max file size in KB
                $config['file_name'] = time() . '_' . $_FILES['PROFILE_PICTURE']['name']; // Optional: rename the file

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('PROFILE_PICTURE')) {
                    // Handle upload failure
                    $this->session->set_flashdata('error', 'PROFILE PICTURE: ' . $this->upload->display_errors());
                    redirect('UserController/editUser/' . $this->input->post('id'));
                    return;
                } else {
                    // Upload successful, save the file URL to the database
                    $upload_data = $this->upload->data();
                    $user_image = base_url() . 'uploads/' . $upload_data['file_name'];
                }
            }

            if (!empty($_FILES['ENROLLMENT_AGREEMENT']['name'])) {
                // File upload configuration
                $config['upload_path'] = $upload_path; // Specify the upload directory
                $config['allowed_types'] = 'gif|jpg|png|jpeg'; // Specify allowed file types
                $config['max_size'] = 5120; // Specify max file size in KB
                $config['file_name'] = time() . '_' . $_FILES['ENROLLMENT_AGREEMENT']['name']; // Optional: rename the file

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('ENROLLMENT_AGREEMENT')) {
                    // Handle upload failure
                    $this->session->set_flashdata('error',  'ENROLLMENT AGREEMENT: ' . $this->upload->display_errors());
                    redirect('UserController/editUser/' . $this->input->post('id'));
                    return;
                } else {
                    // Upload successful, save the file URL to the database
                    $upload_data = $this->upload->data();
                    $enrollment_doc = base_url() . 'uploads/' . $upload_data['file_name'];
                }
            }

            if (!empty($_FILES['PAYMENT_AGREEMENT']['name'])) {
                // File upload configuration
                $config['upload_path'] = $upload_path; // Specify the upload directory
                $config['allowed_types'] = 'gif|jpg|png|jpeg'; // Specify allowed file types
                $config['max_size'] = 5120; // Specify max file size in KB
                $config['file_name'] = time() . '_' . $_FILES['PAYMENT_AGREEMENT']['name']; // Optional: rename the file

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('PAYMENT_AGREEMENT')) {
                    // Handle upload failure
                    $this->session->set_flashdata('error', 'PAYMENT AGREEMENT' . $this->upload->display_errors());
                    redirect('UserController/editUser/' . $this->input->post('id'));
                    return;
                } else {
                    // Upload successful, save the file URL to the database
                    $upload_data = $this->upload->data();
                    $payment_agreement = base_url() . 'uploads/' . $upload_data['file_name'];
                }
            }


            // print_r($user_image);
            // die;

            $permission_ids = $this->input->post('PERMISSION_ID');

            // Ensure $permission_ids is an array before using implode
            if (is_array($permission_ids)) {
                $permission_ids_str = implode(',', $permission_ids);
            } else {
                $permission_ids_str = ''; // or handle it as needed
            }

            $data = array(
                'JOB_TITLE' => $this->input->post('JOB_TITLE'),
                'NAME' => $this->input->post('NAME'),
                'EMAIL' => $this->input->post('EMAIL'),
                'PROFILE_PICTURE' => $user_image,
                'ENROLLMENT_AGREEMENT' => $enrollment_doc,
                'PAYMENT_AGREEMENT' => $payment_agreement,
                // 'CLIENT_TYPE_ID' => $this->input->post('CLIENT'),
                'PASSWORD' => $this->input->post('PASSWORD'),

                'MOBILE' => $this->input->post('MOBILE'),
                'USERS_TYPE_ID' => $this->input->post('USERS_TYPE_ID'),

                'PERMISSION_ID' => $permission_ids_str,
                // 'DIVISION' => $this->input->post('DIVISION'),
                'ADDRESS_LINE_1' => $this->input->post('ADDRESS_LINE_1'),
                'ADDRESS_LINE_2' => $this->input->post('ADDRESS_LINE_2'),
                'CITY' => $this->input->post('CITY'),
                'ZIP' => $this->input->post('ZIP'),
                'COUNTRY' => $this->input->post('COUNTRY'),
                'STATE' => $this->input->post('USERS_STATE'),
                'NOTE' => $this->input->post('NOTE')

            );

            // echo "<pre>";
            // print_r($data);die;
            $this->User_Model->update($this->input->post('id'), $data);

            $this->session->set_flashdata('success', 'User updated successfully.');
            // Redirect to a success page or show a success message
            redirect('users');
        } else {
            redirect('users');
        }
    }

    public function deleteUser($id)
    {
        $this->User_Model->deleteUser($id);
        redirect('users');
    }

    public function getAllPermissons()
    {

        $data['all_permission'] = $this->User_Model->get_all_permission();
        // echo "<pre>";
        // print_r($data['all_permission']);die;

        $this->load->view('users/permission_list', $data);
    }


    public function addPermission()
    {
        $this->load->view('users/add_permisson');
    }


    public function get_states_by_country_id()
    {
        $country_id = $this->input->post('country_id');
        $states = $this->User_Model->get_states_by_country_id($country_id);
        echo json_encode($states);
    }

    // application/controllers/UserController.php

    public function send_credentials($id) {
        $this->load->model('User_Model');
        $user = $this->User_Model->get_user_by_id($id);
    
        if (!$user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('userController/user_list');
        }
    
        $to = $user['EMAIL'];
        $password = $user['PASSWORD'];
        $username = $user['NAME'];
    
        $subject = "Your Login Credentials - Empower Your Destiny";
    
        $message = "
            <p>Dear <strong>{$username}</strong>,</p>
            <p>Your login credentials are as follows:</p>
            <ul>
                <li><strong>Email:</strong> {$to}</li>
                <li><strong>Password:</strong> {$password}</li>
            </ul>
            <p>Best regards,</p>
            <p>Empower Your Destiny Team</p>
        ";
    
        $this->load->library('email');
    
        // SMTP config (if not already globally set)
        // $config = [
        //     'protocol' => 'smtp',
        //     'smtp_host' => 'smtp.yourdomain.com',  // Update this
        //     'smtp_port' => 25,                    // Or 465 if using SSL
        //     'smtp_user' => 'NLP@empoweryourdestiny.com.au',
        //     'smtp_pass' => 'your_password',        // Update this
        //     'mailtype'  => 'html',
        //     'charset'   => 'utf-8',
        //     'newline'   => "\r\n",
        //     'smtp_crypto' => 'tls',
        // ];

     
            $config = [
                'protocol'    => 'smtp',
                'smtp_host'   => 'smtp.hostinger.com',
                'smtp_user'   => 'nlp@empoweryourdestiny.com.au',
                'smtp_pass'   => 'Franh1ve@2024',
                'smtp_port'   => 465,
                'smtp_crypto' => 'ssl',
                'mailtype'    => 'html',
                'charset'     => 'utf-8',
                'newline'     => "\r\n",
            ];
    
        $this->email->initialize($config);
    
        $this->email->from('NLP@empoweryourdestiny.com.au', 'Empower Your Destiny');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
    
        if ($this->email->send()) {
            $this->session->set_flashdata('success', 'Credentials sent successfully to ' . $to);
        } else {
            $this->session->set_flashdata('error', 'Failed to send email. ' . $this->email->print_debugger());
        }
    
        redirect('users');
    }
    
    


}
