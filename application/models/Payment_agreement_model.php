<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_agreement_model extends CI_Model
{
    public function create_payment_request($data)
    {
        $ok = $this->db->insert('payment_agreement_requests', $data);
        if (!$ok) {
            $err = $this->db->error();
            if (!empty($err['message'])) {
                log_message('error', 'create_payment_request insert failed: ' . $err['message']);
            }
            return false;
        }
        $id = (int)$this->db->insert_id();
        return $id > 0 ? $id : false;
    }

    public function get_request_by_token($token)
    {
        return $this->db
            ->get_where('payment_agreement_requests', ['token' => $token])
            ->row_array();
    }

    public function mark_request_opened($id)
    {
        return $this->db
            ->where('id', $id)
            ->update('payment_agreement_requests', [
                'status'    => 'opened',
                'opened_at' => date('Y-m-d H:i:s')
            ]);
    }

    public function mark_request_submitted($id, $agreement_id)
    {
        return $this->db
            ->where('id', $id)
            ->update('payment_agreement_requests', [
                'status'       => 'submitted',
                'submitted_at' => date('Y-m-d H:i:s'),
                'agreement_id' => $agreement_id
            ]);
    }

    public function mark_request_expired($id)
    {
        return $this->db
            ->where('id', $id)
            ->update('payment_agreement_requests', [
                'status' => 'expired'
            ]);
    }

    public function get_all_payment_requests()
    {
        return $this->db
            ->order_by('id', 'DESC')
            ->get('payment_agreement_requests')
            ->result_array();
    }

    public function get_payment_request_by_id($id)
    {
        return $this->db
            ->get_where('payment_agreement_requests', ['id' => (int)$id])
            ->row_array();
    }

    /**
     * Issue a fresh link so the client can open and submit the form again.
     */
    public function reset_request_for_resend($id, $token, $expires_at)
    {
        return $this->db
            ->where('id', (int)$id)
            ->update('payment_agreement_requests', [
                'token'            => $token,
                'token_expires_at' => $expires_at,
                'status'           => 'sent',
                'sent_at'          => date('Y-m-d H:i:s'),
                'opened_at'        => null,
                'submitted_at'     => null,
                'agreement_id'     => null,
            ]);
    }

    public function update_agreement_approval($id, $approved_by, $approval_date)
    {
        $update = [
            'approved_by'   => $approved_by !== '' ? $approved_by : null,
            'approval_date' => $approval_date !== '' ? $approval_date : null,
        ];

        return $this->db
            ->where('id', (int)$id)
            ->update('ENROLL_AGREEMENT_DATA', $update);
    }

    public function save_enrollment_form($data)
    {
        $insert = [
            'request_id'                     => !empty($data['request_id']) ? $data['request_id'] : null,
            'client_id'                      => !empty($data['client_id']) ? $data['client_id'] : null,
    
            'full_name'                      => $data['full_name'] ?? null,
            'business_name'                  => $data['business_name'] ?? null,
            'postal_address'                 => $data['postal_address'] ?? null,
            'city_suburb'                    => $data['city_suburb'] ?? null,
            'postcode'                       => $data['postcode'] ?? null,
            'country'                        => $data['country'] ?? null,
            'contact_number_mobile'          => $data['contact_number_mobile'] ?? null,
            'contact_number_work'            => $data['contact_number_work'] ?? null,
            'date_of_birth'                  => !empty($data['date_of_birth']) ? $data['date_of_birth'] : null,
            'email_address'                  => $data['email_address'] ?? null,
    
            'emergency_contact_name'         => $data['emergency_contact_name'] ?? null,
            'emergency_contact_number'       => $data['emergency_contact_number'] ?? null,
            'emergency_contact_relationship' => $data['emergency_contact_relationship'] ?? null,
    
            // JSON values
            'course_name'                    => $data['course_name'] ?? null,
            'training_date_text'             => $data['training_date_text'] ?? null,
            'other_course_name'              => $data['other_course_name'] ?? null,
    
            'location_name'                  => $data['location_name'] ?? null,
            'notes'                          => $data['notes'] ?? null,
            'total_inc_gst'                  => !empty($data['total_inc_gst']) ? $data['total_inc_gst'] : null,
            'deposit_amount'                 => !empty($data['deposit_amount']) ? $data['deposit_amount'] : null,
            'deposit_paid_via'               => $data['deposit_paid_via'] ?? null,
            'deposit_paid_on'                => !empty($data['deposit_paid_on']) ? $data['deposit_paid_on'] : null,
            'balance_amount'                 => !empty($data['balance_amount']) ? $data['balance_amount'] : null,
            'due_date'                       => !empty($data['due_date']) ? $data['due_date'] : null,
    
            'holds_certification'            => isset($data['holds_certification']) ? 1 : 0,
            'certification_details'          => $data['certification_details'] ?? null,
            'certification_attachment'       => $data['certification_attachment'] ?? null,
    
            'agree_terms_1'                  => isset($data['agree_terms_1']) ? 1 : 0,
            'agree_terms_2'                  => isset($data['agree_terms_2']) ? 1 : 0,
            'agree_terms_3'                  => isset($data['agree_terms_3']) ? 1 : 0,
            'agree_terms_4'                  => isset($data['agree_terms_4']) ? 1 : 0,
            'agree_terms_5'                  => isset($data['agree_terms_5']) ? 1 : 0,
            'agree_terms_6'                  => isset($data['agree_terms_6']) ? 1 : 0,
    
            'payment_option'                 => $data['payment_option'] ?? null,
    
            'stripe_customer_id'             => $data['stripe_customer_id'] ?? null,
            'stripe_payment_method_id'       => $data['stripe_payment_method_id'] ?? null,
            'card_brand'                     => $data['card_brand'] ?? null,
            'card_last4'                     => $data['card_last4'] ?? null,
            'card_expiry_month'              => $data['card_expiry_month'] ?? null,
            'card_expiry_year'               => $data['card_expiry_year'] ?? null,
            'name_on_card'                   => $data['name_on_card'] ?? null,
    
            'signature'                      => $data['signature'] ?? null,
            'signature_date'                 => !empty($data['signature_date']) ? $data['signature_date'] : null,
            'signature_image'                => $data['signature_data'] ?? null,
            'approved_by'                    => $data['approved_by'] ?? null,
            'approval_date'                  => !empty($data['approval_date']) ? $data['approval_date'] : null,
            'ip_address'        => $data['ip_address'] ?? null,
            'user_agent'        => $data['user_agent'] ?? null,
            'client_timezone'   => $data['client_timezone'] ?? null,
            'client_time_iso'   => $data['client_time_iso'] ?? null,
            'screen_resolution' => $data['screen_resolution'] ?? null,

            'payment_arrangement_json' => !empty($data['payment_arrangement_json']) ? $data['payment_arrangement_json'] : null,
        ];
    
        $ok = $this->db->insert('ENROLL_AGREEMENT_DATA', $insert);
        if (!$ok) {
            $err = $this->db->error();
            log_message('error', 'save_enrollment_form insert failed: ' . json_encode($err));
            log_message('error', 'save_enrollment_form query: ' . $this->db->last_query());
            log_message('error', 'save_enrollment_form payload: ' . json_encode($insert));
            return false;
        }

        $id = (int)$this->db->insert_id();
        return $id > 0 ? $id : false;
    }

    public function get_agreement_by_id($id)
    {
        return $this->db
            ->get_where('ENROLL_AGREEMENT_DATA', ['id' => $id])
            ->row_array();
    }

    public function get_data()
    {
        $query = $this->db->get('COURSES');
    
        if ($query === false) {
            log_message('error', 'Courses query failed: ' . $this->db->last_query());
            log_message('error', 'DB error: ' . print_r($this->db->error(), true));
            return [];
        }
    
        return $query->result_array();
    }

    /**
     * Courses shown on the public payment agreement form.
     * When course_visibility_limited is not set to 1, returns the full catalog (same as get_data()).
     * When limited, only COURSE_ID values stored in visible_course_ids (JSON) are returned.
     */
    public function get_courses_for_payment_agreement_form()
    {
        $all = $this->get_data();
        $settings = $this->get_form_settings();

        if (empty($settings['course_visibility_limited']) || $settings['course_visibility_limited'] !== '1') {
            return $all;
        }

        $raw = isset($settings['visible_course_ids']) ? trim((string)$settings['visible_course_ids']) : '';
        if ($raw === '') {
            return [];
        }

        $ids = json_decode($raw, true);
        if (!is_array($ids) || empty($ids)) {
            return [];
        }

        $allowed = array_flip(array_map('strval', $ids));
        $out = [];
        foreach ($all as $course) {
            $cid = isset($course['COURSE_ID']) ? (string)$course['COURSE_ID'] : '';
            if ($cid !== '' && isset($allowed[$cid])) {
                $out[] = $course;
            }
        }

        return $out;
    }

    public function get_form_settings()
{
    $rows = $this->db->get('payment_agreement_form_settings')->result_array();
    $settings = [];

    foreach ($rows as $row) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }

    return $settings;
}

