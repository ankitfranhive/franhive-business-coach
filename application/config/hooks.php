<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| Existing CheckPermission / Common hooks stay disabled — they are incomplete
| and would break login. License enforcement is the only active hook.
*/

$hook['post_controller_constructor'][] = array(
    'class'    => 'LicenseEnforcer',
    'function' => 'enforce',
    'filename' => 'LicenseEnforcer.php',
    'filepath' => 'hooks',
);

/*
$hook['pre_controller'][] = array(
    'class'    => 'CheckPermission',
    'function' => 'check',
    'filename' => 'CommonHooks.php',
    'filepath' => 'hooks'
);

$hook['pre_controller'][] = array(
    'class'    => 'Common',
    'function' => 'send_email',
    'filename' => 'CommonHooks.php',
    'filepath' => 'hooks'
);
*/
