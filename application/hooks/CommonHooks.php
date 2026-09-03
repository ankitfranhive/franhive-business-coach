<?php

// hooks/CheckPermission.php
class CheckPermission
{

    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('User_Model');
        $this->CI->load->model('Role_Model');
        $this->CI->load->model('Permission_Model');
    }

    public function check()
    {
        $user_id = $this->CI->session->userdata('user_id');
        if (!$user_id) {
            redirect('login');
        }

        // Get user roles
        $roles = $this->CI->User_Model->getUserRoles($user_id);
        $role_ids = array_column($roles, 'role_id');

        // Get permissions
        $permissions = [];
        foreach ($role_ids as $role_id) {
            $role_permissions = $this->CI->Role_Model->get_role_permissions($role_id);
            $permissions = array_merge($permissions, array_column($role_permissions, 'PERMISSION_ID'));
        }
        $permissions = array_unique($permissions);

        // Get permission mappings
        $permission_mappings = $this->CI->Permission_Model->get_role_permissions($role_id);
        print_r($permission_mappings);
        die;

        // Check if the user has access to the current page/URL
        // (Implement logic based on your URL structure and permission mappings)
    }
}
