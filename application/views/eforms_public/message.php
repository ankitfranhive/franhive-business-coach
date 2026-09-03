<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Empower Your Destiny</title>
  <style>
    body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f6f7fb;color:#222;}
    .topbar{background:#fff;border-bottom:1px solid #eee;}
    .topbar-inner{max-width:900px;margin:0 auto;padding:14px 16px;display:flex;align-items:center;gap:14px;}
    .logo{height:46px;display:block;}
    .wrap{max-width:900px;margin:18px auto;padding:0 16px;}
    .card{background:#fff;border:1px solid #eee;border-radius:12px;padding:18px;box-shadow:0 2px 10px rgba(0,0,0,0.04);}
    .msg{font-size:16px;margin:0;}
    .footer{max-width:900px;margin:20px auto 40px;padding:0 16px;color:#777;font-size:12px;}
  </style>
</head>
<body>

  <div class="topbar">
    <div class="topbar-inner">
      <img class="logo" src="https://empoweryourdestiny.com.au/wp-content/uploads/2023/09/EYD-Logo-without-tag-line.png" alt="Empower Your Destiny">
      <div style="font-weight:700;">Empower Your Destiny</div>
    </div>
  </div>

  <div class="wrap">
    <div class="card">
      <p class="msg"><?= html_escape($message ?? 'Done') ?></p>
    </div>
  </div>

  <div class="footer">
    © <?= date('Y') ?> Empower Your Destiny.
  </div>

</body>
</html>
