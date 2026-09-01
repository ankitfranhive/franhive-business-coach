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

    public function get_my_tests()
    {
        $user_id = (int)$this->session->userdata('user')['USER_ID'];
    
        $qry = "
            SELECT 
                tua.TEST_ID,
                tua.MAPPING_ID,
                ts.TOTAL_QUESTIONS,
                ts.END_DATE,
                ts.TEST_NAME,
                ts.TEST_START_DATE,
                ts.TEST_TYPE,
    
                -- ✅ latest attempt id
                tur_latest.ID AS RESPONSE_ID,
    
                -- ✅ latest attempt flags
                tur_latest.IS_EVAL,
                tur_latest.IS_SUBMITTED,
                tur_latest.USER_RESPONSE,
    
                -- ✅ attempts count
                COALESCE(tur_cnt.ATTEMPTS, 0) AS ATTEMPTS
    
            FROM TEST_USER_ALLOCATION tua
            INNER JOIN TEST_SERIES ts 
                ON ts.TEST_ID = tua.TEST_ID
    
            -- ✅ latest attempt per test for this user
            LEFT JOIN TEST_USER_RESPONSE tur_latest
                ON tur_latest.ID = (
                    SELECT tur2.ID
                    FROM TEST_USER_RESPONSE tur2
                    WHERE tur2.TEST_ID = tua.TEST_ID
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
                ON tur_cnt.TEST_ID = tua.TEST_ID AND tur_cnt.USER_ID = ?
    
            WHERE tua.USER_ID = ?
              AND ts.IS_DEL = 0
            ORDER BY tua.TEST_ID DESC
        ";
    
        $query = $this->db->query($qry, [$user_id, $user_id, $user_id, $user_id]);
        return $query->result_array();
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
            LEFT JOIN 
                TEST_USER_RESPONSE 
                ON TEST_USER_RESPONSE.TEST_ID = TEST_USER_ALLOCATION.TEST_ID AND TEST_USER_RESPONSE.USER_ID = ".$user_id." 
            WHERE 
                TEST_USER_ALLOCATION.USER_ID = ? AND TEST_USER_ALLOCATION.TEST_ID = ?
                AND TEST_SERIES.IS_DEL = 0
        ";

        $query = $this->db->query($qry, array($user_id, $test_id));
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


    public function saveUserResponse($user_id, $test_id, $user_response, $is_submitted)
{
    $data = [
        'user_id' => $user_id,
        'test_id' => $test_id,
        'user_response' => $user_response,
        'is_submitted'  => $is_submitted
    ];

    $this->db->insert('TEST_USER_RESPONSE', $data);
    return $this->db->affected_rows() > 0;
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
        INNER JOIN TEST_SERIES ts ON ts.TEST_ID = tur.TEST_ID
        INNER JOIN USERS u ON u.USER_ID = tur.USER_ID
        WHERE tur.ID = ?
          AND tur.USER_ID = ?
        LIMIT 1
    ";

    $q = $this->db->query($qry, [$response_id, $user_id]);
    return $q->row_array(); // ✅ single row
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
    $this->db->where('ID', $response_id);
 
    return $this->db->update('TEST_USER_RESPONSE', [
        'IS_EVAL'       => 1,
        'EVALUATED_AT'  => date('Y-m-d H:i:s'),
        'updated_at'    => date('Y-m-d H:i:s'),
    ]);
}

}
?>
