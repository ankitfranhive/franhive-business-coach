<?php
class Ticket_status_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function updateTicketStatus($id, $data)
    {
        $this->db->where('TICKET_STATUS_ID', $id);
        $this->db->update('TICKET_STATUS', $data);
    }

    public function get_all_status()
    {
        $this->db->select('*');
        $this->db->from('TICKET_STATUS');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return [];
        }
    }

    public function insertTicketStatus($data)
    {
        $this->db->insert('TICKET_STATUS', $data);
    }

    public function deleteTicketStatus($id)
    {
        $this->db->where('TICKET_STATUS_ID', $id);
        $this->db->delete('TICKET_STATUS');
    }

    public function get_status($id)
    {
        $this->db->select('*');
        $this->db->from('TICKET_STATUS');
        $this->db->where('TICKET_STATUS_ID', $id);

        $query = $this->db->get();
        return $query->row_array();
    }
}
