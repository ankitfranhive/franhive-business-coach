<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CronController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('email');
        $this->load->model('Campaign_Model');
    }

    public function my_cron_job() {
        // Email configuration
        $config = [
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.hostinger.com',
            'smtp_user' => 'nlp@empoweryourdestiny.com.au',
            'smtp_pass' => 'Franh1ve@2024',
            'smtp_port' => 587,
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n"
        ];
    
        $this->email->initialize($config);
    
        // Fetch all campaigns
        $all_campaign = $this->Campaign_Model->get_all_new_campaigns();

        // print_r( $all_campaign);die;
    
        if (!empty($all_campaign)) {
            foreach ($all_campaign as $val) {
                $all_templates = $this->Campaign_Model->get_template_campaign_mapping_id($val['CAMPAIGN_ID']);

                if (empty($all_templates)) {
                    echo "There is no template set for this time.";
                    continue;

                }
                // print_r($all_templates);die;
    
                foreach ($all_templates as $value) {
                    $template_data = $this->Campaign_Model->get_template_by_id($value['TEMPLATE_ID']);
                    $user_details = $this->Campaign_Model->get_users_by_campiagn_id($value['CAMPAIGN_ID']);
    
                    // print_r($user_details);die;

                    if (is_array($user_details)) {
                        foreach ($user_details as $user_detail) {
                            // Fetch user details (assuming you have a method for this)
                            // $user_details = $this->Campaign_Model->get_user_details_by_id($user_id);
    
                            if (empty($user_detail['user_name'])) {
                                echo "User name not found for User ID:". $user_detail['ENTITY_ID']."<br>";
                                continue;
                            }

                            // echo $template_data['TEMPLATE_BODY'];die;
    
                            // Replace placeholders in the template
                            $personalized_body = str_replace(
                                ['$Name$'], // Placeholder
                                [$user_detail['user_name']], // Replacement
                                $template_data['TEMPLATE_BODY']
                            );


                            $personalized_body .= "<br><br>" . $template_data['TEMPLATE_SIGN'];

                            // $template_data['TEMPLATE_SIGN']
                            // echo $user_detail['email_id'];die;
    
                            $this->email->from("nlp@empoweryourdestiny.com.au", "EYD NLP Team");
                            $this->email->to($user_detail['email_id']);
                            $this->email->subject($template_data['TEMPLATE_SUBJECT']);
                            $this->email->message($personalized_body);

                            // echo $personalized_body;die;
    
                            if ($this->email->send()) {
                                $data['STATUS'] = 0;
                                $this->Campaign_Model->update_campaign($value['CAMPAIGN_ID'], $data);
                                echo "Email sent successfully to " . $user_details['email'] . " for Campaign ID: " . $value['CAMPAIGN_ID'] . "<br>";
                            } else {
                                echo "Failed to send email to " . $user_details['email'] . " for Campaign ID: " . $value['CAMPAIGN_ID'] . "<br>";
                                echo $this->email->print_debugger();
                            }
                        }
                    } else {
                        echo "Invalid user data for Campaign ID: " . $value['CAMPAIGN_ID'] . "<br>";
                    }
                }
            }
        } else {
            echo "There is no Campaign.";
        }
    }
    
}
?>
