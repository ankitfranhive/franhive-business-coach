<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_Model extends CI_Model {

    public function __construct() {
        $this->load->library('session');
    }

    public function get_all_roles($inputs = null) {
        if($inputs) {
            $limit = $inputs->get('limit')??15;
            $page = $inputs->get('page')??1;
            $offset = ($page - 1) * $limit;
            $order = $inputs->get('order')??'desc';
        }
        $this->db->select('ROLE_ID,ROLE_NAME,CREATED_BY,MODIFIED_BY,CREATED_ON,MODIFIED_ON,RECORD_STATUS');
        $this->db->from('ROLES');
        $this->db->where('ROLES.RECORD_STATUS',true);
        if($inputs) {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get();

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return [];
        }
    }

    public function find($id) {
        $this->db->select('r.ROLE_ID, r.ROLE_NAME, r.RECORD_STATUS, rp.PERMISSION_ID');
        $this->db->from('ROLES r');
        $this->db->join('ROLE_PERMISSION rp', 'rp.ROLE_ID = r.ROLE_ID', 'left');
        $this->db->where('r.ROLE_ID', $id);
        $this->db->where('r.RECORD_STATUS', true);
        $query = $this->db->get();

        if ($query->num_rows() >= 1) {
            $role_data = $query->result_array()[0];
            // Group permissions by ROLE_ID
            $permissions = [];
            foreach ($query->result_array() as $row) {
                if (!empty($row['PERMISSION_ID'])) {
                    $permissions[] = $row['PERMISSION_ID'];
                }
            }
            $role_data['permissions'] = $permissions;
            return $role_data;
        } else {
            return false;
        }
    }

    public function save($inputs,$permissions) {
        $date = date('Y-m-d H:i:s',time());
        $logged_in_user = $this->session->userdata('user');
        $data = array(
            'ROLE_NAME' => $inputs->post('role_name'),
            'CREATED_BY' => $logged_in_user['USER_ID'],
            'MODIFIED_BY' => $logged_in_user['USER_ID'],
            'CREATED_ON' => $date,
            'MODIFIED_ON' => $date,
            'RECORD_STATUS' => true
        );
        $affected_row = $this->db->insert('ROLES', $data);
        $role_id = $this->db->insert_id(); 
        if ($role_id && is_array($permissions) && !empty($permissions)) {
            foreach ($permissions as $permission_id) {
                $data = array(
                    'ROLE_ID' => $role_id,
                    'PERMISSION_ID' => $permission_id
                );
                $this->db->insert('ROLE_PERMISSION', $data);
            }
        }
        if ($role_id && $this->db->affected_rows() > 0) {
            return   $role_id;
        } else {
            return false;
        }
    }

    public function update($role_id, $inputs, $permissions) {
        $date = date('Y-m-d H:i:s', time());
        $logged_in_user = $this->session->userdata('user');
    
        $data = array(
            'ROLE_NAME' => $inputs->post('role_name'),
            'MODIFIED_BY' => $logged_in_user['USER_ID'],
            'MODIFIED_ON' => $date
        );
        $this->db->where('ROLES.ROLE_ID', $role_id);
        $this->db->update('ROLES', $data);
    
        // $this->db->where('ROLE_ID', $role_id);
        // $this->db->delete('ROLE_PERMISSION');
    
        if (is_array($permissions)) {
            foreach ($permissions as $permission_id) {
                $data = array(
                    'ROLE_ID' => $role_id,
                    'PERMISSION_ID' => $permission_id
                );
                $this->db->insert('ROLE_PERMISSION', $data);
            }
        }
    
        if ($this->db->affected_rows() > 0) {
            return $role_id;
        } else {
            return false;
        }
    }
    

    public function delete($role_id) {
        $date = date('Y-m-d H:i:s',time());
        $logged_in_user = $this->session->userdata('user');
        $data = array(
            'RECORD_STATUS' => false,
            'MODIFIED_BY' => $logged_in_user['USER_ID'],
            'MODIFIED_ON' => $date
        );
        $this->db->where('ROLES.ROLE_ID', $role_id);
        $this->db->update('ROLES', $data);
        if ($this->db->affected_rows() > 0) {
            return $role_id;
        } else {
            return FALSE;
        }
    }
    
    public function get_role_permissions($role_id) {
        $this->db->select('PERMISSION_ID');
        $this->db->from('ROLE_PERMISSION');
        $this->db->join('PERMISSIONS','PERMISSIONS.PERMISSION_ID','ROLE_PERMISSION.PERMISSION_ID');
        $this->db->where('ROLE_PERMISSION.ROLE_ID', $role_id);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row_array();
        } else {
            return false;
        }
    }


    public function get_user_permissions($user_id) {
        $this->db->select('PERMISSION_ID');
        $this->db->from('USERS');
        $this->db->join('USER_ROLE','USER_ROLE.USER_ID','USERS.USER_ID');
        $this->db->join('ROLES','ROLES.ROLE_ID','USER_ROLE.ROLE_ID');
        $this->db->join('ROLE_PERMISSION','ROLE_PERMISSION.ROLE_ID','ROLES.ROLE_ID');
        $this->db->join('PERMISSIONS','PERMISSIONS.PERMISSION_ID','ROLE_PERMISSION.PERMISSION_ID');
       
        $query = $this->db->get();

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }


    public function permission($permission_id) {
        $this->db->select('PERMISSION_ID,PERMISSION_NAME,CREATED_BY,MODIFIED_BY,CREATED_ON,MODIFIED_ON,RECORD_STATUS');
        $this->db->from('PERMISSIONS');
        $this->db->where('PERMISSIONS.PERMISSION_ID',$permission_id);
        $query = $this->db->get();

        if ($query->num_rows() >= 1) {
            return $query->result_array()[0];
        } else {
            return false;
        }
    }

}
