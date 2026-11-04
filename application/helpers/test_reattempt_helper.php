<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Resolve actual column name when schema mixes legacy uppercase and lowercase.
 */
function test_db_column($db, $table, array $candidates, $fallback)
{
    foreach ($candidates as $col) {
        if ($db->field_exists($col, $table)) {
            return $col;
        }
    }
    return $fallback;
}

/**
 * Grant table used when admin clicks "Re Assigned Test". Safe to call repeatedly.
 */
function ensure_test_user_reattempt_grant_table($db)
{
    if ($db->table_exists('TEST_USER_REATTEMPT_GRANT')) {
        return true;
    }
    $sql = 'CREATE TABLE IF NOT EXISTS TEST_USER_REATTEMPT_GRANT (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id INT UNSIGNED NOT NULL,
        test_id INT UNSIGNED NOT NULL,
        granted_at DATETIME NOT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY uq_user_test (user_id, test_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';
    return (bool) $db->query($sql);
}

/**
 * Per-test resume (draft) and reattempt policy on TEST_SERIES.
 */
function ensure_test_series_policy_columns($db)
{
    $columns = [
        'RESUME_AVAILABLE'     => 'TINYINT(1) NOT NULL DEFAULT 0',
        'REATTEMPT_AVAILABLE'  => 'TINYINT(1) NOT NULL DEFAULT 0',
    ];
    foreach ($columns as $col => $def) {
        if (!$db->field_exists($col, 'TEST_SERIES')) {
            $db->query("ALTER TABLE `TEST_SERIES` ADD COLUMN `{$col}` {$def}");
        }
    }
    return true;
}

function test_series_policy_from_row(array $row)
{
    return [
        'resume_available'    => (int)($row['RESUME_AVAILABLE'] ?? $row['resume_available'] ?? 0),
        'reattempt_available' => (int)($row['REATTEMPT_AVAILABLE'] ?? $row['reattempt_available'] ?? 0),
    ];
}

/** Latest attempt is an in-progress draft (not finally submitted). */
function test_attempt_is_draft(array $row)
{
    $sub = (int) ($row['IS_SUBMITTED'] ?? $row['is_submitted'] ?? 1);
    if ($sub === 1) {
        return false;
    }
    $responseId = (int) ($row['RESPONSE_ID'] ?? $row['ID'] ?? $row['id'] ?? 0);
    if ($responseId > 0) {
        return true;
    }
    $raw = $row['USER_RESPONSE'] ?? $row['user_response'] ?? null;
    return $raw !== null && $raw !== '';
}

function test_row_int(array $row, array $keys, $default = 0)
{
    foreach ($keys as $key) {
        if (array_key_exists($key, $row) && $row[$key] !== null && $row[$key] !== '') {
            return (int) $row[$key];
        }
    }
    return (int) $default;
}
