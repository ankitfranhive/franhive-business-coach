<?php
class Lead_Status_Model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function updateLeadStatus($id, $data)
    {
        $this->db->where('ID', $id);
        $this->db->update('LEAD_STATUS', $data);
    }

    public function getAllLeadStatus()
    {
        $this->db->select('*');
        $this->db->from('LEAD_STATUS');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return [];
        }
    }

    public function insertLeadStatus($data)
    {
        $this->db->insert('LEAD_STATUS', $data);
    }

    public function deleteLeadStatus($id)
    {
        $this->db->where('ID', $id);
        $this->db->delete('LEAD_STATUS');
    }

    public function get_LeadStatus($id)
    {
        $this->db->select('*');
        $this->db->from('LEAD_STATUS');
        $this->db->where('ID', $id);

        $query = $this->db->get();
        return $query->row_array();
    }
}
