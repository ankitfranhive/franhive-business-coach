<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Role_Model');
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

    public function get_entity_by_type($type)
    {
        $this->db->select('*');
        $this->db->from('ENTITY');
        $this->db->where('IS_LEAD', $type);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_all_users()
    {
        $this->db->select("
            USERS.USER_ID,
            USERS.JOB_TITLE,
            USERS.NAME,
            USERS.EMAIL,
            USERS.PASSWORD,
            USERS.USERS_TYPE_ID,
            USERS.PERMISSION_ID,
            USERS.PROFILE_PICTURE,
            GROUP_CONCAT(ROLES.ROLE_NAME ORDER BY ROLES.ROLE_NAME SEPARATOR ',') AS ROLE_NAMES
        ", false); // false => don't escape (needed for GROUP_CONCAT)
    
        $this->db->from('USERS');
        $this->db->join('ROLES', 'FIND_IN_SET(ROLES.ROLE_ID, USERS.USERS_TYPE_ID)', 'left');
        $this->db->group_by('USERS.USER_ID'); // USER_ID must be PRIMARY KEY or UNIQUE
    
        $query = $this->db->get();
        if (!$query) {
            $error = $this->db->error();
            echo 'Query Error: ' . $error['message'];
            echo '<br>SQL: ' . $this->db->last_query();
            return false;
        }
        return $query->num_rows() ? $query->result_array() : false;
    }

    public function get_campaign_crm_users()
    {
        $this->db->select('USER_ID, NAME, EMAIL, MOBILE, CITY, JOB_TITLE');
        $this->db->from('USERS');
        $this->db->order_by('NAME', 'ASC');
        $query = $this->db->get();
        return ($query && $query->num_rows()) ? $query->result_array() : [];
    }






    public function save($inputs)
    {

        $date = date('Y-m-d H:i:s', time());
        $password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
        $data = array(
            'JOB_TITLE' => $inputs['JOB_TITLE'],
            'NAME' => $inputs['NAME'],
            'EMAIL' => $inputs['EMAIL'],
            'PROFILE_PICTURE' =>  $inputs['PROFILE_PICTURE'],
            'MOBILE' => $inputs['MOBILE'],
            'USERS_TYPE_ID' => $inputs['USERS_TYPE_ID'],
            'CLIENT_TYPE_ID' => $inputs['CLIENT_TYPE_ID'],
            'ENROLLMENT_AGREEMENT' => $inputs['ENROLLMENT_AGREEMENT'],
            'PAYMENT_AGREEMENT' => $inputs['PAYMENT_AGREEMENT'],
            'PERMISSION_ID' => implode(',', array($inputs['PERMISSION_ID'])),
            'DIVISION' => $inputs['DIVISION'],
            'ADDRESS_LINE_1' => $inputs['ADDRESS_LINE_1'],
            'ADDRESS_LINE_2' => $inputs['ADDRESS_LINE_2'],
            'CITY' => $inputs['CITY'],
            'ZIP' => $inputs['ZIP'],
            'COUNTRY' => $inputs['COUNTRY'],
            'STATE' => $inputs['STATE'],
            'NOTE' => $inputs['NOTE'],

            // 'PASSWORD' => $password,

            'PASSWORD' => 'Eyd@2026',
            // 'CREATED_BY' => $logged_in_user['user_id'],
            // 'MODIFIED_BY' => $logged_in_user['user_id'],
            'CREATED_ON' => $date,
            'MODIFIED_ON' => $date,
            'RECORD_STATUS' => true
        );
        $this->db->insert('USERS', $data);
        $user_id = $this->db->insert_id();
        if ($inputs['USERS_TYPE_ID']) {
            $data = [
                'ROLE_ID' => $inputs['USERS_TYPE_ID'],
                'USER_ID' => $user_id
            ];
            $affected_rows += $this->db->insert('USER_ROLE', $data);
        }
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function update($user_id, $inputs)
    {
        // Ensure $user_id and $inputs are valid
        if (empty($user_id) || !is_numeric($user_id) || !is_array($inputs)) {
            return false;
        }

        $date = date('Y-m-d H:i:s', time());

        // Prepare data to update
        $data = array(
            'JOB_TITLE' => $inputs['JOB_TITLE'],
            'NAME' => $inputs['NAME'],
            'EMAIL' => $inputs['EMAIL'],
            'PROFILE_PICTURE' =>  $inputs['PROFILE_PICTURE'],
            'MOBILE' => $inputs['MOBILE'],
            'USERS_TYPE_ID' => $inputs['USERS_TYPE_ID'],
            'CLIENT_TYPE_ID' => $inputs['CLIENT_TYPE_ID'],
            'ENROLLMENT_AGREEMENT' => $inputs['ENROLLMENT_AGREEMENT'],
            'PAYMENT_AGREEMENT' => $inputs['PAYMENT_AGREEMENT'],
            'PERMISSION_ID' => implode(',', array($inputs['PERMISSION_ID'])),
            'DIVISION' => $inputs['DIVISION'],
            'ADDRESS_LINE_1' => $inputs['ADDRESS_LINE_1'],
            'ADDRESS_LINE_2' => $inputs['ADDRESS_LINE_2'],
            'CITY' => $inputs['CITY'],
            'ZIP' => $inputs['ZIP'],
            'COUNTRY' => $inputs['COUNTRY'],
            'STATE' => $inputs['STATE'],
            'NOTE' => $inputs['NOTE'],

            'MODIFIED_ON' => $date,
        );

        // Optional: Update password if provided
        if (!empty($inputs['PASSWORD'])) {
            $password = password_hash($inputs['PASSWORD'], PASSWORD_DEFAULT);
            // $data['PASSWORD'] = $password;
            $data['PASSWORD'] =$inputs['PASSWORD'];
        }

        // Perform update operation
        $this->db->where('USER_ID', $user_id);
        $this->db->update('USERS', $data);


        if ($inputs['USERS_TYPE_ID']) {
            $data = [
                'ROLE_ID' => $inputs['USERS_TYPE_ID'],
            ];
            // Perform update operation
            $this->db->where('USER_ID', $user_id);
            $this->db->update('USER_ROLE', $data);
        }
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function find($id)
    {
        $this->db->select('USER_ID,JOB_TITLE,NAME,EMAIL,MOBILE,PASSWORD,USERS_TYPE_ID,CLIENT_TYPE_ID,ENROLLMENT_AGREEMENT,PAYMENT_AGREEMENT,PERMISSION_ID,DIVISION,ADDRESS_LINE_1,ADDRESS_LINE_2,CITY,ZIP,COUNTRY,STATE,PROFILE_PICTURE,NOTE');
        $this->db->from('USERS');
        $this->db->where('USER_ID', $id);
        $query = $this->db->get();

        if ($query->num_rows() >= 1) {
            return $query->result_array()[0];
        } else {
            return false;
        }
    }


    public function get_all_permission()
    {
        // $this->db->select('*');
        $this->db->where('RECORD_STATUS', 0);
        $this->db->from('PERMISSIONS');

        $query = $this->db->get();

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function get_all_countries()
    {
        $this->db->select('COUNTRY_ID,NAME,CURRENCY_ID,COUNTRY_CODE,COUNTRY_ABBREV');
        // $this->db->where('SHOW_COUNTRY', 0);
        $this->db->from('COUNTRIES');

        $query = $this->db->get();

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function get_states_by_country_id($country_id)
    {
        $this->db->select('STATE_ID, STATE_NAME, STATE_ABBREV');
        $this->db->where('COUNTRY_ID', $country_id);
        $query = $this->db->get('STATES');
        return $query->result_array();
    }

    public function getUserRoles($user_id)
    {
        $this->db->select('ROLES.ROLE_NAME as role_name');
        $this->db->from('USERS');
        $this->db->join('ROLES', 'USERS.USERS_TYPE_ID = ROLES.ROLE_ID');
        $this->db->where('USERS.USER_ID', $user_id);
    
        // Execute the query
        $query = $this->db->get();
    
        // Check if query execution failed
        if (!$query) {
            // Log the error message for debugging
            log_message('error', 'Query failed: ' . $this->db->last_query());
            log_message('error', 'Error message: ' . $this->db->error()['message']);
    
            // Return null or handle the error
            return null;
        }
    
        // Fetch and return the result
        $result = $query->row_array(); // Use result_array() for multiple rows
        return $result ? $result : null;
    }
    

    public function deleteUser($user_id)
    {
        $this->db->where('USER_ID', $user_id);
        $this->db->delete('USERS');

        $this->db->where('USER_ID', $user_id);
        $this->db->delete('USER_ROLE');
    }

    public function get_user_by_email_or_mobile($email, $mobile) {
        return $this->db->where('EMAIL', $email)
                        ->or_where('MOBILE', $mobile)
                        ->get('USERS')
                        ->row_array();
    }
    
    public function create_user($data) {
        $this->db->insert('USERS', $data);
        return $this->db->insert_id();
    }

    public function get_user_by_id($id) {
        $query = $this->db->get_where('USERS', ['USER_ID' => $id]);
        if ($query && $query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }

    /**
     * Courses a user is enrolled in (via USER_COURSE_MAPPING).
     */
    public function get_user_courses($user_id) {
        $user_id = (int)$user_id;
        if ($user_id <= 0) {
            return [];
        }

        $sql = "
            SELECT
                c.*,
                ucm.ID AS MAPPING_ID,
                ucm.USER_ID,
                ucm.CREATED_ON AS ENROLLED_ON
            FROM USER_COURSE_MAPPING ucm
            INNER JOIN COURSES c ON c.COURSE_ID = ucm.COURSE_ID
            WHERE ucm.USER_ID = ?
              AND c.RECORD_STATUS = 0
            ORDER BY ucm.ID DESC
        ";

        $query = $this->db->query($sql, [$user_id]);
        return $query ? $query->result_array() : [];
    }

    private $table = 'USERS';
    public function update_password($user_id, $password_hash)
    {
        return $this->db->where('USER_ID', (int)$user_id)
                        ->update($this->table, ['PASSWORD' => $password_hash]);
    }
    
    
}
