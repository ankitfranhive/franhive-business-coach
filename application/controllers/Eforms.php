<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eforms extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Eforms_model', 'eform');   // must be your array-returning model
    $this->load->model('User_Model');
    $this->load->model('Template_Model');
    $this->load->library(['form_validation','pdf']);
    $this->load->helper('email');
  }

  /**
   * Public/general form link.
   * Accepts a short opaque code (preferred) or numeric template ID (legacy).
   * Creates a fresh short-token request, then redirects to the fill URL.
   */
  public function open($code) {
    $code = trim((string)$code);
    $tpl = null;

    if (ctype_digit($code)) {
      // Legacy support for /eforms/open/14
      [$tpl] = $this->eform->get_template_with_fields((int)$code);
    } else {
      $tpl = $this->eform->get_template_by_public_link_code($code);
    }

    if (!$tpl || empty($tpl['id'])) {
      show_404();
      return;
    }

    // Carry confirmation email + thank-you page template from template overrides, if configured.
    $overrides = json_decode($tpl['overrides_json'] ?? '{}', true) ?: [];
    $confirmation_template_id = (int)($overrides['confirmation_email_template_id'] ?? 0);
    $thank_you_page_template_id = (int)($overrides['thank_you_page_template_id'] ?? 0);

    $prefill = [];
    if ($confirmation_template_id > 0) {
      $prefill['confirmation_email_template_id'] = $confirmation_template_id;
    }
    if ($thank_you_page_template_id > 0) {
      $prefill['thank_you_page_template_id'] = $thank_you_page_template_id;
    }

    // Long-lived expiry so the public/general link stays reusable.
    $expires_at = date('Y-m-d H:i:s', strtotime('+10 years'));

    $req = $this->eform->create_request(
      (int)$tpl['id'],
      '',
      '',
      json_encode($prefill, JSON_UNESCAPED_UNICODE),
      $expires_at
    );

    redirect('eforms/fill/' . $req['token']);
  }

  public function fill($token) {

    $req = $this->eform->get_request_by_token($token); // should return row_array()
    if (!$req) show_404();

    // Expiry check
    if (!empty($req['expires_at']) && strtotime($req['expires_at']) < time()) {
      $this->load->view('eforms_public/message', [
        'message' => 'This link has expired. Please contact Empower Your Destiny for a new link.'
      ]);
      return;
    }

    // Status check
    if (($req['status'] ?? '') === 'submitted') {
      $this->load->view('eforms_public/message', [
        'message' => 'This form has already been submitted. Thank you.'
      ]);
      return;
    }

    // Template + fields (from DB or static config when request has static_form_slug)
    [$tpl, $fields] = $this->eform->get_template_with_type_fields($req['template_id'], $req);
    if (!$tpl) show_404();

    $overrides = json_decode($tpl['overrides_json'] ?? '{}', true) ?: [];
    $prefill   = json_decode($req['prefill_json'] ?? '{}', true);

    // ------------------------------------------------------------------
    // VALIDATION RULES
    // NOTE: Checkbox groups (multi-option) are intentionally excluded here
    // because CI's form_validation binds field name WITHOUT [] and never
    // receives the actual array value. We validate them manually below.
    // ------------------------------------------------------------------
    foreach ($fields as $f) {
      // Image and video fields are static display content — skip validation entirely.
      if (in_array($f['type'] ?? '', ['image', 'video', 'section'])) continue;

      $rules = [];

      if ((int)$f['is_required'] === 1) {
        if (($f['type'] ?? '') === 'checkbox') {
          // Skip — handled manually via $checkbox_errors below
          // (CI can't correctly validate array POST fields with set_rules)
        } else {
          $rules[] = 'required';
        }
      }

      if (($f['type'] ?? '') === 'email') $rules[] = 'valid_email';

      if (!empty($rules)) {
        $label = $overrides['labels'][$f['name']] ?? $f['label'];
        $this->form_validation->set_rules($f['name'], $label, $rules);
      }
    }

    // Only require "agree" if the view renders the agreement checkbox.
    if (!empty($tpl['agree_text'])) {
      $this->form_validation->set_rules('agree', 'Agreement', 'required');
    }

    // ------------------------------------------------------------------
    // MANUAL CHECKBOX REQUIRED VALIDATION
    // CI's form_validation cannot handle array POST fields (name[]) so we
    // check required multi-checkbox groups directly from raw POST data.
    // ------------------------------------------------------------------
    $checkbox_errors = [];
    if ($this->input->method() === 'post') {
      foreach ($fields as $f) {
        if (
          ($f['type'] ?? '') === 'checkbox' &&
          (int)($f['is_required']) === 1
        ) {
          $options = [];
          if (!empty($f['options_json'])) {
            $decoded = json_decode($f['options_json'], true);
            if (is_array($decoded)) $options = $decoded;
          }

          // Only validate multi-option checkbox groups
          if (!empty($options)) {
            $posted = $this->input->post($f['name']); // returns array or null
            if (empty($posted)) {
              $label = $overrides['labels'][$f['name']] ?? $f['label'];
              $checkbox_errors[$f['name']] = 'The "' . $label . '" field requires at least one selection.';
            }
          }
        }
      }
    }

    // POST submit
    if ($this->input->method() === 'post') {

      $ci_passed = $this->form_validation->run();

      // ------------------------------------------------------------------
      // REPOPULATE checkbox values into $prefill on ANY failure so the
      // view re-checks the correct boxes when re-rendering the form.
      // ------------------------------------------------------------------
      if (!$ci_passed || !empty($checkbox_errors)) {
        foreach ($fields as $f) {
          if (($f['type'] ?? '') === 'checkbox') {
            $posted = $this->input->post($f['name']);
            $prefill[$f['name']] = is_array($posted) ? $posted : [];
          }
        }
      }

      if ($ci_passed && empty($checkbox_errors)) {

        // signature (only when template enables signature box)
        $enable_signature = !isset($overrides['enable_signature'])
          || (string)$overrides['enable_signature'] === '1'
          || $overrides['enable_signature'] === 1
          || $overrides['enable_signature'] === true;

        $signature_data = $this->input->post('signature_data');
        $signature_path = null;
        if ($enable_signature && !empty($signature_data)) {
          $signature_path = $this->save_signature_png($signature_data, $req['id']);
        }

        $values = [];
        $data_for_pdf = [];

        foreach ($fields as $f) {
          // Image, video and section fields are static display content — skip saving.
          if (in_array($f['type'] ?? '', ['image', 'video', 'section'])) continue;

          $posted = $this->input->post($f['name'], true);
          if (is_array($posted)) $posted = implode(', ', $posted);

          $label = $overrides['labels'][$f['name']] ?? $f['label'];

          $values[] = [
            'field_name'  => $f['name'],
            'field_label' => $label,
            'value_text'  => (string)$posted,
          ];

          $data_for_pdf[$label] = (string)$posted;
        }

        $data_for_pdf['Agreement'] = $this->input->post('agree', true) ? 'Accepted' : '';

        $ip         = $this->input->ip_address();
        $user_agent = substr((string)$this->input->user_agent(), 0, 255);
        $submitted_at = date('Y-m-d H:i:s');

        // Prefer submitted form email/name when available (public/general links)
        $pdf_client_name = trim((string)($req['client_name'] ?? ''));
        $pdf_client_email = trim((string)($req['client_email'] ?? ''));
        foreach ($values as $row) {
          $fname = strtolower(trim((string)($row['field_name'] ?? '')));
          if ($pdf_client_name === '' && in_array($fname, ['full_name', 'client_name', 'name', 'first_name'], true)) {
            $pdf_client_name = trim((string)($row['value_text'] ?? ''));
          }
          if ($pdf_client_email === '' && in_array($fname, ['email', 'client_email', 'email_address'], true)) {
            $pdf_client_email = trim((string)($row['value_text'] ?? ''));
          }
        }

        // PDF HTML — client copy (no Audit/Proof section) for email $PDF$ link
        $pdf_html = $this->load->view('eforms/pdf/submission_pdf', [
          'template' => $tpl,
          'request'  => $req,
          'data'     => $data_for_pdf,
          'signature_path' => $signature_path,
          'include_audit' => false,
          'meta' => [
            'template_title' => $tpl['title'] ?? ($tpl['heading'] ?? 'Form'),
            'client_name'    => $pdf_client_name,
            'client_email'   => $pdf_client_email,
            'submitted_at'   => $submitted_at,
            'ip_address'     => $ip,
            'user_agent'     => $user_agent,
          ],
        ], true);

        $pdf_binary = $this->pdf->create($pdf_html);

        // Save PDF
        $dir = FCPATH.'uploads/eforms/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $file_name = 'submission_'.$req['id'].'_'.time().'.pdf';
        $pdf_path  = 'uploads/eforms/'.$file_name;
        file_put_contents(FCPATH.$pdf_path, $pdf_binary);

        $signature_sha = null;
        if ($signature_path && file_exists(FCPATH.$signature_path)) {
          $signature_sha = hash_file('sha256', FCPATH.$signature_path);
        }

        $pdf_sha = null;
        if ($pdf_path && file_exists(FCPATH.$pdf_path)) {
          $pdf_sha = hash_file('sha256', FCPATH.$pdf_path);
        }

        $referer     = $this->input->server('HTTP_REFERER', true);
        $accept_lang = $this->input->server('HTTP_ACCEPT_LANGUAGE', true);

        $client_tz   = $this->input->post('client_timezone', true);
        $client_iso  = $this->input->post('client_time_iso', true);
        $screen_res  = $this->input->post('screen_resolution', true);

        $static_slug = isset($req['static_form_slug']) ? $req['static_form_slug'] : null;
        $this->eform->create_submission($req['id'], (int)$tpl['id'], $values, $signature_path, $pdf_path, $static_slug);

        $this->eform->mark_submitted($req['id']);
        $this->send_submission_confirmation_email($req, $values, $pdf_path);

        $this->load->view('eforms_public/thank_you', [
          'thank_you_template' => $this->resolve_thank_you_page_template($req, $tpl),
        ]);
        return;
      }
    }

    // GET view (also shown on failed validation)
    $this->load->view('eforms_public/fill', [
      'template'        => $tpl,
      'fields'          => $fields,
      'overrides'       => $overrides,
      'prefill'         => $prefill,
      'agree_text'      => $tpl['agree_text'] ?? ($overrides['texts']['agree_checkbox_text'] ?? 'I have read and agree.'),
      'checkbox_errors' => $checkbox_errors ?? [],   // ← consumed by fill view to show inline errors
    ]);
  }

  // -----------------------------------------------------------------------
  // NOTE: required_checkbox() callback is kept here for reference but is
  // no longer used for rule-based validation (see manual check above).
  // It is safe to leave it or remove it.
  // -----------------------------------------------------------------------
  public function required_checkbox($value)
  {
    if (is_array($value)) {
      return !empty($value);
    }
    return trim((string)$value) !== '';
  }

  private function send_submission_confirmation_email($req, $values, $pdf_path = null)
  {
    $prefill = json_decode($req['prefill_json'] ?? '{}', true);
    $email_template_id = (int)($prefill['confirmation_email_template_id'] ?? 0);
    if ($email_template_id <= 0) {
      return;
    }

    $email = $this->extract_submission_value($values, ['email', 'client_email', 'email_address']);
    if ($email === '') {
      $email = trim((string)($req['client_email'] ?? ''));
    }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      log_message('error', 'Eform confirmation email skipped: no valid email found for request ' . (int)($req['id'] ?? 0));
      return;
    }

    $name = $this->extract_submission_value($values, ['full_name', 'client_name', 'name']);
    if ($name === '') {
      $name = trim((string)($req['client_name'] ?? ''));
    }
    $mobile = $this->extract_submission_value($values, ['mobile', 'phone', 'contact_number_mobile', 'phone_number']);

    $password = '';
    $user = $this->find_confirmation_user($email, $mobile);
    if (!empty($user)) {
      $password = trim((string)($user['PASSWORD'] ?? ''));
    } else {
      $password = 'Eyd2026';
      $user_data = [
        'NAME'       => $name !== '' ? $name : $email,
        'MOBILE'     => $mobile,
        'EMAIL'      => $email,
        'PASSWORD'   => $password,
        'CREATED_ON' => date('Y-m-d H:i:s'),
      ];
      $user_id = $this->User_Model->create_user($user_data);
      if (!$user_id) {
        log_message('error', 'Eform confirmation email: failed to create user for ' . $email);
        return;
      }
    }

    if ($password === '') {
      $password = 'Eyd2026';
    }

    $email_template = $this->Template_Model->get_template_by_id($email_template_id);
    if (empty($email_template)) {
      log_message('error', 'Eform confirmation email: template not found: ' . $email_template_id);
      return;
    }

    $subject = (string)($email_template['TEMPLATE_SUBJECT'] ?? 'Your registration is confirmed');
    $message_body = (string)($email_template['TEMPLATE_BODY'] ?? '');
    $portal_link = 'https://eyd.franhive.com/login';
    $pdf_url = '';
    if (!empty($pdf_path)) {
      $pdf_url = base_url(ltrim((string)$pdf_path, '/'));
    }

    $placeholder_search = [
      '[User Email]', '[UserEmail]', '{{user_email}}', '{{email}}', '$Email$', '$EMAIL$',
      '[User Password]', '[Password]', '{{user_password}}', '{{password}}',
      '{{name}}', '$Name$', '$NAME$',
      '{{training_link}}', '{{portal_link}}',
      '$PDF$', '$Pdf$', '$pdf$',
    ];
    $placeholder_replace = [
      $email, $email, $email, $email, $email, $email,
      $password, $password, $password, $password,
      $name, $name, $name,
      $portal_link, $portal_link,
      $pdf_url, $pdf_url, $pdf_url,
    ];

    $subject = str_replace($placeholder_search, $placeholder_replace, $subject);
    $message_body = str_replace($placeholder_search, $placeholder_replace, $message_body);

    if (!empty($email_template['TEMPLATE_SIGN'])) {
      $signature_html = '<br><br><div style="display:inline-block;">';
      $signature_html .= '<img src="https://eyd.franhive.com/uploads/headshot4.png" style="max-width:200px;max-height:100px;display:block;" alt="Signature">';
      $signature_html .= '<div style="margin-top:2px;">' . nl2br($email_template['TEMPLATE_SIGN']) . '</div>';
      $signature_html .= '</div>';
      $message_body .= $signature_html;
    }

    $attachment_paths = [];
    if (!empty($email_template['ATTACHMENTS'])) {
      $attachments = explode(',', $email_template['ATTACHMENTS']);
      foreach ($attachments as $file) {
        $full_path = FCPATH . 'uploads/' . trim($file);
        if (file_exists($full_path)) {
          $attachment_paths[] = $full_path;
        }
      }
    }

    $this->load->library('email');
    $this->email->clear(true);
    $this->email->initialize([
      'protocol'    => 'smtp',
      'smtp_host'   => 'smtp.hostinger.com',
      'smtp_user'   => 'nlp@empoweryourdestiny.com.au',
      'smtp_pass'   => 'Franh1ve@2024',
      'smtp_port'   => 465,
      'smtp_crypto' => 'ssl',
      'mailtype'    => 'html',
      'charset'     => 'utf-8',
      'newline'     => "\r\n",
      'wordwrap'    => true,
    ]);
    $this->email->set_newline("\r\n");
    $this->email->set_crlf("\r\n");
    $this->email->from(EMAIL_CONFIG_EMAIL, 'Barinderjeet Kaur');
    $this->email->to($email);
    $this->email->subject($subject);
    $this->email->message($message_body);

    foreach ($attachment_paths as $attachment_path) {
      $this->email->attach($attachment_path);
    }

    if (!$this->email->send()) {
      log_message('error', 'Eform confirmation email failed for ' . $email . ': ' . $this->email->print_debugger());
    }
  }

  private function resolve_thank_you_page_template($req, $tpl = null)
  {
    $prefill = json_decode($req['prefill_json'] ?? '{}', true) ?: [];
    $id = (int)($prefill['thank_you_page_template_id'] ?? 0);

    if ($id <= 0 && !empty($tpl)) {
      $overrides = json_decode($tpl['overrides_json'] ?? '{}', true) ?: [];
      $id = (int)($overrides['thank_you_page_template_id'] ?? 0);
    }

    if ($id <= 0) {
      return null;
    }

    // Active-only: deactivated templates fall back to the built-in default page.
    $row = $this->eform->get_thank_you_page_template($id, true);
    if (empty($row) || trim((string)($row['body_html'] ?? '')) === '') {
      return null;
    }

    return $row;
  }

  private function extract_submission_value($values, $field_names)
  {
    foreach ($values as $row) {
      $field_name = strtolower(trim((string)($row['field_name'] ?? '')));
      foreach ($field_names as $candidate) {
        if ($field_name === strtolower($candidate)) {
          return trim((string)($row['value_text'] ?? ''));
        }
      }
    }
    return '';
  }

  private function find_confirmation_user($email, $mobile)
  {
    try {
      $user = $this->User_Model->get_user_by_email($email);
      if (!empty($user)) {
        return $user;
      }
    } catch (\Exception $e) {
      // Ignore "not found" and fall back to mobile lookup below.
    }

    $mobile = trim((string)$mobile);
    if ($mobile !== '') {
      return $this->User_Model->get_user_by_email_or_mobile($email, $mobile);
    }

    return [];
  }

  private function save_signature_png($dataUrl, $request_id) {
    if (strpos($dataUrl, 'data:image/png;base64,') !== 0) return null;

    $b64 = str_replace('data:image/png;base64,', '', $dataUrl);
    $bin = base64_decode($b64);
    if (!$bin) return null;

    $dir = FCPATH.'uploads/eforms/signatures/';
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $name = 'sig_'.$request_id.'_'.time().'.png';
    $path = 'uploads/eforms/signatures/'.$name;
    file_put_contents(FCPATH.$path, $bin);
    return $path;
  }

}