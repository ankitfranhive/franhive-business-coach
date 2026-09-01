<?php
class Ratings_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function updateRating($id, $data)
    {
        $this->db->where('ID', $id);
        $this->db->update('RATINGS', $data);
    }

    public function getAllRatings()
    {
        $this->db->select('*');
        $this->db->from('RATINGS');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return [];
        }
    }

    public function insertRating($data)
    {
        $this->db->insert('RATINGS', $data);
    }

    public function deleteRating($id)
    {
        $this->db->where('ID', $id);
        $this->db->delete('RATINGS');
    }

    public function get_Rating($id)
    {
        $this->db->select('*');
        $this->db->from('RATINGS');
        $this->db->where('ID', $id);

        $query = $this->db->get();
        return $query->row_array();
    }
}
