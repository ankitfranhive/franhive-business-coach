<?php
class Template_Model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_template($id)
    {
        $this->db->select('*');
        $this->db->from('TEMPLATES');
        $this->db->where('TEMPLATE_iD', $id);

        $query = $this->db->get();

        return $query->row_array();
    }

    public function get_template_by_id($template_id)
    {
        $this->db->select('*');
        $this->db->from('TEMPLATES');
        $this->db->where('TEMPLATE_ID', $template_id);

        $query = $this->db->get();

        if ($query->num_rows() >= 1) {
            return $query->row_array();
        } else {
            return false;
        }
    }
    public function get_all_templates()
    {
        $this->db->select('TEMPLATE_ID, TEMPLATE_NAME');
        $this->db->from('TEMPLATES');
        $query = $this->db->get();

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return [];
        }
    }

    public function get_first_template()
    {
        $this->db->select('*');
        $this->db->from('TEMPLATES');
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() >= 1) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    public function save($inputs)
    {
        $date = date('Y-m-d H:i:s', time());
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


    public function delete_product($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('products');
    }
}
