<?php
class LandingPage_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function update()
    {
        // $sql = "ALTER TABLE LANDING_PAGES ADD COLUMN LANDING_PAGE_MAIN_CONTENT LONGTEXT AFTER BANNNer_IMAGE;";
        // $sql = "ALTER TABLE LANDING_PAGES CHANGE COLUMN BANNNER_IMAGE BANNER_IMAGE LONGTEXT;";
        // $this->db->query($sql);
    }

    public function updateLandingPage($id, $data)
    {
        $this->db->where('LANDING_PAGE_ID', $id);
        $this->db->update('LANDING_PAGES', $data);
    }

    public function get_all_landingpages()
    {
        $this->db->select('LANDING_PAGE_ID, LANDING_PAGE_NAME, LANDING_PAGE_URL');
        $this->db->from('LANDING_PAGES');
        $query = $this->db->get();

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return [];
        }
    }

    public function get_all_videosOfLandingPage($id)
    {
        $this->db->select('VIDEO_ID, VIDEO_TITLE, VIDEO_TEXT');
        $this->db->from('LANDING_PAGES_VIDEOS');
        // $this->db->where('LANDING_PAGE_ID', $id);

        $query = $this->db->get();

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return [];
        }
    }

    public function insertLandingPage($data)
    {
        // Print the query before execution
        $this->db->set($data);
        $query = $this->db->get_compiled_insert('LANDING_PAGES');
        // echo "Query to execute: " . $query;
    
        // Execute the insert
        $insert = $this->db->insert('LANDING_PAGES', $data);
    
        // Check for errors
        if (!$insert) {
            $error = $this->db->error(); // Retrieve error message
            // echo "Insert failed: " . $error['message'];die;
        } else {
            echo "Insert successful!";
        }
    }
    

    public function getLandingPageById($id)
{
    $this->db->select('
        LANDING_PAGES.*,
        email_template.TEMPLATE_NAME as email_template_name,
        payment_template.TEMPLATE_NAME as payment_template_name
    ');
    $this->db->from('LANDING_PAGES');
    $this->db->join('TEMPLATES as email_template', 'LANDING_PAGES.REGISTERED_TEMPLATE = email_template.TEMPLATE_ID', 'left');
    $this->db->join('TEMPLATES as payment_template', 'LANDING_PAGES.PAYMENT_TEMPLATE = payment_template.TEMPLATE_ID', 'left'); // ✅ fixed typo
    $this->db->where('LANDING_PAGE_ID', $id);

    $query = $this->db->get();

    // Debug if something goes wrong
    if (!$query) {
        log_message('error', 'DB Error: ' . print_r($this->db->error(), true));
        return false;
    }

    return $query->row_array();
}


    public function deleteLandingPage($id)
    {
        $this->db->where('LANDING_PAGE_ID', $id);
        $this->db->delete('LANDING_PAGES');
    }

    public function insertVideo($data)
    {
        $this->db->insert('LANDING_PAGES_VIDEOS', $data);
    }

    public function updateVideo($id, $data)
    {
        $this->db->where('VIDEO_ID', $id);
        $this->db->update('LANDING_PAGES_VIDEOS', $data);
    }

    public function get_videoData($id)
    {
        $this->db->select('*');
        $this->db->from('LANDING_PAGES_VIDEOS');
        $this->db->where('VIDEO_ID', $id);

        $query = $this->db->get();
        return $query->row_array();
    }

    public function deleteVideo($id)
    {
        $this->db->where('VIDEO_ID', $id);
        $this->db->delete('LANDING_PAGES_VIDEOS');
    }

    public function save_contact($data) {
        return $this->db->insert('LANDINGPAGE_CONTACTS_DATA', $data);
    }
}
