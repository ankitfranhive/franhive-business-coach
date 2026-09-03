<?php
class BasicConfigModel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function updateBasicConfig($id, $data)
    {
        // echo "<pre>";
        // print_r($data);
        // die;
        $this->db->where('ID', $id);
        $this->db->update('BASIC_CONFIG', $data);
    }

    public function get_all_welcome_note_text()
    {
        $this->db->select('ID, WELCOME_NOTE_TEXT');
        $this->db->from('BASIC_CONFIG');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return [];
        }
    }

    public function insertWelcomeNoteText($data)
    {
        $this->db->insert('BASIC_CONFIG', $data);
    }

    public function deleteWelcomeNoteText($id)
    {
        $this->db->where('ID', $id);
        $this->db->delete('BASIC_CONFIG');
    }

    public function get_welcome_note_text($id)
    {
        $this->db->select('ID, WELCOME_NOTE_TEXT');
        $this->db->from('BASIC_CONFIG');
        $this->db->where('ID', $id);

        $query = $this->db->get();
        return $query->row_array();
    }
}
