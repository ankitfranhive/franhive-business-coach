<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entity_model extends CI_Model
{
    /** Normalize phone to E.164-ish (+91XXXXXXXXXX) for better matching */
    private function normalize_phone($raw) {
        if (!$raw) return '';
        $digits = preg_replace('/\D+/', '', $raw); // keep only digits
        // Quick India default: if 10 digits, prefix country code
        if (strlen($digits) === 10) {
            $digits = '91' . $digits;
        }
        if (strpos($digits, '91') === 0) {
            return '+' . $digits;
        }
        // Fallback: add + if not present
        return (strpos($raw, '+') === 0) ? $raw : '+' . $digits;
    }

    /**
     * Find lead by phone/email from ENTITY table.
     * Only returns a few basic columns for sidebar.
     */
    public function find_by_contact($phone = '', $email = '')
    {
        $email = trim((string)$email);
        $norm  = $this->normalize_phone($phone);
        $tail7 = preg_replace('/\D+/', '', $norm);
        $tail7 = substr($tail7, -7); // last 7 digits fallback

        $this->db
            ->select('ENTITY_ID, NAME, TITLE, EMAIL, MOBILE, PHONE, HOME_PHONE, CITY, STATE, LEAD_STATUS, LEAD_OWNER, NEXT_CALL_DATE')
            ->from('ENTITY');

        // WHERE (email match OR phone match)
        $this->db->group_start();

        if ($email !== '') {
            // Primary & secondary email columns (ENTITY has EMAIL + CI_EMAIL)
            $this->db->or_where('EMAIL', $email);
            $this->db->or_where('CI_EMAIL', $email);
        }

        if ($norm !== '') {
            // Exact normalized compare across phone-like columns
            $this->db->or_where('MOBILE', $norm);
            $this->db->or_where('PHONE', $norm);
            $this->db->or_where('HOME_PHONE', $norm);
            $this->db->or_where('CI_PHONE', $norm);
            $this->db->or_where('CI_MOBILE', $norm);

            // Loose fallback using last 7 digits (in case DB not normalized)
            if ($tail7 !== '') {
                $this->db->or_like('MOBILE', $tail7, 'both');
                $this->db->or_like('PHONE', $tail7, 'both');
                $this->db->or_like('HOME_PHONE', $tail7, 'both');
                $this->db->or_like('CI_PHONE', $tail7, 'both');
                $this->db->or_like('CI_MOBILE', $tail7, 'both');
            }
        }

        $this->db->group_end();

        // OPTIONAL: agar sirf leads chahiyein, niche ki line uncomment kar do
        // $this->db->where_in('IS_LEAD', ['1','Y','YES','Yes','true','TRUE']);

        // Freshest on top
        $this->db->order_by('MODIFIED_ON', 'DESC');
        $this->db->limit(3);

        return $this->db->get()->result_array(); // 0..3 rows
    }
}
