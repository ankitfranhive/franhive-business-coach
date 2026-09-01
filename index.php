<?php
/**
 * CodeIgniter
 */

define('ENVIRONMENT', 'development'); // change to 'production' on live

// Show errors in development, but hide PHP 8.4/8.5 deprecations
// (CodeIgniter 3.1.6 is not compatible with those notices).
if (ENVIRONMENT !== 'production') {
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
    ini_set('display_errors', 1);
}

// Path to the front controller (this file)
$system_path = 'system';
$application_folder = 'application';   // adjust if your app folder is elsewhere
$view_folder = '';

// ---------------------------------------------------------------
// NO EDITS BELOW THIS LINE NORMALLY
// ---------------------------------------------------------------

if (($_temp = realpath($system_path)) !== false) {
    $system_path = $_temp;
}

$system_path = rtrim($system_path, '/\\') . '/';

if (!is_dir($system_path)) {
    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo 'Your system folder path does not appear to be set correctly.';
    exit(3);
}

define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('BASEPATH', $system_path);
define('FCPATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('SYSDIR', basename(BASEPATH));

if (is_dir($application_folder)) {
    if (($_temp = realpath($application_folder)) !== false) {
        $application_folder = $_temp;
    }
    define('APPPATH', $application_folder . DIRECTORY_SEPARATOR);
} else {
    if (!is_dir(BASEPATH . $application_folder . DIRECTORY_SEPARATOR)) {
        header('HTTP/1.1 503 Service Unavailable.', true, 503);
        echo 'Your application folder path does not appear to be set correctly.';
        exit(3);
    }
    define('APPPATH', BASEPATH . $application_folder . DIRECTORY_SEPARATOR);
}

if (!isset($view_folder[0]) && is_dir(APPPATH . 'views' . DIRECTORY_SEPARATOR)) {
    $view_folder = APPPATH . 'views';
} elseif (is_dir($view_folder)) {
    if (($_temp = realpath($view_folder)) !== false) {
        $view_folder = $_temp;
    } else {
        $view_folder = rtrim($view_folder, '/\\');
    }
} elseif (!is_dir(APPPATH . $view_folder . DIRECTORY_SEPARATOR)) {
    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo 'Your view folder path does not appear to be set correctly.';
    exit(3);
}

define('VIEWPATH', $view_folder . DIRECTORY_SEPARATOR);

// Boot CI
require_once BASEPATH . 'core/CodeIgniter.php';
