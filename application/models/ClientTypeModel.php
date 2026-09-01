<?php
class ClientTypeModel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function updateClientType($id, $data)
    {
        $this->db->where('ID', $id);
        $this->db->update('CLIENT_TYPE', $data);

        // Return the ID of the updated record
        return $id;
    }


    public function getAllClientTypes()
    {
        $this->db->select('*');
        $this->db->from('CLIENT_TYPE');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return [];
        }
    }

    public function insertClientType($data)
    {
        $this->db->insert('CLIENT_TYPE', $data);
        return $this->db->insert_id(); // Return the ID of the inserted row
    }



    public function getClientTypeById($id)
    {
        $this->db->select('*');
        $this->db->from('CLIENT_TYPE');
        $this->db->where('ID', $id);

        $query = $this->db->get();
        return $query->row_array();
    }

    public function deleteClientTypeById($id)
    {
        // Check if the client exists first (optional but recommended)
        $this->db->select('*');
        $this->db->from('CLIENT_TYPE');
        $this->db->where('ID', $id);
        $query = $this->db->get();
        $data = $query->row_array();

        if ($data) {
            // Client exists, proceed with delete
            $this->db->where('ID', $id);
            $this->db->delete('CLIENT_TYPE');

            if ($this->db->affected_rows() > 0) {
                return true; // Deletion was successful
            } else {
                return false; // Deletion failed
            }
        } else {
            return false; // Client not found
        }
    }
}
