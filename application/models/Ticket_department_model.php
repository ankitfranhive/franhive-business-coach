<?php
class Ticket_department_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function updateTicketDepartment($id, $data)
    {
        $this->db->where('TICKET_DEPARTMENT_ID', $id);
        $this->db->update('TICKET_DEPARTMENT', $data);
    }

    public function get_all_departments()
    {
        $this->db->select('*');
        $this->db->from('TICKET_DEPARTMENT');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return [];
        }
    }

    public function insertTicketDepartmentData($data)
    {
        $this->db->insert('TICKET_DEPARTMENT', $data);
    }

    public function deleteTicketDepartment($id)
    {
        $this->db->where('TICKET_DEPARTMENT_ID', $id);
        $this->db->delete('TICKET_DEPARTMENT');
    }

    public function get_departmentData($id)
    {
        $this->db->select('*');
        $this->db->from('TICKET_DEPARTMENT');
        $this->db->where('TICKET_DEPARTMENT_ID', $id);

        $query = $this->db->get();
        return $query->row_array();
    }
}
