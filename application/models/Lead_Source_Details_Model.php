<?php
class Lead_Source_Details_Model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function updateLeadSourceDetails($id, $data)
    {
        $this->db->where('ID', $id);
        $this->db->update('LEAD_SOURCE_DETAILS', $data);
    }

    public function getAllLeadSourceDetails()
    {
        $this->db->select('*');
        $this->db->from('LEAD_SOURCE_DETAILS');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return [];
        }
    }

    public function insertLeadSourceDetails($data)
    {
        $this->db->insert('LEAD_SOURCE_DETAILS', $data);
    }

    public function deleteLeadSourceDetails($id)
    {
        $this->db->where('ID', $id);
        $this->db->delete('LEAD_SOURCE_DETAILS');
    }

    public function get_LeadSourceDetails($id)
    {
        $this->db->select('*');
        $this->db->from('LEAD_SOURCE_DETAILS');
        $this->db->where('ID', $id);

        $query = $this->db->get();
        return $query->row_array();
    }
}
