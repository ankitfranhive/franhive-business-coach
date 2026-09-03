<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminEforms extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Eforms_model', 'eforms');
        $this->load->model('Template_Model');
        $this->load->library(['form_validation','email']);
        // IMPORTANT: keep your permission checks here if needed (you said permissions are correct)
    }

    // ---------- Templates ----------
    public function templates() {
        $templates = $this->eforms->get_templates();
        foreach ($templates as &$t) {
            $code = $this->eforms->get_or_create_public_link_code((int)$t['id']);
            $t['public_link_code'] = $code;
            $t['public_link'] = $code ? base_url('eforms/open/' . $code) : '';
        }
        unset($t);

        $data['templates'] = $templates;
        $this->load->view('eforms/templates_list', $data);
    }

    public function template_create() {
        $data['form_types'] = $this->eforms->get_form_types(); // or $this->eforms->get_form_types()
        $this->load->view('eforms/template_create', $data);
    }
    

    public function ajax_form_type_fields($form_type_id) {
        $fields = $this->eforms->get_form_type_fields($form_type_id);
        $out = [];
        foreach ($fields as $f) {
            $out[] = [
                'name' => $f['name'],
                'label' => $f['label'],
                'type' => $f['type'],
                'is_required' => (int)$f['is_required'],
            ];
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($out));
    }

    public function template_store() {
        $this->form_validation->set_rules('form_type_id', 'Form Type', 'required');
        $this->form_validation->set_rules('slug', 'Slug', 'required');
        $this->form_validation->set_rules('title', 'Title', 'required');

        if (!$this->form_validation->run()) {
            return $this->template_create();
        }

        $field_names  = (array)$this->input->post('field_name');
        $label_over   = (array)$this->input->post('field_label_override');

        $label_map = [];
        for ($i=0; $i<count($field_names); $i++) {
            $n = trim($field_names[$i] ?? '');
            $l = trim($label_over[$i] ?? '');
            if ($n && $l) $label_map[$n] = $l;
        }

        $overrides = [
            'labels' => $label_map,
            'texts' => [
                'agree_checkbox_text' => $this->input->post('agree_checkbox_text', true),
            ]
        ];

        $tpl_id = $this->eforms->insert_template([
            'form_type_id' => (int)$this->input->post('form_type_id'),
            'slug' => $this->input->post('slug', true),
            'title' => $this->input->post('title', true),
            'heading' => $this->input->post('heading', true),
            'subheading' => $this->input->post('subheading', true),
            'body_html' => $this->input->post('body_html', false),
            'overrides_json' => json_encode($overrides, JSON_UNESCAPED_UNICODE),
            'is_active' => 1,
        ]);

        redirect('admin_eforms/send_form/'.$tpl_id);
    }

    // ---------- Form Types ----------
    public function form_types() {
        $data['form_types'] = $this->eforms->get_form_types();
        $this->load->view('eforms/form_types_list', $data);
    }

    public function form_type_create() {
        $this->load->view('eforms/form_type_create');
    }

    public function form_type_store() {
        $this->form_validation->set_rules('slug', 'Slug', 'required');
        $this->form_validation->set_rules('name', 'Name', 'required');
    
        if (!$this->form_validation->run()) {
            return $this->form_type_create();
        }
    
        $ft_id = $this->eforms->insert_form_type([
            'slug'        => $this->input->post('slug', true),
            'name'        => $this->input->post('name', true),
            'description' => $this->input->post('description', true),
            'is_active'   => 1
        ]);
    
        $names  = (array)$this->input->post('field_name');
        $labels = (array)$this->input->post('field_label');
        $types  = (array)$this->input->post('field_type');
        $reqs   = (array)$this->input->post('field_required'); // 0/1
        $orders = (array)$this->input->post('field_order');    // number
        $options= (array)$this->input->post('field_options');  // comma separated
    
        $pack = [];
        for ($i=0; $i<count($names); $i++) {
            $fname = trim($names[$i] ?? '');
            if ($fname === '') continue;
    
            $fname = preg_replace('/[^a-zA-Z0-9_]/', '_', $fname);
            $fname = preg_replace('/_+/', '_', $fname);
            $fname = trim($fname, '_');
    
            $flabel = trim($labels[$i] ?? '');
            if ($flabel === '') $flabel = $fname;
    
            $ftype = $types[$i] ?? 'text';
            $ftype = in_array($ftype, ['text','email','number','date','textarea','select','checkbox','radio','signature','file','image','video','section'])
                ? $ftype : 'text';
    
            $is_required = (isset($reqs[$i]) && ($reqs[$i] == '1' || $reqs[$i] === 1 || $reqs[$i] === 'on')) ? 1 : 0;
    
            $sort_order = (int)($orders[$i] ?? $i);
    
            $options_json = null;
            $optStr = trim($options[$i] ?? '');
            if ($optStr !== '' && in_array($ftype, ['select','radio','checkbox','image','video'])) {
                if (in_array($ftype, ['image','video'])) {
                    // Store as single-item array to preserve URL (don't split on commas)
                    $options_json = json_encode([$optStr], JSON_UNESCAPED_UNICODE);
                } else {
                    $arr = array_values(array_filter(array_map('trim', explode(',', $optStr))));
                    if (!empty($arr)) $options_json = json_encode($arr, JSON_UNESCAPED_UNICODE);
                }
            }
    
            $pack[] = [
                'name'         => $fname,
                'label'        => $flabel,
                'type'         => $ftype,
                'is_required'  => $is_required,
                'sort_order'   => $sort_order,
                'options_json' => $options_json,
                'placeholder'  => null,
                'help_text'    => null
            ];
        }
    
        $this->eforms->replace_form_type_fields($ft_id, $pack);
        redirect('admin_eforms/form_types');
    }
    
    

    public function form_type_edit($id) {
        $data['form_type'] = $this->eforms->get_form_type($id);
        if (!$data['form_type']) show_404();
    
        $data['fields'] = $this->eforms->get_form_type_fields($id);
    
        $this->load->view('eforms/form_type_edit', $data);
    }
    

    public function form_type_update($id) {
        $this->form_validation->set_rules('slug', 'Slug', 'required');
        $this->form_validation->set_rules('name', 'Name', 'required');
    
        if (!$this->form_validation->run()) {
            return $this->form_type_edit($id);
        }
    
        $this->eforms->update_form_type((int)$id, [
            'slug'        => $this->input->post('slug', true),
            'name'        => $this->input->post('name', true),
            'description' => $this->input->post('description', true),
        ]);
    
        $names  = (array)$this->input->post('field_name');
        $labels = (array)$this->input->post('field_label');
        $types  = (array)$this->input->post('field_type');
        $reqs   = (array)$this->input->post('field_required');
        $orders = (array)$this->input->post('field_order');
        $options= (array)$this->input->post('field_options');
    
        $pack = [];
        for ($i=0; $i<count($names); $i++) {
            $fname = trim($names[$i] ?? '');
            if ($fname === '') continue;
    
            $fname = preg_replace('/[^a-zA-Z0-9_]/', '_', $fname);
            $fname = preg_replace('/_+/', '_', $fname);
            $fname = trim($fname, '_');
    
            $flabel = trim($labels[$i] ?? '');
            if ($flabel === '') $flabel = $fname;
    
            $ftype = $types[$i] ?? 'text';
            $ftype = in_array($ftype, ['text','email','number','date','textarea','select','checkbox','radio','signature','file','image','video','section'])
                ? $ftype : 'text';
    
            $is_required = (isset($reqs[$i]) && ($reqs[$i] == '1' || $reqs[$i] === 1 || $reqs[$i] === 'on')) ? 1 : 0;
    
            $sort_order = (int)($orders[$i] ?? $i);
    
            $options_json = null;
            $optStr = trim($options[$i] ?? '');
            if ($optStr !== '' && in_array($ftype, ['select','radio','checkbox','image','video'])) {
                if (in_array($ftype, ['image','video'])) {
                    // Store as single-item array to preserve URL (don't split on commas)
                    $options_json = json_encode([$optStr], JSON_UNESCAPED_UNICODE);
                } else {
                    $arr = array_values(array_filter(array_map('trim', explode(',', $optStr))));
                    if (!empty($arr)) $options_json = json_encode($arr, JSON_UNESCAPED_UNICODE);
                }
            }
    
            $pack[] = [
                'name'         => $fname,
                'label'        => $flabel,
                'type'         => $ftype,
                'is_required'  => $is_required,
                'sort_order'   => $sort_order,
                'options_json' => $options_json,
                'placeholder'  => null,
                'help_text'    => null
            ];
        }
    
        $this->eforms->replace_form_type_fields((int)$id, $pack);
    
        $this->session->set_flashdata('success', 'Form Type updated successfully.');
        redirect('admin_eforms/form_types');
    }
    

    // ---------- Send Form (Request) ----------
    public function send_form($template_id) {
        [$tpl, $fields] = $this->eforms->get_template_with_fields($template_id);
        if (!$tpl) show_404();
    
        $data['template'] = $tpl;
        $data['fields']   = $fields;
        $data['email_templates'] = $this->Template_Model->get_all_templates();
    
        if ($this->input->method() === 'post') {
            $client_name  = $this->input->post('client_name', true);
            $client_email = $this->input->post('client_email', true);
            $email_template_id = (int)$this->input->post('email_template_id');
            $expiry_hours = (int)$this->input->post('expiry_hours');
            if ($expiry_hours <= 0) $expiry_hours = 24;
    
            $expires_at = date('Y-m-d H:i:s', time() + ($expiry_hours * 3600));
    
            // Optional prefill values (basic)
            $prefill = [
                'client_name'  => $client_name,
                'client_email' => $client_email,
                'confirmation_email_template_id' => $email_template_id > 0 ? $email_template_id : null,
            ];
            $prefill_json = json_encode($prefill);

            // Persist confirmation template onto the eForm template overrides
            // so the general/public Copy Link can also send the same confirmation email.
            if ($email_template_id > 0) {
                $overrides = json_decode($tpl['overrides_json'] ?? '{}', true) ?: [];
                $overrides['confirmation_email_template_id'] = $email_template_id;
                $this->eforms->update_template((int)$tpl['id'], [
                    'overrides_json' => json_encode($overrides, JSON_UNESCAPED_UNICODE),
                ]);
            }
    
            // Create request + link
            $req  = $this->eforms->create_request($tpl['id'], $client_name, $client_email, $prefill_json, $expires_at);
            $link = base_url('eforms/fill/' . $req['token']);
    
            // ---- SMTP CONFIG (Hostinger) : STARTTLS 587 ----
            $emailConfig = [
                'protocol'    => 'smtp',
                'smtp_host'   => 'smtp.hostinger.com',
                'smtp_user'   => 'nlp@empoweryourdestiny.com.au',
                'smtp_pass'   => 'Franh1ve@2024',
                'smtp_port'   => 465,
                'smtp_crypto' => 'ssl',
                'mailtype'    => 'html',
                'charset'     => 'utf-8',
                'newline'     => "\r\n",
                'wordwrap'     => TRUE,
            ];
    
            // Load/init email each time to avoid old config caching
            $this->load->library('email');
            $this->email->initialize($emailConfig);
            $this->email->set_newline("\r\n");
            $this->email->set_crlf("\r\n");
    
            // Email body (HTML)
            $subject = $tpl['title'] . ' - Please complete';
            $body = '
                <p>Hi <b>' . html_escape($client_name) . '</b>,</p>
                <p>Please complete the form using the secure link below:</p>
                <p><a href="' . $link . '" target="_blank">' . $link . '</a></p>
                <p><b>Expiry:</b> ' . html_escape($expires_at) . '</p>
                <p>Thanks,<br>Empower Your Destiny</p>
            ';
    
            $this->email->from('nlp@empoweryourdestiny.com.au', 'Empower Your Destiny');
            $this->email->to($client_email);
            $this->email->subject($subject);
            $this->email->message($body);
    
            // SEND ONCE (important)
            if (!$this->email->send()) {
                // show detailed error
                echo $this->email->print_debugger(['headers', 'subject', 'body']);
                exit;
            }
    
            $data['link']    = $link;
            $data['request'] = $req;
        }
    
        $this->load->view('eforms/send_form', $data);
    }
    

    public function requests() {
        $data['requests'] = $this->eforms->get_requests();
        $this->load->view('eforms/requests_list', $data);
    }


    public function template_edit($id) {
        $tpl = $this->eforms->get_template($id);
        if (!$tpl) show_404();
    
        // template + its form type fields
        [$template, $fields] = $this->eforms->get_template_with_fields($id);
    
        $data['template']  = $template; // array
        $data['fields']    = $fields;   // array
        $data['overrides'] = json_decode($template['overrides_json'] ?? '{}', true);
    
        $this->load->view('eforms/template_edit', $data);
    }
    
    public function template_update($id) {
        $tpl = $this->eforms->get_template($id);
        if (!$tpl) show_404();
    
        $this->form_validation->set_rules('slug', 'Slug', 'required');
        $this->form_validation->set_rules('title', 'Title', 'required');
    
        if (!$this->form_validation->run()) {
            return $this->template_edit($id);
        }
    
        $field_names = (array)$this->input->post('field_name');
        $label_over  = (array)$this->input->post('field_label_override');
        $field_secs  = (array)$this->input->post('field_section');

        // Build label overrides map
        $label_map = [];
        for ($i = 0; $i < count($field_names); $i++) {
            $n = trim($field_names[$i] ?? '');
            $l = trim($label_over[$i] ?? '');
            if ($n && $l) $label_map[$n] = $l;
        }

        // Build field → section map (skip empty assignments)
        $field_sections_map = [];
        for ($i = 0; $i < count($field_names); $i++) {
            $n = trim($field_names[$i] ?? '');
            $s = trim($field_secs[$i] ?? '');
            if ($n !== '' && $s !== '') $field_sections_map[$n] = $s;
        }

        // Build sections array from posted section_id[] + section_name[]
        $sec_ids   = (array)$this->input->post('section_id');
        $sec_names = (array)$this->input->post('section_name');
        $sections  = [];
        for ($i = 0; $i < count($sec_ids); $i++) {
            $sid  = trim($sec_ids[$i] ?? '');
            $snam = trim($sec_names[$i] ?? '');
            if ($sid !== '' && $snam !== '') {
                $sections[] = ['id' => $sid, 'name' => $snam];
            }
        }

        $overrides = [
            'labels'           => $label_map,
            'texts'            => [
                'agree_checkbox_text' => $this->input->post('agree_checkbox_text', true),
            ],
            'banner_image_url' => $this->input->post('banner_image_url', true) ?? '',
            'sections'         => $sections,
            'field_sections'   => $field_sections_map,
        ];
    
        $this->eforms->update_template($id, [
            'slug'         => $this->input->post('slug', true),
            'title'        => $this->input->post('title', true),
            'heading'      => $this->input->post('heading', true),
            'subheading'   => $this->input->post('subheading', true),
            'body_html'    => $this->input->post('body_html', false),
            'overrides_json' => json_encode($overrides, JSON_UNESCAPED_UNICODE),
        ]);
    
        $this->session->set_flashdata('success', 'Template updated successfully.');
        redirect('admin_eforms/templates');
    }
    public function template_delete($id) {
        $tpl = $this->eforms->get_template($id);
        if (!$tpl) show_404();
    
        $count = $this->eforms->template_requests_count($id);
        if ($count > 0) {
            $this->session->set_flashdata('error', 'Cannot delete: this template already has sent requests.');
            redirect('admin_eforms/templates');
            return;
        }
    
        $this->eforms->delete_template($id);
    
        $this->session->set_flashdata('success', 'Template deleted successfully.');
        redirect('admin_eforms/templates');
    }

    public function submissions() {
        $raw = $this->input->get('template_ids');
        if ($raw === null && isset($_GET['template_ids'])) {
            $raw = $_GET['template_ids'];
        }

        // Support: template_ids[]=1&template_ids[]=2  OR  template_ids=1,2  OR single value
        if (is_string($raw) && $raw !== '') {
            $raw = preg_split('/\s*,\s*/', $raw);
        }
        if (!is_array($raw)) {
            $raw = [];
        }

        $selected_template_ids = [];
        foreach ($raw as $value) {
            $id = (int)$value;
            if ($id > 0) {
                $selected_template_ids[] = $id;
            }
        }
        $selected_template_ids = array_values(array_unique($selected_template_ids));

        $templates = $this->eforms->get_templates();
        $selected_names = [];
        foreach ($templates as $template) {
            $tid = (int)($template['id'] ?? 0);
            if (in_array($tid, $selected_template_ids, true)) {
                $selected_names[] = $template['title'] ?? ('Template #' . $tid);
            }
        }

        $data['templates'] = $templates;
        $data['selected_template_ids'] = $selected_template_ids;
        $data['selected_template_names'] = $selected_names;
        $data['submissions'] = $this->eforms->get_submissions(500, $selected_template_ids);
        $data['total_count'] = is_array($data['submissions']) ? count($data['submissions']) : 0;
        $this->load->view('eforms/submissions_list', $data);
    }
    
    public function submission_view($id) {
        $submission = $this->eforms->get_submission($id);
        if (!$submission) show_404();
    
        $data['submission'] = $submission;
        $data['values'] = $this->eforms->get_submission_values($id);
    
        $this->load->view('eforms/submission_view', $data);
    }

    /**
     * Rebuild PDF with Basic + Audit details, then redirect to the file.
     * Keeps older submissions up to date with the latest PDF layout.
     */
    public function download_pdf($id) {
        $this->load->library('pdf');

        $submission = $this->eforms->get_submission($id);
        if (!$submission) show_404();

        $values = $this->eforms->get_submission_values($id);
        $data_for_pdf = [];
        foreach ($values as $row) {
            $label = $row['field_label'] ?? ($row['field_name'] ?? 'Field');
            $data_for_pdf[$label] = (string)($row['value_text'] ?? '');
        }

        $template = null;
        if (!empty($submission['static_form_slug'])) {
            [$template] = $this->eforms->get_static_form_config($submission['static_form_slug']);
        } elseif (!empty($submission['template_id'])) {
            $template = $this->eforms->get_template((int)$submission['template_id']);
        }
        if (!$template) {
            $template = [
                'title' => $submission['template_title'] ?? 'Form',
                'heading' => $submission['template_title'] ?? 'Form',
                'subheading' => '',
                'body_html' => '',
            ];
        }

        $request = [
            'client_name' => $submission['client_name'] ?? '',
            'client_email' => $submission['client_email'] ?? '',
        ];

        $client_name = trim((string)($submission['client_name'] ?? ''));
        $client_email = trim((string)($submission['client_email'] ?? ''));
        foreach ($values as $row) {
            $fname = strtolower(trim((string)($row['field_name'] ?? '')));
            if ($client_name === '' && in_array($fname, ['full_name', 'client_name', 'name', 'first_name'], true)) {
                $client_name = trim((string)($row['value_text'] ?? ''));
            }
            if ($client_email === '' && in_array($fname, ['email', 'client_email', 'email_address'], true)) {
                $client_email = trim((string)($row['value_text'] ?? ''));
            }
        }

        $pdf_html = $this->load->view('eforms/pdf/submission_pdf', [
            'template' => $template,
            'request' => $request,
            'data' => $data_for_pdf,
            'signature_path' => $submission['signature_path'] ?? null,
            'meta' => [
                'template_title' => $submission['template_title'] ?? ($template['title'] ?? 'Form'),
                'client_name' => $client_name,
                'client_email' => $client_email,
                'submitted_at' => $submission['created_at'] ?? date('Y-m-d H:i:s'),
                'ip_address' => $submission['ip_address'] ?? '',
                'user_agent' => $submission['user_agent'] ?? '',
            ],
        ], true);

        $pdf_binary = $this->pdf->create($pdf_html);

        $dir = FCPATH . 'uploads/eforms/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $file_name = 'submission_' . (int)$submission['id'] . '_' . time() . '.pdf';
        $pdf_path = 'uploads/eforms/' . $file_name;
        file_put_contents(FCPATH . $pdf_path, $pdf_binary);

        // Keep submission record pointing to latest PDF
        $this->db->where('id', (int)$id)->update('ef_submissions', [
            'pdf_path' => $pdf_path,
        ]);

        redirect(base_url($pdf_path));
    }

    // ---------- IICT Enrolment Agreement (static form) ----------

    /** Send IICT Enrolment Agreement to a client – creates link and emails it (like other eForms). */
    public function send_iict_form() {
        $slug = $this->eforms::STATIC_FORM_IICT;
        list($tpl, $fields) = $this->eforms->get_static_form_config($slug);
        if (!$tpl) {
            $this->session->set_flashdata('error', 'IICT form config not found.');
            redirect('admin_eforms/templates');
            return;
        }
        $data['template'] = $tpl;
        $data['form_title'] = $tpl['title'] ?? 'IICT Enrolment and Payment Agreement';

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('client_name', 'Client Name', 'required');
            $this->form_validation->set_rules('client_email', 'Client Email', 'required|valid_email');
            if ($this->form_validation->run()) {
                $client_name  = $this->input->post('client_name', true);
                $client_email = $this->input->post('client_email', true);
                $expiry_hours = (int)$this->input->post('expiry_hours');
                if ($expiry_hours <= 0) $expiry_hours = 24;
                $expires_at = date('Y-m-d H:i:s', time() + ($expiry_hours * 3600));
                $prefill = ['full_name' => $client_name, 'email' => $client_email];
                $prefill_json = json_encode($prefill, JSON_UNESCAPED_UNICODE);

                $req = $this->eforms->create_request(0, $client_name, $client_email, $prefill_json, $expires_at, $slug);
                $link = base_url('eforms/fill/' . $req['token']);

                $emailConfig = [
                    'protocol' => 'smtp', 'smtp_host' => 'smtp.hostinger.com',
                    'smtp_user' => 'nlp@empoweryourdestiny.com.au', 'smtp_pass' => 'Franh1ve@2024',
                    'smtp_port' => 465, 'smtp_crypto' => 'ssl', 'mailtype' => 'html', 'charset' => 'utf-8',
                    'newline' => "\r\n", 'wordwrap' => TRUE,
                ];
                $this->load->library('email');
                $this->email->initialize($emailConfig);
                $this->email->set_newline("\r\n");
                $subject = ($tpl['title'] ?? 'IICT Enrolment and Payment Agreement') . ' - Please complete';
                $body = '<p>Hi <b>' . html_escape($client_name) . '</b>,</p>';
                $body .= '<p>Please complete the IICT Enrolment and Payment Agreement using the secure link below:</p>';
                $body .= '<p><a href="' . $link . '" target="_blank">' . $link . '</a></p>';
                $body .= '<p><b>Expiry:</b> ' . html_escape($expires_at) . '</p>';
                $body .= '<p>Thanks,<br>Empower Your Destiny</p>';
                $this->email->from('nlp@empoweryourdestiny.com.au', 'Empower Your Destiny');
                $this->email->to($client_email);
                $this->email->subject($subject);
                $this->email->message($body);
                if ($this->email->send()) {
                    $data['link'] = $link;
                    $data['request'] = $req;
                    $data['success'] = true;
                } else {
                    $data['error'] = 'Email could not be sent. Link: ' . $link;
                }
            }
        }

        $this->load->view('eforms/send_iict_form', $data);
    }

    /** Edit IICT form content and fields (saved to DB; overrides config file). */
    public function iict_form_edit() {
        $slug = $this->eforms::STATIC_FORM_IICT;
        list($tpl, $fields) = $this->eforms->get_static_form_config($slug);
        if (!$tpl) {
            show_404();
            return;
        }
        $data['template'] = $tpl;
        $data['fields']   = $fields;
        $data['slug']     = $slug;
        $this->load->view('eforms/iict_form_edit', $data);
    }

    public function iict_form_update() {
        $slug = $this->eforms::STATIC_FORM_IICT;
        $this->form_validation->set_rules('title', 'Title', 'required');
        if (!$this->form_validation->run()) {
            return $this->iict_form_edit();
        }
        $template = [
            'id' => 0, 'form_type_id' => 0, 'slug' => 'iict-enrolment-agreement',
            'title'       => $this->input->post('title', true),
            'heading'     => $this->input->post('heading', true),
            'subheading'  => $this->input->post('subheading', true),
            'body_html'   => $this->input->post('body_html', false),
            'overrides_json' => '{}',
            'agree_text'  => $this->input->post('agree_text', true),
        ];
        $names   = (array)$this->input->post('field_name');
        $labels  = (array)$this->input->post('field_label');
        $types   = (array)$this->input->post('field_type');
        $reqs    = (array)$this->input->post('field_required');
        $orders  = (array)$this->input->post('field_order');
        $fields = [];
        $allowed_types = ['text','email','number','date','textarea','select','checkbox','radio','image','video','section'];
        foreach ($names as $i => $n) {
            $n = trim($n ?? '');
            if ($n === '') continue;
            $n = preg_replace('/[^a-zA-Z0-9_]/', '_', $n);
            $n = preg_replace('/_+/', '_', trim($n, '_'));
            $label = trim($labels[$i] ?? $n);
            $type = in_array($types[$i] ?? 'text', $allowed_types) ? $types[$i] : 'text';
            $fields[] = [
                'name' => $n, 'label' => $label, 'type' => $type,
                'is_required' => (isset($reqs[$i]) && ($reqs[$i] == '1' || $reqs[$i] === 'on')) ? 1 : 0,
                'sort_order' => (int)($orders[$i] ?? $i), 'options_json' => null,
            ];
        }
        usort($fields, function ($a, $b) { return $a['sort_order'] <=> $b['sort_order']; });
        $config = ['template' => $template, 'fields' => $fields];
        if ($this->eforms->save_static_form_to_db($slug, $config)) {
            $this->session->set_flashdata('success', 'IICT form updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Could not save (table ef_static_forms may not exist). Run the SQL to create it.');
        }
        redirect('admin_eforms/iict_form_edit');
    }

    /**
 * Callback: validates that at least one checkbox was selected in a checkbox group.
 * CI's built-in `required` rule does not handle array fields (name[]).
 */
public function required_checkbox($val, $field_name) {
    $posted = $this->input->post($field_name);
    if (empty($posted)) {
        $this->form_validation->set_message(
            'required_checkbox',
            'The {field} field requires at least one selection.'
        );
        return false;
    }
    return true;
}
}