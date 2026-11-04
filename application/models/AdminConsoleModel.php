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
        $this->db->select('QUESTION_ID, QUESTION_NAME, QUESTION_GROUP, QUESTION_TYPE, QUESTION_MARKS');
        $this->db->order_by('QUESTION_ID', 'DESC');
        $query = $this->db->get('TEST_SERIES_QUESTIONS');
        return $query->result();
    }

    /**
     * Server-side DataTables feed for question bank (optional test mapping).
     *
     * @return array{draw:int,recordsTotal:int,recordsFiltered:int,data:array<int,array>}
     */
    public function admin_questions_datatable(array $in)
    {
        $test_id = (int) ($in['test_id'] ?? 0);
        $draw = (int) ($in['draw'] ?? 1);
        $start = max(0, (int) ($in['start'] ?? 0));
        $length = (int) ($in['length'] ?? 25);
        if ($length <= 0) {
            $length = 25;
        }
        if ($length > 100) {
            $length = 100;
        }

        $searchRaw = $in['search']['value'] ?? ($in['search'] ?? '');
        $search = trim(strip_tags((string) $searchRaw));
        $filter_group = trim((string) ($in['filter_group'] ?? ''));
        $filter_type = (string) ($in['filter_type'] ?? '');
        $filter_in_test = (string) ($in['filter_in_test'] ?? '');

        $orderCol = 1;
        $orderDir = 'DESC';
        if (!empty($in['order'][0])) {
            $orderCol = (int) ($in['order'][0]['column'] ?? 1);
            $orderDir = strtolower((string) ($in['order'][0]['dir'] ?? 'desc')) === 'asc' ? 'ASC' : 'DESC';
        }

        $join = '';
        $selectExtra = '';
        if ($test_id > 0) {
            $join = ' LEFT JOIN TEST_QUESTION_MAPPING m ON m.QUESTION_ID = q.QUESTION_ID AND m.TEST_ID = ' . $test_id;
            $selectExtra = ', m.ID AS MAPPING_ID';
        }

        $where = ' WHERE 1=1 ';
        $binds = [];

        if ($filter_group !== '') {
            $where .= ' AND q.QUESTION_GROUP = ? ';
            $binds[] = $filter_group;
        }
        if ($filter_type !== '' && is_numeric($filter_type)) {
            $where .= ' AND q.QUESTION_TYPE = ? ';
            $binds[] = (int) $filter_type;
        }
        if ($test_id > 0 && $filter_in_test === '1') {
            $where .= ' AND m.ID IS NOT NULL ';
        }
        if ($test_id > 0 && $filter_in_test === '0') {
            $where .= ' AND m.ID IS NULL ';
        }
        if ($search !== '') {
            $like = '%' . $this->db->escape_like_str($search) . '%';
            $where .= ' AND (q.QUESTION_NAME LIKE ? OR q.QUESTION_GROUP LIKE ? OR q.QUESTION_ID = ?) ';
            $binds[] = $like;
            $binds[] = $like;
            $binds[] = (int) $search;
        }

        if ($test_id > 0) {
            $colMap = [
                0 => 'q.QUESTION_ID',
                1 => 'q.QUESTION_ID',
                2 => 'q.QUESTION_NAME',
                3 => 'q.QUESTION_GROUP',
                4 => 'q.QUESTION_TYPE',
                5 => 'q.QUESTION_MARKS',
                6 => 'q.QUESTION_ID',
                7 => 'q.QUESTION_ID',
            ];
        } else {
            $colMap = [
                0 => 'q.QUESTION_ID',
                1 => 'q.QUESTION_NAME',
                2 => 'q.QUESTION_GROUP',
                3 => 'q.QUESTION_MARKS',
                4 => 'q.QUESTION_ID',
            ];
        }
        $orderBy = $colMap[$orderCol] ?? 'q.QUESTION_ID';

        $from = ' FROM TEST_SERIES_QUESTIONS q ' . $join;

        $totalRow = $this->db->query('SELECT COUNT(*) AS c FROM TEST_SERIES_QUESTIONS q')->row_array();
        $recordsTotal = (int) ($totalRow['c'] ?? 0);

        $filteredRow = $this->db->query('SELECT COUNT(*) AS c ' . $from . $where, $binds)->row_array();
        $recordsFiltered = (int) ($filteredRow['c'] ?? 0);

        $sql = 'SELECT q.QUESTION_ID, q.QUESTION_NAME, q.QUESTION_GROUP, q.QUESTION_TYPE, q.QUESTION_MARKS'
            . $selectExtra . $from . $where
            . ' ORDER BY ' . $orderBy . ' ' . $orderDir
            . ' LIMIT ' . (int) $start . ', ' . (int) $length;

        $rows = $this->db->query($sql, $binds)->result_array();
        $data = [];
        foreach ($rows as $row) {
            $data[] = [
                'QUESTION_ID'   => (int) ($row['QUESTION_ID'] ?? 0),
                'QUESTION_NAME' => strip_tags((string) ($row['QUESTION_NAME'] ?? '')),
                'QUESTION_GROUP'=> trim((string) ($row['QUESTION_GROUP'] ?? '')),
                'QUESTION_TYPE' => (int) ($row['QUESTION_TYPE'] ?? 2),
                'QUESTION_MARKS'=> (string) ($row['QUESTION_MARKS'] ?? ''),
                'MAPPING_ID'    => isset($row['MAPPING_ID']) ? (int) $row['MAPPING_ID'] : 0,
                'IN_TEST'       => !empty($row['MAPPING_ID']) ? 1 : 0,
            ];
        }

        return [
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ];
    }

    public function get_questions_in_test($test_id)
    {
        $test_id = (int) $test_id;
        $sql = "
            SELECT
                q.QUESTION_GROUP,
                q.QUESTION_ID,
                q.QUESTION_NAME,
                q.QUESTION_TYPE,
                q.QUESTION_MARKS,
                m.ID AS MAPPING_ID,
                m.MAP_STATUS
            FROM TEST_SERIES_QUESTIONS q
            INNER JOIN TEST_QUESTION_MAPPING m
                ON m.QUESTION_ID = q.QUESTION_ID AND m.TEST_ID = ?
            ORDER BY COALESCE(m.QUESTION_SORT, m.ID), m.ID
        ";
        return $this->db->query($sql, [$test_id])->result();
    }

    public function get_distinct_question_groups()
    {
        $this->db->distinct();
        $this->db->select('QUESTION_GROUP');
        $this->db->from('TEST_SERIES_QUESTIONS');
        $this->db->where('QUESTION_GROUP IS NOT NULL', null, false);
        $this->db->where('QUESTION_GROUP !=', '');
        $this->db->order_by('QUESTION_GROUP', 'ASC');
        return $this->db->get()->result();
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
        $this->db->select('TEST_ID, TEST_NAME, TOTAL_QUESTIONS, TOTAL_MARKS, TEST_START_DATE, TEST_TYPE');
        $this->db->where('IS_DEL !=', 1);
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
                COALESCE(cnt.ATTEMPTS, 0) AS ATTEMPTS
            FROM TEST_USER_ALLOCATION tua
            INNER JOIN TEST_SERIES ts ON ts.TEST_ID = tua.TEST_ID
            INNER JOIN USERS u ON u.USER_ID = tua.USER_ID
            LEFT JOIN (
                SELECT USER_ID, TEST_ID, COUNT(*) AS ATTEMPTS
                FROM TEST_USER_RESPONSE
                GROUP BY USER_ID, TEST_ID
            ) cnt ON cnt.USER_ID = tua.USER_ID AND cnt.TEST_ID = tua.TEST_ID
            LEFT JOIN TEST_USER_RESPONSE tur_latest ON tur_latest.ID = (
                SELECT MAX(t2.ID)
                FROM TEST_USER_RESPONSE t2
                WHERE t2.USER_ID = tua.USER_ID AND t2.TEST_ID = tua.TEST_ID
            )
            WHERE ts.IS_DEL = 0
              AND COALESCE(tua.IS_REMOVED, 0) = 0
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
        $this->db->order_by('USER_ID', 'DESC');
        $query = $this->db->get('USERS');
        return $query->result();
    }

    public function get_users_for_test_allocation()
    {
        $sql = "
            SELECT
                u.USER_ID,
                u.NAME,
                u.EMAIL,
                COALESCE(cnt.alloc_count, 0) AS ALLOCATED_TESTS
            FROM USERS u
            LEFT JOIN (
                SELECT USER_ID, COUNT(*) AS alloc_count
                FROM TEST_USER_ALLOCATION
                WHERE COALESCE(IS_REMOVED, 0) = 0
                GROUP BY USER_ID
            ) cnt ON cnt.USER_ID = u.USER_ID
            ORDER BY u.USER_ID DESC
        ";
        $q = $this->db->query($sql);
        return $q ? $q->result() : [];
    }

    public function get_user_by_id($user_id)
    {
        $user_id = (int) $user_id;
        if ($user_id <= 0) {
            return null;
        }
        $this->db->where('USER_ID', $user_id);
        return $this->db->get('USERS')->row();
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
        $test_id = (int) ($data['TEST_ID'] ?? 0);
        $question_id = (int) ($data['QUESTION_ID'] ?? 0);
        if ($test_id <= 0 || $question_id <= 0) {
            return false;
        }
        $this->db->where('TEST_ID', $test_id);
        $this->db->where('QUESTION_ID', $question_id);
        if ($this->db->count_all_results('TEST_QUESTION_MAPPING') > 0) {
            return false;
        }
        return (bool) $this->db->insert('TEST_QUESTION_MAPPING', $data);
    }

    /**
     * @return array{added:int, skipped:int, question_ids:int[]}
     */
    public function save_test_question_mappings_bulk($test_id, array $question_ids)
    {
        $test_id = (int) $test_id;
        $question_ids = array_values(array_unique(array_filter(array_map('intval', $question_ids))));
        if ($test_id <= 0 || empty($question_ids)) {
            return ['added' => 0, 'skipped' => 0, 'question_ids' => []];
        }

        $this->db->select('QUESTION_ID');
        $this->db->where('TEST_ID', $test_id);
        $this->db->where_in('QUESTION_ID', $question_ids);
        $existing = array_map('intval', array_column($this->db->get('TEST_QUESTION_MAPPING')->result_array(), 'QUESTION_ID'));

        $added = 0;
        $skipped = 0;
        $added_ids = [];
        foreach ($question_ids as $qid) {
            if (in_array($qid, $existing, true)) {
                $skipped++;
                continue;
            }
            if ($this->db->insert('TEST_QUESTION_MAPPING', [
                'QUESTION_ID' => $qid,
                'TEST_ID'     => $test_id,
            ])) {
                $added++;
                $added_ids[] = $qid;
                $existing[] = $qid;
            } else {
                $skipped++;
            }
        }

        return ['added' => $added, 'skipped' => $skipped, 'question_ids' => $added_ids];
    }

    public function save_test_user_mapping($data)
    {
        $test_id = (int) ($data['TEST_ID'] ?? 0);
        $user_id = (int) ($data['USER_ID'] ?? 0);
        if ($test_id <= 0 || $user_id <= 0) {
            return false;
        }
        $this->db->where('TEST_ID', $test_id);
        $this->db->where('USER_ID', $user_id);
        $existing = $this->db->get('TEST_USER_ALLOCATION')->result();
        foreach ($existing as $row) {
            if ((int) ($row->IS_REMOVED ?? 0) === 0) {
                return false;
            }
        }
        return (bool) $this->db->insert('TEST_USER_ALLOCATION', $data);
    }

    /**
     * @return array{added:int, skipped:int, test_ids:int[]}
     */
    public function save_test_user_mappings_bulk($user_id, array $test_ids)
    {
        $user_id = (int) $user_id;
        $test_ids = array_values(array_unique(array_filter(array_map('intval', $test_ids))));
        if ($user_id <= 0 || empty($test_ids)) {
            return ['added' => 0, 'skipped' => 0, 'test_ids' => []];
        }

        $this->db->select('TEST_ID, IS_REMOVED');
        $this->db->where('USER_ID', $user_id);
        $this->db->where_in('TEST_ID', $test_ids);
        $existing = [];
        foreach ($this->db->get('TEST_USER_ALLOCATION')->result_array() as $row) {
            if ((int) ($row['IS_REMOVED'] ?? 0) === 0) {
                $existing[] = (int) $row['TEST_ID'];
            }
        }
        $existing = array_values(array_unique($existing));

        $added = 0;
        $skipped = 0;
        $added_ids = [];
        foreach ($test_ids as $tid) {
            if (in_array($tid, $existing, true)) {
                $skipped++;
                continue;
            }
            if ($this->db->insert('TEST_USER_ALLOCATION', [
                'TEST_ID' => $tid,
                'USER_ID' => $user_id,
            ])) {
                $added++;
                $added_ids[] = $tid;
                $existing[] = $tid;
            } else {
                $skipped++;
            }
        }

        return ['added' => $added, 'skipped' => $skipped, 'test_ids' => $added_ids];
    }



    /**
     * Map lowercase TEST_USER_RESPONSE columns to legacy uppercase keys used in views.
     */
    public function normalize_test_user_response_row($row)
    {
        if (empty($row) || !is_array($row)) {
            return $row;
        }
        $pairs = [
            'id'             => 'ID',
            'user_id'        => 'USER_ID',
            'test_id'        => 'TEST_ID',
            'user_response'  => 'USER_RESPONSE',
            'is_submitted'   => 'IS_SUBMITTED',
            'is_eval'        => 'IS_EVAL',
            'marks_obtained' => 'MARKS_OBTAINED',
            'total_marks'    => 'TOTAL_MARKS',
        ];
        foreach ($pairs as $lower => $upper) {
            if (!array_key_exists($upper, $row) && array_key_exists($lower, $row)) {
                $row[$upper] = $row[$lower];
            }
        }
        return $row;
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

        $row = $query->row_array();
        return $this->normalize_test_user_response_row($row);
    }
    

    public function get_userassigned_tests($user_id)
    {
        $user_id = (int) $user_id;
        $sql = "
            SELECT
                ts.TEST_ID,
                ts.TEST_NAME,
                ts.TEST_TYPE,
                ts.TOTAL_QUESTIONS,
                ts.TOTAL_MARKS,
                ts.TEST_START_DATE,
                ts.END_DATE,
                tua.USER_ID,
                tua.MAPPING_ID,
                tua.IS_REMOVED
            FROM TEST_SERIES ts
            LEFT JOIN TEST_USER_ALLOCATION tua
                ON tua.TEST_ID = ts.TEST_ID AND tua.USER_ID = ?
            WHERE COALESCE(ts.IS_DEL, 0) = 0
            ORDER BY ts.TEST_ID DESC
        ";
        $q = $this->db->query($sql, [$user_id]);
        return $q ? $q->result() : [];
    }

    public function update_user_test_eval($data)
    {
        $id = (int)($data['ID'] ?? $data['id'] ?? 0);
        if ($id <= 0) {
            return false;
        }
        unset($data['ID'], $data['id']);
        if (isset($data['IS_EVAL'])) {
            $data['is_eval'] = $data['IS_EVAL'];
            unset($data['IS_EVAL']);
        }
        if (isset($data['MARKS_OBTAINED'])) {
            $data['marks_obtained'] = $data['MARKS_OBTAINED'];
            unset($data['MARKS_OBTAINED']);
        }
        if (isset($data['TOTAL_MARKS'])) {
            $data['total_marks'] = $data['TOTAL_MARKS'];
            unset($data['TOTAL_MARKS']);
        }
        if (isset($data['MARKS_DATA'])) {
            $data['marks_data'] = $data['MARKS_DATA'];
            unset($data['MARKS_DATA']);
        }

        $this->db->where('id', $id);
        $update_eval_data = $this->db->update('TEST_USER_RESPONSE', $data);

        if ($update_eval_data) {
            return true;
        } else {
            return false; // Optionally handle the case where the update fails
        }
    }


    public function grant_test_reattempt_for_user($user_id, $test_id)
    {
        $this->load->helper('test_reattempt');
        ensure_test_user_reattempt_grant_table($this->db);

        $user_id = (int)$user_id;
        $test_id = (int)$test_id;
        if ($user_id <= 0 || $test_id <= 0 || !$this->db->table_exists('TEST_USER_REATTEMPT_GRANT')) {
            return false;
        }
        $now = date('Y-m-d H:i:s');
        return $this->db->query(
            'INSERT INTO TEST_USER_REATTEMPT_GRANT (user_id, test_id, granted_at) VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE granted_at = VALUES(granted_at)',
            [$user_id, $test_id, $now]
        );
    }

    public function clear_test_reattempt_grant_for_user($user_id, $test_id)
    {
        $user_id = (int)$user_id;
        $test_id = (int)$test_id;
        if ($user_id <= 0 || $test_id <= 0 || !$this->db->table_exists('TEST_USER_REATTEMPT_GRANT')) {
            return false;
        }
        $this->db->where('user_id', $user_id)->where('test_id', $test_id)
            ->delete('TEST_USER_REATTEMPT_GRANT');
        return true;
    }

    public function remove_user_test_mapping($mapping_id, $is_removed)
    {
        $this->load->helper('test_reattempt');
        ensure_test_user_reattempt_grant_table($this->db);

        $is_removed = (int)$is_removed;
        $mapping_id = (int)$mapping_id;
        $removedCol = test_db_column($this->db, 'TEST_USER_ALLOCATION', ['IS_REMOVED', 'is_removed'], 'IS_REMOVED');
        $mapIdCol = test_db_column($this->db, 'TEST_USER_ALLOCATION', ['MAPPING_ID', 'mapping_id'], 'MAPPING_ID');

        $data = [$removedCol => $is_removed];
        if ($this->db->field_exists('ALLOW_REATTEMPT', 'TEST_USER_ALLOCATION')) {
            $data['ALLOW_REATTEMPT'] = ($is_removed === 0) ? 1 : 0;
        } elseif ($this->db->field_exists('allow_reattempt', 'TEST_USER_ALLOCATION')) {
            $data['allow_reattempt'] = ($is_removed === 0) ? 1 : 0;
        }

        $map = $this->db->get_where('TEST_USER_ALLOCATION', [$mapIdCol => $mapping_id])->row_array();
        if (empty($map)) {
            return false;
        }

        $this->db->where($mapIdCol, $mapping_id);
        $this->db->update('TEST_USER_ALLOCATION', $data);

        $uid = (int)($map['USER_ID'] ?? $map['user_id'] ?? 0);
        $tid = (int)($map['TEST_ID'] ?? $map['test_id'] ?? 0);
        if ($uid > 0 && $tid > 0) {
            if ($is_removed === 0) {
                $this->grant_test_reattempt_for_user($uid, $tid);
            } else {
                $this->clear_test_reattempt_grant_for_user($uid, $tid);
            }
        }

        return true;
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

    public function get_test_sections($test_id)
    {
        $this->load->helper('test_sections');
        ensure_test_sections_schema($this->db);
        $test_id = (int) $test_id;
        $this->db->where('TEST_ID', $test_id);
        $this->db->order_by('SORT_ORDER', 'ASC');
        $this->db->order_by('SECTION_ID', 'ASC');
        return $this->db->get('TEST_SECTIONS')->result_array();
    }

    public function sync_test_sections($test_id, array $sections)
    {
        $this->load->helper('test_sections');
        ensure_test_sections_schema($this->db);
        $test_id = (int) $test_id;
        if ($test_id <= 0) {
            return false;
        }

        $this->db->trans_start();
        $keepIds = [];
        $sort = 0;
        foreach ($sections as $sec) {
            $name = trim($sec['name'] ?? $sec['SECTION_NAME'] ?? '');
            if ($name === '') {
                continue;
            }
            $sort++;
            $sid = (int) ($sec['id'] ?? $sec['SECTION_ID'] ?? 0);
            if ($sid > 0) {
                $this->db->where('SECTION_ID', $sid);
                $this->db->where('TEST_ID', $test_id);
                $this->db->update('TEST_SECTIONS', [
                    'SECTION_NAME' => $name,
                    'SORT_ORDER'   => $sort,
                ]);
                $keepIds[] = $sid;
            } else {
                $this->db->insert('TEST_SECTIONS', [
                    'TEST_ID'      => $test_id,
                    'SECTION_NAME' => $name,
                    'SORT_ORDER'   => $sort,
                    'CREATED_ON'   => date('Y-m-d H:i:s'),
                ]);
                $keepIds[] = (int) $this->db->insert_id();
            }
        }

        if (!empty($keepIds)) {
            $this->db->where('TEST_ID', $test_id);
            $this->db->where_not_in('SECTION_ID', $keepIds);
            $removed = $this->db->get('TEST_SECTIONS')->result_array();
            foreach ($removed as $r) {
                $rid = (int) $r['SECTION_ID'];
                $this->db->where('SECTION_ID', $rid)->update('TEST_QUESTION_MAPPING', ['SECTION_ID' => null]);
            }
            $this->db->where('TEST_ID', $test_id);
            $this->db->where_not_in('SECTION_ID', $keepIds);
            $this->db->delete('TEST_SECTIONS');
        } else {
            $this->db->where('TEST_ID', $test_id)->update('TEST_QUESTION_MAPPING', ['SECTION_ID' => null]);
            $this->db->where('TEST_ID', $test_id)->delete('TEST_SECTIONS');
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function get_test_mapped_questions_admin($test_id)
    {
        $this->load->helper('test_sections');
        ensure_test_sections_schema($this->db);
        $test_id = (int) $test_id;
        $sql = "
            SELECT
                tqm.ID AS MAPPING_ID,
                tqm.QUESTION_ID,
                tqm.SECTION_ID,
                tqm.QUESTION_SORT,
                tsq.QUESTION_NAME,
                tsq.QUESTION_GROUP,
                sec.SECTION_NAME,
                sec.SORT_ORDER AS SECTION_SORT
            FROM TEST_QUESTION_MAPPING tqm
            INNER JOIN TEST_SERIES_QUESTIONS tsq ON tsq.QUESTION_ID = tqm.QUESTION_ID
            LEFT JOIN TEST_SECTIONS sec ON sec.SECTION_ID = tqm.SECTION_ID
            WHERE tqm.TEST_ID = ?
            ORDER BY COALESCE(sec.SORT_ORDER, 9999), COALESCE(tqm.QUESTION_SORT, tqm.ID), tqm.ID
        ";
        $q = $this->db->query($sql, [$test_id]);
        return $q ? $q->result_array() : [];
    }

    public function update_test_question_sections($test_id, array $assignments)
    {
        $this->load->helper('test_sections');
        ensure_test_sections_schema($this->db);
        $test_id = (int) $test_id;
        if ($test_id <= 0) {
            return false;
        }

        $sections = $this->get_test_sections($test_id);
        $sectionIds = array_map(function ($s) {
            return (int) $s['SECTION_ID'];
        }, $sections);

        $this->db->trans_start();
        foreach ($assignments as $row) {
            $mappingId = (int) ($row['mapping_id'] ?? 0);
            $sectionId = isset($row['section_id']) && $row['section_id'] !== '' ? (int) $row['section_id'] : null;
            $sort = isset($row['question_sort']) ? (int) $row['question_sort'] : null;
            if ($mappingId <= 0) {
                continue;
            }
            if ($sectionId !== null && !in_array($sectionId, $sectionIds, true)) {
                $sectionId = null;
            }
            $payload = ['SECTION_ID' => $sectionId];
            if ($sort !== null && $sort > 0) {
                $payload['QUESTION_SORT'] = $sort;
            }
            $this->db->where('ID', $mappingId);
            $this->db->where('TEST_ID', $test_id);
            $this->db->update('TEST_QUESTION_MAPPING', $payload);
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}
