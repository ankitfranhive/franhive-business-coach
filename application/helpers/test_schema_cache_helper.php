<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Bump when new auto-migrations are added so cache is invalidated. */
define('TEST_SCHEMA_CACHE_VERSION', 'v6');

function test_schema_cache_file()
{
    return APPPATH . 'cache/test_schema_' . TEST_SCHEMA_CACHE_VERSION . '.ok';
}

function test_schema_cache_hit()
{
    return is_file(test_schema_cache_file());
}

function test_schema_cache_mark_ok()
{
    $dir = APPPATH . 'cache';
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    @file_put_contents(test_schema_cache_file(), date('c'));
}

/**
 * Run lightweight schema ensures once per deploy (file cache) + once per request (static).
 */
function ensure_test_admin_schema_cached($db)
{
    static $done = false;
    if ($done) {
        return true;
    }

    if (test_schema_cache_hit()) {
        $done = true;
        return true;
    }

    $CI = function_exists('get_instance') ? get_instance() : null;
    if ($CI) {
        $CI->load->helper('test_reattempt');
        $CI->load->helper('test_sections');
    } else {
        require_once APPPATH . 'helpers/test_reattempt_helper.php';
        require_once APPPATH . 'helpers/test_sections_helper.php';
    }

    ensure_test_user_reattempt_grant_table($db);
    ensure_test_series_policy_columns($db);
    ensure_test_sections_schema($db);

    test_schema_cache_mark_ok();
    $done = true;
    return true;
}
