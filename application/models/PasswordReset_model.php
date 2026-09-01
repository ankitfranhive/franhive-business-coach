<?php defined('BASEPATH') OR exit('No direct script access allowed');

class PasswordReset_model extends CI_Model
{
    private $table = 'password_resets';

    public function create_token($data)
    {
        // Optional: delete older tokens for the same email to keep table clean
        $this->db->where('email', $data['email'])->delete($this->table);
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function recent_request_exists($email, $minutes = 5)
    {
        $since = date('Y-m-d H:i:s', time() - ($minutes * 60));
        return $this->db->where('email', $email)
                        ->where('created_at >=', $since)
                        ->count_all_results($this->table) > 0;
    }

    public function get_valid_by_token($token)
    {
        // We store only a hash in DB, so we must iterate latest few for this email,
        // but we don't have the email yet. Lightweight way:
        // fetch fresh tokens (not used, not expired), then verify.
        $now = date('Y-m-d H:i:s');
        $rows = $this->db->where('used_at IS NULL', null, false)
                         ->where('expires_at >=', $now)
                         ->order_by('id', 'DESC')
                         ->limit(500) // reasonable scan cap
                         ->get($this->table)
                         ->result_array();

        foreach ($rows as $r) {
            if (password_verify($token, $r['token_hash'])) {
                return $r; // include id, email, etc.
            }
        }
        return null;
    }

    public function mark_used($id)
    {
        return $this->db->where('id', (int)$id)
                        ->update($this->table, ['used_at' => date('Y-m-d H:i:s')]);
    }

    public function get_user_by_email($email)
    {
        $this->db->select('USER_ID,JOB_TITLE,NAME,EMAIL,MOBILE,CITY,ADDRESS_LINE_1,PASSWORD,USERS_TYPE_ID,PERMISSION_ID,PROFILE_PICTURE');
        $this->db->from('USERS');
        $this->db->where('EMAIL', $email);
        $query = $this->db->get();

        // Print the last query
        // echo $this->db->last_query();die;

        if ($query->num_rows() >= 1) {
            return $query->row_array();
        } else {
            throw new \Exception('User Not Found', 405);
        }
    }

    public function update_password($user_id, $password_hash)
    {
        return $this->db->where('id', (int)$user_id)
                        ->update($this->table, ['PASSWORD' => $password_hash]);
    }
}
