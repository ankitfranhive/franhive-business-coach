<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
use Dompdf\Options;




class Welcome extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_test_model'); // Load your model
    }

    public function index()
    {
        $data = $this->User_test_model->get_data(); // Call the method in your model
        $this->load->view('user_dashboard');
    }

    public function myTest() {
        $data['my_tests'] = $this->User_test_model->get_my_tests();
        $this->load->view('user_test_listing', $data);
    }

    public function myTestDashboard()
{
    $test_id = (int)$this->uri->segment(2);

    $data['test_details']   = $this->User_test_model->getTestDetails($test_id);
    $data['test_questions'] = $this->User_test_model->getTestQuestions($test_id);

    // ✅ If no questions, show message (helps debugging)
    if (empty($data['test_questions'])) {
        echo "No questions mapped to this test.";
        exit;
    }

    $data['user_test_details'] = $this->User_test_model->user_test_details(
        $this->session->userdata('user')['USER_ID'],
        $test_id
    );

    // time checks (your logic)
    $test_start_datetime = strtotime($data['test_details']['TEST_START_DATE']);
    $test_end_datetime   = strtotime($data['test_details']['END_DATE']);
    $current_timestamp   = time();

    if ($current_timestamp < $test_start_datetime) {
        $data['error_msg'] = "The test is upcoming.";
    } elseif ($current_timestamp > $test_end_datetime) {
        $data['error_msg'] = "The test has expired.";
    } else {
        $data['error_msg'] = "";
    }

    if (!empty($data['error_msg'])) {
        echo $data['error_msg'];
        exit;
    }

    // ✅ Hydrate options for all MCQ-like types (1 = Most/Least, 3/4/5 = MCQ)
    $testType = (int)$data['test_details']['TEST_TYPE'];
    if (in_array($testType, [1,3,4,5], true)) {

        $questionIds = [];
        foreach ($data['test_questions'] as $q) {
            $questionIds[] = (int)$q['QUESTION_ID'];
        }

        $optionsMap = $this->User_test_model->getOptionsByQuestionIds($questionIds);

        foreach ($data['test_questions'] as &$q) {
            $qid = (int)$q['QUESTION_ID'];

            if (!empty($optionsMap[$qid])) {
                $q['options'] = $optionsMap[$qid];  // new table options
            } else {
                $q['options'] = $this->User_test_model->buildLegacyOptionsFromQuestionRow($q); // fallback option_1..4
            }
        }
        unset($q);
    }

    // Load views (your original logic)
    if (in_array($testType, [1,3,4,5], true)) {
        $this->load->view('user_test_mcq_dashboard', $data);
    } else {
        $this->load->view('user_test_dashboard', $data);
    }
    
}


    public function thankYouPage()
    {
        $this->load->view('thankyou_test_submit');
    }

    public function saveTestResponse()
    {
        $json_data = $this->input->raw_input_stream; // Get raw input stream
        $data = json_decode($json_data, true); // Decode JSON data

        $user_data = $this->session->userdata('user');

        $test_id = $data['test_id'];
        $is_submitted = $data['is_submitted'];
        $responses = json_encode($data['responses'], true);

        $save_responses = $this->User_test_model->saveUserResponse(
            $user_data['USER_ID'],
            $test_id,
            $responses,
            $is_submitted
        );

        if ($save_responses) return true;
    }

    public function myTestReports() {

        $user_data = $this->session->userdata('user');
        $data['my_test_reports'] = $this->User_test_model->get_my_tests_reports($user_data['USER_ID']);
        $this->load->view('user_reports_listings', $data);
    }

    public function myCourses() {
        $data['my_courses'] = $this->User_test_model->get_my_courses();
         $welcomeConfig = $this->db->select('WELCOME_NOTE_TEXT')
                ->from('BASIC_CONFIG')
                ->order_by('ID', 'DESC')
                ->limit(1)
                ->get()
                ->row_array();
    
            $data['welcome_note_text_new'] = $welcomeConfig['WELCOME_NOTE_TEXT'] ?? null;


            // echo  $welcome_note_text;die;
    
            // keep: store in session (even though we also pass to view)
            // $this->session->set_userdata('welcome_note_text',  $data['welcome_note_text']);
        $this->load->view('user_course_listing', $data);
    }

    public function paymentAgreement() {
        $data['all_courses'] = $this->User_test_model->get_data();
        $this->load->view('payment_agreement/add_agreement', $data);
    }

    public function enroll_data_submit() {
        $saved = $this->User_test_model->save_enrollment_form($this->input->post());
        echo $saved ? "Form submitted successfully!" : "Error saving form.";
    }

    public function paymentAgreementList() {
        $data['all_agreements'] = $this->User_test_model->get_all_agreements();
        $this->load->view('payment_agreement/payment_agreement_list', $data);
    }





    public function submitEvaluation($response_id)
{
    // $user_id = (int)$this->session->userdata('user')['USER_ID'];

    // Mark evaluated
    $ok = $this->User_test_model->mark_evaluated((int)$response_id);

    if ($ok) {
        $this->session->set_flashdata('success', 'Evaluation submitted successfully.');
    } else {
        $this->session->set_flashdata('error', 'Failed to submit evaluation.');
    }

    redirect('test-reports');
}

