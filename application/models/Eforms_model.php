<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eforms_model extends CI_Model {

    /** Slug for the static IICT Enrolment and Payment Agreement form */
    const STATIC_FORM_IICT = 'iict_enrolment_agreement';

    // ---------- Static form config (DB override > config file > defaults) ----------
    public function get_static_form_config($slug) {
        $all = $this->get_static_form_from_db($slug);
        if (empty($all) || !is_array($all)) {
            $this->config->load('iict_enrolment_form', false, true);
            $all = $this->config->item($slug);
            if (empty($all) || !is_array($all)) {
                $all = $this->config->item($slug, 'iict_enrolment_form');
            }
        }
        if (empty($all) || !is_array($all)) {
            $all = $this->get_static_form_iict_defaults();
        }
        if (empty($all) || !is_array($all)) {
            return [null, []];
        }
        $template = isset($all['template']) ? $all['template'] : [];
        $fields   = isset($all['fields']) ? $all['fields'] : [];
        return [$template, $fields];
    }

    public function get_static_form_from_db($slug) {
        if (!$this->db->table_exists('ef_static_forms')) {
            return null;
        }
        $row = $this->db->get_where('ef_static_forms', ['slug' => $slug])->row_array();
        if (empty($row) || empty($row['config_json'])) {
            return null;
        }
        $decoded = json_decode($row['config_json'], true);
        return is_array($decoded) ? $decoded : null;
    }

    public function save_static_form_to_db($slug, $config) {
        if (!$this->db->table_exists('ef_static_forms')) {
            return false;
        }
        $data = [
            'config_json' => json_encode($config, JSON_UNESCAPED_UNICODE),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];
        $exists = $this->db->get_where('ef_static_forms', ['slug' => $slug])->row_array();
        if ($exists) {
            $this->db->where('slug', $slug)->update('ef_static_forms', $data);
        } else {
            $data['slug'] = $slug;
            $data['created_at'] = $data['updated_at'];
            $this->db->insert('ef_static_forms', $data);
        }
        return true;
    }

    /** Default IICT form definition when config file is missing or not loaded */
    private function get_static_form_iict_defaults() {
        return [
            'template' => [
                'id' => 0, 'form_type_id' => 0, 'slug' => 'iict-enrolment-agreement',
                'title' => 'IICT Enrolment and Payment Agreement',
                'heading' => 'IICT Enrolment and Payment Agreement',
                'subheading' => 'Please complete all fields and sign below.',
                'body_html' => '<p>By completing this form you agree to the terms of enrolment and payment as set out in the IICT Enrolment and Payment Agreement.</p>',
                'overrides_json' => '{}',
                'agree_text' => 'I have read and agree to the IICT Enrolment and Payment Agreement terms and conditions.',
            ],
            'fields' => [
                ['name' => 'full_name', 'label' => 'Full Name', 'type' => 'text', 'is_required' => 1, 'sort_order' => -10, 'options_json' => null],
                ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'is_required' => 1, 'sort_order' => -9, 'options_json' => null],
                ['name' => 'phone', 'label' => 'Phone', 'type' => 'text', 'is_required' => 1, 'sort_order' => -8, 'options_json' => null],
                ['name' => 'address', 'label' => 'Address', 'type' => 'textarea', 'is_required' => 1, 'sort_order' => -7, 'options_json' => null],
                ['name' => 'city', 'label' => 'City / Suburb', 'type' => 'text', 'is_required' => 1, 'sort_order' => -6, 'options_json' => null],
                ['name' => 'state', 'label' => 'State / Territory', 'type' => 'text', 'is_required' => 1, 'sort_order' => -5, 'options_json' => null],
                ['name' => 'postcode', 'label' => 'Postcode', 'type' => 'text', 'is_required' => 1, 'sort_order' => -4, 'options_json' => null],
                ['name' => 'date_of_birth', 'label' => 'Date of Birth', 'type' => 'date', 'is_required' => 1, 'sort_order' => -3, 'options_json' => null],
                ['name' => 'course_program', 'label' => 'Course / Program Name', 'type' => 'text', 'is_required' => 1, 'sort_order' => -2, 'options_json' => null],
                ['name' => 'emergency_contact', 'label' => 'Emergency Contact Name', 'type' => 'text', 'is_required' => 0, 'sort_order' => 0, 'options_json' => null],
                ['name' => 'emergency_phone', 'label' => 'Emergency Contact Phone', 'type' => 'text', 'is_required' => 0, 'sort_order' => 1, 'options_json' => null],
            ],
        ];
    }

    // ---------- Form Types ----------
    public function get_form_types() {
        return $this->db->order_by('id','DESC')->get('ef_form_types')->result_array();
    }

    public function get_form_type($id) {
        return $this->db->get_where('ef_form_types', ['id' => (int)$id])->row_array();
    }

    public function insert_form_type($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('ef_form_types', $data);
        return $this->db->insert_id();
    }

    public function update_form_type($id, $data) {
        $this->db->where('id', (int)$id)->update('ef_form_types', $data);
    }

    public function get_form_type_fields($form_type_id) {
        return $this->db->order_by('sort_order','ASC')
            ->get_where('ef_form_type_fields', ['form_type_id' => (int)$form_type_id])
            ->result_array();
    }

    public function replace_form_type_fields($form_type_id, $fields) {
        $this->db->where('form_type_id', (int)$form_type_id)->delete('ef_form_type_fields');
        foreach ($fields as $f) {
            $f['form_type_id'] = (int)$form_type_id;
            $this->db->insert('ef_form_type_fields', $f);
        }
    }

    // ---------- Templates ----------
    public function get_templates() {
        $this->db->select('t.*, ft.name AS form_type_name');
        $this->db->from('ef_templates t');
        $this->db->join('ef_form_types ft', 'ft.id=t.form_type_id', 'left');
        $this->db->order_by('t.id','DESC');
        return $this->db->get()->result_array();
    }

    public function insert_template($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('ef_templates', $data);
        return $this->db->insert_id();
    }

    public function get_template($id) {
        return $this->db->get_where('ef_templates', ['id' => (int)$id])->row_array();
    }

    public function get_template_with_fields($template_id) {
        $tpl = $this->get_template($template_id);
        if (!$tpl) return [null, []];
        $fields = $this->get_form_type_fields($tpl['form_type_id']);
        return [$tpl, $fields];
    }

    // ---------- Requests ----------
    public function create_request($template_id, $client_name, $client_email, $prefill_json, $expires_at, $static_form_slug = null) {
        $token = $this->generate_unique_code(10, 'ef_requests', 'token');
        $data = [
            'template_id' => (int)$template_id,
            'client_name' => $client_name,
            'client_email' => $client_email,
            'prefill_json' => $prefill_json,
            'token' => $token,
            'status' => 'sent',
            'expires_at' => $expires_at,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        if ($static_form_slug !== null && $static_form_slug !== '') {
            $data['static_form_slug'] = $static_form_slug;
        }
        $this->db->insert('ef_requests', $data);
        $data['id'] = $this->db->insert_id();
        return $data;
    }

    /**
     * Ensure each template has a short opaque public link code (not the numeric ID).
     * Stored in overrides_json.public_link_code
     */
    public function get_or_create_public_link_code($template_id) {
        $tpl = $this->get_template($template_id);
        if (!$tpl) return null;

        $overrides = json_decode($tpl['overrides_json'] ?? '{}', true) ?: [];
        $code = trim((string)($overrides['public_link_code'] ?? ''));
        if ($code !== '' && preg_match('/^[A-Za-z0-9]{6,20}$/', $code)) {
            return $code;
        }

        // Generate unique short code and persist on template overrides.
        do {
            $code = $this->generate_unique_code(8);
        } while ($this->get_template_by_public_link_code($code));

        $overrides['public_link_code'] = $code;
        $this->update_template((int)$template_id, [
            'overrides_json' => json_encode($overrides, JSON_UNESCAPED_UNICODE),
        ]);

        return $code;
    }

    public function get_template_by_public_link_code($code) {
        $code = trim((string)$code);
        if ($code === '') return null;

        $rows = $this->db->select('id, overrides_json')->get('ef_templates')->result_array();
        foreach ($rows as $row) {
            $overrides = json_decode($row['overrides_json'] ?? '{}', true) ?: [];
            if (!empty($overrides['public_link_code']) && hash_equals((string)$overrides['public_link_code'], $code)) {
                return $this->get_template((int)$row['id']);
            }
        }
        return null;
    }

    private function generate_unique_code($length = 10, $table = null, $column = null) {
        $alphabet = 'abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $max = strlen($alphabet) - 1;

        for ($attempt = 0; $attempt < 20; $attempt++) {
            $code = '';
            $bytes = random_bytes($length);
            for ($i = 0; $i < $length; $i++) {
                $code .= $alphabet[ord($bytes[$i]) % ($max + 1)];
            }

            if ($table && $column) {
                $exists = $this->db->where($column, $code)->count_all_results($table) > 0;
                if (!$exists) return $code;
            } else {
                return $code;
            }
        }

        // Extremely unlikely fallback
        return bin2hex(random_bytes((int)ceil($length / 2)));
    }

    public function get_requests() {
        $this->db->select('r.*, t.title AS template_title, r.static_form_slug');
        $this->db->from('ef_requests r');
        $this->db->join('ef_templates t', 't.id=r.template_id', 'left');
        $this->db->order_by('r.id','DESC');
        return $this->db->get()->result_array();
    }

    public function get_request_by_token($token) {
        return $this->db->get_where('ef_requests', ['token' => $token])->row_array();
    }

    public function mark_submitted($request_id) {
        $this->db->where('id', (int)$request_id)->update('ef_requests', [
            'status' => 'submitted',
            'submitted_at' => date('Y-m-d H:i:s')
        ]);
    }

    // ---------- Submission ----------
    public function create_submission($request_id, $template_id, $values, $signature_path, $pdf_path, $static_form_slug = null) {

        $insert = [
            'request_id'      => (int)$request_id,
            'template_id'     => (int)$template_id,
            'ip_address'      => $this->input->ip_address(),
            'user_agent'      => substr((string)$this->input->user_agent(), 0, 255),
            'signature_path'  => $signature_path,
            'pdf_path'        => $pdf_path,
            'created_at'      => date('Y-m-d H:i:s'),
        ];
        if ($static_form_slug !== null && $static_form_slug !== '') {
            $insert['static_form_slug'] = $static_form_slug;
        }
        $this->db->insert('ef_submissions', $insert);
        $submission_id = $this->db->insert_id();
    
        foreach ($values as $row) {
            $this->db->insert('ef_submission_values', [
                'submission_id' => (int)$submission_id,
                'field_name'    => $row['field_name'],
                'field_label'   => $row['field_label'],
                'value_text'    => $row['value_text'],
            ]);
        }
    
        return $submission_id;
    }
    

    /**
     * Get template and fields. If $request has static_form_slug, returns config-based template + fields.
     */
    public function get_template_with_type_fields($template_id, $request = null) {
        if ($request && !empty($request['static_form_slug'])) {
            return $this->get_static_form_config($request['static_form_slug']);
        }

        $tpl = $this->db->get_where('ef_templates', ['id' => (int)$template_id])->row_array();
        if (!$tpl) return [null, []];

        $fields = $this->db->order_by('sort_order','ASC')
            ->get_where('ef_form_type_fields', ['form_type_id' => (int)$tpl['form_type_id']])
            ->result_array();

        return [$tpl, $fields];
    }


    public function update_template($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', (int)$id)->update('ef_templates', $data);
    }

    public function template_requests_count($template_id) {
        return (int)$this->db->where('template_id', (int)$template_id)->count_all_results('ef_requests');
    }

    public function delete_template($id) {
        $this->db->where('id', (int)$id)->delete('ef_templates');
    }


    public function get_submissions($limit = 200, $template_ids = []) {
        $ids = [];
        if (!empty($template_ids) && is_array($template_ids)) {
            foreach ($template_ids as $value) {
                $id = (int)$value;
                if ($id > 0) {
                    $ids[] = $id;
                }
            }
            $ids = array_values(array_unique($ids));
        }

        $this->db->select('s.*, s.static_form_slug, r.client_name, r.client_email, t.title as template_title');
        $this->db->from('ef_submissions s');
        $this->db->join('ef_requests r', 'r.id = s.request_id', 'left');
        $this->db->join('ef_templates t', 't.id = s.template_id', 'left');

        if (!empty($ids)) {
            // Match either submission template_id or request template_id
            // so older/inconsistent rows still filter correctly.
            $this->db->group_start();
            $this->db->where_in('s.template_id', $ids);
            $this->db->or_where_in('r.template_id', $ids);
            $this->db->group_end();
        }

        $this->db->order_by('s.created_at', 'DESC');
        $this->db->limit((int)$limit);
        $rows = $this->db->get()->result_array();
        foreach ($rows as &$row) {
            if (empty($row['template_title']) && !empty($row['static_form_slug'])) {
                [$tpl] = $this->get_static_form_config($row['static_form_slug']);
                $row['template_title'] = $tpl['title'] ?? 'Static Form';
            }
        }
        return $rows;
    }

    public function get_submission($id) {
        $this->db->select('s.*, s.static_form_slug, r.client_name, r.client_email, r.token, r.expires_at, r.submitted_at as request_submitted_at, t.title as template_title');
        $this->db->from('ef_submissions s');
        $this->db->join('ef_requests r', 'r.id = s.request_id', 'left');
        $this->db->join('ef_templates t', 't.id = s.template_id', 'left');
        $this->db->where('s.id', (int)$id);
        $row = $this->db->get()->row_array();
        if ($row && empty($row['template_title']) && !empty($row['static_form_slug'])) {
            [$tpl] = $this->get_static_form_config($row['static_form_slug']);
            $row['template_title'] = $tpl['title'] ?? 'Static Form';
        }
        return $row;
    }

    public function get_submission_values($submission_id) {
        return $this->db->order_by('id', 'ASC')
            ->get_where('ef_submission_values', ['submission_id' => (int)$submission_id])
            ->result_array();
    }

    
}


