<?php

class AdminConsoleModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database(); // Load the database library
    }

    public function get_data()
    {
        // Write your select query here
        $query = $this->db->get('COURSES');
        return $query->result(); // Return the result
    }

    public function get_questions()
    {
        // Fetch questions from your database
        $this->db->order_by('QUESTION_ID', 'DESC');
        $query = $this->db->get('TEST_SERIES_QUESTIONS');
        return $query->result();
    }


    public function get_questions_in_test($test_id )
    {
        // Fetch questions from your database
        $query = "SELECT 
        TEST_SERIES_QUESTIONS.QUESTION_GROUP,TEST_SERIES_QUESTIONS.QUESTION_ID,TEST_SERIES_QUESTIONS.QUESTION_NAME,
        TEST_QUESTION_MAPPING.ID as `MAPPING_ID`,
        TEST_QUESTION_MAPPING.MAP_STATUS 
        FROM TEST_SERIES_QUESTIONS
        LEFT JOIN TEST_QUESTION_MAPPING ON TEST_QUESTION_MAPPING.QUESTION_ID = TEST_SERIES_QUESTIONS.QUESTION_ID AND TEST_QUESTION_MAPPING.TEST_ID =".$test_id ."
        ORDER BY QUESTION_ID DESC
                            ";

        $query = $this->db->query($query);
        return $query->result();
    }


    

    // public function get_question_details($questionId)
    // {
    //     // Fetch questions from your database
    //     $this->db->WHERE('QUESTION_ID', $questionId);

    //     $query = $this->db->get('TEST_SERIES_QUESTIONS');
    //     return $query->row_array();
    // }


    public function save_question($data)
    {
        // Validate data before inserting
        if (is_array($data) && !empty($data)) {
            if ($this->db->insert('TEST_SERIES_QUESTIONS', $data)) {
                return true; // Successfully inserted
            } else {
                // Log or display the database error
                $error = $this->db->error(); // Get the error details
                log_message('error', 'Database Error: ' . $error['message']); // Log the error
                echo 'Database Error: ' . $error['message']; // Display the error (for debugging only)
                return false;
            }
        }
        echo 'Invalid data provided.'; // Display an error message for invalid data
        return false;
    }



    public function save_question_with_options($questionData, $options = [])
    {
        $this->db->trans_start();
    
        $this->db->insert('TEST_SERIES_QUESTIONS', $questionData);
        $question_id = $this->db->insert_id();
    
        if (!$question_id) {
            $this->db->trans_rollback();
            return false;
        }
    
        if (!empty($options)) {
            foreach ($options as &$opt) {
                $opt['QUESTION_ID'] = $question_id;
                $opt['CREATED_ON']  = date('Y-m-d H:i:s');
            }
            unset($opt);
    
            $this->db->insert_batch('TEST_SERIES_QUESTION_OPTIONS', $options);
        }
    
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    


    
    public function update_question($question_id, $data)
    {
        $this->db->where('QUESTION_ID', $question_id);
        $this->db->update('TEST_SERIES_QUESTIONS', $data);
    }
    


    public function delete_question($question_id)
    {
        // Delete the question from the database
        $this->db->where('QUESTION_ID', $question_id);
        $this->db->delete('TEST_SERIES_QUESTIONS'); // Change 'your table' to your actual table name
    }


    public function get_question_details($questionId)
    {
        $this->db->where('QUESTION_ID', $questionId);
        $q = $this->db->get('TEST_SERIES_QUESTIONS');
        return $q->row_array();
    }
    
    public function get_question_options($questionId)
    {
        // 1) Try new table first
        $this->db->where('QUESTION_ID', $questionId);
        $this->db->order_by('SORT_ORDER', 'ASC');
        $q = $this->db->get('TEST_SERIES_QUESTION_OPTIONS');
    
        if ($q->num_rows() > 0) {
            return $q->result_array(); // each row has OPTION_TEXT, IS_CORRECT, OPTION_MARKS...
        }
    
        // 2) Fallback legacy columns option_1..4
        $question = $this->get_question_details($questionId);
        if (!$question) return [];
    
        $legacy = [];
        for ($i = 1; $i <= 4; $i++) {
            $text = trim($question["option_$i"] ?? '');
            if ($text === '') continue;
    
            $legacy[] = [
                'OPTION_ID'     => null, // legacy has no id
                'OPTION_TEXT'   => $text,
                'IS_CORRECT'    => (int)($question["is_correct_$i"] ?? 0),
                'OPTION_MARKS'  => 0, // legacy doesn't have per option marks
                'SORT_ORDER'    => $i,
                'IS_LEGACY'     => 1
            ];
        }
        return $legacy;
    }
    

    public function update_question_with_options($question_id, $data, $question_type, $options = [])
    {
        $this->db->trans_start();
    
        // Update main question table
        $this->db->where('QUESTION_ID', $question_id);
        $this->db->update('TEST_SERIES_QUESTIONS', $data);
    
        if ($question_type == 1) {
            // MCQ: Replace options in new table
            // Delete existing new-style options (if any)
            $this->db->where('QUESTION_ID', $question_id);
            $this->db->delete('TEST_SERIES_QUESTION_OPTIONS');
    
            // Insert new ones
            if (!empty($options)) {
                foreach ($options as &$opt) {
                    $opt['QUESTION_ID'] = $question_id;
                    $opt['CREATED_ON']  = date('Y-m-d H:i:s');
                }
                unset($opt);
    
                $this->db->insert_batch('TEST_SERIES_QUESTION_OPTIONS', $options);
            }
    
            // OPTIONAL (recommended): clear legacy option_1..4 so you don't have two sources
            // If you want to keep legacy untouched, comment this out.
            $clearLegacy = [
                'option_1' => null, 'option_2' => null, 'option_3' => null, 'option_4' => null,
                'is_correct_1' => 0, 'is_correct_2' => 0, 'is_correct_3' => 0, 'is_correct_4' => 0
            ];
            $this->db->where('QUESTION_ID', $question_id);
            $this->db->update('TEST_SERIES_QUESTIONS', $clearLegacy);
    
        } else {
            // Subjective: Remove new table options (if any)
            $this->db->where('QUESTION_ID', $question_id);
            $this->db->delete('TEST_SERIES_QUESTION_OPTIONS');
    
            // And clear legacy options too
            $clearLegacy = [
                'option_1' => null, 'option_2' => null, 'option_3' => null, 'option_4' => null,
                'is_correct_1' => 0, 'is_correct_2' => 0, 'is_correct_3' => 0, 'is_correct_4' => 0
            ];
            $this->db->where('QUESTION_ID', $question_id);
            $this->db->update('TEST_SERIES_QUESTIONS', $clearLegacy);
        }
    
        $this->db->trans_complete();
        return $this->db->trans_status();
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
        $sql = "
            SELECT
                ts.TEST_ID,
                ts.TEST_NAME,
                ts.TEST_TYPE,
                ts.TOTAL_MARKS,
    
                u.USER_ID,
                u.NAME AS USER_NAME,
    
                tur_latest.ID AS RESPONSE_ID,
                tur_latest.MARKS_OBTAINED,
                tur_latest.IS_EVAL,
                tur_latest.IS_SUBMITTED,
                tur_latest.updated_at AS SUBMIT_DATE,
    
                cnt.ATTEMPTS
    
            FROM TEST_USER_ALLOCATION tua
            INNER JOIN TEST_SERIES ts ON ts.TEST_ID = tua.TEST_ID
            INNER JOIN USERS u ON u.USER_ID = tua.USER_ID
    
            -- ✅ attempts count
            LEFT JOIN (
                SELECT USER_ID, TEST_ID, COUNT(*) AS ATTEMPTS
                FROM TEST_USER_RESPONSE
                GROUP BY USER_ID, TEST_ID
            ) cnt ON cnt.USER_ID = tua.USER_ID AND cnt.TEST_ID = tua.TEST_ID
    
            -- ✅ latest attempt id
            LEFT JOIN (
                SELECT USER_ID, TEST_ID, MAX(ID) AS LAST_ID
                FROM TEST_USER_RESPONSE
                GROUP BY USER_ID, TEST_ID
            ) last ON last.USER_ID = tua.USER_ID AND last.TEST_ID = tua.TEST_ID
    
            -- ✅ latest attempt row
            LEFT JOIN TEST_USER_RESPONSE tur_latest ON tur_latest.ID = last.LAST_ID
    
            WHERE ts.IS_DEL = 0
            ORDER BY tur_latest.ID DESC
        ";
    
        $q = $this->db->query($sql);
    
        // ✅ Guard + show real error
        if ($q === false) {
            log_message('error', 'get_tests_reports SQL failed: ' . $this->db->last_query());
            log_message('error', 'DB error: ' . print_r($this->db->error(), true));
            return []; // prevent fatal error
        }
    
        return $q->result();
    }
    




    public function get_attempts_by_test_user($test_id, $user_id)
{
    $test_id = (int)$test_id;
    $user_id = (int)$user_id;

    $sql = "
        SELECT
            tur.*,
            ts.TEST_NAME,
            ts.TEST_TYPE,
            ts.TOTAL_MARKS,
            u.NAME AS USER_NAME
        FROM TEST_USER_RESPONSE tur
        INNER JOIN TEST_SERIES ts ON ts.TEST_ID = tur.TEST_ID
        INNER JOIN USERS u ON u.USER_ID = tur.USER_ID
        WHERE tur.TEST_ID = ?
          AND tur.USER_ID = ?
        ORDER BY tur.ID DESC
    ";

    $q = $this->db->query($sql, [$test_id, $user_id]);

    if ($q === false) {
        log_message('error', 'get_attempts_by_test_user failed: ' . print_r($this->db->error(), true));
        return [];
    }

    return $q->result();
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
        $query = "SELECT 
            utr.*, 
            ts.TEST_NAME, 
            ts.TEST_START_DATE, 
            ts.TEST_TYPE, 
            ts.TOTAL_QUESTIONS, 
            ts.TEST_DURATION,
            usr.EMAIL,
            usr.NAME,
            usr.PROFILE_PICTURE
        FROM TEST_USER_RESPONSE utr 
        INNER JOIN TEST_SERIES ts ON ts.TEST_ID = utr.test_id
        INNER JOIN USERS usr ON utr.USER_ID = usr.USER_ID
        WHERE utr.id = ? ";
    
        $query = $this->db->query($query, [$test_id]);
    
        if (!$query) {
            log_message('error', 'Database error: ' . $this->db->error()['message']); // Log error
            return false;
        }
    
        return $query->row_array();
    }
    

    public function get_userassigned_tests($user_id)
    {
        // Fetch questions from your database

        $query = "SELECT TEST_SERIES.*, TEST_USER_ALLOCATION.USER_ID,TEST_USER_ALLOCATION.MAPPING_ID,TEST_USER_ALLOCATION.IS_REMOVED
                    FROM TEST_SERIES 
                    LEFT JOIN TEST_USER_ALLOCATION on TEST_USER_ALLOCATION.TEST_ID = TEST_SERIES.TEST_ID AND TEST_USER_ALLOCATION.USER_ID = ".$user_id."
                  
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
