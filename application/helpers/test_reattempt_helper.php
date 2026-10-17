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
