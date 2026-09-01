<?php
class Ticket_Model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function updateTicket($id, $data)
    {
        $this->db->where('TICKET_ID', $id);
        $this->db->update('TICKETS', $data);
    }

    public function get_all_tickets()
    {
        $this->db->select('
            TICKETS.TICKET_ID,
            TICKETS.SUBJECT,
            TICKET_DEPARTMENT.DEPARTMENT_NAME AS DEPARTMENT_NAME,
            USERS.NAME AS STORE_NAME, 
            TICKET_PRIORITY.PRIORITY_NAME AS PRIORITY_NAME, 
            TICKET_STATUS.STATUS AS STATUS_NAME
        ');
        $this->db->from('TICKETS');
        $this->db->join('TICKET_DEPARTMENT', 'TICKETS.DEPARTMENT = TICKET_DEPARTMENT.TICKET_DEPARTMENT_ID', 'left');
        $this->db->join('USERS', 'TICKETS.STORE_ID = USERS.USER_ID', 'left');
        $this->db->join('TICKET_PRIORITY', 'TICKETS.PRIORITY = TICKET_PRIORITY.TICKET_PRIORITY_ID', 'left');
        $this->db->join('TICKET_STATUS', 'TICKETS.TICKET_STATUS = TICKET_STATUS.TICKET_STATUS_ID', 'left');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return [];
        }
    }

    public function insertTicketData($data)
    {
        $this->db->insert('TICKETS', $data);
    }

    public function deleteTicket($id)
    {
        $this->db->where('TICKET_ID', $id);
        $this->db->delete('TICKETS');
    }

    public function get_ticketData($id)
    {

        $this->db->select('
            TICKETS.TICKET_ID,
            TICKETS.SUBJECT,
            TICKETS.*,
            TICKET_DEPARTMENT.DEPARTMENT_NAME AS DEPARTMENT_NAME,
            USERS.NAME AS STORE_NAME, 
            TICKET_PRIORITY.PRIORITY_NAME AS PRIORITY_NAME, 
            TICKET_STATUS.STATUS AS STATUS_NAME
        ');
        $this->db->from('TICKETS');
        $this->db->join('TICKET_DEPARTMENT', 'TICKETS.DEPARTMENT = TICKET_DEPARTMENT.TICKET_DEPARTMENT_ID', 'left');
        $this->db->join('USERS', 'TICKETS.STORE_ID = USERS.USER_ID', 'left');
        $this->db->join('TICKET_PRIORITY', 'TICKETS.PRIORITY = TICKET_PRIORITY.TICKET_PRIORITY_ID', 'left');
        $this->db->join('TICKET_STATUS', 'TICKETS.TICKET_STATUS = TICKET_STATUS.TICKET_STATUS_ID', 'left');

        $this->db->where('TICKET_ID', $id);

        $query = $this->db->get();
        return $query->row_array();
    }
}
