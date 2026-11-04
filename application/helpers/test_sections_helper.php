<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function ensure_test_sections_schema($db)
{
    if (!$db->table_exists('TEST_SECTIONS')) {
        $db->query('CREATE TABLE IF NOT EXISTS `TEST_SECTIONS` (
            `SECTION_ID` int unsigned NOT NULL AUTO_INCREMENT,
            `TEST_ID` int unsigned NOT NULL,
            `SECTION_NAME` varchar(255) NOT NULL,
            `SORT_ORDER` int unsigned NOT NULL DEFAULT 1,
            `CREATED_ON` datetime DEFAULT NULL,
            PRIMARY KEY (`SECTION_ID`),
            KEY `idx_test_sections_test` (`TEST_ID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
    }

    if (!$db->field_exists('USE_SECTIONS', 'TEST_SERIES')) {
        $db->query('ALTER TABLE `TEST_SERIES` ADD COLUMN `USE_SECTIONS` TINYINT(1) NOT NULL DEFAULT 0');
    }

    if (!$db->field_exists('SECTION_ID', 'TEST_QUESTION_MAPPING')) {
        $db->query('ALTER TABLE `TEST_QUESTION_MAPPING` ADD COLUMN `SECTION_ID` int unsigned NULL DEFAULT NULL');
    }
    if (!$db->field_exists('QUESTION_SORT', 'TEST_QUESTION_MAPPING')) {
        $db->query('ALTER TABLE `TEST_QUESTION_MAPPING` ADD COLUMN `QUESTION_SORT` int unsigned NULL DEFAULT NULL');
    }

    return true;
}

/** Section index 1 → value 2, 2 → 3, … */
function value_number_from_section_sort($sectionSortOrder)
{
    return (int) $sectionSortOrder + 1;
}

function is_even_value_number($valueNumber)
{
    return ((int) $valueNumber) % 2 === 0;
}

function value_test_empty_counts()
{
    return ['me' => 0, 'dont_care' => 0, 'not_me' => 0];
}

function value_test_load_question_section_map($db, $test_id)
{
    ensure_test_sections_schema($db);
    $test_id = (int) $test_id;
    if ($test_id <= 0) {
        return ['sections' => [], 'by_question' => []];
    }

    $sections = [];
    $db->where('TEST_ID', $test_id);
    $db->order_by('SORT_ORDER', 'ASC');
    foreach ($db->get('TEST_SECTIONS')->result_array() as $sec) {
        $sort = (int) ($sec['SORT_ORDER'] ?? 0);
        $sid = (int) $sec['SECTION_ID'];
        $sections[$sid] = [
            'section_id'   => $sid,
            'section_name' => $sec['SECTION_NAME'],
            'sort_order'   => $sort,
            'value_number' => value_number_from_section_sort($sort),
            'counts'       => value_test_empty_counts(),
            'weighted_me'  => 0.0,
            'weighted_not_me' => 0.0,
        ];
    }

    $byQuestion = [];
    $sql = "
        SELECT tqm.QUESTION_ID, tqm.SECTION_ID, sec.SECTION_NAME, sec.SORT_ORDER AS SECTION_SORT
        FROM TEST_QUESTION_MAPPING tqm
        LEFT JOIN TEST_SECTIONS sec ON sec.SECTION_ID = tqm.SECTION_ID
        WHERE tqm.TEST_ID = ?
    ";
    foreach ($db->query($sql, [$test_id])->result_array() as $row) {
        $qid = (int) $row['QUESTION_ID'];
        $sid = (int) ($row['SECTION_ID'] ?? 0);
        $sort = (int) ($row['SECTION_SORT'] ?? 0);
        $byQuestion[$qid] = [
            'section_id'   => $sid ?: null,
            'section_name' => $row['SECTION_NAME'] ?? '',
            'value_number' => $sid > 0 && $sort > 0 ? value_number_from_section_sort($sort) : null,
        ];
    }

    return ['sections' => $sections, 'by_question' => $byQuestion];
}

function value_test_apply_section_analytics(array &$data, $db, $test_id)
{
    $test_id = (int) $test_id;
    $useSections = false;
    if ($test_id > 0) {
        $row = $db->query('SELECT COALESCE(USE_SECTIONS, 0) AS USE_SECTIONS FROM TEST_SERIES WHERE TEST_ID = ?', [$test_id])->row_array();
        $useSections = !empty($row) && (int) ($row['USE_SECTIONS'] ?? 0) === 1;
    }

    $data['has_section_analytics'] = false;
    if (!$useSections) {
        return;
    }

    $map = value_test_load_question_section_map($db, $test_id);
    $sections = $map['sections'];
    $byQ = $map['by_question'];

    $valuesSummary = [];
    foreach ($sections as $sec) {
        $vn = (int) ($sec['value_number'] ?? 0);
        if ($vn <= 0) {
            continue;
        }
        $valuesSummary[$vn] = [
            'value_number' => $vn,
            'section_name' => $sec['section_name'] ?? '',
            'counts'       => value_test_empty_counts(),
        ];
    }

    $even = value_test_empty_counts();
    $odd = value_test_empty_counts();

    foreach ($data['question_analysis'] as &$qa) {
        $qid = (int) ($qa['question_id'] ?? 0);
        $meta = $byQ[$qid] ?? null;
        $bucket = $qa['choice_bucket'] ?? '';
        $weighted = (float) ($qa['weighted_score'] ?? 0);

        $qa['section_id'] = $meta['section_id'] ?? null;
        $qa['section_name'] = $meta['section_name'] ?? '';
        $qa['value_number'] = $meta['value_number'] ?? null;

        $sid = (int) ($meta['section_id'] ?? 0);
        if ($sid > 0 && isset($sections[$sid]) && $bucket !== '') {
            $sections[$sid]['counts'][$bucket]++;
            if ($bucket === 'me') {
                $sections[$sid]['weighted_me'] += $weighted;
            } elseif ($bucket === 'not_me') {
                $sections[$sid]['weighted_not_me'] += $weighted;
            }

            $vn = (int) $sections[$sid]['value_number'];
            if (!isset($valuesSummary[$vn])) {
                $valuesSummary[$vn] = [
                    'value_number' => $vn,
                    'section_name' => $sections[$sid]['section_name'],
                    'counts'       => value_test_empty_counts(),
                ];
            }
            $valuesSummary[$vn]['counts'][$bucket]++;

            if (is_even_value_number($vn)) {
                $even[$bucket]++;
            } else {
                $odd[$bucket]++;
            }
        }
    }
    unset($qa);

    ksort($valuesSummary);
    $data['sections_summary'] = array_values($sections);
    usort($data['sections_summary'], function ($a, $b) {
        return ($a['sort_order'] ?? 0) <=> ($b['sort_order'] ?? 0);
    });
    foreach ($data['sections_summary'] as &$s) {
        $s['weighted_me'] = round((float) $s['weighted_me'], 2);
        $s['weighted_not_me'] = round((float) $s['weighted_not_me'], 2);
    }
    unset($s);

    $data['values_summary'] = array_values($valuesSummary);
    $data['even_values_summary'] = $even;
    $data['odd_values_summary'] = $odd;
    $data['has_section_analytics'] = count($sections) > 0;
}
