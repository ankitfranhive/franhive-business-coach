<?php
class BusinessTypeModel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function updateBusinessType($id, $data)
    {
        $this->db->where('ID', $id);
        $this->db->update('BUSINESS_TYPE', $data);
    }

    public function getAllBusinessType()
    {
        $this->db->select('*');
        $this->db->from('BUSINESS_TYPE');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return [];
        }
    }

    public function insertBusinessType($data)
    {
        $this->db->insert('BUSINESS_TYPE', $data);
    }

    public function deleteBusinessType($id)
    {
        $this->db->where('ID', $id);
        $this->db->delete('BUSINESS_TYPE');
    }

    public function getBusinessType($id)
    {
        $this->db->select('*');
        $this->db->from('BUSINESS_TYPE');
        $this->db->where('ID', $id);

        $query = $this->db->get();
        return $query->row_array();
    }
}
