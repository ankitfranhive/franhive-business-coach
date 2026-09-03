<?php
class Client_Model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function updateClient($id, $data)
    {
        $this->db->where('ENTITY_ID', $id);
        $this->db->update('ENTITY', $data);

        // Return the ID of the updated record
        return $id;
    }

    public function getTotalClients()
    {
        $this->db->select('*');
        $this->db->from('ENTITY');
        $this->db->where('IS_LEAD', 'N');
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function getRecentlyAddedClients($limit = 10)
    {
        $this->db->select('*');
        $this->db->from('ENTITY');
        $this->db->where('IS_LEAD', 'N');
        $this->db->order_by('ENTITY_ID', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return [];
        }
    }

    public function getAllClients()
    {
        $this->db->select('*');
        $this->db->from('ENTITY');
        $this->db->where('IS_LEAD', 'N');
        $this->db->where('RECORD_STATUS', 0);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return [];
        }
    }

    public function getAllLeadsAndClients()
    {
        $this->db->select('*');
        $this->db->from('ENTITY');
        // $this->db->where('IS_LEAD', 'N');
        $this->db->where('RECORD_STATUS', 0);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return [];
        }
    }
    public function insertClient($data)
    {
        $this->db->insert('ENTITY', $data);
        return $this->db->insert_id(); // Return the ID of the inserted row
    }

    public function deleteClient($id)
    {
        $this->db->where('ID', $id);
        $this->db->delete('CLIENT');
    }

    public function getClientById($id)
    {
        $this->db->select('*');
        $this->db->from('ENTITY');
        $this->db->where('ENTITY_ID', $id);

        $query = $this->db->get();
        return $query->row_array();
    }

    public function deleteClientById($id)
    {
        $this->db->select('*');
        $this->db->from('ENTITY');
        $this->db->where('ENTITY_ID', $id);

        $query = $this->db->get();
        return $query->row_array();
    }
}