public function save_form_settings($data)
{
    foreach ($data as $key => $value) {
        $exists = $this->db->get_where('payment_agreement_form_settings', ['setting_key' => $key])->row_array();

        if ($exists) {
            $this->db->where('setting_key', $key)->update('payment_agreement_form_settings', [
                'setting_value' => $value
            ]);
        } else {
            $this->db->insert('payment_agreement_form_settings', [
                'setting_key' => $key,
                'setting_value' => $value
            ]);
        }
    }

    return true;
}

    public function get_consent_term_defaults()
    {
        return [
            1 => 'I understand that my payment plans will process through Stripe.',
            2 => 'I am the card holder, or I am authorized to act for the company.',
            3 => 'I authorize future product purchases until I withdraw authorization in writing.',
            4 => 'I understand the COVID / online delivery conditions.',
            5 => 'I accept the cancellation and refund policy.',
            6 => 'I understand that product charges may apply if cancellation happens after receiving products.',
        ];
    }

    /**
     * Labels for checkboxes: if term_N exists in settings (even empty), use trimmed value; otherwise use default.
     */
    public function get_effective_consent_term_labels(array $settings)
    {
        $defaults = $this->get_consent_term_defaults();
        $out = [];
        for ($i = 1; $i <= 6; $i++) {
            $k = 'term_' . $i;
            if (array_key_exists($k, $settings)) {
                $out[$i] = trim((string)$settings[$k]);
            } else {
                $out[$i] = $defaults[$i];
            }
        }
        return $out;
    }

    public function has_any_consent_checkbox_terms(array $settings)
    {
        foreach ($this->get_effective_consent_term_labels($settings) as $label) {
            if ($label !== '') {
                return true;
            }
        }
        return false;
    }
}