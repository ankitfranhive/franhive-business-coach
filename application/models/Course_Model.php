<?php
class Course_Model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function updateCourse($id, $data)
    {
        $this->db->where('ID', $id);
        $this->db->update('COURSES', $data);
    }

    public function getAllCourse()
    {
        $this->db->select('*');
        $this->db->from('COURSES');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return [];
        }
    }

    public function insertCourse($data)
    {
        $this->db->insert('COURSES', $data);
    }

    public function deleteCourse($id)
    {
        $this->db->where('ID', $id);
        $this->db->delete('COURSES');
    }

    public function getCourseById($id)
    {
        $this->db->select('*');
        $this->db->from('COURSES');
        $this->db->where('ID', $id);

        $query = $this->db->get();
        return $query->row_array();
    }
}
