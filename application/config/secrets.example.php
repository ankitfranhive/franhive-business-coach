<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Copy this file to secrets.php and fill in real values.
 * secrets.php is gitignored and must not be committed.
 */

defined('STRIPE_MODE') || define('STRIPE_MODE', 'live'); // 'test' or 'live'

defined('STRIPE_PUBLISHABLE_KEY') || define('STRIPE_PUBLISHABLE_KEY', 'YOUR_STRIPE_PUBLISHABLE_KEY');
defined('STRIPE_SECRET_KEY')      || define('STRIPE_SECRET_KEY',      'YOUR_STRIPE_SECRET_KEY');
defined('STRIPE_WEBHOOK_SECRET')  || define('STRIPE_WEBHOOK_SECRET',  'YOUR_STRIPE_WEBHOOK_SECRET');

defined('CT_SECRET') || define('CT_SECRET', 'REPLACE_ME');
