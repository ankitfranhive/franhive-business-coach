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
        $data['counts'] = [
            'draft' => $this->Campaign_Model->count_by_workflow('draft'),
            'setup' => $this->Campaign_Model->count_by_workflow('setup'),
            'scheduled' => $this->Campaign_Model->count_by_workflow('scheduled'),
            'in_progress' => $this->Campaign_Model->count_by_workflow('in_progress'),
            'sent' => $this->Campaign_Model->count_by_workflow('sent'),
            'paused' => $this->Campaign_Model->count_by_workflow('paused'),
        ];
        $data['recent_campaigns'] = $this->Campaign_Model->get_recent_campaigns(12);
        $data['in_progress_campaigns'] = $this->Campaign_Model->get_campaigns_by_workflow('in_progress', 8);
        $data['scheduled_campaigns'] = $this->Campaign_Model->get_campaigns_by_workflow('scheduled', 8);
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
        $data['crm_users'] = $this->User_Model->get_campaign_crm_users();
        $this->load->view('campaign/add_campaign', $data);
    }


    public function create_campaign()
    {
        $TITLE = $this->input->post('TITLE');
        $MANAGER_NAME = $this->input->post('MANAGER_NAME');
        $MODULE_NAME = $this->input->post('MODULE_NAME');
        $REPLY_ADDRESS = $this->input->post('REPLY_ADDRESS') ?: 'nlp@empoweryourdestiny.com.au';
        $START_DATE = $this->input->post('START_DATE');
        $END_DATE = $this->input->post('END_DATE');
        $selected_template_ids = $this->input->post('TEMPLATE_IDS');
        $sending_order = $this->input->post('SENDING_ORDER');
        $send_date = $this->input->post('SEND_DATE');
        $template_start_time = $this->input->post('TEMPLATE_START_TIME');
        $selected_user_ids = $this->input->post('CONTACT_IDS');
        $save_action = $this->input->post('SAVE_ACTION') ?: 'draft';
        $TIMEZONE = campaign_normalize_timezone($this->input->post('TIMEZONE'));
        $template_timezones = $this->input->post('TEMPLATE_TIMEZONE');

        $has_templates = !empty($selected_template_ids);
        $has_contacts = !empty($selected_user_ids);
        $workflow = $this->resolve_save_action($save_action, $has_templates, $has_contacts);

        $data = array(
            'TITLE' => $TITLE,
            'MANAGER_NAME' => $MANAGER_NAME,
            'MODULE_NAME' => $MODULE_NAME,
            'REPLY_ADDRESS' => $REPLY_ADDRESS,
            'START_DATE' => $START_DATE,
            'END_DATE' => $END_DATE,
            'TIMEZONE' => $TIMEZONE,
            'WORKFLOW_STATUS' => $workflow,
            'STATUS' => campaign_legacy_status($workflow),
            'CREATED_ON' => date('Y-m-d H:i:s'),
            'LAST_ACTIVITY_AT' => date('Y-m-d H:i:s'),
        );

        $campaign_id = $this->Campaign_Model->create_new_campaign($data);
        if ($campaign_id) {
            $this->save_campaign_children(
                $campaign_id,
                $selected_template_ids,
                $sending_order,
                $send_date,
                $template_start_time,
                $selected_user_ids,
                $this->contact_source_from_module($MODULE_NAME),
                $TIMEZONE,
                $template_timezones
            );
            $this->Campaign_Model->refresh_campaign_counts($campaign_id);
        }

        redirect('/campaigns');
    }



    public function viewCampaign($campaign_id)
    {
        // Load the campaign data based on the $course_id
        $campaign = $this->Campaign_Model->get_campaign_by_id($campaign_id);

        // Get all available templates and users
        $data['all_templates'] = $this->Campaign_Model->get_all_templates();
        $selected_templates = $this->Campaign_Model->get_selected_templates($campaign_id);
        $data['selected_templates'] = array_column($selected_templates, null, 'TEMPLATE_ID');
        $data['recipients'] = $this->Campaign_Model->get_users_by_campiagn_id($campaign_id);
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
        $data['all_users'] = $this->Client_Model->getAllLeadsAndClients();
        $data['crm_users'] = $this->User_Model->get_campaign_crm_users();

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
        $save_action = $this->input->post('SAVE_ACTION');
        $TEMPLATE_IDS = $this->input->post('TEMPLATE_IDS');
        $selected_contact_ids = $this->input->post('CONTACT_IDS');
        $TIMEZONE = campaign_normalize_timezone($this->input->post('TIMEZONE'));
        $template_timezones = $this->input->post('TEMPLATE_TIMEZONE');
        $workflow = $this->resolve_save_action(
            $save_action ?: ($this->input->post('WORKFLOW_STATUS') ?: 'draft'),
            !empty($TEMPLATE_IDS),
            !empty($selected_contact_ids)
        );

        $data = array(
            'TITLE' => $TITLE,
            'MANAGER_NAME' => $MANAGER_NAME,
            'MODULE_NAME' => $MODULE_NAME,
            'START_DATE' => $START_DATE,
            'END_DATE' => $END_DATE,
            'TIMEZONE' => $TIMEZONE,
            'WORKFLOW_STATUS' => $workflow,
            'STATUS' => campaign_legacy_status($workflow),
            'MODIFIED_ON' => date('Y-m-d H:i:s'),
            'LAST_ACTIVITY_AT' => date('Y-m-d H:i:s'),
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
                $row_timezone = campaign_normalize_timezone(
                    ($template_timezones[$TEMPLATE_ID] ?? '') ?: $TIMEZONE
                );
                $mapping_data = array(
                    'CAMPAIGN_ID' => $CAMPAIGN_ID,
                    'TEMPLATE_ID' => $TEMPLATE_ID,
                    'SENDING_ORDER' => $SENDING_ORDER[$TEMPLATE_ID],
                    'SEND_DATE' => $SEND_DATE[$TEMPLATE_ID],
                    'TEMPLATE_START_TIME' => $this->start_hour($TEMPLATE_START_TIME[$TEMPLATE_ID] ?? ''),
                    'TIMEZONE' => $row_timezone,
                    'SEND_AT' => $this->build_send_at($SEND_DATE[$TEMPLATE_ID] ?? '', $TEMPLATE_START_TIME[$TEMPLATE_ID] ?? '', $row_timezone),
                    'SEND_STATUS' => 'pending',
                    'STATUS' => 1,
                );
                $this->Campaign_Model->insert_campaign_template($mapping_data);
            }
        }


        // First, delete existing mappings for this campaign
        $this->Campaign_Model->delete_campaign_users($CAMPAIGN_ID);

        // Save the selected users for this campaign
        if (!empty($selected_contact_ids)) {
            $contact_source = $this->contact_source_from_module($MODULE_NAME);
            foreach ($selected_contact_ids as $user_id) {
                $user_data = array(
                    'CAMPAIGN_ID' => $CAMPAIGN_ID,
                    'USER_ID' => $user_id,
                    'ASSIGN_DATE' => date('Y-m-d H:i:s'),
                    'STATUS' => 'Active',
                    'CONTACT_SOURCE' => $contact_source,
                );
                $this->Campaign_Model->add_campaign_user($user_data);
            }
        }

        $this->Campaign_Model->refresh_campaign_counts($CAMPAIGN_ID);
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
            'MODULE_NAME' => in_array($MODULE_NAME, ['Lead', 'Client', 'Campaign'], true) ? $MODULE_NAME : 'Lead',
            'TEMPLATE_NAME' => $TEMPLATE_NAME,
            'TEMPLATE_SUBJECT' => $TEMPLATE_SUBJECT,
            'TEMPLATE_BODY' => $TEMPLATE_BODY,
            'TEMPLATE_SIGN' => $TEMPLATE_SIGN,
            'ATTACHMENTS' => $attachment_string,
            'MERGE_TAGS' => json_encode(array_keys(campaign_merge_tags())),
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
        $template = $this->Campaign_Model->get_template_by_id($template_id);
        if (!empty($template) && trim((string)$template['MODULE_NAME']) === 'Payment Agreement') {
            redirect('templates');
            return;
        }
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
            'MODULE_NAME' => in_array($MODULE_NAME, ['Lead', 'Client', 'Campaign'], true) ? $MODULE_NAME : $existing_template['MODULE_NAME'],
            'TEMPLATE_SUBJECT' => $TEMPLATE_SUBJECT,
            'TEMPLATE_BODY' => $TEMPLATE_BODY,
            'TEMPLATE_SIGN' => $TEMPLATE_SIGN,
            'ATTACHMENTS' => $attachment_string,
            'MERGE_TAGS' => json_encode(array_keys(campaign_merge_tags())),
        );

        $this->Campaign_Model->update_template($TEMPLATE_ID, $data);

        redirect('templates');
    }


    public function deleteTemplate($campaign_id)
    {
        $template = $this->Campaign_Model->get_template_by_id($campaign_id);
        if (!empty($template) && trim((string)$template['MODULE_NAME']) === 'Payment Agreement') {
            redirect('templates');
            return;
        }
        $this->Campaign_Model->delete_template($campaign_id);
        redirect('templates');
    }

    public function activateCampaign($campaign_id)
    {
        $this->Campaign_Model->set_workflow($campaign_id, 'scheduled');
        redirect('campaigns');
    }

    public function pauseCampaign($campaign_id)
    {
        $this->Campaign_Model->set_workflow($campaign_id, 'paused');
        redirect('campaigns');
    }

    private function resolve_save_action($action, $has_templates, $has_contacts)
    {
        if ($action === 'scheduled') {
            return ($has_templates && $has_contacts) ? 'scheduled' : 'setup';
        }
        if ($action === 'setup' || ($has_templates && $has_contacts && $action !== 'draft')) {
            return 'setup';
        }
        return 'draft';
    }

    private function contact_source_from_module($module_name)
    {
        return trim((string)$module_name) === 'User' ? 'user' : 'entity';
    }

    private function save_campaign_children($campaign_id, $template_ids, $sending_order, $send_date, $template_start_time, $user_ids, $contact_source = 'entity', $timezone = null, $template_timezones = [])
    {
        $timezone = campaign_normalize_timezone($timezone);
        if (!is_array($template_timezones)) {
            $template_timezones = [];
        }
        if (!empty($template_ids)) {
            foreach ($template_ids as $template_id) {
                $row_timezone = campaign_normalize_timezone(
                    ($template_timezones[$template_id] ?? '') ?: $timezone
                );
                $this->Campaign_Model->insert_campaign_template([
                    'CAMPAIGN_ID' => $campaign_id,
                    'TEMPLATE_ID' => $template_id,
                    'SENDING_ORDER' => isset($sending_order[$template_id]) ? $sending_order[$template_id] : null,
                    'SEND_DATE' => isset($send_date[$template_id]) ? $send_date[$template_id] : null,
                    'TEMPLATE_START_TIME' => $this->start_hour($template_start_time[$template_id] ?? ''),
                    'TIMEZONE' => $row_timezone,
                    'SEND_AT' => $this->build_send_at($send_date[$template_id] ?? '', $template_start_time[$template_id] ?? '', $row_timezone),
                    'SEND_STATUS' => 'pending',
                    'STATUS' => 1,
                ]);
            }
        }

        if (!empty($user_ids)) {
            foreach ($user_ids as $user_id) {
                $this->Campaign_Model->add_campaign_user([
                    'CAMPAIGN_ID' => $campaign_id,
                    'USER_ID' => $user_id,
                    'ASSIGN_DATE' => time(),
                    'STATUS' => 1,
                    'CONTACT_SOURCE' => $contact_source,
                ]);
            }
        }
    }

    private function build_send_at($date, $time, $timezone = null)
    {
        return campaign_build_send_at($date, $time, $timezone);
    }

    private function start_hour($time)
    {
        $time = trim((string)$time);
        if ($time === '') {
            return 9;
        }
        return (int)explode(':', $time)[0];
    }
}
