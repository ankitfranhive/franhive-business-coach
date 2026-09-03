<?php
// Helpers
function money_fmt($cents, $cur) {
  $cur = strtoupper($cur ?: 'USD');
  $symbols = ['USD'=>'$','INR'=>'₹','EUR'=>'€','GBP'=>'£','AUD'=>'A$','CAD'=>'C$'];
  $sym = $symbols[$cur] ?? '';
  return $sym . number_format(($cents/100), 2) . " " . $cur;
}

$name   = $customer_name ?: 'there';
$email  = $customer_email ?: '';
$total  = money_fmt($amount_total ?? 0, $currency ?? 'usd');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Payment Successful</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 4 -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <style>
    body {
      min-height: 100vh;
      background: radial-gradient(1200px 600px at 50% -20%, #fff6e9, #f3f6f8 50%, #e9eef2);
      color: #1f2937;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji","Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    }
    .card {
      border: 0;
      border-radius: 18px;
      box-shadow: 0 10px 30px rgba(16,24,40,.08);
      overflow: hidden;
    }
    .hero {
      background: linear-gradient(135deg, #34d399, #10b981);
      color: #fff;
      padding: 32px 24px;
    }
    .checkmark {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      background: #fff;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 8px 20px rgba(255,255,255,.25);
    }
    .checkmark svg { width: 36px; height: 36px; }
    .details dt { color: #6b7280; }
    .details dd { margin-bottom: .75rem; }
    .item-row {
      display: flex; justify-content: space-between; align-items: center;
      padding: 10px 0; border-bottom: 1px dashed #e5e7eb;
    }
    .item-row:last-child { border-bottom: 0; }
    .btn-brand {
      background-color: #f9af30; border: none; color: #111827;
      font-weight: 600; border-radius: 10px;
    }
    .btn-brand:hover { background-color: #e89c28; color: #111827; }
    .muted { color: #6b7280; }
    .smallcaps { font-variant: all-small-caps; letter-spacing: .04em; }
  </style>
</head>
<body>
  <div class="container" style="max-width: 880px;">
    <div class="card">
      <div class="hero text-center">
        <div class="checkmark mb-3">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M20 7L9 18L4 13" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <h1 class="h3 mb-1">Payment successful</h1>
        <p class="mb-0">Thanks, <strong><?= htmlspecialchars($name) ?></strong>! Your transaction is confirmed.</p>
      </div>

      <div class="card-body p-4 p-md-5">
        <div class="row">
          <div class="col-md-7">
            <h2 class="h5 mb-3">Order summary</h2>

            <?php if (!empty($items)): ?>
              <div class="mb-3">
                <?php foreach ($items as $it): ?>
                  <div class="item-row">
                    <div>
                      <div class="font-weight-600"><?= htmlspecialchars($it['name']) ?></div>
                      <div class="muted small">Qty: <?= (int)($it['quantity'] ?? 1) ?></div>
                    </div>
                    <div class="font-weight-600"><?= money_fmt((int)$it['amount'], $currency) ?></div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mt-3 pt-2" style="border-top:1px solid #eef2f7">
              <div class="smallcaps muted">Total paid</div>
              <div class="h5 mb-0"><?= $total ?></div>
            </div>
          </div>

          <div class="col-md-5 mt-4 mt-md-0">
            <h2 class="h5 mb-3">Payment details</h2>
            <dl class="details">
              <?php if ($email): ?>
                <dt>Email</dt><dd><?= htmlspecialchars($email) ?></dd>
              <?php endif; ?>
              <?php if (!empty($card_brand) || !empty($card_last4)): ?>
                <dt>Card</dt>
                <dd><?= htmlspecialchars(strtoupper($card_brand ?? 'CARD')) ?> •••• <?= htmlspecialchars($card_last4 ?? '••••') ?></dd>
              <?php endif; ?>
              <dt>Date</dt><dd><?= htmlspecialchars($created_at) ?></dd>
              <dt>Reference</dt><dd><code><?= htmlspecialchars($session_id) ?></code></dd>
            </dl>

            <div class="d-grid">
              <?php if (!empty($receipt_url)): ?>
                <a class="btn btn-success btn-block mb-2" href="<?= htmlspecialchars($receipt_url) ?>" target="_blank" rel="noopener">Download receipt</a>
              <?php endif; ?>
              <a class="btn btn-brand btn-block" href="<?= base_url() ?>">Go to homepage</a>
            </div>

            <p class="muted small mt-3 mb-0">
              A confirmation has been sent to your email<?php if($email) echo ' ('.htmlspecialchars($email).')'; ?>.
              If you need help, reply to the email or contact support.
            </p>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Optional confetti (tiny, CSS-only) could be added if you want -->
</body>
</html>
