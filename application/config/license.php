<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| License / AMC module (vendor-only).
| Real secrets live in license_local.php (gitignored) or environment variables.
*/

$config['license_admin_slug'] = getenv('LICENSE_ADMIN_SLUG') ?: 'sys-38564376';
$config['license_signing_secret'] = getenv('LICENSE_SIGNING_SECRET') ?: '';
$config['license_admin_allowed_ips'] = getenv('LICENSE_ADMIN_ALLOWED_IPS') ?: '';
$config['license_cron_token'] = getenv('LICENSE_CRON_TOKEN') ?: '';
$config['license_alert_email'] = getenv('LICENSE_ALERT_EMAIL') ?: 'vendor@franhive.internal';
$config['license_alert_webhook'] = getenv('LICENSE_ALERT_WEBHOOK') ?: '';
$config['license_heartbeat_url'] = getenv('LICENSE_HEARTBEAT_URL') ?: '';
$config['license_support_email'] = getenv('LICENSE_SUPPORT_EMAIL') ?: 'support@franhive.com';
$config['license_renew_url'] = getenv('LICENSE_RENEW_URL') ?: '';
$config['license_vendor_email'] = getenv('LICENSE_VENDOR_EMAIL') ?: 'vendor@franhive.internal';
$config['license_vendor_password_hash'] = getenv('LICENSE_VENDOR_PASSWORD_HASH') ?: '';
$config['license_session_ttl'] = 20; // minutes
$config['license_super_admin_permission_id'] = 9; // User Management — not a role name
$config['license_enforcement_files'] = array(
    'application/libraries/LicenseVerifier.php',
    'application/hooks/LicenseEnforcer.php',
    'application/views/license/blocker.php',
);

$local = __DIR__ . '/license_local.php';
if (is_file($local)) {
    include $local;
}
