<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Telephony extends CI_Controller
{

    public function __construct() {
        parent::__construct();
        $this->load->model('Entity_model');
        $this->load->helper(['url', 'html']);
    }
    public function index()
    {
        $partner = getenv('CLOUDTALK_PARTNER') ?: 'lms';
        $sidebarEnabled = true; // or getenv('TELEPHONY_SIDEBAR_ENABLED') === 'true';

        $data = ['partner' => $partner, 'sidebarEnabled' => $sidebarEnabled];
        $this->load->view('telephony/index', $data);
    }

    public function sidebar()
    {
        $phone = $this->input->get('phone', true);
        $email = $this->input->get('email', true);

        $rows = $this->Entity_model->find_by_contact($phone, $email);

        $lead = !empty($rows) ? $rows[0] : null;

        // Basic fields
        if ($lead) {
            $name      = !empty($lead['NAME']) ? $lead['NAME'] : (!empty($lead['TITLE']) ? $lead['TITLE'] : 'Lead');
            $entityId  = (int)$lead['ENTITY_ID'];
            $emailOut  = !empty($lead['EMAIL']) ? $lead['EMAIL'] : '';
            // prefer MOBILE > PHONE > HOME_PHONE
            $phoneOut  = $lead['MOBILE'] ?: ($lead['PHONE'] ?: ($lead['HOME_PHONE'] ?: ''));
            $cityState = trim(($lead['CITY'] ?: '') . (!empty($lead['STATE']) ? (', ' . $lead['STATE']) : ''));
            $status    = $lead['LEAD_STATUS'] ?: '';
            $owner     = $lead['LEAD_OWNER'] ?: '';
            $nextCall  = $lead['NEXT_CALL_DATE'] ? date('d M Y, H:i', strtotime($lead['NEXT_CALL_DATE'])) : '';

            $html = '<div style="display:grid;gap:8px">'
                  . '<div><strong>'.htmlspecialchars($name).'</strong> (ID: '.$entityId.')</div>'
                  . ($status ? '<div>Status: '.htmlspecialchars($status).'</div>' : '')
                  . ($owner  ? '<div>Owner: '.htmlspecialchars($owner).'</div>' : '')
                  . ($phoneOut ? '<div>📞 '.htmlspecialchars($phoneOut).'</div>' : '')
                  . ($emailOut ? '<div>✉️ '.htmlspecialchars($emailOut).'</div>' : '')
                  . ($cityState ? '<div>'.htmlspecialchars($cityState).'</div>' : '')
                  . ($nextCall ? '<div>Next call: '.htmlspecialchars($nextCall).'</div>' : '')
                  . '<div><a href="'.base_url('leads/view/'.$entityId).'" target="_blank" rel="noopener">Open lead</a></div>'
                  . '</div>';

            // If multiple matches, add a tiny hint
            if (count($rows) > 1) {
                $html .= '<div style="margin-top:8px"><small>'.count($rows).' matches — showing best match.</small></div>';
            }
        } else {
            // No match
            $html = '<div><strong>No match found</strong><br>'
                  . ($phone ? '<small>Phone: '.htmlspecialchars($phone).'</small><br>' : '')
                  . ($email ? '<small>Email: '.htmlspecialchars($email).'</small><br>' : '')
                  . '<div style="margin-top:8px">'
                  . '<a href="'.base_url('leads?search='.urlencode($phone ?: $email)).'" target="_blank" rel="noopener">Search in Leads</a>'
                  . '</div></div>';
        }

        $this->output->set_content_type('text/html')->set_output($html);
    }

    private function fakeStudentLookup($phone, $email)
    {
        if ($phone === '+91 9876543210' || $email === 'learner@example.com') {
            return ['name'=>'Test Learner','id'=>12345,'program'=>'BBA 2025','phone'=>$phone,'email'=>$email,'notes_url'=>'/user/12345/notes'];
        }
        return [];
    }
}