public function downloadReportPdf($response_id)
{
    $user_id = (int)$this->session->userdata('user')['USER_ID'];
    $response_id = (int)$response_id;

    // ✅ Fetch ONE attempt by response id + user
    $row = $this->User_test_model->get_test_report_row_by_response_id($response_id, $user_id);
    if (empty($row)) {
        show_error('Report not found or access denied.', 404);
        return;
    }

    // ✅ Only Type 4 / 5
    $testType = (int)$row['TEST_TYPE'];
    if (!in_array($testType, [4, 5], true)) {
        show_error('This report type is not supported here.', 400);
        return;
    }

    $data = $this->_buildReportDataForPdf($row);

    $html = $this->load->view('test_report_brs_pdf', $data, true);

    $dompdf = new \Dompdf\Dompdf();
    if (method_exists($dompdf, 'set_option')) {
        $dompdf->set_option('isRemoteEnabled', true);
        $dompdf->set_option('isHtml5ParserEnabled', true);
    }

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $filename = 'test_report_' . $row['TEST_ID'] . '_response_' . $row['ID'] . '.pdf';
    $dompdf->stream($filename, ["Attachment" => true]);
    exit;
}




private function _buildReportDataForPdf(array $row)
{
    $testType = (int)$row['TEST_TYPE'];

    // Decode stored USER_RESPONSE (your data is a JSON array)
    $payload = !empty($row['USER_RESPONSE']) ? json_decode($row['USER_RESPONSE'], true) : [];
    $responses = (is_array($payload) && isset($payload['responses']) && is_array($payload['responses']))
        ? $payload['responses']
        : (is_array($payload) ? $payload : []);

    // Base view data
    $data = [
        'user_data' => $row,
        'attempted' => 0,
        'total_marks_gained' => 0,
        'user_score' => null,
        'interpretation' => null,
        'question_analysis' => [],
        'brs_table' => [],
        'show_brs_extras' => false,
    ];

    // Collect question IDs
    $questionIds = array_values(array_unique(
        array_map('intval', array_filter(array_column($responses, 'question_id')))
    ));

    // Fetch questions
    $questionsById = [];
    if ($questionIds) {
        $this->db->select('QUESTION_ID, QUESTION_NAME');
        $this->db->where_in('QUESTION_ID', $questionIds);
        foreach ($this->db->get('TEST_SERIES_QUESTIONS')->result_array() as $q) {
            $questionsById[(int)$q['QUESTION_ID']] = $q;
        }
    }

    // Fetch option text from options table (supports up to 10)
    $optionsByQid = [];
    if ($questionIds) {
        $this->db->select('QUESTION_ID, OPTION_TEXT, SORT_ORDER');
        $this->db->where_in('QUESTION_ID', $questionIds);
        $this->db->order_by('QUESTION_ID', 'ASC');
        $this->db->order_by('SORT_ORDER', 'ASC');
        $optRows = $this->db->get('TEST_SERIES_QUESTION_OPTIONS')->result_array();

        foreach ($optRows as $o) {
            $qid = (int)$o['QUESTION_ID'];
            $key = 'option_' . (int)$o['SORT_ORDER'];
            $optionsByQid[$qid][$key] = (string)($o['OPTION_TEXT'] ?? '');
        }
    }

    // -----------------------
    // TYPE 4 (BRS)
    // -----------------------
    if ($testType === 4) {
        $data['show_brs_extras'] = true;
        $reverseQNumbers = [2,4,6];

        $attempted = 0;
        $total = 0;
        $analysis = [];

        foreach ($responses as $r) {
            $qid = isset($r['question_id']) ? (int)$r['question_id'] : 0;
            if (!$qid || !isset($questionsById[$qid])) continue;

            $selectedKey = (string)($r['selected_option'] ?? '');
            if ($selectedKey === '') continue;

            $selectedN = (int)str_replace('option_', '', $selectedKey); // 1..5
            if ($selectedN < 1 || $selectedN > 5) $selectedN = 0;

            $qText = (string)$questionsById[$qid]['QUESTION_NAME'];

            // detect Q number from label: [Q-2 For BRS]
            $qNumber = 0;
            if (preg_match('/\[Q-(\d+)\s*For\s*BRS\]/i', $qText, $m)) {
                $qNumber = (int)$m[1];
            }
            $isReverse = in_array($qNumber, $reverseQNumbers, true);

            // BRS scoring: normal = n, reverse = 6-n
            $marks = ($selectedN > 0) ? ($isReverse ? (6 - $selectedN) : $selectedN) : 0;

            $attempted++;
            $total += $marks;

            $yourAnswerText = $optionsByQid[$qid][$selectedKey] ?? $selectedKey;

            $analysis[] = [
                'question_id'    => $qid,
                'question_text'  => $qText,
                'your_answer'    => $yourAnswerText,
                'answer_key'     => $selectedKey,
                'marks'          => $marks,
                'reverse_scored' => $isReverse ? 1 : 0,
                'time_spent'     => isset($r['time_spent']) ? (int)$r['time_spent'] : 0,
            ];
        }

        $totalQuestions = !empty($row['TOTAL_QUESTIONS']) ? (int)$row['TOTAL_QUESTIONS'] : max(1, $attempted);
        $avg = $totalQuestions > 0 ? round($total / $totalQuestions, 2) : 0;

        $interpretation = 'Low resilience';
        if ($avg >= 4.31) $interpretation = 'High resilience';
        else if ($avg >= 3.00) $interpretation = 'Normal resilience';

        $data['attempted'] = $attempted;
        $data['total_marks_gained'] = $total;
        $data['user_score'] = $avg;
        $data['interpretation'] = $interpretation;
        $data['question_analysis'] = $analysis;

        $data['brs_table'] = [
            ['range' => '1.00 - 2.99', 'label' => 'Low resilience'],
            ['range' => '3.00 - 4.30', 'label' => 'Normal resilience'],
            ['range' => '4.31 - 5.00', 'label' => 'High resilience'],
        ];

        return $data;
    }

    // -----------------------
    // TYPE 5 (normal scoring 1..10)
    // -----------------------
    if ($testType === 5) {
        $data['show_brs_extras'] = false;

        $attempted = 0;
        $total = 0;
        $analysis = [];

        foreach ($responses as $r) {
            $qid = isset($r['question_id']) ? (int)$r['question_id'] : 0;
            if (!$qid || !isset($questionsById[$qid])) continue;

            $selectedKey = (string)($r['selected_option'] ?? '');
            if ($selectedKey === '') continue;

            $selectedN = (int)str_replace('option_', '', $selectedKey); // 1..10
            if ($selectedN < 1) $selectedN = 0;

            $marks = $selectedN; // normal scoring

            $attempted++;
            $total += $marks;

            $yourAnswerText = $optionsByQid[$qid][$selectedKey] ?? $selectedKey;

            $analysis[] = [
                'question_id'    => $qid,
                'question_text'  => (string)$questionsById[$qid]['QUESTION_NAME'],
                'your_answer'    => $yourAnswerText,
                'answer_key'     => $selectedKey,
                'marks'          => $marks,
                'reverse_scored' => 0,
                'time_spent'     => isset($r['time_spent']) ? (int)$r['time_spent'] : 0,
            ];
        }

        $data['attempted'] = $attempted;
        $data['total_marks_gained'] = $total;
        $data['question_analysis'] = $analysis;

        return $data;
    }

    // fallback (for other types)
    return $data;
}


