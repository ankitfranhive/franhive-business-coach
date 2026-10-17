<?php

class User_test_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->database();

        if (!$this->session->userdata('user') || !isset($this->session->userdata('user')['USER_ID'])) {
            redirect('login');
        }
    }

    public function get_data() {
        $this->db->select('*');
        $this->db->from('COURSES');
        $this->db->where('RECORD_STATUS', 0);
        $this->db->order_by('COURSE_ID', 'DESC');
        $this->db->limit(10);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_questions() {
        $query = $this->db->get('TEST_SERIES_QUESTIONS');
        return $query->result();
    }

    public function reattempt_grant_table_ready()
    {
        $this->load->helper('test_reattempt');
        ensure_test_user_reattempt_grant_table($this->db);
        return $this->db->table_exists('TEST_USER_REATTEMPT_GRANT');
    }

    public function user_has_reattempt_grant($user_id, $test_id)
    {
        $user_id = (int)$user_id;
        $test_id = (int)$test_id;
        if ($user_id <= 0 || $test_id <= 0 || !$this->reattempt_grant_table_ready()) {
            return false;
        }
        $n = (int) $this->db->where('user_id', $user_id)
            ->where('test_id', $test_id)
            ->count_all_results('TEST_USER_REATTEMPT_GRANT');
        return $n > 0;
    }

    public function grant_test_reattempt($user_id, $test_id)
    {
        $user_id = (int)$user_id;
        $test_id = (int)$test_id;
        if ($user_id <= 0 || $test_id <= 0 || !$this->reattempt_grant_table_ready()) {
            return false;
        }
        $now = date('Y-m-d H:i:s');
        $sql = "INSERT INTO TEST_USER_REATTEMPT_GRANT (user_id, test_id, granted_at)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE granted_at = VALUES(granted_at)";
        return $this->db->query($sql, [$user_id, $test_id, $now]);
    }

    public function clear_test_reattempt_grant($user_id, $test_id)
    {
        $user_id = (int)$user_id;
        $test_id = (int)$test_id;
        if ($user_id <= 0 || $test_id <= 0 || !$this->reattempt_grant_table_ready()) {
            return false;
        }
        $this->db->where('user_id', $user_id)->where('test_id', $test_id)
            ->delete('TEST_USER_REATTEMPT_GRANT');
        return true;
    }

    protected function normalize_my_test_row(array $row)
    {
        $row['IS_EVAL'] = (int)($row['IS_EVAL'] ?? $row['is_eval'] ?? 0);
        $row['IS_SUBMITTED'] = (int)($row['IS_SUBMITTED'] ?? $row['is_submitted'] ?? 0);
        $row['ALLOW_REATTEMPT'] = (int)($row['ALLOW_REATTEMPT'] ?? $row['allow_reattempt'] ?? 0);
        $row['HAS_REATTEMPT_GRANT'] = (int)($row['HAS_REATTEMPT_GRANT'] ?? 0);
        return $row;
    }

    public function get_my_tests()
    {
        $user_id = (int)$this->session->userdata('user')['USER_ID'];
        $this->load->helper('test_reattempt');

        $allocUser = test_db_column($this->db, 'TEST_USER_ALLOCATION', ['USER_ID', 'user_id'], 'USER_ID');
        $allocTest = test_db_column($this->db, 'TEST_USER_ALLOCATION', ['TEST_ID', 'test_id'], 'TEST_ID');
        $removedCol = test_db_column($this->db, 'TEST_USER_ALLOCATION', ['IS_REMOVED', 'is_removed'], 'IS_REMOVED');

        if ($this->db->field_exists('ALLOW_REATTEMPT', 'TEST_USER_ALLOCATION')) {
            $reattemptSelect = 'COALESCE(tua.ALLOW_REATTEMPT, 0) AS ALLOW_REATTEMPT';
        } elseif ($this->db->field_exists('allow_reattempt', 'TEST_USER_ALLOCATION')) {
            $reattemptSelect = 'COALESCE(tua.allow_reattempt, 0) AS ALLOW_REATTEMPT';
        } else {
            $reattemptSelect = '0 AS ALLOW_REATTEMPT';
        }

        $respPk = test_db_column($this->db, 'TEST_USER_RESPONSE', ['id', 'ID'], 'id');
        $respTest = test_db_column($this->db, 'TEST_USER_RESPONSE', ['test_id', 'TEST_ID'], 'test_id');
        $respUser = test_db_column($this->db, 'TEST_USER_RESPONSE', ['user_id', 'USER_ID'], 'user_id');

        $grantJoin = '';
        $grantSelect = '0 AS HAS_REATTEMPT_GRANT';
        if ($this->reattempt_grant_table_ready()) {
            $grantJoin = "LEFT JOIN TEST_USER_REATTEMPT_GRANT tg
                ON tg.user_id = tua.{$allocUser} AND tg.test_id = tua.{$allocTest}";
            $grantSelect = 'CASE WHEN tg.id IS NOT NULL THEN 1 ELSE 0 END AS HAS_REATTEMPT_GRANT';
        }
    
        $qry = "
            SELECT 
                tua.{$allocTest} AS TEST_ID,
                tua.MAPPING_ID,
                ts.TOTAL_QUESTIONS,
                ts.END_DATE,
                ts.TEST_NAME,
                ts.TEST_START_DATE,
                ts.TEST_TYPE,
                {$reattemptSelect},
                {$grantSelect},
    
                tur_latest.{$respPk} AS RESPONSE_ID,
                COALESCE(tur_latest.is_eval, tur_latest.IS_EVAL, 0) AS IS_EVAL,
                COALESCE(tur_latest.is_submitted, tur_latest.IS_SUBMITTED, 0) AS IS_SUBMITTED,
                COALESCE(tur_latest.user_response, tur_latest.USER_RESPONSE) AS USER_RESPONSE,
    
                COALESCE(tur_cnt.ATTEMPTS, 0) AS ATTEMPTS
    
            FROM TEST_USER_ALLOCATION tua
            INNER JOIN TEST_SERIES ts 
                ON ts.TEST_ID = tua.{$allocTest}
            {$grantJoin}
    
            LEFT JOIN TEST_USER_RESPONSE tur_latest
                ON tur_latest.{$respPk} = (
                    SELECT tur2.{$respPk}
                    FROM TEST_USER_RESPONSE tur2
                    WHERE tur2.{$respTest} = tua.{$allocTest}
                      AND tur2.{$respUser} = ?
                    ORDER BY tur2.{$respPk} DESC
                    LIMIT 1
                )
    
            LEFT JOIN (
                SELECT {$respTest} AS TEST_ID, {$respUser} AS USER_ID, COUNT(*) AS ATTEMPTS
                FROM TEST_USER_RESPONSE
                WHERE {$respUser} = ?
                GROUP BY {$respTest}, {$respUser}
            ) tur_cnt
                ON tur_cnt.TEST_ID = tua.{$allocTest} AND tur_cnt.USER_ID = tua.{$allocUser}
    
            WHERE tua.{$allocUser} = ?
              AND COALESCE(tua.{$removedCol}, 0) = 0
              AND ts.IS_DEL = 0
            ORDER BY tua.{$allocTest} DESC
        ";
    
        $query = $this->db->query($qry, [$user_id, $user_id, $user_id]);
        $rows = $query ? $query->result_array() : [];
        foreach ($rows as $i => $row) {
            $row = $this->normalize_my_test_row($row);
            if ((int)$row['HAS_REATTEMPT_GRANT'] !== 1
                && $this->user_has_reattempt_grant($user_id, (int)$row['TEST_ID'])) {
                $row['HAS_REATTEMPT_GRANT'] = 1;
            }
            $rows[$i] = $row;
        }
        return $rows;
    }

    /** True when test is assigned to user and not soft-removed (admin "Remove Test"). */
    public function user_has_active_allocation($user_id, $test_id)
    {
        $user_id = (int)$user_id;
        $test_id = (int)$test_id;
        if ($user_id <= 0 || $test_id <= 0) {
            return false;
        }
        $row = $this->db->select('IS_REMOVED')
            ->from('TEST_USER_ALLOCATION')
            ->where('USER_ID', $user_id)
            ->where('TEST_ID', $test_id)
            ->limit(1)
            ->get()
            ->row_array();
        if (empty($row)) {
            return false;
        }
        return (int)($row['IS_REMOVED'] ?? $row['is_removed'] ?? 0) === 0;
    }
    

    public function user_test_details($user_id, $test_id) {

        $qry = "
            SELECT 
                TEST_USER_ALLOCATION.TEST_ID,
                TEST_USER_ALLOCATION.MAPPING_ID,
                TEST_SERIES.TOTAL_QUESTIONS,
                TEST_SERIES.END_DATE, 
                TEST_SERIES.TEST_NAME,
                TEST_SERIES.TEST_START_DATE,
                TEST_USER_RESPONSE.IS_EVAL,
                TEST_SERIES.TEST_TYPE,
                TEST_USER_RESPONSE.IS_SUBMITTED,
                TEST_USER_RESPONSE.USER_RESPONSE
            FROM 
                TEST_USER_ALLOCATION
            LEFT JOIN 
                TEST_SERIES 
                ON TEST_SERIES.TEST_ID = TEST_USER_ALLOCATION.TEST_ID 
            LEFT JOIN TEST_USER_RESPONSE
                ON TEST_USER_RESPONSE.ID = (
                    SELECT tur2.ID
                    FROM TEST_USER_RESPONSE tur2
                    WHERE tur2.TEST_ID = TEST_USER_ALLOCATION.TEST_ID
                      AND tur2.USER_ID = ?
                    ORDER BY tur2.ID DESC
                    LIMIT 1
                )
            WHERE 
                TEST_USER_ALLOCATION.USER_ID = ? AND TEST_USER_ALLOCATION.TEST_ID = ?
                AND COALESCE(TEST_USER_ALLOCATION.IS_REMOVED, 0) = 0
                AND TEST_SERIES.IS_DEL = 0
        ";

        $query = $this->db->query($qry, array($user_id, $user_id, $test_id));
        return $query->result_array();
    }

    // ✅ safer binding, same output
    public function getTestDetails($test_id)
    {
        $sql = "SELECT 
                    TOTAL_QUESTIONS,
                    TEST_NAME,
                    TEST_INSTRUCTIONS,
                    TEST_TYPE,
                    END_DATE,
                    TEST_START_DATE,
                    TEST_TYPE
                FROM TEST_SERIES
                WHERE TEST_ID = ?";
        return $this->db->query($sql, [$test_id])->row_array();
    }
    

    // ✅ still returns option_1..4 for legacy, plus is_correct_1..4 to allow fallback building
    public function getTestQuestions($test_id)
{
    $sql = "SELECT 
                tsq.QUESTION_NAME, 
                tsq.QUESTION_ID,
                tsq.option_1,
                tsq.option_2,
                tsq.option_3,
                tsq.option_4,
                tsq.is_correct_1,
                tsq.is_correct_2,
                tsq.is_correct_3,
                tsq.is_correct_4
            FROM TEST_QUESTION_MAPPING tqm
            INNER JOIN TEST_SERIES_QUESTIONS tsq 
                ON tsq.QUESTION_ID = tqm.QUESTION_ID
            WHERE tqm.TEST_ID = ?";

    return $this->db->query($sql, [$test_id])->result_array();
}


    // ✅ NEW: bulk load options from new table
    public function getOptionsByQuestionIds($questionIds = [])
{
    if (empty($questionIds)) return [];

    $this->db->select('QUESTION_ID, OPTION_TEXT, IS_CORRECT, OPTION_MARKS, SORT_ORDER');
    $this->db->from('TEST_SERIES_QUESTION_OPTIONS');
    $this->db->where_in('QUESTION_ID', $questionIds);
    $this->db->order_by('QUESTION_ID', 'ASC');
    $this->db->order_by('SORT_ORDER', 'ASC');

    $rows = $this->db->get()->result_array();

    $grouped = [];
    foreach ($rows as $r) {
        $qid = (int)$r['QUESTION_ID'];
        $grouped[$qid][] = $r;
    }
    return $grouped;
}

public function buildLegacyOptionsFromQuestionRow($qRow)
{
    $legacy = [];
    $qid = isset($qRow['QUESTION_ID']) ? (int)$qRow['QUESTION_ID'] : 0;

    for ($i = 1; $i <= 4; $i++) {
        $text = trim($qRow["option_$i"] ?? '');
        if ($text === '') continue;

        $legacy[] = [
            'QUESTION_ID'  => $qid,
            'OPTION_TEXT'  => $text,
            'IS_CORRECT'   => (int)($qRow["is_correct_$i"] ?? 0),
            'OPTION_MARKS' => 0,
            'SORT_ORDER'   => $i
        ];
    }
    return $legacy;
}


    // public function saveUserResponse($user_id, $test_id, $user_response, $is_submitted) {

    //     $data = array(
    //         'user_response' => $user_response,
    //         'is_submitted'  => $is_submitted
    //     );

    //     $this->db->where('user_id', $user_id);
    //     $this->db->where('test_id', $test_id);
    //     $query = $this->db->get('TEST_USER_RESPONSE');

    //     if ($query->num_rows() > 0) {
    //         $this->db->where('user_id', $user_id);
    //         $this->db->where('test_id', $test_id);
    //         $this->db->update('TEST_USER_RESPONSE', $data);
    //     } else {
    //         $data['user_id'] = $user_id;
    //         $data['test_id'] = $test_id;
    //         $this->db->insert('TEST_USER_RESPONSE', $data);
    //     }

    //     return $this->db->affected_rows() > 0;
    // }


    public function clear_allocation_reattempt_flag($user_id, $test_id)
    {
        $user_id = (int)$user_id;
        $test_id = (int)$test_id;
        if ($this->db->field_exists('ALLOW_REATTEMPT', 'TEST_USER_ALLOCATION')) {
            $this->db->where('USER_ID', $user_id)
                ->where('TEST_ID', $test_id)
                ->update('TEST_USER_ALLOCATION', ['ALLOW_REATTEMPT' => 0]);
        }
        $this->clear_test_reattempt_grant($user_id, $test_id);
    }

    public function saveUserResponse($user_id, $test_id, $user_response, $is_submitted)
{
    if (!$this->user_has_active_allocation($user_id, $test_id)) {
        return false;
    }

    $data = [
        'user_id' => $user_id,
        'test_id' => $test_id,
        'user_response' => $user_response,
        'is_submitted'  => $is_submitted,
        'is_eval'       => 0,
    ];

    $this->db->insert('TEST_USER_RESPONSE', $data);
    $ok = $this->db->affected_rows() > 0;
    if ($ok) {
        $this->clear_allocation_reattempt_flag($user_id, $test_id);
    }
    return $ok;
}


public function get_my_tests_reports($user_id)
{
    $user_id = (int)$user_id;

    $qry = "
        SELECT
            ts.TEST_ID,
            ts.TEST_NAME,
            ts.TEST_START_DATE,
            ts.END_DATE,
            ts.TEST_TYPE,
            ts.TOTAL_QUESTIONS,

            u.NAME AS USER_NAME,

            -- ✅ count attempts
            COALESCE(tur_cnt.ATTEMPTS, 0) AS ATTEMPTS,

            -- ✅ latest attempt id
            tur_latest.ID AS RESPONSE_ID,

            -- ✅ latest attempt flags
            tur_latest.IS_EVAL,
            tur_latest.IS_SUBMITTED

        FROM TEST_SERIES ts
        INNER JOIN TEST_USER_ALLOCATION tua
            ON tua.TEST_ID = ts.TEST_ID
        INNER JOIN USERS u
            ON u.USER_ID = tua.USER_ID

        -- ✅ latest attempt for this user & test
        LEFT JOIN TEST_USER_RESPONSE tur_latest
            ON tur_latest.ID = (
                SELECT tur2.ID
                FROM TEST_USER_RESPONSE tur2
                WHERE tur2.TEST_ID = ts.TEST_ID
                  AND tur2.USER_ID = ?
                ORDER BY tur2.ID DESC
                LIMIT 1
            )

        -- ✅ attempts count per test
        LEFT JOIN (
            SELECT TEST_ID, USER_ID, COUNT(*) AS ATTEMPTS
            FROM TEST_USER_RESPONSE
            WHERE USER_ID = ?
            GROUP BY TEST_ID, USER_ID
        ) tur_cnt
            ON tur_cnt.TEST_ID = ts.TEST_ID AND tur_cnt.USER_ID = ?

        WHERE tua.USER_ID = ?
          AND COALESCE(tua.IS_REMOVED, 0) = 0
          AND ts.IS_DEL = 0
        ORDER BY tur_latest.ID DESC, ts.TEST_ID DESC
    ";

    $q = $this->db->query($qry, [$user_id, $user_id, $user_id, $user_id]);
    return $q->result_array();
}


    public function get_my_courses() {
        $user_id = $this->session->userdata('user')['USER_ID'];

        $qry = "
           SELECT COURSES.*
            FROM USER_COURSE_MAPPING
            INNER JOIN COURSES on COURSES.COURSE_ID  = USER_COURSE_MAPPING.COURSE_ID
            WHERE 
                USER_COURSE_MAPPING.USER_ID = ? 
                AND COURSES.RECORD_STATUS = 0
                ORDER BY USER_COURSE_MAPPING.ID DESC
        ";

        $query = $this->db->query($qry, array($user_id));
        return $query->result_array();
    }

    public function get_user_by_email_or_mobile($email, $mobile) {
        return $this->db->where('EMAIL', $email)
                        ->or_where('PHONE', $mobile)
                        ->get('USERS')
                        ->row_array();
    }

    public function create_user($data) {
        $this->db->insert('USERS', $data);
        return $this->db->insert_id();
    }

    public function save_enrollment_form($data) {
        $insert = [
            'full_name' => $data['full_name'],
            'date_of_birth' => $data['date_of_birth'],
            'gender' => $data['gender'],
            'street_address' => $data['street_address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'zip_code' => $data['zip_code'],
            'phone_number' => $data['phone_number'],
            'email_address' => $data['email_address'],

            'emergency_contact_name' => $data['emergency_contact_name'],
            'emergency_contact_phone' => $data['emergency_contact_phone'],
            'emergency_contact_relationship' => $data['emergency_contact_relationship'],

            'program_id' => $data['program_id'],
            'training_month' => $data['program_start_month'],
            'training_year' => $data['training_year'],

            'total' => $data['total'],
            'deposit_amount' => $data['deposit_amount'],
            'for_training' => $data['for_training'],
            'deposit_paid_via' => $data['deposit_paid_via'],
            'paid_on' => $data['paid_on'],
            'balance_of' => $data['balance_of'],
            'due_date' => $data['due_date'],

            'agree_terms_1' => isset($data['agree_terms_1']) ? 1 : 0,
            'agree_terms_2' => isset($data['agree_terms_2']) ? 1 : 0,
            'agree_terms_3' => isset($data['agree_terms']) ? 1 : 0,

            'payment_option' => $data['payment_option'],
            'credit_card_number' => $data['credit_card_number'],
            'expiry_date' => $data['expiry_date'],
            'name_on_card' => $data['name_on_card'],
            'cvc' => $data['cvc'],

            'signature' => $data['signature'],
            'signature_date' => $data['signature_date'],
            'approved_by' => $data['approved_by'],
            'approval_date' => $data['approval_date']
        ];

        return $this->db->insert('ENROLL_AGREEMENT_DATA', $insert);
    }

    public function get_all_agreements() {
        $query = $this->db->get('ENROLL_AGREEMENT_DATA');
        return $query->result_array();
    }



    public function get_test_report_row_by_response_id($response_id, $user_id)
{
    $qry = "
        SELECT 
            tur.*,
            ts.TEST_NAME,
            ts.TEST_START_DATE,
            ts.END_DATE,
            ts.TEST_TYPE,
            ts.TOTAL_QUESTIONS,
            u.EMAIL,
            u.NAME,
            u.PROFILE_PICTURE
        FROM TEST_USER_RESPONSE tur
        INNER JOIN TEST_SERIES ts ON ts.TEST_ID = tur.test_id
        INNER JOIN USERS u ON u.USER_ID = tur.user_id
        WHERE tur.id = ?
          AND tur.user_id = ?
        LIMIT 1
    ";

    $q = $this->db->query($qry, [(int)$response_id, (int)$user_id]);
    return $q->row_array();
}

    /** Admin PDF / reports: any user's response by id (no session user filter). */
    public function get_test_report_row_by_response_id_admin($response_id)
    {
        $qry = "
        SELECT 
            tur.*,
            ts.TEST_NAME,
            ts.TEST_START_DATE,
            ts.END_DATE,
            ts.TEST_TYPE,
            ts.TOTAL_QUESTIONS,
            u.EMAIL,
            u.NAME,
            u.PROFILE_PICTURE
        FROM TEST_USER_RESPONSE tur
        INNER JOIN TEST_SERIES ts ON ts.TEST_ID = tur.test_id
        INNER JOIN USERS u ON u.USER_ID = tur.user_id
        WHERE tur.id = ?
        LIMIT 1
    ";
        $q = $this->db->query($qry, [(int)$response_id]);
        return $q->row_array();
    }



    public function get_test_report_rows_by_test_id_user($test_id, $user_id)
    {
        $qry = "
            SELECT 
                tur.*,
                ts.TEST_NAME,
                ts.TEST_START_DATE,
                ts.END_DATE,
                ts.TEST_TYPE,
                ts.TOTAL_QUESTIONS,
                u.EMAIL,
                u.NAME,
                u.PROFILE_PICTURE
            FROM TEST_USER_RESPONSE tur
            INNER JOIN TEST_SERIES ts ON ts.TEST_ID = tur.TEST_ID
            INNER JOIN USERS u ON u.USER_ID = tur.USER_ID
            WHERE tur.TEST_ID = ?
              AND tur.USER_ID = ?
            ORDER BY tur.ID DESC
        ";
    
        $q = $this->db->query($qry, [$test_id, $user_id]);
    
        return $q->result_array(); // ✅ array for multiple attempts
    }
    


public function mark_evaluated($response_id)
{
    $response_id = (int)$response_id;
    if ($response_id <= 0) {
        return false;
    }
    $this->db->where('id', $response_id);

    $payload = ['is_eval' => 1];
    if ($this->db->field_exists('evaluated_at', 'TEST_USER_RESPONSE')) {
        $payload['evaluated_at'] = date('Y-m-d H:i:s');
    }
    if ($this->db->field_exists('updated_at', 'TEST_USER_RESPONSE')) {
        $payload['updated_at'] = date('Y-m-d H:i:s');
    }

    return $this->db->update('TEST_USER_RESPONSE', $payload);
}

}
?>
