<?php
class PermissionMiddleware {
    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
    }

    public function handle($permissions) {
        if (!$this->isAuthenticated()) {
            redirect('login');
            exit();
        }
        $userPermissionData = $this->getUserPermissionData();
        foreach($permissions as $permission) {
            if(!in_array($permission,$userPermissionData)) {
                redirect('/');
            }
        }
        
        return true;
    }

    protected function isAuthenticated() {
        return $this->CI->session->userdata('user') !== false;
    }

    protected function getUserData() {
        return $this->CI->session->userdata('user');
    }

    protected function getUserPermissionData() {
        return $this->CI->session->userdata('permissions');
    }
}