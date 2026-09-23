<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Access unavailable</title>
    <style>
        body{margin:0;font-family:Georgia,serif;background:#f4f1ea;color:#2b2b2b;}
        .wrap{max-width:520px;margin:12vh auto;padding:32px;background:#fff;border:1px solid #e6e0d4;border-radius:8px;}
        h1{font-size:22px;margin:0 0 12px;}
        p{line-height:1.5;margin:0 0 10px;}
        a{color:#1b4d6e;}
    </style>
</head>
<body>
<div class="wrap">
<?php
    $reason = $reason ?? 'invalid';
    $expiry_label = !empty($expiry_date) ? date('d-m-Y', strtotime($expiry_date)) : '';
?>
<?php if (($variant ?? 'user') === 'admin'): ?>
    <h1>Subscription inactive</h1>
    <?php if ($reason === 'expired'): ?>
        <p>Your AMC/subscription expired<?php if ($expiry_label): ?> on <?= htmlspecialchars($expiry_label); ?><?php endif; ?>. Please renew to continue using the CRM.</p>
    <?php elseif ($reason === 'suspended'): ?>
        <p>This subscription is suspended or cancelled. Please contact support to restore access.</p>
    <?php else: ?>
        <p>This subscription could not be verified<?php if ($expiry_label): ?> (expiry <?= htmlspecialchars($expiry_label); ?>)<?php endif; ?>. Save and sign the license again from the vendor panel, then refresh.</p>
    <?php endif; ?>
    <?php if (!empty($renew_url)): ?>
        <p><a href="<?= htmlspecialchars($renew_url); ?>">Renew now</a></p>
    <?php endif; ?>
    <?php if (!empty($support_email)): ?>
        <p>Need help? Contact <?= htmlspecialchars($support_email); ?>.</p>
    <?php endif; ?>
<?php else: ?>
    <h1>Access denied</h1>
    <p>You are not authorized to access this system. Please contact your administrator.</p>
<?php endif; ?>
</div>
</body>
</html>
