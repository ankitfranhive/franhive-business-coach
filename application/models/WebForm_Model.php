<?php
class WebForm_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function updateWebForm($id, $data)
    {
        $this->db->where('FORM_ID', $id);
        $this->db->update('WEB_FORMS', $data);
    }

    public function get_all_webforms()
    {
        $this->db->select('FORM_ID, WEB_FORM_TITLE, WEB_FORM_NAME');
        $this->db->from('WEB_FORMS');
        $query = $this->db->get();

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return [];
        }
    }

    public function insertWebPage($data)
    {
        $this->db->insert('WEB_FORMS', $data);
    }

    public function deleteWebForm($id)
    {
        $this->db->where('FORM_ID', $id);
        $this->db->delete('WEB_FORMS');
    }

    public function get_webFromData($id)
    {
        $this->db->select('*');
        $this->db->from('WEB_FORMS');
        $this->db->where('FORM_ID', $id);

        $query = $this->db->get();
        return $query->row_array();
    }
}
