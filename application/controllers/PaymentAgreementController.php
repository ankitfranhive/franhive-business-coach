<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PaymentAgreementController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Payment_agreement_model', 'Campaign_Model'));
        // Keep constructor light — do not load email/pdf on every hit (shared hosting).
        $this->load->library(array('session', 'form_validation'));
        $this->load->helper(array('url', 'form', 'email_template'));
    }

    public function paymentAgreementRequests()
{
    $data['requests'] = $this->Payment_agreement_model->get_all_payment_requests();
    $data['all_courses'] = $this->Payment_agreement_model->get_data();
    $data['email_templates'] = $this->Campaign_Model->get_templates_for_payment_agreement();

    $data['success'] = $this->session->flashdata('success');
    $data['error']   = $this->session->flashdata('error');

    $this->load->view('payment_agreement/payment_agreement_requests', $data);

    // force clear
    $this->session->unset_userdata('success');
    $this->session->unset_userdata('error');
}

    public function sendPaymentAgreement()
    {
        if ($this->input->method() !== 'post') {
            show_error('Invalid request method.');
        }

        $client_id = $this->input->post('client_id');
        // Primary input from current UI
        $client_name = trim((string)$this->input->post('client_name'));
        // Backward compatibility if older form sends first/last names
        $first_name = trim((string)$this->input->post('first_name'));
        $last_name  = trim((string)$this->input->post('last_name'));
        if ($client_name === '') {
            $client_name = trim($first_name . ' ' . $last_name);
        }
        $client_email  = trim((string)$this->input->post('client_email'));
        $business_name = trim($this->input->post('business_name'));
        $total_raw     = $this->input->post('total_inc_gst');
        $selected_course_id = trim((string)$this->input->post('selected_course_id'));
        $selected_course_start_date = trim((string)$this->input->post('selected_course_start_date'));
        $selected_course_end_date = trim((string)$this->input->post('selected_course_end_date'));

        if (empty($client_name) || empty($client_email)) {
            $this->session->set_flashdata('error', 'Client name and email are required.');
            redirect('payment-agreement/requests');
        }

        if ($total_raw === null || trim((string)$total_raw) === '') {
            $this->session->set_flashdata('error', 'Total (inc GST) is required.');
            redirect('payment-agreement/requests');
        }

        if (!is_numeric($total_raw) || (float)$total_raw < 0) {
            $this->session->set_flashdata('error', 'Total (inc GST) must be a valid non-negative number.');
            redirect('payment-agreement/requests');
        }

        $total_inc_gst = round((float)$total_raw, 2);

        $deposit_raw = $this->input->post('deposit_amount');
        $deposit_amount = null;
        if ($deposit_raw !== null && trim((string)$deposit_raw) !== '') {
            if (!is_numeric($deposit_raw) || (float)$deposit_raw < 0) {
                $this->session->set_flashdata('error', 'Deposit Amount must be a valid non-negative number.');
                redirect('payment-agreement/requests');
            }
            $deposit_amount = round((float)$deposit_raw, 2);
            if ($deposit_amount > $total_inc_gst) {
                $this->session->set_flashdata('error', 'Deposit Amount cannot be greater than Total (inc GST).');
                redirect('payment-agreement/requests');
            }
        }

        $deposit_paid_on = trim((string)$this->input->post('deposit_paid_on'));
        if ($deposit_paid_on !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $deposit_paid_on)) {
            $this->session->set_flashdata('error', 'Deposit Date must be a valid date (YYYY-MM-DD).');
            redirect('payment-agreement/requests');
        }
        if ($deposit_paid_on === '') {
            $deposit_paid_on = null;
        }

        if ($selected_course_id === '') {
            $this->session->set_flashdata('error', 'Please select a course for this user.');
            redirect('payment-agreement/requests');
        }
        if ($selected_course_start_date === '' || $selected_course_end_date === '') {
            $this->session->set_flashdata('error', 'Please provide both selected course start and end dates.');
            redirect('payment-agreement/requests');
        }
        if ($selected_course_end_date < $selected_course_start_date) {
            $this->session->set_flashdata('error', 'Selected course end date cannot be earlier than start date.');
            redirect('payment-agreement/requests');
        }

        $template_id = (int)$this->input->post('email_template_id');
        if ($template_id <= 0) {
            $this->session->set_flashdata('error', 'Please select an email template.');
            redirect('payment-agreement/requests');
        }

        $email_template = $this->Campaign_Model->get_template_by_id($template_id);
        if (empty($email_template) || (isset($email_template['RECORD_STATUS']) && (int)$email_template['RECORD_STATUS'] !== 0)) {
            $this->session->set_flashdata('error', 'Selected email template is invalid or no longer available.');
            redirect('payment-agreement/requests');
        }
        $module_name = trim((string)($email_template['MODULE_NAME'] ?? ''));
        if ($module_name !== 'Payment Agreement') {
            $this->session->set_flashdata('error', 'Please select a template with module "Payment Agreement".');
            redirect('payment-agreement/requests');
        }

        $thank_you_template_id = (int)$this->input->post('thank_you_email_template_id');
        if ($thank_you_template_id <= 0) {
            $this->session->set_flashdata('error', 'Please select a thank you email template.');
            redirect('payment-agreement/requests');
        }

        $thank_you_email_template = $this->Campaign_Model->get_template_by_id($thank_you_template_id);
        if (empty($thank_you_email_template) || (isset($thank_you_email_template['RECORD_STATUS']) && (int)$thank_you_email_template['RECORD_STATUS'] !== 0)) {
            $this->session->set_flashdata('error', 'Selected thank you email template is invalid or no longer available.');
            redirect('payment-agreement/requests');
        }
        $ty_module = trim((string)($thank_you_email_template['MODULE_NAME'] ?? ''));
        if ($ty_module !== 'Payment Agreement') {
            $this->session->set_flashdata('error', 'Please select a thank you template with module "Payment Agreement".');
            redirect('payment-agreement/requests');
        }

        $token      = bin2hex(random_bytes(32));
        $expiry_err = null;
        $expires_at = $this->compute_token_expires_at(
            $this->input->post('token_expiry_value'),
            $this->input->post('token_expiry_unit'),
            $expiry_err
        );
        if ($expiry_err !== null) {
            $this->session->set_flashdata('error', $expiry_err);
            redirect('payment-agreement/requests');
        }

        $pa_intro_ov = $this->input->post('payment_arrangement_intro_override', false);
        $requestData = array(
            'client_id'        => !empty($client_id) ? $client_id : null,
            'client_name'      => $client_name,
            'client_email'     => $client_email,
            'business_name'    => $business_name,
            'total_inc_gst'    => $total_inc_gst,
            'deposit_amount'   => $deposit_amount,
            'deposit_paid_on'  => $deposit_paid_on,
            'selected_course_id' => $selected_course_id,
            'selected_course_start_date' => $selected_course_start_date,
            'selected_course_end_date' => $selected_course_end_date,
            'payment_arrangement_intro_override' => is_string($pa_intro_ov) ? $pa_intro_ov : '',
            'thank_you_email_template_id' => $thank_you_template_id,
            'thank_you_message' => null,
            'token'            => $token,
            'token_expires_at' => $expires_at,
            'status'           => 'sent',
            'sent_by'          => $this->session->userdata('user_id') ? $this->session->userdata('user_id') : null
        );

        $request_id = $this->Payment_agreement_model->create_payment_request($requestData);

        if (!$request_id) {
            $this->session->set_flashdata('error', 'Unable to create payment request.');
            redirect('payment-agreement/requests');
        }

        $link = base_url('payment-agreement/form/' . $token);

        $emailConfig = array(
            'protocol'    => 'smtp',
            'smtp_host'   => 'smtp.hostinger.com',
            'smtp_user'   => 'nlp@empoweryourdestiny.com.au',
            'smtp_pass'   => 'Franh1ve@2024',
            'smtp_port'   => 465,
            'smtp_crypto' => 'ssl',
            'smtp_timeout'=> 8,
            'mailtype'    => 'html',
            'charset'     => 'utf-8',
            'newline'     => "\r\n",
            'wordwrap'    => TRUE
        );

        $this->load->library('email');
        $this->email->initialize($emailConfig);
        $this->email->set_newline("\r\n");

        $template_vars = array(
            'name'          => $client_name,
            'link'          => $link,
            'business_name' => $business_name,
            'sender_name'   => 'Empower Your Destiny',
        );

        $subject_tpl = trim((string)($email_template['TEMPLATE_SUBJECT'] ?? ''));
        if ($subject_tpl !== '') {
            $subject = apply_email_template_placeholders($subject_tpl, $template_vars);
        } else {
            $subject = trim($business_name) !== ''
                ? $business_name . ' for ' . $client_name
                : 'Payment agreement for ' . $client_name;
        }

        $body = build_template_email_body($email_template, $template_vars);

        $this->email->from('nlp@empoweryourdestiny.com.au', 'Empower Your Destiny');
        $this->email->to($client_email);
        $this->email->subject($subject);
        $this->email->message($body);

        if ($this->email->send()) {
            $this->session->set_flashdata('success', 'Payment agreement email sent successfully. Link expires on ' . date('d M Y, g:i A', strtotime($expires_at)) . '.');
        } else {
            log_message('error', 'Payment agreement email send failed: ' . $this->email->print_debugger());
            $this->session->set_flashdata('error', 'Email could not be sent. Please try again or contact administrator.');
        }

        redirect('payment-agreement/requests');
    }

    public function resendPaymentAgreement()
    {
        if ($this->input->method() !== 'post') {
            show_error('Invalid request method.');
        }

        $request_id = (int)$this->input->post('request_id');
        if ($request_id <= 0) {
            $this->session->set_flashdata('error', 'Invalid payment request.');
            redirect('payment-agreement/requests');
        }

        $request = $this->Payment_agreement_model->get_payment_request_by_id($request_id);
        if (empty($request)) {
            $this->session->set_flashdata('error', 'Payment request not found.');
            redirect('payment-agreement/requests');
        }

        $client_name  = trim((string)($request['client_name'] ?? ''));
        $client_email = trim((string)($request['client_email'] ?? ''));
        if ($client_name === '' || $client_email === '') {
            $this->session->set_flashdata('error', 'This request is missing client name or email.');
            redirect('payment-agreement/requests');
        }

        $template_id = (int)$this->input->post('email_template_id');
        if ($template_id <= 0) {
            $templates = $this->Campaign_Model->get_templates_for_payment_agreement();
            if (!empty($templates)) {
                $template_id = (int)$templates[0]['TEMPLATE_ID'];
            }
        }

        if ($template_id <= 0) {
            $this->session->set_flashdata('error', 'Please select an email template to resend this form.');
            redirect('payment-agreement/requests');
        }

        $email_template = $this->Campaign_Model->get_template_by_id($template_id);
        if (empty($email_template) || (isset($email_template['RECORD_STATUS']) && (int)$email_template['RECORD_STATUS'] !== 0)) {
            $this->session->set_flashdata('error', 'Selected email template is invalid or no longer available.');
            redirect('payment-agreement/requests');
        }
        $module_name = trim((string)($email_template['MODULE_NAME'] ?? ''));
        if ($module_name !== 'Payment Agreement') {
            $this->session->set_flashdata('error', 'Please select a template with module "Payment Agreement".');
            redirect('payment-agreement/requests');
        }

        $thank_you_template_id = (int)$this->input->post('thank_you_email_template_id');
        if ($thank_you_template_id <= 0) {
            $existing_ty = isset($request['thank_you_email_template_id']) ? (int)$request['thank_you_email_template_id'] : 0;
            $thank_you_template_id = $existing_ty > 0 ? $existing_ty : 0;
        }
        if ($thank_you_template_id <= 0) {
            $this->session->set_flashdata('error', 'Please select a thank you email template to resend this form.');
            redirect('payment-agreement/requests');
        }

        $thank_you_email_template = $this->Campaign_Model->get_template_by_id($thank_you_template_id);
        if (empty($thank_you_email_template) || (isset($thank_you_email_template['RECORD_STATUS']) && (int)$thank_you_email_template['RECORD_STATUS'] !== 0)) {
            $this->session->set_flashdata('error', 'Selected thank you email template is invalid or no longer available.');
            redirect('payment-agreement/requests');
        }
        $ty_module = trim((string)($thank_you_email_template['MODULE_NAME'] ?? ''));
        if ($ty_module !== 'Payment Agreement') {
            $this->session->set_flashdata('error', 'Please select a thank you template with module "Payment Agreement".');
            redirect('payment-agreement/requests');
        }

        $token      = bin2hex(random_bytes(32));
        $expiry_err = null;
        $expires_at = $this->compute_token_expires_at(
            $this->input->post('token_expiry_value'),
            $this->input->post('token_expiry_unit'),
            $expiry_err
        );
        if ($expiry_err !== null) {
            $this->session->set_flashdata('error', $expiry_err);
            redirect('payment-agreement/requests');
        }

        // Clear any old heavy thank_you_message blobs; content now lives in email templates only.
        $extra_resend = array(
            'thank_you_email_template_id' => $thank_you_template_id,
            'thank_you_message' => null,
        );

        if (!$this->Payment_agreement_model->reset_request_for_resend($request_id, $token, $expires_at, $extra_resend)) {
            $this->session->set_flashdata('error', 'Unable to prepare form for resend.');
            redirect('payment-agreement/requests');
        }

        $link = base_url('payment-agreement/form/' . $token);
        $business_name = trim((string)($request['business_name'] ?? ''));

        $emailConfig = array(
            'protocol'    => 'smtp',
            'smtp_host'   => 'smtp.hostinger.com',
            'smtp_user'   => 'nlp@empoweryourdestiny.com.au',
            'smtp_pass'   => 'Franh1ve@2024',
            'smtp_port'   => 465,
            'smtp_crypto' => 'ssl',
            'smtp_timeout'=> 8,
            'mailtype'    => 'html',
            'charset'     => 'utf-8',
            'newline'     => "\r\n",
            'wordwrap'    => TRUE
        );

        $this->load->library('email');
        $this->email->initialize($emailConfig);
        $this->email->set_newline("\r\n");

        $template_vars = array(
            'name'          => $client_name,
            'link'          => $link,
            'business_name' => $business_name,
            'sender_name'   => 'Empower Your Destiny',
        );

        $subject_tpl = trim((string)($email_template['TEMPLATE_SUBJECT'] ?? ''));
        if ($subject_tpl !== '') {
            $subject = apply_email_template_placeholders($subject_tpl, $template_vars);
        } else {
            $subject = trim($business_name) !== ''
                ? $business_name . ' for ' . $client_name
                : 'Payment agreement for ' . $client_name;
        }

        $body = build_template_email_body($email_template, $template_vars);

        $this->email->from('nlp@empoweryourdestiny.com.au', 'Empower Your Destiny');
        $this->email->to($client_email);
        $this->email->subject($subject);
        $this->email->message($body);

        if ($this->email->send()) {
            $this->session->set_flashdata('success', 'Payment agreement form resent successfully. The new link expires on ' . date('d M Y, g:i A', strtotime($expires_at)) . '.');
        } else {
            log_message('error', 'Payment agreement resend email failed: ' . $this->email->print_debugger());
            $this->session->set_flashdata('error', 'Form link was reset but email could not be sent. Please try again or contact administrator.');
        }

        redirect('payment-agreement/requests');
    }

    public function updateAgreementApproval($agreement_id = null)
    {
        if ($this->input->method() !== 'post') {
            show_error('Invalid request method.');
        }

        $agreement_id = (int)$agreement_id;
        if ($agreement_id <= 0) {
            $this->session->set_flashdata('error', 'Invalid agreement.');
            redirect('payment-agreement/requests');
        }

        $agreement = $this->Payment_agreement_model->get_agreement_by_id($agreement_id);
        if (!$agreement) {
            show_404();
        }

        $approved_by = trim((string)$this->input->post('approved_by'));
        $approval_date = trim((string)$this->input->post('approval_date'));

        if ($approval_date !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $approval_date)) {
            $this->session->set_flashdata('error', 'Approval date must be a valid date (YYYY-MM-DD).');
            redirect('payment-agreement/view/' . $agreement_id);
        }

        $ok = $this->Payment_agreement_model->update_agreement_approval($agreement_id, $approved_by, $approval_date);
        if ($ok) {
            $this->session->set_flashdata('success', 'Approval details updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Unable to update approval details.');
        }

        redirect('payment-agreement/view/' . $agreement_id);
    }


    public function paymentAgreementForm($token = null)
{
    if (empty($token)) {
        show_error('Invalid request link.');
    }

    $request = $this->Payment_agreement_model->get_request_by_token($token);

    if (!$request) {
        show_error('Invalid request link.');
    }

    if (strtotime($request['token_expires_at']) < time()) {
        if ($request['status'] !== 'submitted') {
            $this->Payment_agreement_model->mark_request_expired($request['id']);
        }
        $this->load->view('payment_agreement/link_expired');
        return;
    }

    if ($request['status'] === 'submitted') {
        $this->load->view('payment_agreement/already_submitted');
        return;
    }

    if ($request['status'] === 'sent') {
        $this->Payment_agreement_model->mark_request_opened($request['id']);
        $request = $this->Payment_agreement_model->get_request_by_token($token);
    }

    $data['request'] = $request;
    $data['all_courses'] = $this->Payment_agreement_model->get_courses_for_payment_agreement_form();
    $form_settings = $this->Payment_agreement_model->get_form_settings();
    $data['form_settings'] = $form_settings;
    $data['consent_term_labels'] = $this->Payment_agreement_model->get_effective_consent_term_labels($form_settings);
    $data['show_consent_terms_heading'] = $this->Payment_agreement_model->has_any_consent_checkbox_terms($form_settings);

    $this->load->view('payment_agreement/add_agreement', $data);
}

    // public function paymentAgreementForm($token = null)
    // {
    //     if (empty($token)) {
    //         show_error('Invalid request link.');
    //     }

    //     $request = $this->Payment_agreement_model->get_request_by_token($token);

    //     if (!$request) {
    //         show_error('Invalid request link.');
    //     }

    //     if (strtotime($request['token_expires_at']) < time()) {
    //         if ($request['status'] !== 'submitted') {
    //             $this->Payment_agreement_model->mark_request_expired($request['id']);
    //         }
    //         $this->load->view('payment_agreement/link_expired');
    //         return;
    //     }

    //     if ($request['status'] === 'submitted') {
    //         $this->load->view('payment_agreement/already_submitted');
    //         return;
    //     }

    //     if ($request['status'] === 'sent') {
    //         $this->Payment_agreement_model->mark_request_opened($request['id']);
    //         $request = $this->Payment_agreement_model->get_request_by_token($token);
    //     }

    //     $data['request'] = $request;
    //     $data['all_courses'] = $this->Payment_agreement_model->get_data();
    //     // echo '<pre>';
    //     // print_r($data);
    //     // echo '</pre>';
    //     // exit;

    //     $this->load->view('payment_agreement/add_agreement', $data);
    // }

    public function enroll_data_submit($token = null)
    {
        if (empty($token)) {
            show_error('Invalid submission token.');
        }
    
        $request = $this->Payment_agreement_model->get_request_by_token($token);
    
        if (!$request) {
            show_error('Invalid submission request.');
        }

        // Capture thank-you email template id only (page content is static / lightweight).
        $saved_thank_you_template_id = isset($request['thank_you_email_template_id'])
            ? (int)$request['thank_you_email_template_id']
            : 0;
    
        if (strtotime($request['token_expires_at']) < time()) {
            if ($request['status'] !== 'submitted') {
                $this->Payment_agreement_model->mark_request_expired($request['id']);
            }
            show_error('This form link has expired.');
        }
    
        if ($request['status'] === 'submitted') {
            // Already submitted: show the shared thank-you page (Zoom + WhatsApp).
            $this->load->view('eforms_public/thank_you');
            return;
        }
    
        // Validation
        $this->form_validation->set_message('regex_match', 'The {field} field is not in the correct format.');
        $this->form_validation->set_message('valid_email', 'Please enter a valid email address.');
        $this->form_validation->set_message('numeric', 'The {field} field must be a number.');

        $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|max_length[60]|regex_match[/^[A-Za-z][A-Za-z .\'-]*$/]');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|max_length[60]|regex_match[/^[A-Za-z][A-Za-z .\'-]*$/]');
        $this->form_validation->set_rules('business_name', 'Business / Company Name', 'required|trim|max_length[120]');
        $this->form_validation->set_rules('postal_address', 'Postal Address', 'required|trim|max_length[200]');
        $this->form_validation->set_rules('city_suburb', 'City / Suburb', 'required|trim|max_length[80]|regex_match[/^[A-Za-z][A-Za-z .\'-]*$/]');
        $this->form_validation->set_rules('postcode', 'Postcode', 'required|trim|min_length[3]|max_length[10]|regex_match[/^[0-9]{3,10}$/]');
        $this->form_validation->set_rules('emergency_contact_name', 'Emergency Contact Name', 'required|trim|max_length[80]|regex_match[/^[A-Za-z][A-Za-z .\'-]*$/]');
        $this->form_validation->set_rules('emergency_contact_relationship', 'Relationship', 'required|trim|max_length[60]|regex_match[/^[A-Za-z][A-Za-z .\'-]*$/]');
        $this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'required|trim|regex_match[/^\d{4}-\d{2}-\d{2}$/]');
        $this->form_validation->set_rules('email_address', 'Email', 'required|trim|valid_email|max_length[120]');
        $this->form_validation->set_rules('signature_first_name', 'Signature First Name', 'required|trim|max_length[60]|regex_match[/^[A-Za-z][A-Za-z .\'-]*$/]');
        $this->form_validation->set_rules('signature_last_name', 'Signature Last Name', 'required|trim|max_length[60]|regex_match[/^[A-Za-z][A-Za-z .\'-]*$/]');
        $this->form_validation->set_rules('signature_date', 'Signature Date', 'required|trim|regex_match[/^\d{4}-\d{2}-\d{2}$/]');
        $this->form_validation->set_rules('signature_data', 'Signature', 'required|trim');
        $this->form_validation->set_rules('country_code', 'Country Code', 'required|trim|regex_match[/^\+[0-9]{1,4}$/]');
        $this->form_validation->set_rules('country_code_work', 'Work Country Code', 'required|trim|regex_match[/^\+[0-9]{1,4}$/]');
        $this->form_validation->set_rules('emergency_country_code', 'Emergency Country Code', 'required|trim|regex_match[/^\+[0-9]{1,4}$/]');
        $this->form_validation->set_rules('contact_number_mobile', 'Contact Number (Mobile)', 'required|trim|regex_match[/^[0-9]{7,15}$/]');
        $this->form_validation->set_rules('contact_number_work', 'Contact Number (Work)', 'required|trim|regex_match[/^[0-9]{7,15}$/]');
        $this->form_validation->set_rules('emergency_contact_number', 'Emergency Contact Number', 'required|trim|regex_match[/^[0-9]{7,15}$/]');
        $this->form_validation->set_rules('total_inc_gst', 'Total (inc GST)', 'trim|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('deposit_amount', 'Deposit Paid', 'required|trim|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('deposit_paid_via', 'Deposit Paid Via', 'required|trim|in_list[Bank Transfer,Payment Link,Others]');
        $this->form_validation->set_rules('deposit_paid_on', 'Deposit Paid On', 'required|trim|regex_match[/^\d{4}-\d{2}-\d{2}$/]');
        $this->form_validation->set_rules('due_date', 'Due Date', 'required|trim|regex_match[/^\d{4}-\d{2}-\d{2}$/]');
        // $this->form_validation->set_rules('holds_certification', 'Certification confirmation', 'required');
        // $this->form_validation->set_rules('certification_details', 'Certification Details', 'required|trim');
        $this->form_validation->set_rules('payment_arrangement_type', 'Preferred payment arrangement', 'required|trim|in_list[pay_full,pay_plan]');
        $this->form_validation->set_rules(
            'payment_option',
            'Preferred payment method',
            'required|trim|in_list[Secure Online Payment (Xero & Stripe),Bank Transfer / PayID]'
        );

        if ($this->form_validation->run() === false) {
            $data['request'] = $request;
            $data['all_courses'] = $this->Payment_agreement_model->get_courses_for_payment_agreement_form();
            $form_settings = $this->Payment_agreement_model->get_form_settings();
            $data['form_settings'] = $form_settings;
            $data['consent_term_labels'] = $this->Payment_agreement_model->get_effective_consent_term_labels($form_settings);
            $data['show_consent_terms_heading'] = $this->Payment_agreement_model->has_any_consent_checkbox_terms($form_settings);
            $this->load->view('payment_agreement/add_agreement', $data);
            return;
        }

        $form_settings = $this->Payment_agreement_model->get_form_settings();
        $consent_term_labels = $this->Payment_agreement_model->get_effective_consent_term_labels($form_settings);
        foreach ($consent_term_labels as $term_index => $term_label) {
            if ($term_label === '') {
                continue;
            }
            if ((string)$this->input->post('agree_terms_' . (int)$term_index) !== '1') {
                $this->session->set_flashdata('error', 'Please confirm all consent and terms checkboxes.');
                $data['request'] = $request;
                $data['all_courses'] = $this->Payment_agreement_model->get_courses_for_payment_agreement_form();
                $data['form_settings'] = $form_settings;
                $data['consent_term_labels'] = $consent_term_labels;
                $data['show_consent_terms_heading'] = $this->Payment_agreement_model->has_any_consent_checkbox_terms($form_settings);
                $this->load->view('payment_agreement/add_agreement', $data);
                return;
            }
        }

        $posted_courses = $this->input->post('course_name');
        $posted_courses = is_array($posted_courses) ? $posted_courses : array();
        if (empty($request['selected_course_id']) && empty($posted_courses)) {
            $this->session->set_flashdata('error', 'Please select at least one course.');
            $data['request'] = $request;
            $data['all_courses'] = $this->Payment_agreement_model->get_courses_for_payment_agreement_form();
            $data['form_settings'] = $form_settings;
            $data['consent_term_labels'] = $consent_term_labels;
            $data['show_consent_terms_heading'] = $this->Payment_agreement_model->has_any_consent_checkbox_terms($form_settings);
            $this->load->view('payment_agreement/add_agreement', $data);
            return;
        }

        $course_dates_post = $this->input->post('course_dates');
        $course_dates_post = is_array($course_dates_post) ? $course_dates_post : array();
        $pa_type = $this->input->post('payment_arrangement_type');
        $pa_err = $this->validate_payment_arrangement_input($pa_type, $this->input->post(), $form_settings, $course_dates_post);

        if ($pa_err !== null) {
            $this->session->set_flashdata('error', $pa_err);
            $data['request'] = $request;
            $data['all_courses'] = $this->Payment_agreement_model->get_courses_for_payment_agreement_form();
            $data['form_settings'] = $form_settings;
            $data['consent_term_labels'] = $this->Payment_agreement_model->get_effective_consent_term_labels($form_settings);
            $data['show_consent_terms_heading'] = $this->Payment_agreement_model->has_any_consent_checkbox_terms($form_settings);
            $this->load->view('payment_agreement/add_agreement', $data);
            return;
        }
    
        $post = $this->input->post();

        $fn = isset($post['first_name']) ? trim((string)$post['first_name']) : '';
        $ln = isset($post['last_name']) ? trim((string)$post['last_name']) : '';
        $post['full_name'] = trim($fn . ' ' . $ln);
    
        $selected_course_ids = isset($post['course_name']) && is_array($post['course_name'])
            ? array_values($post['course_name'])
            : array();
    
        $course_dates = isset($post['course_dates']) && is_array($post['course_dates'])
            ? $post['course_dates']
            : array();

        if (!empty($request['selected_course_id'])) {
            $forced_course_id = (string)$request['selected_course_id'];
            $selected_course_ids = array($forced_course_id);
            if (!empty($request['selected_course_start_date'])) {
                $course_dates[$forced_course_id] = (string)$request['selected_course_start_date'];
            }
        }
    
        $other_course_name = isset($post['other_course_name'])
            ? trim($post['other_course_name'])
            : '';
    
        $signature_data = isset($post['signature_data'])
            ? $post['signature_data']
            : '';
    
        // Combine signature first + last name into one DB field
        $signature_first_name = isset($post['signature_first_name']) ? trim($post['signature_first_name']) : '';
        $signature_last_name  = isset($post['signature_last_name']) ? trim($post['signature_last_name']) : '';
        $signature_full_name  = trim($signature_first_name . ' ' . $signature_last_name);
    
        // Country code + phone numbers
        $country_code          = isset($post['country_code']) ? trim($post['country_code']) : '';
        $emergency_country_code = isset($post['emergency_country_code']) ? trim($post['emergency_country_code']) : '';
        $contact_mobile        = isset($post['contact_number_mobile']) ? trim($post['contact_number_mobile']) : '';
        $contact_work          = isset($post['contact_number_work']) ? trim($post['contact_number_work']) : '';
        $emergency_contact     = isset($post['emergency_contact_number']) ? trim($post['emergency_contact_number']) : '';
        $contact_mobile_full   = !empty($country_code) && !empty($contact_mobile) ? $country_code . ' ' . $contact_mobile : $contact_mobile;
        $contact_work_full     = !empty($country_code) && !empty($contact_work) ? $country_code . ' ' . $contact_work : $contact_work;
    
        $file_name = null;
        if (!empty($_FILES['certification_attachment']['name'])) {
            $config['upload_path']   = './uploads/payment_agreement/';
            $config['allowed_types'] = 'jpg|jpeg|png|pdf|doc|docx';
            $config['max_size']      = 5120;
            $config['encrypt_name']  = true;
    
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }
    
            $this->load->library('upload', $config);
    
            if ($this->upload->do_upload('certification_attachment')) {
                $uploadData = $this->upload->data();
                $file_name = $uploadData['file_name'];
            } else {
                log_message('error', 'File upload failed: ' . $this->upload->display_errors('', ''));
                $this->session->set_flashdata('error', 'Certification attachment upload failed. Please try again with a valid file (jpg, png, pdf, doc, docx, max 5MB).');
                $data['request'] = $request;
                $data['all_courses'] = $this->Payment_agreement_model->get_courses_for_payment_agreement_form();
                $data['form_settings'] = $form_settings;
                $data['consent_term_labels'] = $this->Payment_agreement_model->get_effective_consent_term_labels($form_settings);
                $data['show_consent_terms_heading'] = $this->Payment_agreement_model->has_any_consent_checkbox_terms($form_settings);
                $this->load->view('payment_agreement/add_agreement', $data);
                return;
            }
        }
    
        $post['request_id'] = $request['id'];
        $post['client_id'] = $request['client_id'];
        $post['certification_attachment'] = $file_name;
    
        $post['course_name'] = !empty($selected_course_ids) ? json_encode($selected_course_ids) : null;
        $post['training_date_text'] = !empty($course_dates) ? json_encode($course_dates) : null;
        $post['other_course_name'] = $other_course_name;
    
        $post['signature_data'] = $signature_data;
        $post['signature'] = $signature_full_name;
    
        // Save separate values too if your DB has these columns
        $post['signature_first_name'] = $signature_first_name;
        $post['signature_last_name'] = $signature_last_name;
    
        // Save country code and phone data
        $post['country_code'] = $country_code;
        $post['contact_number_mobile'] = $contact_mobile;
        $post['contact_number_work'] = $contact_work;
        // Keep emergency contact number as plain digits for DB compatibility.
        $post['emergency_contact_number'] = $emergency_contact;
        unset($post['emergency_country_code']);
    
        // Optional: save full phone numbers too if your DB has these columns
        $post['contact_number_mobile_full'] = $contact_mobile_full;
        $post['contact_number_work_full'] = $contact_work_full;
    
        $post['ip_address'] = $this->input->ip_address();
        $post['user_agent'] = $this->input->user_agent();
        $post['client_timezone'] = isset($post['client_timezone']) ? $post['client_timezone'] : null;
        $post['client_time_iso'] = isset($post['client_time_iso']) ? $post['client_time_iso'] : null;
        $post['screen_resolution'] = isset($post['screen_resolution']) ? $post['screen_resolution'] : null;

        if (isset($request['total_inc_gst']) && $request['total_inc_gst'] !== null && $request['total_inc_gst'] !== '') {
            $post['total_inc_gst'] = $request['total_inc_gst'];
        }

        if (isset($request['deposit_amount']) && $request['deposit_amount'] !== null && $request['deposit_amount'] !== '') {
            $post['deposit_amount'] = $request['deposit_amount'];
        }

        if (isset($request['deposit_paid_on']) && $request['deposit_paid_on'] !== null && $request['deposit_paid_on'] !== '') {
            $post['deposit_paid_on'] = $request['deposit_paid_on'];
        }

        $balance_payable = $this->compute_balance_payable($post, $request);
        $post['balance_amount'] = $balance_payable;

        if ($pa_type === 'pay_plan') {
            $n = isset($post['pay_plan_num_instalments']) ? (int)$post['pay_plan_num_instalments'] : 0;
            if ($n < 1) {
                $n = 1;
            }
            $post['pay_plan_total'] = $balance_payable;
            $post['pay_plan_instalment_amount'] = (string)round((float)$balance_payable / $n, 2);
        }

        $post['payment_arrangement_json'] = $this->build_payment_arrangement_json($pa_type, $post);

        $agreement_id = $this->Payment_agreement_model->save_enrollment_form($post);
    
        if ($agreement_id) {
            $this->Payment_agreement_model->mark_request_submitted($request['id'], $agreement_id);

            if ($saved_thank_you_template_id <= 0 && !empty($request['thank_you_email_template_id'])) {
                $saved_thank_you_template_id = (int)$request['thank_you_email_template_id'];
            }
            $request['thank_you_email_template_id'] = $saved_thank_you_template_id > 0 ? $saved_thank_you_template_id : null;

            // IMPORTANT: echo the thank-you HTML immediately.
            // CI buffers views; calling fastcgi_finish_request() before CI _display()
            // caused a blank page in the browser.
            $thank_you_html = $this->load->view('eforms_public/thank_you', array(), true);
            echo $thank_you_html;

            @session_write_close();
            while (ob_get_level() > 0) {
                @ob_end_flush();
            }
            @flush();

            if (function_exists('fastcgi_finish_request')) {
                @fastcgi_finish_request();
            } elseif (function_exists('litespeed_finish_request')) {
                @litespeed_finish_request();
            }

            try {
                $this->send_payment_agreement_thank_you_email($request, $post);
            } catch (Exception $e) {
                log_message('error', 'Payment agreement thank-you email exception: ' . $e->getMessage());
            }

            // Stop CI from sending output again.
            exit;
        } else {
            $dbErr = $this->db->error();
            log_message('error', 'Agreement insert failed: ' . json_encode($dbErr));
            log_message('error', 'Agreement insert failed request_id=' . (isset($request['id']) ? $request['id'] : 'null') . ' token=' . (isset($request['token']) ? $request['token'] : 'null'));
            log_message('error', 'Agreement insert failed posted keys: ' . implode(',', array_keys((array)$post)));

            $debugMsg = 'Unable to save form at the moment. Please try again.';
            if (!empty($dbErr['message'])) {
                $debugMsg .= ' DB: ' . $dbErr['message'] . ' (Code: ' . (isset($dbErr['code']) ? $dbErr['code'] : 'n/a') . ')';
            }
            show_error($debugMsg);
        }
    }

    public function viewPaymentAgreement($agreement_id)
    {
        $data['agreement'] = $this->Payment_agreement_model->get_agreement_by_id($agreement_id);

        if (!$data['agreement']) {
            show_404();
        }

        $data['success'] = $this->session->flashdata('success');
        $data['error']   = $this->session->flashdata('error');

        $data['all_courses'] = $this->Payment_agreement_model->get_data();
        $form_settings = $this->Payment_agreement_model->get_form_settings();
        $data['form_settings'] = $form_settings;
        $data['consent_term_labels'] = $this->Payment_agreement_model->get_effective_consent_term_labels($form_settings);
        $data['show_consent_terms_heading'] = $this->Payment_agreement_model->has_any_consent_checkbox_terms($form_settings);

        $this->load->view('payment_agreement/view_agreement', $data);
    }

    public function previewAgreement($id)
    {
        $agreement = $this->Payment_agreement_model->get_agreement_by_id($id);

        if (!$agreement) {
            show_404();
        }

        $agreement['course_name_decoded'] = !empty($agreement['course_name'])
            ? json_decode($agreement['course_name'], true)
            : array();

        $agreement['training_date_text_decoded'] = !empty($agreement['training_date_text'])
            ? json_decode($agreement['training_date_text'], true)
            : array();

        $data['agreement'] = $agreement;
        $data['all_courses'] = $this->Payment_agreement_model->get_data();
        $form_settings = $this->Payment_agreement_model->get_form_settings();
        $data['form_settings'] = $form_settings;
        $data['consent_term_labels'] = $this->Payment_agreement_model->get_effective_consent_term_labels($form_settings);
        $data['show_consent_terms_heading'] = $this->Payment_agreement_model->has_any_consent_checkbox_terms($form_settings);

        $this->load->view('payment_agreement/view_agreement_preview', $data);
    }

    public function paymentAgreementList()
    {
        $data['all_agreements'] = $this->Payment_agreement_model->get_all_agreements();
        $this->load->view('payment_agreement/payment_agreement_list', $data);
    }

    public function deleteAgreement($id)
    {
        $deleted = $this->Payment_agreement_model->delete_agreement($id);

        if ($deleted) {
            $this->session->set_flashdata('success', 'Agreement deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Unable to delete agreement.');
        }

        redirect('payment_agreement_list');
    }
    public function downloadAgreementPDF($id)
{
    $this->load->library('pdf');

    $agreement = $this->Payment_agreement_model->get_agreement_by_id($id);

    if (!$agreement) {
        show_404();
    }

    $agreement['course_name_decoded'] = !empty($agreement['course_name'])
        ? json_decode($agreement['course_name'], true)
        : array();

    $agreement['training_date_text_decoded'] = !empty($agreement['training_date_text'])
        ? json_decode($agreement['training_date_text'], true)
        : array();

    $data['agreement'] = $agreement;
    $data['all_courses'] = $this->Payment_agreement_model->get_data();
    $form_settings = $this->Payment_agreement_model->get_form_settings();
    $data['form_settings'] = $form_settings;
    $data['consent_term_labels'] = $this->Payment_agreement_model->get_effective_consent_term_labels($form_settings);
    $data['show_consent_terms_heading'] = $this->Payment_agreement_model->has_any_consent_checkbox_terms($form_settings);

    $html = $this->load->view('payment_agreement/view_agreement_pdf', $data, true);

    $pdf_binary = $this->pdf->create($html);

    $file_name = 'payment_agreement_' . $agreement['id'] . '_' . time() . '.pdf';

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $file_name . '"');
    header('Content-Length: ' . strlen($pdf_binary));

    echo $pdf_binary;
    exit;
}