public function viewTestReport($test_id)
{
    $user_id = (int)$this->session->userdata('user')['USER_ID'];
    $test_id = (int)$test_id;

    // ✅ Get all attempts (rows) for this test + user
    $rows = $this->User_test_model->get_test_report_rows_by_test_id_user($test_id, $user_id);

    if (empty($rows)) {
        show_error('Report not found or access denied.', 404);
        return;
    }

    // ✅ Only allow for type 4 and 5 here (same test series => same type)
    $testType = (int)$rows[0]['TEST_TYPE'];
    if (!in_array($testType, [4, 5], true)) {
        show_error('This report type is not supported here.', 400);
        return;
    }

    // ✅ Build report data for EACH attempt
    $attempt_reports = [];
    foreach ($rows as $row) {
        $attempt_reports[] = $this->_buildReportDataForPdf($row); // should include user_data + response_id etc.
    }

    $data = [
        'attempt_reports' => $attempt_reports,

        // ✅ Helpful for header/user card outside loop
        'testType'        => $testType,
        'test_name'       => $rows[0]['TEST_NAME'] ?? '',
        'total_questions' => (int)($rows[0]['TOTAL_QUESTIONS'] ?? 0),
        'test_start_date' => $rows[0]['TEST_START_DATE'] ?? '',
        'user_name'       => $rows[0]['NAME'] ?? '',
        'user_email'      => $rows[0]['EMAIL'] ?? '',
        'profile_picture' => $rows[0]['PROFILE_PICTURE'] ?? '',
    ];

    $this->load->view('test_report_brs_usr', $data);
}


}
