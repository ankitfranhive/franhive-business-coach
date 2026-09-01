<?php

class LeadModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database(); // Load the database library
    }


    public function get_all_leads()
    {

        $query = $this->db->query("
            SELECT 
                L.ENTITY_ID, 
                L.NAME AS ENTITY_NAME, 
                L.CITY, 
                L.LEAD_OWNER, 
                U.NAME AS LEAD_OWNER_NAME, 
                L.COUNTRY, 
                L.LEAD_STATUS, 
                L.EMAIL, 
                L.PHONE, 
                S.STATE_NAME AS STATE, 
                CD.OPTION_VALUE AS LEAD_SOURCE 
            FROM ENTITY L 
            LEFT JOIN USERS U ON L.LEAD_OWNER = U.USER_ID
            LEFT JOIN STATES S ON L.STATE = S.STATE_ID 
            LEFT JOIN COMMON_DROPDOWNS CD ON L.LEAD_SOURCE = CD.OPTION_ID AND CD.DROPDOWN_TYPE = 'leadsource'
            WHERE L.RECORD_STATUS = 0 AND L.IS_LEAD = 'Y'  
            ORDER BY L.CREATED_ON DESC
        ");


        return $query->result_array();
    }

    public function insertLead($data)
    {
        $this->db->insert('ENTITY', $data);
        return $this->db->insert_id();
    }

    public function getLeadById($id)
    {
        $this->db->where('ENTITY_ID', $id);
        $query = $this->db->get('ENTITY');
        return $query->row_array();
    }

    public function updateLead($id, $data)
    {
        $this->db->where('ENTITY_ID', $id);
        $this->db->update('ENTITY', $data);
        return $this->db->affected_rows() > 0;

    }

    public function updateLeadToClient($id){
        $this->db->where('ENTITY_ID', $id);
        $this->db->update('ENTITY', array('IS_LEAD' => 'N'));
        return $this->db->affected_rows() > 0;

    }

    public function deleteLead($id,)
    {
        $this->db->where('ENTITY_ID', $id);
        $this->db->delete('ENTITY');
    }



    public function get_recent_leads()
    {

        $query = $this->db->query("SELECT ENTITY_ID, NAME, CITY, S.STATE_NAME STATE, CD.OPTION_VALUE LEAD_SOURCE FROM ENTITY L LEFT JOIN STATES S ON L.STATE = S.STATE_ID LEFT JOIN COMMON_DROPDOWNS CD ON L.LEAD_SOURCE = CD.OPTION_ID AND CD.DROPDOWN_TYPE = 'leadsource' WHERE  L.RECORD_STATUS = 0 AND IS_LEAD = 'Y'  ORDER BY L.CREATED_ON DESC LIMIT 10");

        return $query->result_array();
    }

    public function count_leads_last_30_days()
{
    // Counts active leads created in the last 30 days
    $sql = "
        SELECT COUNT(*) AS cnt
        FROM ENTITY L
        WHERE L.RECORD_STATUS = 0
          AND L.IS_LEAD = 'Y'
          AND DATE(L.CREATED_ON) >= DATE_SUB(CURDATE(), INTERVAL 90 DAY)
    ";
    $row = $this->db->query($sql)->row_array();
    return (int)($row['cnt'] ?? 0);
}


    public function get_recent_tasks()
    {
        $query = $this->db->query("SELECT LT.*, U.NAME AS ASSIGNED_TO_USER
            FROM FD_TASKS LT
            LEFT JOIN USERS U ON LT.ASSIGN_TO = U.USER_ID
            WHERE LT.RECORD_STATUS = 0
            ORDER BY LT.CREATED_ON DESC LIMIT 10");
    
        return $query->result_array();
    }
    
    public function count_tasks_last_30_days()
{
    $sql = "
        SELECT COUNT(*) AS cnt
        FROM FD_TASKS LT
        WHERE LT.RECORD_STATUS = 0
          AND DATE(LT.CREATED_ON) >= DATE_SUB(CURDATE(), INTERVAL 90 DAY)
    ";
    $row = $this->db->query($sql)->row_array();
    return (int)($row['cnt'] ?? 0);
}



    public function get_all_tasks($user_id, $user_role)
    {
        if ($user_role === 'ADMIN') {
            // Fetch all tasks
            $query = $this->db->query("
                SELECT 
                E.ENTITY_ID, E.NAME, TASK_ID, SUBJECT, 
                OPTION_VALUE AS STATUS, 
                U.NAME AS ACTIVITY_OWNER, 
                FDT.START_DATE AS ACTIVITY_DATE  
                FROM LEAD_TASKS FDT 
                LEFT JOIN ENTITY E ON E.ENTITY_ID = FDT.ENTITY_ID  
                LEFT JOIN USERS U ON FDT.ASSIGN_TO = U.USER_ID 
                LEFT JOIN COMMON_DROPDOWNS CD ON FDT.STATUS = CD.OPTION_ID AND DROPDOWN_TYPE = 'taskstatus'  
                WHERE FDT.RECORD_STATUS = 0 AND IS_LEAD = 'Y'   
                ORDER BY FDT.CREATED_ON DESC
            ");
        

            //$query = $this->db->query("SELECT E.ENTITY_ID,E.NAME,TASK_ID,SUBJECT, OPTION_VALUE AS STATUS, U.NAME ACTIVITY_OWNER, FDT.START_DATE ACTIVITY_DATE  FROM LEAD_TASKS FDT LEFT JOIN ENTITY E ON E.ENTITY_ID = FDT.ENTITY_ID  LEFT JOIN USERS U ON FDT.ASSIGN_TO = U.USER_ID LEFT JOIN COMMON_DROPDOWNS CD ON FDT.STATUS = CD.OPTION_ID AND DROPDOWN_TYPE = 'taskstatus'  WHERE  FDT.RECORD_STATUS = 0 AND IS_LEAD = 'Y'   ORDER BY FDT.CREATED_ON DESC");
            return $query->result_array();
        } else {
            // Fetch tasks assigned to the user
            $query = $this->db->query("
            SELECT 
                LT.*, 
                U.NAME AS ASSIGNED_TO_USER,
                E.NAME AS ENTITY_NAME,
                CD.OPTION_VALUE AS STATUS
            FROM LEAD_TASKS LT
            LEFT JOIN USERS U ON LT.ASSIGN_TO = U.USER_ID
            LEFT JOIN ENTITY E ON LT.ENTITY_ID = E.ENTITY_ID
            LEFT JOIN COMMON_DROPDOWNS CD ON LT.STATUS = CD.OPTION_ID AND CD.DROPDOWN_TYPE = 'taskstatus'
            WHERE LT.RECORD_STATUS = 0 AND LT.ASSIGN_TO = ?
            ORDER BY LT.CREATED_ON DESC
        ", array($user_id));

        
        }
        return $query->result_array();
    }




    public function get_question_details($questionId)
    {
        // Fetch questions from your database
        $this->db->WHERE('QUESTION_ID', $questionId);

        $query = $this->db->get('TEST_SERIES_QUESTIONS');
        return $query->row_array();
    }


    public function save_question($data)
    {
        // Insert the question data into the TEST_SERIES_QUESTION table
        $this->db->insert('TEST_SERIES_QUESTIONS', $data);
    }


    public function update_question($question_id, $data)
    {
        // Update the question in the database
        $this->db->where('QUESTION_ID', $question_id);
        $this->db->update('TEST_SERIES_QUESTIONS', $data); // Change 'your table' to your actual table name
    }


    public function delete_question($question_id)
    {
        // Delete the question from the database
        $this->db->where('QUESTION_ID', $question_id);
        $this->db->delete('TEST_SERIES_QUESTIONS'); // Change 'your table' to your actual table name
    }

    public function get_tests()
    {
        // Fetch questions from your database
        $this->db->where('IS_DEL !=', 1); // Use '!=' to exclude records where IS_DEL is equal to 1
        $this->db->order_by('TEST_ID', 'DESC');
        $query = $this->db->get('TEST_SERIES');
        return $query->result();
    }

    public function get_test_details($test_id)
    {
        // Fetch questions from your database
        $this->db->WHERE('TEST_ID', $test_id);

        $query = $this->db->get('TEST_SERIES');
        return $query->row_array();
    }


    public function get_tests_reports()
    {
        $this->db->select('TEST_USER_RESPONSE.*, TEST_SERIES.TEST_NAME,USERS.NAME');
        $this->db->from('TEST_USER_RESPONSE');
        $this->db->join('TEST_SERIES', 'TEST_SERIES.test_id = TEST_USER_RESPONSE.test_id', 'inner');
        $this->db->join('USERS', 'USERS.USER_ID = TEST_USER_RESPONSE.user_id', 'inner'); // Added join with USER table
        $this->db->order_by('TEST_USER_RESPONSE.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_all_users()
    {
        // Fetch questions from your database
        $this->db->order_by('USER_ID', 'DESC');
        $query = $this->db->get('USERS');
        return $query->result();
    }


    public function save_test($data)
    {
        // Insert the question data into the TEST_SERIES_QUESTION table
        $this->db->insert('TEST_SERIES', $data);
    }


    public function update_test_details($data, $test_id)
    {

        $this->db->where('TEST_ID', $test_id);
        $update_test_data = $this->db->update('TEST_SERIES', $data);

        if ($update_test_data) {
            return true;
        } else {
            return false; // Optionally handle the case where the update fails
        }
    }

    public function save_test_questing_mapping($data)
    {
        // Insert the question data into the TEST_SERIES_QUESTION table
        $add_qustn =  $this->db->insert('TEST_QUESTION_MAPPING', $data);
        $this->db->last_query();
        if ($add_qustn) {
            return true;
        }
    }

    public function save_test_user_mapping($data)
    {
        // Insert the question data into the TEST_SERIES_QUESTION table
        $add_qustn =  $this->db->insert('TEST_USER_ALLOCATION', $data);
        $this->db->last_query();
        if ($add_qustn) {
            return true;
        }
    }



    public function getTestResponses($test_id)
    {
        // Fetch questions from your database
        $query = "select * from TEST_USER_RESPONSE utr inner join TEST_SERIES ts on ts.TEST_ID = utr.test_id
        where utr.id =" . $test_id . "";
        $query = $this->db->query($query);
        return $query->row_array();
    }

    public function get_userassigned_tests($user_id)
    {
        // Fetch questions from your database

        $query = "SELECT TEST_SERIES.*, TEST_USER_ALLOCATION.USER_ID,TEST_USER_ALLOCATION.MAPPING_ID,TEST_USER_ALLOCATION.IS_REMOVED
                    FROM TEST_SERIES 
                    LEFT JOIN TEST_USER_ALLOCATION on TEST_USER_ALLOCATION.TEST_ID = TEST_SERIES.TEST_ID
                    ORDER BY TEST_SERIES.TEST_ID DESC
                    ";

        $query = $this->db->query($query);
        return $query->result();
    }

    public function update_user_test_eval($data)
    {

        $this->db->where('ID', $data['ID']);
        $update_eval_data = $this->db->update('TEST_USER_RESPONSE', $data);

        if ($update_eval_data) {
            return true;
        } else {
            return false; // Optionally handle the case where the update fails
        }
    }


    public function remove_user_test_mapping($mapping_id, $is_removed)
    {
        // Assuming $mapping_id is the ID of the mapping you want to remove

        $data = array(
            'is_removed' => $is_removed // Assuming 'is_removed' is the column you want to update
        );

        $this->db->where('MAPPING_ID', $mapping_id);
        $this->db->update('TEST_USER_ALLOCATION', $data);

        // Check if the update was successful
        $affected_rows = $this->db->affected_rows();

        if ($affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function delete_test($test_id)
    {
        // Update the is_del column in the database
        $this->db->where('TEST_ID', $test_id);
        $update_data = array('IS_DEL' => 1);
        $this->db->update('TEST_SERIES', $update_data);

        // Return true if the update was successful, false otherwise
        return $this->db->affected_rows() > 0;
    }
}
