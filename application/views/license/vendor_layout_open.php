<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>License ops</title>
<style>
body{margin:0;font-family:-apple-system,BlinkMacSystemFont,sans-serif;background:#0f1419;color:#e7ecf3;}
a{color:#8cb4ff;text-decoration:none;}
.top{display:flex;gap:16px;align-items:center;padding:12px 20px;background:#1b232c;border-bottom:1px solid #2c3640;}
.top b{margin-right:auto;}
.wrap{padding:20px 24px;max-width:1100px;}
.flash{padding:8px 12px;border-radius:6px;margin:0 0 12px;}
.ok{background:#14351f;color:#b6f0c5;}
.err{background:#3a1515;color:#ffc4c4;}
table{width:100%;border-collapse:collapse;background:#1b232c;}
th,td{padding:8px 10px;border-bottom:1px solid #2c3640;text-align:left;font-size:13px;}
input,select,textarea{width:100%;box-sizing:border-box;padding:7px 8px;background:#0f1419;color:#e7ecf3;border:1px solid #3a4654;border-radius:4px;}
label{display:block;font-size:12px;margin:0 0 4px;color:#9aa8b8;}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
.card{background:#1b232c;padding:14px;border-radius:8px;margin:0 0 16px;}
button,.btn{display:inline-block;background:#2f6fed;color:#fff;border:0;padding:8px 12px;border-radius:4px;cursor:pointer;}
.muted{color:#8b98a5;font-size:12px;}
</style>
</head>
<body>
<div class="top">
    <b>License ops</b>
    <a href="<?= htmlspecialchars(base_url($slug)); ?>">Clients</a>
    <a href="<?= htmlspecialchars(base_url($slug . '/logs')); ?>">Logs</a>
    <a href="<?= htmlspecialchars(base_url($slug . '/logout')); ?>">Exit</a>
</div>
<div class="wrap">
<?php if (!empty($ok)): ?><div class="flash ok"><?= htmlspecialchars($ok); ?></div><?php endif; ?>
<?php if (!empty($err)): ?><div class="flash err"><?= htmlspecialchars($err); ?></div><?php endif; ?>
