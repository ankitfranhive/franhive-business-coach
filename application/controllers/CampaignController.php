<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CampaignController extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('AdminConsoleModel');
        $this->load->model('Campaign_Model');
        $this->load->model('User_Model');
        $this->load->model('Client_Model');
    }



    public function campaignDashboard()
    {

        $data['active_campaign_count'] = $this->Campaign_Model->get_active_campaign_count();
        $data['completed_campaign_count'] = $this->Campaign_Model->get_completed_campaign_count();

        $data['recent_campaigns'] = $this->Campaign_Model->get_campaigns_list(10, 1);
        $data['completed_campaign'] = $this->Campaign_Model->get_campaigns_list(10, 0);

        $this->load->view('campaign/campaign_dashboard', $data);
    }




    public function getAllCampaign()
    {

        $data['all_campaign'] = $this->Campaign_Model->get_all_campaigns();
        // echo "<pre>";
        // print_r($data['all_campaign']);die;

        $this->load->view('campaign/campaign_list', $data);
    }

    public function addCampaign()
    {
        $data['all_templates'] = $this->Campaign_Model->get_all_templates();
        $data['all_users'] = $this->Client_Model->getAllLeadsAndClients();
        // echo "<pre>";
        // print_r($data['all_users'] );
        $this->load->view('campaign/add_campaign', $data);
    }


    public function create_campaign()
    {
        // Retrieving form data
        $TITLE = $this->input->post('TITLE');
        $MANAGER_NAME = $this->input->post('MANAGER_NAME');
        $MODULE_NAME = $this->input->post('MODULE_NAME');
        $REPLY_ADDRESS = $this->input->post('REPLY_ADDRESS');
        $START_DATE = $this->input->post('START_DATE');
        $END_DATE = $this->input->post('END_DATE');

        // Retrieving selected template details
        $selected_template_ids = $this->input->post('TEMPLATE_IDS'); // Array of selected template IDs
        $sending_order = $this->input->post('SENDING_ORDER'); // Array of sending orders
        $send_date = $this->input->post('SEND_DATE'); // Array of send dates
        $template_start_time = $this->input->post('TEMPLATE_START_TIME'); // Array of start times

        // Retrieving selected user IDs
        $selected_user_ids = $this->input->post('CONTACT_IDS'); // Array of selected user IDs

        // Save campaign data
        $data = array(
            'TITLE' => $TITLE,
            'MANAGER_NAME' => $MANAGER_NAME,
            'MODULE_NAME' => $MODULE_NAME,
            'REPLY_ADDRESS' => $REPLY_ADDRESS,
            'START_DATE' => $START_DATE,
            'END_DATE' => $END_DATE,
            'STATUS' => 1,
            'CREATED_ON' => date('Y-m-d H:i:s'),
        );

        // Create new campaign
        $campaign_id = $this->Campaign_Model->create_new_campaign($data);
        // echo '<pre>';
        // print_r($campaign_id);
        // die;
        if ($campaign_id) {
            // Save template mappings
            if (!empty($selected_template_ids)) {
                // echo '<pre>';
                // print_r($selected_template_ids);
                // die;
                $template_data = array();
                foreach ($selected_template_ids as $index => $template_id) {
                    $template_data = array(
                        'CAMPAIGN_ID' => $campaign_id,
                        'TEMPLATE_ID' => $template_id,
                        'SENDING_ORDER' => isset($sending_order[$template_id]) ? $sending_order[$template_id] : null,
                        'SEND_DATE' => isset($send_date[$template_id]) ? $send_date[$template_id] : null,
                        'TEMPLATE_START_TIME' => isset($template_start_time[$template_id]) ? $template_start_time[$template_id] : null,
                        'STATUS' => 1
                    );
                    $this->Campaign_Model->insert_campaign_template($template_data);
                }
            }

            // Save user assignments
            if (!empty($selected_user_ids)) {
                $user_data = array();
                foreach ($selected_user_ids as $user_id) {

                    $user_data = array(
                        'CAMPAIGN_ID' => $campaign_id,
                        'USER_ID' => $user_id,
                        'ASSIGN_DATE' => date('Y-m-d H:i:s'),
                        'STATUS' => 'Active'
                    );
                    $this->Campaign_Model->add_campaign_user($user_data);
                }
            }
        }

        redirect('/campaigns');
    }



    public function viewCampaign($campaign_id)
    {
        // Load the campaign data based on the $course_id
        $campaign = $this->Campaign_Model->get_campaign_by_id($campaign_id);

        // Get all available templates and users
        $data['all_templates'] = $this->Campaign_Model->get_all_templates();
        $data['all_users'] = $this->User_Model->get_all_users();

        // Get the selected templates and users for this campaign
        $selected_templates = $this->Campaign_Model->get_selected_templates($campaign_id);
        $selected_users = $this->Campaign_Model->get_selected_users($campaign_id);
        // print_r($selected_templates);
        // die;
        // Prepare the data for the view
        $data['selected_templates'] = array_column($selected_templates, null, 'TEMPLATE_ID');

        $data['selected_contact_ids'] = array_column($selected_users, 'USER_ID');

        // Pass the campaign data to your view for editing
        $data['campaign_data'] = $campaign;
        $this->load->view('campaign/view_campaign', $data);
    }

    public function deleteCampaign($campaign_id)
    {
        // Load the campaign data based on the $course_id
        $campaign = $this->Campaign_Model->delete_campaign($campaign_id);
        redirect('campaigns');
    }



    public function editCampaign($campaign_id)
    {
        // Load the campaign data based on the $campaign_id
        $campaign = $this->Campaign_Model->get_campaign_by_id($campaign_id);

        // Get all available templates and users
        $data['all_templates'] = $this->Campaign_Model->get_all_templates();
        // $data['all_users'] = $this->User_Model->get_all_users();

        $data['all_users'] = $this->Client_Model->getAllLeadsAndClients();
        // echo "<pre>";
        // print_r($data['all_users']);die;

        // Get the selected templates and users for this campaign
        $selected_templates = $this->Campaign_Model->get_selected_templates($campaign_id);
        $selected_users = $this->Campaign_Model->get_selected_users($campaign_id);
        // print_r($selected_templates);
        // die;
        // Prepare the data for the view
        $data['selected_templates'] = array_column($selected_templates, null, 'TEMPLATE_ID');

        $data['selected_contact_ids'] = array_column($selected_users, 'USER_ID');

        // Pass the campaign data to your view for editing
        $data['campaign_data'] = $campaign;
        // echo "<pre>";
        // print_r($data['campaign_data']);
        // die;
        $this->load->view('campaign/edit_campaign', $data);
    }



    public function updateCampaign()
    {
        // Retrieve form data 
        $CAMPAIGN_ID = $this->input->post('CAMPAIGN_ID');
        $TITLE = $this->input->post('TITLE');
        $MANAGER_NAME = $this->input->post('MANAGER_NAME');
        $MODULE_NAME = $this->input->post('MODULE_NAME');
        $START_DATE = $this->input->post('START_DATE');
        $END_DATE = $this->input->post('END_DATE');
        $STATUS =  $this->input->post('STATUS');

        // echo "<pre>";
        // print_r($this->input->post());die;

        // Update campaign details in the campaign table
        $data = array(
            'TITLE' => $TITLE,
            'MANAGER_NAME' => $MANAGER_NAME,
            'MODULE_NAME' => $MODULE_NAME,
            'START_DATE' => $START_DATE,
            'END_DATE' => $END_DATE,
            'STATUS' => $STATUS,
            'MODIFIED_ON' => date('Y-m-d H:i:s'),
        );

        $this->Campaign_Model->update_campaign($CAMPAIGN_ID, $data);

        // Update the template mapping
        $this->Campaign_Model->delete_campaign_templates($CAMPAIGN_ID); // Assuming you delete and reinsert

        $TEMPLATE_IDS = $this->input->post('TEMPLATE_IDS');
        $selected_contact_ids = $this->input->post('CONTACT_IDS');

        $SENDING_ORDER = $this->input->post('SENDING_ORDER');
        $SEND_DATE = $this->input->post('SEND_DATE');
        $TEMPLATE_START_TIME = $this->input->post('TEMPLATE_START_TIME');

        // print_r($TEMPLATE_START_TIME);
        // die;

        if (!empty($TEMPLATE_IDS)) {
            foreach ($TEMPLATE_IDS as $TEMPLATE_ID) {
                $mapping_data = array(
                    'CAMPAIGN_ID' => $CAMPAIGN_ID,
                    'TEMPLATE_ID' => $TEMPLATE_ID,
                    'SENDING_ORDER' => $SENDING_ORDER[$TEMPLATE_ID],
                    'SEND_DATE' => $SEND_DATE[$TEMPLATE_ID],
                    'TEMPLATE_START_TIME' => $TEMPLATE_START_TIME[$TEMPLATE_ID],
                    'STATUS' => 1, // or another default status
                );
                $this->Campaign_Model->insert_campaign_template($mapping_data);
            }
        }


        // First, delete existing mappings for this campaign
        $this->Campaign_Model->delete_campaign_users($CAMPAIGN_ID);

        // Save the selected users for this campaign
        if (!empty($selected_contact_ids)) {
            foreach ($selected_contact_ids as $user_id) {
                $user_data = array(
                    'CAMPAIGN_ID' => $CAMPAIGN_ID,
                    'USER_ID' => $user_id,
                    'ASSIGN_DATE' => date('Y-m-d H:i:s'),
                    'STATUS' => 'Active'
                );
                $this->Campaign_Model->add_campaign_user($user_data);
            }
        }

        redirect('campaigns');
    }



    public function getAllTemplates()
    {

        $data['all_templates'] = $this->Campaign_Model->get_all_templates();
        // echo "<pre>";
        // print_r($data['all_course']);die;

        $this->load->view('campaign/template_list', $data);
    }


    public function addTemplate()
    {
        $this->load->view('campaign/add_template');
    }



    public function createTemplate()
    {
        // Retrieve form data
        $MODULE_NAME = trim((string)$this->input->post('MODULE_NAME'));
        $TEMPLATE_NAME = $this->input->post('TEMPLATE_NAME');
        $TEMPLATE_SUBJECT = $this->input->post('TEMPLATE_SUBJECT');
        $TEMPLATE_BODY = $this->input->post('TEMPLATE_BODY');
        $TEMPLATE_SIGN = $this->input->post('TEMPLATE_SIGN');

        // Create the uploads directory if it doesn't exist
        $upload_path = './uploads/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        // Initialize attachment array
        $attachment = array();
        $attachment_string = '';

        // Check if files were selected
        if (!empty($_FILES['ATTACHMENTS']['name'][0])) {
            // File upload configuration
            $config['upload_path'] = $upload_path; // Specify the upload directory
            $config['allowed_types'] = 'gif|jpeg|jpg|png|pdf|doc|docx'; // Specify allowed file types
            $config['max_size'] = 5120; // Specify max file size in KB

            // Load the upload library with the configuration
            $this->load->library('upload', $config);

            // Loop through each file
            $files = $_FILES['ATTACHMENTS'];
            $file_count = count($files['name']);

            for ($i = 0; $i < $file_count; $i++) {
                $_FILES['file']['name'] = $files['name'][$i];
                $_FILES['file']['type'] = $files['type'][$i];
                $_FILES['file']['tmp_name'] = $files['tmp_name'][$i];
                $_FILES['file']['error'] = $files['error'][$i];
                $_FILES['file']['size'] = $files['size'][$i];

                // Perform the upload
                if ($this->upload->do_upload('file')) {
                    // Upload successful, save the file path
                    $upload_data = $this->upload->data();
                    $attachment[] = base_url() . 'uploads/' . $upload_data['file_name'];
                } else {
                    // Handle upload failure
                    $error = $this->upload->display_errors();
                    echo $error; // Display error message (optional)
                }
            }

            // Convert the array of file paths to a comma-separated string
            $attachment_string = implode(', ', $attachment);
        }

        // Save the data to the database using your model
        $data = array(
            'MODULE_NAME' => $MODULE_NAME,
            'TEMPLATE_NAME' => $TEMPLATE_NAME,
            'TEMPLATE_SUBJECT' => $TEMPLATE_SUBJECT,
            'TEMPLATE_BODY' => $TEMPLATE_BODY,
            'TEMPLATE_SIGN' => $TEMPLATE_SIGN,
            'ATTACHMENTS' => $attachment_string,
            'RECORD_STATUS' => 0,
        );

        $this->Campaign_Model->create_new_template($data);

        redirect('templates');
    }


    public function viewTemplate($template_id)
    {
        // Load the template data based on the $course_id
        $template = $this->Campaign_Model->get_template_by_id($template_id);

        // echo "<pre>";
        // print_r($template);die;
        $data['template_data'] = $template;
        $this->load->view('campaign/view_template', $data);
    }

    public function editTemplate($template_id)
    {

        // Load the template data based on the $course_id
        $template = $this->Campaign_Model->get_template_by_id($template_id);

        // echo "<pre>";
        // print_r($template);
        // die;
        $data['template_data'] = $template;
        $this->load->view('campaign/edit_template', $data);
    }


    public function updateTemplate()
    {
        // Retrieve form data 
        $TEMPLATE_ID = $this->input->post('TEMPLATE_ID');
        $TEMPLATE_NAME = $this->input->post('TEMPLATE_NAME');
        $MODULE_NAME = trim((string)$this->input->post('MODULE_NAME'));
        $TEMPLATE_SUBJECT = $this->input->post('TEMPLATE_SUBJECT');
        $TEMPLATE_BODY = $this->input->post('TEMPLATE_BODY');
        $TEMPLATE_SIGN = $this->input->post('TEMPLATE_SIGN');

        // Create the uploads directory if it doesn't exist
        $upload_path = './uploads/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        // Load the model to get existing data
        $existing_template = $this->Campaign_Model->get_template_by_id($TEMPLATE_ID);

        // Initialize attachment array
        $attachment = array();
        $attachment_string = '';

        // Check if files were selected
        if (!empty($_FILES['ATTACHMENTS']['name'][0])) {
            // File upload configuration
            $config['upload_path'] = $upload_path; // Specify the upload directory
            $config['allowed_types'] = 'gif|jpeg|jpg|png|pdf|doc|docx'; // Specify allowed file types
            $config['max_size'] = 5120; // Specify max file size in KB

            // Load the upload library with the configuration
            $this->load->library('upload', $config);

            // Loop through each file
            $files = $_FILES['ATTACHMENTS'];
            $file_count = count($files['name']);

            for ($i = 0; $i < $file_count; $i++) {
                $_FILES['file']['name'] = $files['name'][$i];
                $_FILES['file']['type'] = $files['type'][$i];
                $_FILES['file']['tmp_name'] = $files['tmp_name'][$i];
                $_FILES['file']['error'] = $files['error'][$i];
                $_FILES['file']['size'] = $files['size'][$i];

                // Perform the upload
                if ($this->upload->do_upload('file')) {
                    // Upload successful, save the file path
                    $upload_data = $this->upload->data();
                    $attachment[] = base_url() . 'uploads/' . $upload_data['file_name'];
                } else {
                    // Handle upload failure
                    $error = $this->upload->display_errors();
                    log_message('error', 'File upload error: ' . $error);
                    echo $error;  // Display error message (optional)
                }
            }

            // Convert the array of file paths to a comma-separated string
            $attachment_string = implode(', ', $attachment);

            // Remove old files if new files are uploaded
            if (!empty($existing_template['ATTACHMENTS'])) {
                $old_files = explode(', ', $existing_template['ATTACHMENTS']);
                foreach ($old_files as $old_file) {
                    // Remove old files from the filesystem
                    $file_path = str_replace(base_url(), $upload_path, $old_file);
                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                }
            }
        } else {
            // If no new files are uploaded, keep the old files
            $attachment_string = $existing_template['ATTACHMENTS'];
        }

        // Save the updated data to the database using your model
        $data = array(
            'TEMPLATE_NAME' => $TEMPLATE_NAME,
            'MODULE_NAME' => $MODULE_NAME,
            'TEMPLATE_SUBJECT' => $TEMPLATE_SUBJECT,
            'TEMPLATE_BODY' => $TEMPLATE_BODY,
            'TEMPLATE_SIGN' => $TEMPLATE_SIGN,
            'ATTACHMENTS' => $attachment_string,
        );

        $this->Campaign_Model->update_template($TEMPLATE_ID, $data);

        redirect('templates');
    }


    public function deleteTemplate($campaign_id)
    {
        // Load the campaign data based on the $course_id
        $campaign = $this->Campaign_Model->delete_template($campaign_id);
        redirect('templates');
    }
}
