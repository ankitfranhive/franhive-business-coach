<?php
class Lead_Source_Model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function updateLeadSource($id, $data)
    {
        $this->db->where('ID', $id);
        $this->db->update('LEAD_SOURCE', $data);
    }

    public function getAllLeadSource()
    {
        $this->db->select('*');
        $this->db->from('LEAD_SOURCE');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return [];
        }
    }

    public function insertLeadSource($data)
    {
        $this->db->insert('LEAD_SOURCE', $data);
    }

    public function deleteLeadSource($id)
    {
        $this->db->where('ID', $id);
        $this->db->delete('LEAD_SOURCE');
    }

    public function get_LeadSource($id)
    {
        $this->db->select('*');
        $this->db->from('LEAD_SOURCE');
        $this->db->where('ID', $id);

        $query = $this->db->get();
        return $query->row_array();
    }
}
