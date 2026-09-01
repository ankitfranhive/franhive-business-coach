<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Module_Model extends CI_Model {

    public function get_all_modules() {
        $this->db->select('MODULE_ID,MODULE_NAME,CREATED_BY,MODIFIED_BY,CREATED_ON,MODIFIED_ON,RECORD_STATUS');
        $this->db->from('MODULES');
        $this->db->where('MODULES.RECORD_STATUS',true);
        $query = $this->db->get();

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return [];
        }
    }
    
    // public function get_role_modules($role_id) {
    //     $this->db->select('MODULE_ID');
    //     $this->db->from('ROLE_MODULE');
    //     $this->db->join('MODULES','MODULES.MODULE_ID','ROLE_MODULE.MODULE_ID');
    //     $this->db->where('ROLE_MODULE.ROLE_ID', $role_id);
    //     $query = $this->db->get();
    //     if ($query->num_rows() == 1) {
    //         return $query->row_array();
    //     } else {
    //         return false;
    //     }
    // }


    // public function get_user_modules($user_id) {
    //     $this->db->select('MODULES.MODULE_ID');
    //     $this->db->from('USERS');
    //     $this->db->join('USER_ROLE', 'USER_ROLE.USER_ID = USERS.USER_ID');
    //     $this->db->join('ROLES', 'ROLES.ROLE_ID = USER_ROLE.ROLE_ID');
    //     $this->db->join('ROLE_MODULE', 'ROLE_MODULE.ROLE_ID = ROLES.ROLE_ID');
    //     $this->db->join('MODULES', 'MODULES.MODULE_ID = ROLE_MODULE.MODULE_ID');
    //     $this->db->where('USERS.USER_ID',$user_id);
       
    //     $query = $this->db->get();

    //     if ($query->num_rows() >= 1) {
    //         return $query->result_array();
    //     } else {
    //         return false;
    //     }
    // }


    public function find($module_id) {
        // $this->db->select('*');
        $this->db->select('MODULE_ID,MODULE_NAME,CREATED_BY,MODIFIED_BY,CREATED_ON,MODIFIED_ON,RECORD_STATUS');
        $this->db->from('MODULES');
        $this->db->where('MODULES.MODULE_ID',$module_id);
        $this->db->where('MODULES.RECORD_STATUS',true);
        $query = $this->db->get();

        if ($query->num_rows() >= 1) {
            return $query->result_array()[0];
        } else {
            return false;
        }
    }

    public function save($inputs) {
        $date = date('Y-m-d H:i:s',time());
        $logged_in_user = $this->session->userdata('user');
        $data = array(
            'MODULE_NAME' => $inputs->post('module_name'),
            'CREATED_BY' => $logged_in_user['USER_ID'],
            'MODIFIED_BY' => $logged_in_user['USER_ID'],
            'CREATED_ON' => $date,
            'MODIFIED_ON' => $date,
            'RECORD_STATUS' => true
        );
        $this->db->insert('MODULES', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function update($module_id,$inputs) {
        $date = date('Y-m-d H:i:s',time());
        $logged_in_user = $this->session->userdata('user');
        $data = array(
            'MODULE_NAME' => $inputs->post('module_name'),
            'MODIFIED_BY' => $logged_in_user['USER_ID'],
            'MODIFIED_ON' => $date
        );
        $this->db->where('MODULES.MODULE_ID', $module_id);
        $this->db->update('MODULES', $data);
        if ($this->db->affected_rows() > 0) {
            return $module_id;
        } else {
            return FALSE;
        }
    }

    public function delete($module_id) {
        $date = date('Y-m-d H:i:s',time());
        $logged_in_user = $this->session->userdata('user');
        $data = array(
            'RECORD_STATUS' => false,
            'MODIFIED_BY' => $logged_in_user['USER_ID'],
            'MODIFIED_ON' => $date
        );
        $this->db->where('MODULES.MODULE_ID', $module_id);
        $this->db->update('MODULES', $data);
        if ($this->db->affected_rows() > 0) {
            return $module_id;
        } else {
            return FALSE;
        }
    }

}
