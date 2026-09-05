<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CronController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('email');
        $this->load->model('Campaign_Model');
        $this->load->helper('campaign');
    }

    public function my_cron_job() {
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

        $all_campaign = $this->Campaign_Model->get_schedulable_campaigns();
        if (empty($all_campaign)) {
            $all_campaign = $this->Campaign_Model->get_all_new_campaigns();
        }

        if (empty($all_campaign)) {
            echo "There is no Campaign.";
            return;
        }

        foreach ($all_campaign as $val) {
            $campaign_id = $val['CAMPAIGN_ID'];
            $all_templates = $this->Campaign_Model->get_due_template_mappings($campaign_id);
            if (empty($all_templates)) {
                echo "No due emails for campaign {$campaign_id}.<br>";
                continue;
            }

            $this->Campaign_Model->set_workflow($campaign_id, 'in_progress');
            $user_details = $this->Campaign_Model->get_users_by_campiagn_id($campaign_id);

            foreach ($all_templates as $mapping) {
                $template_data = $this->Campaign_Model->get_template_by_id($mapping['TEMPLATE_ID']);
                if (empty($template_data) || empty($user_details)) {
                    continue;
                }

                foreach ($user_details as $user_detail) {
                    if (empty($user_detail['email_id'])) {
                        echo "Email not found for User ID: " . ($user_detail['ENTITY_ID'] ?? '') . "<br>";
                        continue;
                    }

                    $personalized_subject = campaign_fill_merge_tags($template_data['TEMPLATE_SUBJECT'], $user_detail);
                    $personalized_body = campaign_fill_merge_tags($template_data['TEMPLATE_BODY'], $user_detail);
                    $personalized_sign = campaign_fill_merge_tags($template_data['TEMPLATE_SIGN'], $user_detail);
                    $personalized_body .= "<br><br>" . $personalized_sign;

                    $from_name = $val['MANAGER_NAME'] ?: 'EYD NLP Team';
                    $this->email->clear(true);
                    $this->email->from("nlp@empoweryourdestiny.com.au", $from_name);
                    $this->email->to($user_detail['email_id']);
                    $this->email->subject($personalized_subject);
                    $this->email->message($personalized_body);

                    if ($this->email->send()) {
                        $this->Campaign_Model->log_campaign_email([
                            'CAMPAIGN_ID' => $campaign_id,
                            'TEMPLATE_ID' => $mapping['TEMPLATE_ID'],
                            'FOREIGN_ID' => $user_detail['ENTITY_ID'],
                            'TO_EMAIL' => $user_detail['email_id'],
                            'SUBJECT' => $personalized_subject,
                            'BODY' => $personalized_body,
                            'SEND_DATE' => date('Y-m-d H:i:s'),
                        ]);
                        echo "Email sent to " . $user_detail['email_id'] . " for Campaign ID: {$campaign_id}<br>";
                    } else {
                        echo "Failed to send email to " . $user_detail['email_id'] . " for Campaign ID: {$campaign_id}<br>";
                    }
                }

                $this->Campaign_Model->mark_template_mapping_sent($mapping['ID']);
            }

            $this->Campaign_Model->refresh_campaign_counts($campaign_id);
            if (!$this->Campaign_Model->campaign_has_pending_emails($campaign_id)) {
                $this->Campaign_Model->set_workflow($campaign_id, 'sent');
            }
        }
    }
}
