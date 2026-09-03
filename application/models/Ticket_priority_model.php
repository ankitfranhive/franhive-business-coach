<?php
class Ticket_priority_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function updateTicketPriority($id, $data)
    {
        $this->db->where('TICKET_PRIORITY_ID', $id);
        $this->db->update('TICKET_PRIORITY', $data);
    }

    public function get_all_prioritys()
    {
        $this->db->select('*');
        $this->db->from('TICKET_PRIORITY');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return [];
        }
    }

    public function insertTicketPriority($data)
    {
        $this->db->insert('TICKET_PRIORITY', $data);
    }

    public function deleteTicketPriority($id)
    {
        $this->db->where('TICKET_PRIORITY_ID', $id);
        $this->db->delete('TICKET_PRIORITY');
    }

    public function get_priority($id)
    {
        $this->db->select('*');
        $this->db->from('TICKET_PRIORITY');
        $this->db->where('TICKET_PRIORITY_ID', $id);

        $query = $this->db->get();
        return $query->row_array();
    }
}
