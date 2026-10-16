<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('test_report_decode_responses')) {
    function test_report_decode_responses(array $row)
    {
        $raw = $row['USER_RESPONSE'] ?? $row['user_response'] ?? '';
        $payload = !empty($raw) ? json_decode($raw, true) : [];
        if (is_array($payload) && isset($payload['responses']) && is_array($payload['responses'])) {
            return $payload['responses'];
        }
        return is_array($payload) ? $payload : [];
    }
}

if (!function_exists('test_report_disc_pdf_data')) {
    /** Type 1 — Most/Least (DISC-style) report data for PDF/HTML views. */
    function test_report_disc_pdf_data(array $row)
    {
        $responses = test_report_decode_responses($row);
        $most_likely_counts = [];
        $least_likely_counts = [];

        foreach ($responses as $response) {
            if (!empty($response['most_likely'])) {
                $k = $response['most_likely'];
                if (!isset($most_likely_counts[$k])) {
                    $most_likely_counts[$k] = 0;
                }
                $most_likely_counts[$k]++;
            }
            if (!empty($response['least_likely'])) {
                $k = $response['least_likely'];
                if (!isset($least_likely_counts[$k])) {
                    $least_likely_counts[$k] = 0;
                }
                $least_likely_counts[$k]++;
            }
        }

        foreach (['option_1', 'option_2', 'option_3', 'option_4'] as $opt) {
            if (!isset($most_likely_counts[$opt])) {
                $most_likely_counts[$opt] = 0;
            }
            if (!isset($least_likely_counts[$opt])) {
                $least_likely_counts[$opt] = 0;
            }
        }

        return [
            'user_data'           => $row,
            'responses'           => $responses,
            'most_likely_counts'  => $most_likely_counts,
            'least_likely_counts' => $least_likely_counts,
        ];
    }
}

if (!function_exists('test_report_brs_pdf_data')) {
    /** Types 4 & 5 — BRS / scale report data (shared by user + admin PDF). */
    function test_report_brs_pdf_data($CI, array $row)
    {
        $testType = (int)($row['TEST_TYPE'] ?? 0);
        $responses = test_report_decode_responses($row);

        $data = [
            'user_data'          => $row,
            'attempted'          => 0,
            'total_marks_gained' => 0,
            'user_score'         => null,
            'interpretation'     => null,
            'question_analysis'  => [],
            'brs_table'          => [],
            'show_brs_extras'    => false,
        ];

        $questionIds = array_values(array_unique(
            array_map('intval', array_filter(array_column($responses, 'question_id')))
        ));

        $questionsById = [];
        if ($questionIds) {
            $CI->db->select('QUESTION_ID, QUESTION_NAME');
            $CI->db->where_in('QUESTION_ID', $questionIds);
            foreach ($CI->db->get('TEST_SERIES_QUESTIONS')->result_array() as $q) {
                $questionsById[(int)$q['QUESTION_ID']] = $q;
            }
        }

        $optionsByQid = [];
        if ($questionIds) {
            $CI->db->select('QUESTION_ID, OPTION_TEXT, SORT_ORDER');
            $CI->db->where_in('QUESTION_ID', $questionIds);
            $CI->db->order_by('QUESTION_ID', 'ASC');
            $CI->db->order_by('SORT_ORDER', 'ASC');
            foreach ($CI->db->get('TEST_SERIES_QUESTION_OPTIONS')->result_array() as $o) {
                $qid = (int)$o['QUESTION_ID'];
                $key = 'option_' . (int)$o['SORT_ORDER'];
                $optionsByQid[$qid][$key] = (string)($o['OPTION_TEXT'] ?? '');
            }
        }

        if ($testType === 4) {
            $data['show_brs_extras'] = true;
            $reverseQNumbers = [2, 4, 6];
            $attempted = 0;
            $total = 0;
            $analysis = [];

            foreach ($responses as $r) {
                $qid = isset($r['question_id']) ? (int)$r['question_id'] : 0;
                if (!$qid || !isset($questionsById[$qid])) {
                    continue;
                }
                $selectedKey = (string)($r['selected_option'] ?? '');
                if ($selectedKey === '') {
                    continue;
                }
                $selectedN = (int)str_replace('option_', '', $selectedKey);
                if ($selectedN < 1 || $selectedN > 5) {
                    $selectedN = 0;
                }
                $qText = (string)$questionsById[$qid]['QUESTION_NAME'];
                $qNumber = 0;
                if (preg_match('/\[Q-(\d+)\s*For\s*BRS\]/i', $qText, $m)) {
                    $qNumber = (int)$m[1];
                }
                $isReverse = in_array($qNumber, $reverseQNumbers, true);
                $marks = $selectedN > 0 ? ($isReverse ? (6 - $selectedN) : $selectedN) : 0;
                $attempted++;
                $total += $marks;
                $analysis[] = [
                    'question_id'    => $qid,
                    'question_text'  => $qText,
                    'your_answer'    => $optionsByQid[$qid][$selectedKey] ?? $selectedKey,
                    'answer_key'     => $selectedKey,
                    'marks'          => $marks,
                    'reverse_scored' => $isReverse ? 1 : 0,
                    'time_spent'     => isset($r['time_spent']) ? (int)$r['time_spent'] : 0,
                ];
            }

            $totalQuestions = !empty($row['TOTAL_QUESTIONS']) ? (int)$row['TOTAL_QUESTIONS'] : max(1, $attempted);
            $avg = $totalQuestions > 0 ? round($total / $totalQuestions, 2) : 0;
            $interpretation = 'Low resilience';
            if ($avg >= 4.31) {
                $interpretation = 'High resilience';
            } elseif ($avg >= 3.00) {
                $interpretation = 'Normal resilience';
            }

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

        if ($testType === 5) {
            $attempted = 0;
            $total = 0;
            $analysis = [];
            foreach ($responses as $r) {
                $qid = isset($r['question_id']) ? (int)$r['question_id'] : 0;
                if (!$qid || !isset($questionsById[$qid])) {
                    continue;
                }
                $selectedKey = (string)($r['selected_option'] ?? '');
                if ($selectedKey === '') {
                    continue;
                }
                $selectedN = (int)str_replace('option_', '', $selectedKey);
                if ($selectedN < 1) {
                    $selectedN = 0;
                }
                $marks = $selectedN;
                $attempted++;
                $total += $marks;
                $analysis[] = [
                    'question_id'    => $qid,
                    'question_text'  => (string)$questionsById[$qid]['QUESTION_NAME'],
                    'your_answer'    => $optionsByQid[$qid][$selectedKey] ?? $selectedKey,
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

        return $data;
    }
}

if (!function_exists('test_report_stream_pdf')) {
    function test_report_stream_pdf($html, $filename)
    {
        $dompdf = new Dompdf\Dompdf();
        if (method_exists($dompdf, 'set_option')) {
            $dompdf->set_option('isRemoteEnabled', true);
            $dompdf->set_option('isHtml5ParserEnabled', true);
        }
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream($filename, ['Attachment' => true]);
        exit;
    }
}
