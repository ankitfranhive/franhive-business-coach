<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permission_Model extends CI_Model
{

    public function get_all_permissions()
    {
        $this->db->select('PERMISSIONS.PERMISSION_ID, PERMISSIONS.PERMISSION_NAME, PERMISSIONS.CREATED_BY, PERMISSIONS.MODIFIED_BY, PERMISSIONS.CREATED_ON, PERMISSIONS.MODIFIED_ON, PERMISSIONS.RECORD_STATUS, MODULES.MODULE_NAME, MODULES.MODULE_ID');
        $this->db->from('PERMISSIONS');
        $this->db->join('MODULES', 'MODULES.MODULE_ID = PERMISSIONS.MODULE_ID');
        $this->db->where('PERMISSIONS.RECORD_STATUS', true);
        
        $query = $this->db->get();
    
        // Check if the query failed
        if (!$query) {
            // Log or debug the error
            $error = $this->db->error(); // Produces an array with 'code' and 'message'
            log_message('error', 'Database error: ' . print_r($error, true));
            return [];
        }
    
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return [];
        }
    }
    

    public function get_role_permissions($role_id)
{
    $this->db->select("GROUP_CONCAT(ROLE_PERMISSION.PERMISSION_ID) AS permission_ids");
    $this->db->from('ROLE_PERMISSION');
    $this->db->join('PERMISSIONS', 'PERMISSIONS.PERMISSION_ID = ROLE_PERMISSION.PERMISSION_ID');
    $this->db->where('ROLE_PERMISSION.ROLE_ID', $role_id);
    $query = $this->db->get();

    // Return all matching rows as an array
    if ($query->num_rows() > 0) {
        return $query->row_array();
    } else {
        return false;
    }
}



    public function get_user_permissions($user_id)
    {
        $this->db->select('PERMISSIONS.PERMISSION_ID');
        $this->db->from('USERS');
        $this->db->join('USER_ROLE', 'USER_ROLE.USER_ID = USERS.USER_ID');
        $this->db->join('ROLES', 'ROLES.ROLE_ID = USER_ROLE.ROLE_ID');
        $this->db->join('ROLE_PERMISSION', 'ROLE_PERMISSION.ROLE_ID = ROLES.ROLE_ID');
        $this->db->join('PERMISSIONS', 'PERMISSIONS.PERMISSION_ID = ROLE_PERMISSION.PERMISSION_ID');
        $this->db->where('USERS.USER_ID', $user_id);

        $query = $this->db->get();

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return [];
        }
    }

    public function getPermissions($permission_ids)
    {
        if (empty($permission_ids)) {
            return []; // Return an empty array if no permission IDs are provided
        }

        $this->db->select('*');
        $this->db->from('PERMISSIONS_MAPPING');
        $this->db->where_in('PERMISSION_ID', $permission_ids);
        $query = $this->db->get();
        return $query->result_array();
    }


    public function get_modules_with_permissions()
    {
        $this->db->select('MODULES.MODULE_ID, MODULES.MODULE_NAME, PERMISSIONS.PERMISSION_ID, PERMISSIONS.PERMISSION_NAME');
        $this->db->from('MODULES');
        $this->db->join('PERMISSIONS', 'PERMISSIONS.MODULE_ID = MODULES.MODULE_ID');
        $this->db->where('PERMISSIONS.RECORD_STATUS', true);
        $this->db->order_by('MODULES.MODULE_NAME', 'ASC');
        $this->db->order_by('PERMISSIONS.PERMISSION_NAME', 'ASC');
        $query = $this->db->get();
        $modules_with_permissions = [];
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $key = $row['MODULE_NAME'] . '__' . $row['MODULE_ID'];
                if (!isset($modules_with_permissions[$key])) {
                    $modules_with_permissions[$key] = [];
                }
                $modules_with_permissions[$key][] = [
                    'PERMISSION_ID' => $row['PERMISSION_ID'],
                    'PERMISSION_NAME' => $row['PERMISSION_NAME']
                ];
            }
        }
        return $modules_with_permissions;
    }



    public function find($permission_id)
    {
        // $this->db->select('*');
        $this->db->select('PERMISSION_ID,PERMISSION_NAME,MODULES.MODULE_NAME,MODULES.MODULE_ID');
        $this->db->from('PERMISSIONS');
        $this->db->join('MODULES', 'MODULES.MODULE_ID = PERMISSIONS.MODULE_ID');
        $this->db->where('PERMISSIONS.PERMISSION_ID', $permission_id);
        $this->db->where('PERMISSIONS.RECORD_STATUS', true);
        $query = $this->db->get();

        if ($query->num_rows() >= 1) {
            return $query->result_array()[0];
        } else {
            return false;
        }
    }

    public function save($inputs)
    {
        $date = date('Y-m-d H:i:s', time());
        $logged_in_user = $this->session->userdata('user');
        $data = array(
            'PERMISSION_NAME' => $inputs->post('permission_name'),
            'MODULE_ID' => $inputs->post('module_id'),
            'CREATED_BY' => $logged_in_user['USER_ID'],
            'MODIFIED_BY' => $logged_in_user['USER_ID'],
            'CREATED_ON' => $date,
            'MODIFIED_ON' => $date,
            'RECORD_STATUS' => true
        );
        $this->db->insert('PERMISSIONS', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function update($permission_id, $inputs)
    {
        $date = date('Y-m-d H:i:s', time());
        $logged_in_user = $this->session->userdata('user');
        $data = array(
            'PERMISSION_NAME' => $inputs->post('permission_name'),
            'MODULE_ID' => $inputs->post('module_id'),
            'MODIFIED_BY' => $logged_in_user['USER_ID'],
            'MODIFIED_ON' => $date
        );
        $this->db->where('PERMISSIONS.PERMISSION_ID', $permission_id);
        $this->db->update('PERMISSIONS', $data);
        if ($this->db->affected_rows() > 0) {
            return $permission_id;
        } else {
            return FALSE;
        }
    }

    public function delete($permission_id)
    {
        $date = date('Y-m-d H:i:s', time());
        $logged_in_user = $this->session->userdata('user');
        $data = array(
            'RECORD_STATUS' => false,
            'MODIFIED_BY' => $logged_in_user['USER_ID'],
            'MODIFIED_ON' => $date
        );
        $this->db->where('PERMISSIONS.PERMISSION_ID', $permission_id);
        $this->db->update('PERMISSIONS', $data);
        if ($this->db->affected_rows() > 0) {
            return $permission_id;
        } else {
            return FALSE;
        }
    }
}