public function formFieldSettings()
{
    $data['settings'] = $this->Payment_agreement_model->get_form_settings();
    $data['all_courses'] = $this->Payment_agreement_model->get_data();
    $this->load->view('payment_agreement/form_settings', $data);
}

public function saveFormFieldSettings()
{
    if ($this->input->method() !== 'post') {
        show_error('Invalid request method.');
    }

    $post = $this->input->post();
    unset(
        $post['visible_course_ids'],
        $post['consent_rich_html'],
        $post['payment_arrangement_intro_html'],
        $post['payment_arrangement_bonus_html'],
        $post['payment_arrangement_plan_footer_html']
    );

    $limited = !empty($this->input->post('course_visibility_limited'));
    $post['course_visibility_limited'] = $limited ? '1' : '0';

    if ($limited) {
        $ids = $this->input->post('visible_course_ids');
        $post['visible_course_ids'] = is_array($ids)
            ? json_encode(array_values(array_map('strval', $ids)))
            : '[]';
    } else {
        $post['visible_course_ids'] = '';
    }

    $consent_html = $this->input->post('consent_rich_html', false);
    $post['consent_rich_html'] = is_string($consent_html) ? $consent_html : '';

    $post['payment_arrangement_intro_html'] = is_string($this->input->post('payment_arrangement_intro_html', false))
        ? $this->input->post('payment_arrangement_intro_html', false) : '';
    $post['payment_arrangement_bonus_html'] = is_string($this->input->post('payment_arrangement_bonus_html', false))
        ? $this->input->post('payment_arrangement_bonus_html', false) : '';
    $post['payment_arrangement_plan_footer_html'] = is_string($this->input->post('payment_arrangement_plan_footer_html', false))
        ? $this->input->post('payment_arrangement_plan_footer_html', false) : '';

    $post['payment_arrangement_allow_dates_after_training'] = !empty($this->input->post('payment_arrangement_allow_dates_after_training')) ? '1' : '0';

    $this->Payment_agreement_model->save_form_settings($post);

    $this->session->set_flashdata('success', 'Form settings updated successfully.');
    redirect('payment-agreement/form-settings');
}

    protected function payment_arrangement_effective_max_date(array $course_date_values, array $form_settings)
    {
        $dates = array();
        foreach ($course_date_values as $d) {
            $d = trim((string)$d);
            if ($d !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $d)) {
                $dates[] = $d;
            }
        }
        sort($dates);
        $training_max = !empty($dates) ? $dates[count($dates) - 1] : null;

        $ignore = !empty($form_settings['payment_arrangement_allow_dates_after_training'])
            && (string)$form_settings['payment_arrangement_allow_dates_after_training'] === '1';

        $backend = isset($form_settings['payment_arrangement_date_max_override'])
            ? trim((string)$form_settings['payment_arrangement_date_max_override'])
            : '';

        if ($ignore) {
            return $backend !== '' ? $backend : null;
        }

        $candidates = array();
        if ($training_max !== null) {
            $candidates[] = $training_max;
        }
        if ($backend !== '') {
            $candidates[] = $backend;
        }
        if (empty($candidates)) {
            return null;
        }
        sort($candidates);
        return $candidates[0];
    }

    /**
     * @param array|object $post
     * @return string|null Error message or null if OK
     */
    protected function validate_payment_arrangement_input($pa_type, $post, array $form_settings, array $course_dates_raw)
    {
        $post = is_array($post) ? $post : (array)$post;

        if (!in_array($pa_type, array('pay_full', 'pay_plan'), true)) {
            return 'Please select whether you are paying in full or on a payment plan.';
        }

        if ($pa_type === 'pay_full') {
            $d = isset($post['pay_full_commit_date']) ? trim((string)$post['pay_full_commit_date']) : '';
            if ($d === '') {
                return 'Please choose the date you will complete full payment.';
            }
        } else {
            $ack = isset($post['pay_plan_ack_name']) ? trim((string)$post['pay_plan_ack_name']) : '';
            $cd = isset($post['pay_plan_commit_date']) ? trim((string)$post['pay_plan_commit_date']) : '';
            if ($ack === '') {
                return 'Please enter your name or initials for the payment plan acknowledgement.';
            }
            if ($cd === '') {
                return 'Please choose the commitment date for your payment plan.';
            }
            $ni = isset($post['pay_plan_num_instalments']) ? (int)$post['pay_plan_num_instalments'] : 0;
            if ($ni < 1) {
                return 'Please enter the number of instalments (at least 1).';
            }
        }

        $max = $this->payment_arrangement_effective_max_date($course_dates_raw, $form_settings);
        if ($max === null) {
            return null;
        }

        $check = array();
        if ($pa_type === 'pay_full') {
            // No max date cap for the full payment commitment date.
        } else {
            $check[] = isset($post['pay_plan_final_date']) ? trim((string)$post['pay_plan_final_date']) : '';
            // No max date cap for the plan commitment date.
        }

        foreach ($check as $d) {
            if ($d === '') {
                continue;
            }
            if ($d > $max) {
                return 'A payment arrangement date is after the latest date allowed (your training dates or an administrator limit).';
            }
        }

        return null;
    }

    /**
     * Remaining balance: Total (inc GST) minus deposit; never negative (deposit capped at total).
     */
    protected function compute_balance_payable(array $post, array $request)
    {
        $total = 0.0;
        if (isset($request['total_inc_gst']) && $request['total_inc_gst'] !== null && $request['total_inc_gst'] !== '') {
            $total = (float)$request['total_inc_gst'];
        } elseif (isset($post['total_inc_gst']) && is_numeric($post['total_inc_gst'])) {
            $total = (float)$post['total_inc_gst'];
        }

        $dep = 0.0;
        if (isset($request['deposit_amount']) && $request['deposit_amount'] !== null && $request['deposit_amount'] !== '') {
            $dep = (float)$request['deposit_amount'];
        } elseif (isset($post['deposit_amount']) && $post['deposit_amount'] !== '' && is_numeric($post['deposit_amount'])) {
            $dep = (float)$post['deposit_amount'];
        }
        if ($dep < 0) {
            $dep = 0.0;
        }
        if ($dep > $total) {
            $dep = $total;
        }

        return round(max(0.0, $total - $dep), 2);
    }

    protected function build_payment_arrangement_json($pa_type, array $post)
    {
        $bal = isset($post['balance_amount']) ? trim((string)$post['balance_amount']) : '';
        $plan_total = isset($post['pay_plan_total']) ? trim((string)$post['pay_plan_total']) : '';
        if ($pa_type === 'pay_plan' && $bal !== '' && is_numeric($bal)) {
            $plan_total = (string)(float)$bal;
        } elseif ($plan_total === '' && $bal !== '' && is_numeric($bal)) {
            $plan_total = (string)(float)$bal;
        }

        $payload = array(
            'type' => $pa_type,
            'pay_full_commit_date' => isset($post['pay_full_commit_date']) ? trim((string)$post['pay_full_commit_date']) : '',
            'pay_plan_total' => $plan_total,
            'pay_plan_num_instalments' => isset($post['pay_plan_num_instalments']) ? trim((string)$post['pay_plan_num_instalments']) : '',
            'pay_plan_instalment_amount' => isset($post['pay_plan_instalment_amount']) ? trim((string)$post['pay_plan_instalment_amount']) : '',
            'pay_plan_final_date' => isset($post['pay_plan_final_date']) ? trim((string)$post['pay_plan_final_date']) : '',
            'pay_plan_notes' => isset($post['pay_plan_notes']) ? trim((string)$post['pay_plan_notes']) : '',
            'pay_plan_ack_name' => isset($post['pay_plan_ack_name']) ? trim((string)$post['pay_plan_ack_name']) : '',
            'pay_plan_commit_date' => isset($post['pay_plan_commit_date']) ? trim((string)$post['pay_plan_commit_date']) : '',
        );

        return json_encode($payload);
    }

    /**
     * Send confirmation/thank-you email after successful form submission, if a template was chosen.
     */
    protected function send_payment_agreement_thank_you_email($request, array $post = array())
    {
        if (empty($request) || !is_array($request)) {
            return false;
        }

        $template_id = isset($request['thank_you_email_template_id']) ? (int)$request['thank_you_email_template_id'] : 0;
        if ($template_id <= 0) {
            return false;
        }

        $to_email = '';
        if (!empty($post['email_address'])) {
            $to_email = trim((string)$post['email_address']);
        }
        if ($to_email === '' && !empty($request['client_email'])) {
            $to_email = trim((string)$request['client_email']);
        }
        if ($to_email === '' || !filter_var($to_email, FILTER_VALIDATE_EMAIL)) {
            log_message('error', 'Payment agreement thank-you email skipped: missing/invalid recipient for request_id=' . (isset($request['id']) ? $request['id'] : 'n/a'));
            return false;
        }

        $client_name = '';
        if (!empty($post['full_name'])) {
            $client_name = trim((string)$post['full_name']);
        }
        if ($client_name === '' && !empty($request['client_name'])) {
            $client_name = trim((string)$request['client_name']);
        }

        $business_name = isset($request['business_name']) ? trim((string)$request['business_name']) : '';

        $email_template = $this->Campaign_Model->get_template_by_id($template_id);
        if (empty($email_template) || (isset($email_template['RECORD_STATUS']) && (int)$email_template['RECORD_STATUS'] !== 0)) {
            log_message('error', 'Payment agreement thank-you email skipped: invalid template_id=' . $template_id);
            return false;
        }

        $emailConfig = array(
            'protocol'    => 'smtp',
            'smtp_host'   => 'smtp.hostinger.com',
            'smtp_user'   => 'nlp@empoweryourdestiny.com.au',
            'smtp_pass'   => 'Franh1ve@2024',
            'smtp_port'   => 465,
            'smtp_crypto' => 'ssl',
            'smtp_timeout'=> 8,
            'mailtype'    => 'html',
            'charset'     => 'utf-8',
            'newline'     => "\r\n",
            'wordwrap'    => TRUE
        );

        $this->load->library('email');
        $this->email->clear(true);
        $this->email->initialize($emailConfig);
        $this->email->set_newline("\r\n");

        $template_vars = array(
            'name'          => $client_name,
            'link'          => '',
            'business_name' => $business_name,
            'sender_name'   => 'Empower Your Destiny',
        );

        $subject_tpl = trim((string)($email_template['TEMPLATE_SUBJECT'] ?? ''));
        if ($subject_tpl !== '') {
            $subject = apply_email_template_placeholders($subject_tpl, $template_vars);
        } else {
            $subject = 'Thank you for submitting your payment agreement';
        }

        $body = build_template_email_body($email_template, $template_vars);

        $this->email->from('nlp@empoweryourdestiny.com.au', 'Empower Your Destiny');
        $this->email->to($to_email);
        $this->email->subject($subject);
        $this->email->message($body);

        if ($this->email->send()) {
            return true;
        }

        log_message('error', 'Payment agreement thank-you email failed for request_id=' . (isset($request['id']) ? $request['id'] : 'n/a') . ': ' . $this->email->print_debugger());
        return false;
    }

    /**
     * Build token_expires_at from admin-selected duration (hours, days, or weeks).
     */
    protected function compute_token_expires_at($value, $unit, &$error = null)
    {
        $value = (int)$value;
        $unit  = strtolower(trim((string)$unit));

        if ($value <= 0) {
            $error = 'Link expiry duration must be at least 1.';
            return null;
        }

        if (!in_array($unit, array('hours', 'days', 'weeks'), true)) {
            $error = 'Please select a valid link expiry unit (hours, days, or weeks).';
            return null;
        }

        $max = array('hours' => 720, 'days' => 90, 'weeks' => 12);
        if ($value > $max[$unit]) {
            $error = 'Link expiry is too long. Maximum allowed: ' . $max[$unit] . ' ' . $unit . '.';
            return null;
        }

        return date('Y-m-d H:i:s', strtotime('+' . $value . ' ' . $unit));
    }
}