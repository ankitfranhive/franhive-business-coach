<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// --- Force-load Composer autoload (relative to application/controllers) ---
$autoloads = [
    __DIR__ . '/../../vendor/autoload.php', // /franhive_portal/vendor/autoload.php
    APPPATH . '../vendor/autoload.php',
    FCPATH . 'vendor/autoload.php',
    '/home/u649484662/domains/franhive.com/public_html/franhive_portal/vendor/autoload.php',
];
foreach ($autoloads as $a) {
    if (is_file($a)) { require_once $a; break; }
}

// Now Stripe classes should be available
use Stripe\StripeClient;

ini_set('display_errors', '0');

class PaymentController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url']);
    }

    private function json($arr, int $code = 200)
    {
        if (isset($this->security)) {
            $arr['csrf_token_name'] = $this->security->get_csrf_token_name();
            $arr['csrf_hash']       = $this->security->get_csrf_hash();
        }
        return $this->output->set_status_header($code)
                            ->set_content_type('application/json')
                            ->set_output(json_encode($arr));
    }

    // Tiny diagnostic to confirm autoload + Stripe
    public function autoload_diag()
    {
        $paths = [
            __DIR__ . '/../../vendor/autoload.php',
            APPPATH . '../vendor/autoload.php',
            FCPATH . 'vendor/autoload.php',
            '/home/u649484662/domains/franhive.com/public_html/franhive_portal/vendor/autoload.php',
        ];
        $found = array_values(array_filter($paths, 'is_file'));
        $stripe = class_exists('\Stripe\StripeClient') ? 'yes' : 'no';
        return $this->json(['autoload_found' => $found, 'stripe_class_loaded' => $stripe]);
    }

    public function validate_promo()
    {
        header('Content-Type: application/json; charset=utf-8');
    
        try {
            if (!class_exists('\Stripe\StripeClient')) {
                http_response_code(500);
                echo json_encode(['status'=>'error','message'=>'Stripe SDK not loaded.']); return;
            }
    
            $code     = trim($this->input->post('code') ?: '');
            $amount   = max(0, (int)$this->input->post('amount_cents'));   // e.g. 310200
            $currency = strtolower(trim($this->input->post('currency') ?: 'aud')); // 'aud'
    
            if ($code === '') {
                echo json_encode(['status'=>'error','message'=>'Please enter a coupon code.']); return;
            }
    
            $stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);
    
            // Find ACTIVE promotion code with this exact human code (LIVE or TEST depending on your key)
            $res = $stripe->promotionCodes->all([
                'code'   => $code,        // case-insensitive human code the user typed
                'active' => true,
                'limit'  => 1,
                'expand' => ['data.coupon'],
            ]);
    
            if (!$res || empty($res->data)) {
                // Most common: promo not created in LIVE, or disabled/expired
                echo json_encode(['status'=>'error','message'=>'Invalid or expired coupon.']); return;
            }
    
            $promo  = $res->data[0];
            $coupon = $promo->coupon;
            $restr  = $promo->restrictions ?? null;
    
            // Per-code redemption cap
            if (!empty($promo->max_redemptions) && $promo->times_redeemed >= $promo->max_redemptions) {
                echo json_encode(['status'=>'error','message'=>'This code has reached its redemption limit.']); return;
            }
    
            // Min order amount restriction on the *promotion code*
            if ($restr && !empty($restr->minimum_amount)) {
                $minAmt = (int)$restr->minimum_amount;
                $minCur = strtolower($restr->minimum_amount_currency ?? '');
                if ($minCur && $minCur !== $currency) {
                    echo json_encode(['status'=>'error','message'=>'This code requires prices in '.strtoupper($minCur).'.']); return;
                }
                if ($amount < $minAmt) {
                    echo json_encode(['status'=>'error','message'=>'Minimum order '.strtoupper($currency).' '.number_format($minAmt/100, 2).' required.']); return;
                }
            }
    
            // Compute discount
            $discount = 0;
            if (!empty($coupon->percent_off)) {
                $discount = (int) floor($amount * ($coupon->percent_off / 100));
            } elseif (!empty($coupon->amount_off)) {
                // amount_off coupons are currency-bound
                $cCur = strtolower($coupon->currency ?? '');
                if ($cCur && $cCur !== $currency) {
                    echo json_encode(['status'=>'error','message'=>'This code is only valid for '.strtoupper($cCur).' prices.']); return;
                }
                $discount = min($amount, (int)$coupon->amount_off);
            }
    
            $total = max(0, $amount - $discount);
    
            echo json_encode([
                'status'  => 'success',
                'data'    => [
                    'code'               => $promo->code,
                    'promotion_code_id'  => $promo->id,
                    'coupon_id'          => $coupon->id,
                    'percent_off'        => (int)($coupon->percent_off ?: 0),
                    'discount_cents'     => (int)$discount,
                    'total_cents'        => (int)$total,
                ],
            ]);
            return;
    
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Stripe gave a structured error: show customer-friendly text, log the details
            if (function_exists('log_message')) log_message('error', '[validate_promo] Stripe API: '.$e->getMessage());
            http_response_code(400);
            echo json_encode(['status'=>'error','message'=>$e->getMessage()]); return;
        } catch (\Throwable $e) {
            if (function_exists('log_message')) log_message('error', '[validate_promo] PHP: '.$e->getMessage());
            http_response_code(500);
            echo json_encode(['status'=>'error','message'=>'Server error. Please try again.']); return;
        }
    }
    


public function create_checkout_session()
{
    try {
        if (!class_exists('\Stripe\StripeClient')) {
            throw new \Exception('Stripe SDK not loaded (check composer_autoload).');
        }

        // ---- Inputs (from AJAX) ----
        $amountCents       = (int)($this->input->post('amount_cents') ?? 0);
        $currency          = strtolower(trim($this->input->post('currency') ?? 'aud')); // default AUD
        $productNameRaw    = $this->input->post('product_name') ?: 'Course';
        $landingPageId     = (int)($this->input->post('landing_page_id') ?? 0);
        $promotionCodeId   = trim($this->input->post('promotion_code_id') ?? '');
        $payMode           = strtolower(trim($this->input->post('pay_mode') ?? 'full')); // 'deposit' | 'full'

        // ---- Server-side business rules ----
        // Force deposit amount and disable promos if pay_mode = deposit
        // (never trust the client for money-related logic)
        $DEPOSIT_CENTS = 50000; // A$500

        if ($payMode === 'deposit') {
            $amountCents     = $DEPOSIT_CENTS;
            $promotionCodeId = ''; // ignore any client-sent promo on deposit
            $productName     = $productNameRaw . ' — Deposit';
        } else {
            // Full payment path (promos allowed)
            $productName     = $productNameRaw . ' — Full Payment';
            if ($amountCents <= 0) {
                throw new \Exception('Invalid amount for full payment.');
            }
        }

        // Basic currency sanity
        if (!preg_match('/^[a-z]{3}$/', $currency)) {
            $currency = 'aud';
        }

        $stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);

        // ---- Build Checkout Session params ----
        $params = [
            'mode'                   => 'payment',
            'payment_method_types'   => ['card'], // add more if enabled on your account
            'line_items'             => [[
                'quantity'   => 1,
                'price_data' => [
                    'currency'     => $currency,
                    'unit_amount'  => $amountCents,
                    'product_data' => [
                        'name'     => $productName,
                        'metadata' => [
                            'landing_page_id' => (string)$landingPageId,
                            'pay_mode'        => $payMode,
                        ],
                    ],
                ],
            ]],
            'success_url' => base_url('payment/success?session_id={CHECKOUT_SESSION_ID}'),
            'cancel_url'  => base_url('payment/cancel?session_id={CHECKOUT_SESSION_ID}'),
            'metadata'    => [
                'landing_page_id' => (string)$landingPageId,
                'pay_mode'        => $payMode,
                'currency'        => strtoupper($currency),
            ],
        ];

        // Promotions only on full payments
        if ($payMode !== 'deposit') {
            if ($promotionCodeId !== '') {
                // Pre-apply the exact promotion code
                $params['discounts'] = [[ 'promotion_code' => $promotionCodeId ]];
            } else {
                // Allow customer to enter a code on Checkout page
                $params['allow_promotion_codes'] = true;
            }
        }

        $session = $stripe->checkout->sessions->create($params);

        return $this->json([
            'status' => 'success',
            'data'   => ['session_id' => $session->id],
        ]);
    } catch (\Throwable $e) {
        log_message('error', 'create_checkout_session: ' . $e->getMessage());
        return $this->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}



public function success()
{
    $sessionId = $this->input->get('session_id', TRUE);
    if (!$sessionId) { show_error('Missing session_id', 400); return; }

    // Defaults (used if Stripe calls fail)
    $amountTotal = 0; $currency = 'usd';
    $customerName = ''; $customerEmail = '';
    $receiptUrl = ''; $cardBrand = ''; $cardLast4 = '';
    $createdAt = date('M j, Y g:i A');
    $items = [['name' => 'Purchase', 'quantity' => 1, 'amount' => 0]];
    $err = '';

    // NEW (deposit plan fields; safe defaults)
    $customerId       = '';
    $paymentMethodId  = '';
    $totalCentsMeta   = 0;
    $depositCents     = 0;
    $remainingCents   = 0;
    $planMode         = '';   // 'auto' or 'manual'
    $balanceDueDate   = '';   // YYYY-MM-DD
    $landingPageId    = 0;
    $promotionCodeId  = '';

    // --- Notify admin if actually paid (guard to avoid false positives) ---
// Track admin notify result for debug



    try {
        if (!class_exists('\Stripe\StripeClient')) throw new \Exception('Stripe SDK not loaded.');
        $stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);

        // Keep expansions conservative (add payment_method + latest_charge if available)
        $session = $stripe->checkout->sessions->retrieve($sessionId, [
            'expand' => ['payment_intent.payment_method', 'payment_intent.latest_charge']
        ]);

        // Totals & customer
        $amountTotal   = (int)($session->amount_total ?? $amountTotal);
        $currency      = strtolower($session->currency ?? $currency);
        $customerName  = $session->customer_details->name  ?? $customerName;
        $customerEmail = $session->customer_details->email ?? $customerEmail;
        $customerId    = $session->customer ?? ''; // NEW

        // Metadata (from our deposit Checkout)
        $md = is_object($session->metadata ?? null) ? $session->metadata : (object)[];
        $totalCentsMeta   = (int)($md->total_cents      ?? 0);
        $depositCents     = (int)($md->deposit_cents    ?? 0);
        $remainingCents   = (int)($md->remaining_cents  ?? 0);
        $planMode         = (string)($md->plan_mode     ?? '');
        $balanceDueDate   = (string)($md->balance_due_date ?? '');
        $landingPageId    = (int)($md->landing_page_id  ?? 0);
        $promotionCodeId  = (string)($md->promotion_code_id ?? '');

        // PaymentIntent → latest_charge (receipt + card details) + payment_method id
        $pi = is_object($session->payment_intent ?? null) ? $session->payment_intent : null;
        if (!$pi && is_string($session->payment_intent ?? null)) {
            try { $pi = $stripe->paymentIntents->retrieve($session->payment_intent, ['expand' => ['latest_charge','payment_method']]); } catch (\Throwable $e) {}
        }
        if ($pi) {
            // Card details from latest_charge
            $charge = (is_object($pi->latest_charge ?? null)) ? $pi->latest_charge : null;
            if ($charge) {
                $receiptUrl = $charge->receipt_url ?? $receiptUrl;
                $cardBrand  = $charge->payment_method_details->card->brand ?? $cardBrand;
                $cardLast4  = $charge->payment_method_details->card->last4 ?? $cardLast4;
                if (!empty($charge->created)) $createdAt = date('M j, Y g:i A', $charge->created);
            }
            // NEW: Payment method ID saved for off-session charges
            if (!empty($pi->payment_method)) {
                if (is_object($pi->payment_method) && !empty($pi->payment_method->id)) {
                    $paymentMethodId = $pi->payment_method->id;
                } elseif (is_string($pi->payment_method)) {
                    $paymentMethodId = $pi->payment_method;
                }
            }
        }

        // Line items (separate call; never fatal)
        try {
            $li = $stripe->checkout->sessions->listLineItems($sessionId, ['limit' => 20]);
            if (is_object($li) && is_array($li->data ?? null) && count($li->data)) {
                $items = [];
                foreach ($li->data as $row) {
                    $items[] = [
                        'name'     => $row->description ?? 'Item',
                        'quantity' => (int)($row->quantity ?? 1),
                        'amount'   => (int)($row->amount_total ?? 0),
                    ];
                }
            } else {
                // fallback to session total as single line
                $items = [['name' => 'Purchase', 'quantity' => 1, 'amount' => $amountTotal]];
            }
        } catch (\Throwable $e) {
            $items = [['name' => 'Purchase', 'quantity' => 1, 'amount' => $amountTotal]];
        }

        // In PaymentController::success() (after you fetched $session and metadata):
$this->load->model('OrderModel');
$this->OrderModel->create([
  'landing_page_id'   => $landingPageId,
  'session_id'        => $sessionId,
  'customer_id'       => $customerId,
  'payment_method_id' => $paymentMethodId,   // may be empty if not returned
  'currency'          => strtoupper($currency),
  'total_cents'       => $totalCentsMeta ?: $amountTotal,
  'deposit_cents'     => $depositCents ?: $amountTotal,
  'remaining_cents'   => $remainingCents,
  'plan_mode'         => $planMode ?: 'auto',
  'balance_due_date'  => $balanceDueDate ?: null,
  'promotion_code_id' => $promotionCodeId ?: null,
  'status'            => 'deposit_paid',
]);

// In your cron endpoint:
$today = date('Y-m-d');
$due   = $this->OrderModel->get_due_auto_balances($today);
foreach ($due as $o) {
  // atomically claim
  if (!$this->OrderModel->claim_for_charge($o['id'])) continue;
  $this->load->helper('common');
  // try to charge off-session (your helper)
  $pi = $this->charge_balance_now($o['customer_id'], $o['payment_method_id'], (int)$o['remaining_cents'], strtolower($o['currency']), (int)$o['landing_page_id']);

  if ($pi) {
    $this->OrderModel->mark_balance_paid($o['id']);
  } else {
    // fallback path sent invoice in helper; mark_invoice_sent:
    $this->OrderModel->mark_invoice_sent($o['id']);
  }
}


    } catch (\Throwable $e) {
        $err = $e->getMessage(); // not shown to user unless debug=1
    }

    // --- Robust admin notification on paid checkout ---
$adminMailTried = false;
$adminMailSent  = null;

try {
    // Normalize statuses
    $sessionStatus  = strtolower($session->status ?? '');            // e.g. "complete"
    $paymentStatus  = strtolower($session->payment_status ?? '');    // e.g. "paid"
    $mode           = strtolower($session->mode ?? '');              // e.g. "payment"
    $piStatus       = is_object($pi) ? strtolower($pi->status ?? '') : '';  // e.g. "succeeded"
    $chargeId       = (is_object($charge) && !empty($charge->id)) ? $charge->id : '';
    $chargePaid     = is_object($charge) ? (bool)($charge->paid ?? false) : false;
    $chargeStatus   = is_object($charge) ? strtolower($charge->status ?? '') : ''; // "succeeded"

    // Consider the session "paid" under any of these truths:
    $isPaid = (
        $paymentStatus === 'paid' ||                                  // normal mode=payment success
        ($sessionStatus === 'complete' && $mode === 'payment') ||     // some versions set status=complete
        $piStatus === 'succeeded' ||                                  // PI explicitly succeeded
        $chargePaid || $chargeStatus === 'succeeded'                  // charge is paid
    );

    if ($isPaid) {
        $adminMailTried = true;
        // Send the admin notification
        $adminMailSent = $this->notify_admin_payment_success([
            'product_name'   => $this->input->get('product') ?: ($landing_page['LANDING_PAGE_NAME'] ?? 'Course'),
            'customer_name'  => $customerName,
            'customer_email' => $customerEmail,
            'session_id'     => $sessionId,
            'amount_cents'   => (int)$amountTotal,
            'currency'       => $currency,
            'created_at'     => $createdAt,
            'receipt_url'    => $receiptUrl,
            'card_brand'     => $cardBrand,
            'card_last4'     => $cardLast4,
            'line_items'     => $items,    // array of [name, quantity, amount]
            'charge_id'      => $chargeId,
        ]);
    }
} catch (\Throwable $e) {
    if (function_exists('log_message')) log_message('error', '[success notify] '.$e->getMessage());
}


    // --- Render pretty page (no throws) ---
    // Add AUD symbol mapping (keeps your previous behavior for USD/EUR/INR)
    $curU = strtoupper($currency);
    $sym = ($curU === 'INR') ? '₹' :
           (($curU === 'USD') ? '$' :
           (($curU === 'EUR') ? '€' :
           (($curU === 'AUD') ? 'A$' : '')));
    $fmt = function($c) use($sym,$curU){ return ($sym?:'') . number_format(($c/100), 2) . ' ' . $curU; };

    $nameSafe  = htmlspecialchars($customerName ?: 'there', ENT_QUOTES);
    $emailSafe = htmlspecialchars($customerEmail ?: '', ENT_QUOTES);
    $refSafe   = htmlspecialchars($sessionId, ENT_QUOTES);
    $cardTxt   = ($cardBrand || $cardLast4) ? strtoupper($cardBrand).' •••• '.$cardLast4 : 'Card';
    $showDebug = isset($_GET['debug']) && $_GET['debug'] === '1';

    // Pre-format (used in optional "Balance info" box)
    $hasBalance = ($remainingCents > 0);
    $dueText    = $balanceDueDate ? date('M j, Y', strtotime($balanceDueDate)) : '';
    $planLabel  = ($planMode === 'manual') ? 'Manual invoice' : (($planMode === 'auto') ? 'Auto-charge' : '');
    ?>
<!DOCTYPE html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Payment Successful</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style>
  body{min-height:100vh;background:#f3f6f8;display:flex;align-items:center;justify-content:center;padding:24px}
  .card{border:0;border-radius:18px;box-shadow:0 10px 30px rgba(16,24,40,.08);overflow:hidden;max-width:900px}
  .hero{background:linear-gradient(135deg,#34d399,#10b981);color:#fff;padding:30px 24px}
  .check{width:64px;height:64px;border-radius:50%;background:#fff;display:inline-flex;align-items:center;justify-content:center;margin-bottom:10px}
  .check svg{width:36px;height:36px}
  .item{display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px dashed #e5e7eb}
  .item:last-child{border-bottom:0}
  .muted{color:#6b7280}
  .btn-brand{background:#f9af30;border:none;color:#111827;font-weight:600;border-radius:10px}
  .btn-brand:hover{background:#e89c28}

  /* Optional: balance info badge */
  .balance-box{background:#F3F6F8;border:1px dashed #e5e7eb;border-radius:12px;padding:12px}
  .pill{display:inline-block;background:#111827;color:#fff;border-radius:999px;padding:4px 10px;font-weight:700}
</style>
</head><body>
  <div class="card w-100">
    <div class="hero text-center">
      <div class="check">
        <svg viewBox="0 0 24 24" fill="none"><path d="M20 7L9 18L4 13" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </div>
      <h1 class="h4 mb-1">Payment successful</h1>
      <p class="mb-0">Thanks, <strong><?= $nameSafe ?></strong>! Your transaction is confirmed.</p>
    </div>
    <div class="p-4 p-md-5">
      <div class="row">
        <div class="col-md-7">
          <h2 class="h5 mb-3">Order summary</h2>
          <div class="mb-3">
            <?php foreach ($items as $it): ?>
            <div class="item">
              <div>
                <div class="font-weight-bold"><?= htmlspecialchars($it['name'], ENT_QUOTES) ?></div>
                <div class="muted small">Qty: <?= (int)$it['quantity'] ?></div>
              </div>
              <div class="font-weight-bold"><?= $fmt((int)$it['amount']) ?></div>
            </div>
            <?php endforeach; ?>
          </div>
          <div class="d-flex justify-content-between align-items-center mt-3 pt-2" style="border-top:1px solid #eef2f7">
            <div class="text-uppercase small muted" style="letter-spacing:.04em">Total paid</div>
            <div class="h5 mb-0"><?= $fmt((int)$amountTotal) ?></div>
          </div>
        </div>
        <div class="col-md-5 mt-4 mt-md-0">
          <h2 class="h5 mb-3">Payment details</h2>
          <dl class="mb-3">
            <?php if ($emailSafe): ?><dt class="muted">Email</dt><dd><?= $emailSafe ?></dd><?php endif; ?>
            <dt class="muted">Card</dt><dd><?= htmlspecialchars($cardTxt, ENT_QUOTES) ?></dd>
            <dt class="muted">Date</dt><dd><?= htmlspecialchars($createdAt, ENT_QUOTES) ?></dd>
            <dt class="muted">Reference</dt><dd><code><?= $refSafe ?></code></dd>
          </dl>

          <?php if ($hasBalance): ?>
          <!-- Optional balance info: shows only if remaining_cents > 0 -->
          <div class="balance-box mb-3">
            <div class="small muted mb-1">Balance info</div>
            <div><strong>Remaining:</strong> <span class="pill"><?= $fmt($remainingCents) ?></span></div>
            <?php if ($planLabel): ?><div><strong>Plan:</strong> <?= htmlspecialchars($planLabel, ENT_QUOTES) ?></div><?php endif; ?>
            <?php if ($dueText):   ?><div><strong>Due date:</strong> <?= htmlspecialchars($dueText, ENT_QUOTES) ?></div><?php endif; ?>
            <div class="small muted mt-1">We’ll email updates. Need to change your plan? Contact support.</div>
          </div>
          <?php endif; ?>

          <div class="d-grid">
            <?php if (!empty($receiptUrl)): ?>
              <a class="btn btn-success btn-block mb-2" href="<?= htmlspecialchars($receiptUrl, ENT_QUOTES) ?>" target="_blank" rel="noopener">Download receipt</a>
            <?php endif; ?>
            <a class="btn btn-brand btn-block" href="<?= base_url() ?>">Go to homepage</a>
          </div>
          <p class="muted small mt-3 mb-0">
            A confirmation has been sent to your email<?php if($emailSafe) echo ' ('.$emailSafe.')'; ?>.
          </p>

          <?php if ($showDebug && $err): ?>
            <div class="alert alert-warning mt-3"><strong>Debug:</strong> <?= htmlspecialchars($err, ENT_QUOTES) ?></div>
          <?php endif; ?>
          <?php if (!empty($showDebug) && $showDebug): ?>
  <div class="alert alert-info mt-3 mb-0">
    <div><strong>Debug</strong></div>
    <div>session.status: <?= htmlspecialchars($session->status ?? 'n/a', ENT_QUOTES) ?></div>
    <div>session.payment_status: <?= htmlspecialchars($session->payment_status ?? 'n/a', ENT_QUOTES) ?></div>
    <div>pi.status: <?= htmlspecialchars(is_object($pi)?($pi->status ?? 'n/a'):'n/a', ENT_QUOTES) ?></div>
    <div>charge.status: <?= htmlspecialchars(is_object($charge)?($charge->status ?? 'n/a'):'n/a', ENT_QUOTES) ?></div>
    <div>charge.paid: <?= htmlspecialchars(is_object($charge)?(($charge->paid ?? false)?'true':'false'):'n/a', ENT_QUOTES) ?></div>
    <div>Admin email tried: <?= $adminMailTried ? 'yes' : 'no' ?>; sent: <?= ($adminMailSent===true?'yes':($adminMailSent===false?'no':'n/a')) ?></div>
  </div>
<?php endif; ?>

        </div>
      </div>
    </div>
  </div>
</body></html>
<?php
}


    

public function cancel()
{
    $sessionId = $this->input->get('session_id', TRUE);

    // Defaults (even if we can't talk to Stripe)
    $customerEmail = '';
    $errorMsg = '';
    $createdAt = date('M j, Y g:i A');

    try {
        if ($sessionId && class_exists('\Stripe\StripeClient')) {
            $stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);

            // Pull basic session + last payment error if any
            $session = $stripe->checkout->sessions->retrieve($sessionId, [
                'expand' => ['payment_intent']
            ]);

            $customerEmail = $session->customer_details->email ?? '';
            $pi = is_object($session->payment_intent ?? null) ? $session->payment_intent : null;

            // If PaymentIntent id only, try to expand latest state
            if (!$pi && is_string($session->payment_intent ?? null)) {
                try { $pi = $stripe->paymentIntents->retrieve($session->payment_intent); } catch (\Throwable $e) {}
            }

            if ($pi && isset($pi->last_payment_error) && is_object($pi->last_payment_error)) {
                // Human-friendly reason from Stripe (e.g., “Your card has insufficient funds.”)
                $errorMsg = $pi->last_payment_error->message ?? '';
            }

            if ($pi && !empty($pi->created)) {
                $createdAt = date('M j, Y g:i A', $pi->created);
            }
        }
    } catch (\Throwable $e) {
        // Keep page friendly; log quietly
        log_message('error', 'Payment cancel render error: '.$e->getMessage());
    }

    // Render a polished “Payment not completed” page
    $emailSafe = htmlspecialchars($customerEmail ?: '', ENT_QUOTES);
    $refSafe   = htmlspecialchars($sessionId ?: '—', ENT_QUOTES);
    $msgSafe   = htmlspecialchars($errorMsg ?: 'You canceled the checkout or the payment could not be completed.', ENT_QUOTES);
    $dateSafe  = htmlspecialchars($createdAt, ENT_QUOTES);
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Payment not completed</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style>
  body{min-height:100vh;background:#f3f6f8;display:flex;align-items:center;justify-content:center;padding:24px}
  .card{border:0;border-radius:18px;box-shadow:0 10px 30px rgba(16,24,40,.08);overflow:hidden;max-width:900px}
  .hero{background:linear-gradient(135deg,#f87171,#ef4444);color:#fff;padding:30px 24px}
  .icon{width:64px;height:64px;border-radius:50%;background:#fff;display:inline-flex;align-items:center;justify-content:center;margin-bottom:10px;box-shadow:0 8px 20px rgba(255,255,255,.25)}
  .icon svg{width:36px;height:36px}
  .muted{color:#6b7280}
  .btn-brand{background:#f9af30;border:none;color:#111827;font-weight:600;border-radius:10px}
  .btn-brand:hover{background:#e89c28}
  .tip{display:flex;align-items:flex-start;margin-bottom:.5rem}
  .tip .dot{width:8px;height:8px;border-radius:50%;background:#ef4444;margin-right:.5rem;margin-top:.45rem}
  code{word-break:break-all}
</style>
</head>
<body>
  <div class="card w-100">
    <div class="hero text-center">
      <div class="icon">
        <!-- Cross icon -->
        <svg viewBox="0 0 24 24" fill="none">
          <path d="M6 6L18 18M6 18L18 6" stroke="#ef4444" stroke-width="3" stroke-linecap="round"/>
        </svg>
      </div>
      <h1 class="h4 mb-1">Payment not completed</h1>
      <p class="mb-0">No charges were made. You can try again in a moment.</p>
    </div>

    <div class="p-4 p-md-5">
      <div class="row">
        <div class="col-md-7">
          <h2 class="h5 mb-3">What happened?</h2>
          <div class="alert alert-light border">
            <?= $msgSafe ?>
          </div>

          <h3 class="h6 mt-4 mb-2">Quick tips</h3>
          <div class="tip"><div class="dot"></div><div>Double-check your card details and try again.</div></div>
          <div class="tip"><div class="dot"></div><div>If your bank requires 3D Secure/OTP, complete the verification.</div></div>
          <div class="tip"><div class="dot"></div><div>Try a different card or contact your bank for approval.</div></div>
        </div>

        <div class="col-md-5 mt-4 mt-md-0">
          <h2 class="h5 mb-3">Details</h2>
          <dl class="mb-3">
            <?php if ($emailSafe): ?><dt class="muted">Email</dt><dd><?= $emailSafe ?></dd><?php endif; ?>
            <dt class="muted">Date</dt><dd><?= $dateSafe ?></dd>
            <dt class="muted">Reference</dt><dd><code><?= $refSafe ?></code></dd>
          </dl>

          <div class="d-grid">
            <!-- Send them back to your purchase page -->
            <a class="btn btn-brand btn-block mb-2" href="<?= base_url('The-Destiny-Duo-Certification-Bundle/23') ?>">Try again</a>
            <a class="btn btn-outline-secondary btn-block" href="https://www.facebook.com/barinderjeet.kaur.39">Contact support</a>
          </div>

          <p class="muted small mt-3 mb-0">
            If you were charged by mistake, your bank will automatically reverse the authorization.
          </p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
<?php
}

// application/controllers/PaymentController.php
public function create_checkout_session_deposit()
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        if (!class_exists('\Stripe\StripeClient')) {
            http_response_code(500);
            echo json_encode(['status'=>'error','message'=>'Stripe SDK not loaded (composer_autoload).']);
            return;
        }

        // ---- Inputs ----
        $deposit_cents     = max(0, (int)$this->input->post('deposit_cents'));   // e.g. 50000
        $total_cents       = max(0, (int)$this->input->post('total_cents'));     // e.g. 155100 (after coupon)
        $currency          = strtolower(trim($this->input->post('currency') ?: 'aud'));
        $promotion_code_id = trim($this->input->post('promotion_code_id') ?: '');
        $landing_page_id   = (int)$this->input->post('landing_page_id');
        $product_name      = trim($this->input->post('product_name') ?: 'Course');
        $plan_mode         = in_array($this->input->post('plan_mode'), ['auto','manual'], true)
                             ? $this->input->post('plan_mode') : 'auto';
        $balance_due_date  = trim($this->input->post('balance_due_date') ?: '');

        if ($deposit_cents <= 0) {
            http_response_code(400);
            echo json_encode(['status'=>'error','message'=>'Invalid deposit.']);
            return;
        }
        if ($total_cents < $deposit_cents) $total_cents = $deposit_cents;

        $remaining_cents = max(0, $total_cents - $deposit_cents);

        $stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);

        // Build base params common to both attempts
        $baseParams = [
            'mode'                  => 'payment',
            'payment_method_types'  => ['card'],     // be explicit for older API dates
            'line_items' => [[
                'price_data' => [
                    'currency'     => $currency,
                    'product_data' => [
                        'name'     => $product_name . ' — Deposit',
                        'metadata' => ['landing_page_id' => (string)$landing_page_id],
                    ],
                    'unit_amount'  => $deposit_cents, // CHARGE ONLY THE DEPOSIT NOW
                ],
                'quantity' => 1,
            ]],
            'metadata' => [
                'landing_page_id'   => (string)$landing_page_id,
                'currency'          => $currency,
                'total_cents'       => (string)$total_cents,
                'deposit_cents'     => (string)$deposit_cents,
                'remaining_cents'   => (string)$remaining_cents,
                'plan_mode'         => $plan_mode,
                'balance_due_date'  => $balance_due_date,
                'promotion_code_id' => $promotion_code_id,
            ],
            'success_url' => base_url('payment/success?session_id={CHECKOUT_SESSION_ID}'),
            'cancel_url'  => base_url('payment/cancel'),
        ];

        // Attempt 1: save card for off-session later via setup_future_usage
        $params1 = $baseParams;
        $params1['payment_intent_data'] = [
            'setup_future_usage' => 'off_session',
            'metadata'           => [
                'type'            => 'deposit',
                'landing_page_id' => (string)$landing_page_id,
            ],
        ];

        try {
            $session = $stripe->checkout->sessions->create($params1);
        } catch (\Throwable $e1) {
            // Attempt 2 (fallback): no setup_future_usage (some API versions/accounts reject it here)
            // NOTE: this will still take the deposit; you can save card later via a separate SetupIntent.
            $session = $stripe->checkout->sessions->create($baseParams);
        }

        echo json_encode(['status'=>'success','data'=>['session_id'=>$session->id]]);
        return;

    } catch (\Throwable $e) {
        http_response_code(500);
        // Log the server-side error for deeper debugging
        if (function_exists('log_message')) {
            log_message('error', '[Deposit Checkout] '. $e->getMessage());
        }
        echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
        return;
    }
}


// In application/controllers/PaymentController.php

public function send_bank_details_email()
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        // --- Read inputs ---
        $email      = trim($this->input->post('email') ?: '');
        $first_name = trim($this->input->post('first_name') ?: $this->input->post('name') ?: '');
        $product    = trim($this->input->post('product_name') ?: 'The Destiny Duo Certification');
        $deposit    = max(0, (int)$this->input->post('deposit_cents'));     // e.g. 50000
        $total      = max(0, (int)$this->input->post('total_cents'));       // e.g. 155100
        $remaining  = max(0, (int)$this->input->post('remaining_cents'));   // e.g. 105100
        $currency   = strtolower(trim($this->input->post('currency') ?: 'aud')); // 'aud'
        $coupon     = trim($this->input->post('coupon_code') ?: '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->json_error('Invalid email address.');
        }

        // --- Helpers ---
        $money = function($cents) use ($currency) {
            $prefix = (strtoupper($currency) === 'AUD') ? 'A$' : (strtoupper($currency).' ');
            return $prefix . number_format(($cents / 100), 2);
        };

        // Friendly first name fallback
        if ($first_name === '' && strpos($email, '@') !== false) {
            $guess = ucfirst(preg_replace('/[^a-z]+/i', ' ', strstr($email, '@', true)));
            $first_name = trim($guess) ?: 'there';
        }
        if ($first_name === '') $first_name = 'there';

        // --- Compose subject + HTML body (your wording, with dynamic amounts) ---
        $subject = 'Almost There - Final Step to Secure Your Spot!';

        $body  = '';
        $body .= '<p>Hi '.htmlspecialchars($first_name, ENT_QUOTES).',</p>';
        $body .= '<p>Thank you for enrolling in <strong>'.htmlspecialchars($product, ENT_QUOTES).'</strong>. We are so excited to welcome you into this transformational journey!</p>';
        $body .= '<p>As you have chosen <strong>Bank Transfer</strong> as your payment method, here are the details to finalize your enrolment:</p>';

        // Bank details (AU)
        $body .= '<h4 style="margin:12px 0 6px">💳 Bank Transfer Details (Australia)</h4>';
        $body .= '<ul style="margin:0 0 12px 18px;padding:0">';
        $body .= '<li><strong>Account Name:</strong> Empower Your Destiny</li>';
        $body .= '<li><strong>BSB:</strong> 033161</li>';
        $body .= '<li><strong>Account Number:</strong> 249860</li>';
        $body .= '<li><strong>Bank:</strong> Westpac</li>';
        $body .= '<li><strong>Reference:</strong> Please use your full name + program name (e.g., Jane Smith – Destiny Duo) so we can match your payment.</li>';
        $body .= '</ul>';

        // Coupon guidance
        $body .= '<h4 style="margin:12px 0 6px">✅ Applying Your Coupon Code</h4>';
        $body .= '<p>If you’re using a coupon code (for early bird or bundle offer), simply deduct the discounted amount when making your transfer.</p>';
        $body .= '<p style="margin:6px 0 10px 0">Example:</p>';
        $body .= '<ul style="margin:0 0 12px 18px;padding:0">';
        $body .= '<li>you’ll transfer <strong>'.$money($total).'</strong> (if paying full amount)</li>';
        $body .= '<li>you’ll transfer <strong>'.$money($deposit).'</strong> as deposit (if going for payment plan option)</li>';
        $body .= '</ul>';
        $body .= '<p>(We will crosscheck your payment with the coupon you applied at checkout.)</p>';

        // Optional: echo the remaining explicitly
        if ($remaining > 0) {
            $body .= '<p><em>Remaining balance (due later):</em> <strong>'.$money($remaining).'</strong></p>';
        }

        // Notes
        $body .= '<h4 style="margin:12px 0 6px">Please Note:</h4>';
        $body .= '<ul style="margin:0 0 12px 18px;padding:0">';
        $body .= '<li>Your seat will be confirmed once payment is received and cleared into our account.</li>';
        $body .= '<li>As spaces are limited, we recommend making the transfer promptly to secure your place.</li>';
        $body .= '<li>Once payment is received, you will get a confirmation email with your welcome pack and next steps.</li>';
        $body .= '</ul>';

        $body .= '<p>If you have any questions or need assistance, please reply to this email - we are here to help.</p>';
        $body .= '<p>With gratitude,<br>Empower Your Destiny Team</p>';

        // --- Send via SMTP (Hostinger) ---
        $this->load->library('email');

        $config = [
            'protocol'    => 'smtp',
            'smtp_host'   => 'smtp.hostinger.com',
            'smtp_user'   => 'nlp@empoweryourdestiny.com.au',
            'smtp_pass'   => 'Franh1ve@2024',              // consider moving to a constant/env
            'smtp_port'   => 465,
            'smtp_crypto' => 'ssl',
            'mailtype'    => 'html',
            'charset'     => 'utf-8',
            'newline'     => "\r\n",
        ];
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");

        // From: use your branded sender (can also be the same as smtp_user)
        $fromEmail = defined('EMAIL_CONFIG_EMAIL') ? EMAIL_CONFIG_EMAIL : 'nlp@empoweryourdestiny.com.au';
        $this->email->from($fromEmail, 'EYD Training');
        $this->email->to($email);
        $this->email->subject($subject);
        $this->email->message($body);

        // Optional: send a copy to team
        // $this->email->bcc('support@empoweryourdestiny.com.au');

        if (!$this->email->send()) {
            $err = method_exists($this->email, 'print_debugger') ? $this->email->print_debugger() : 'Email sending failed.';
            if (function_exists('log_message')) log_message('error', '[send_bank_details_email] '.$err);
            return $this->json_error('Email sending failed. Please try again.');
        }

        return $this->json_ok(['sent' => true]);

    } catch (\Throwable $e) {
        if (function_exists('log_message')) log_message('error', '[send_bank_details_email] '.$e->getMessage());
        return $this->json_error('Server error. Please try again.');
    }
}

/* If your controller doesn’t already have json_ok / json_error helpers, add these: */
private function json_ok($data = [])
{
    echo json_encode(['status' => 'success', 'data' => $data]); return;
}
private function json_error($message)
{
    echo json_encode(['status' => 'error', 'message' => $message]); return;
}


private function notify_admin_payment_success(array $info): bool
{
    $this->load->library('email');

    $config = [
        'protocol'    => 'smtp',
        'smtp_host'   => 'smtp.hostinger.com',
        'smtp_user'   => 'nlp@empoweryourdestiny.com.au',
        'smtp_pass'   => 'Franh1ve@2024',
        'smtp_port'   => 465,
        'smtp_crypto' => 'ssl',
        'mailtype'    => 'html',
        'charset'     => 'utf-8',
        'newline'     => "\r\n",
        // 'smtp_timeout' => 15, // optional
    ];
    $this->email->initialize($config);
    $this->email->set_newline("\r\n");

    $to = 'info@empoweryourdestiny.com.au';
    // $to = 'yesiamankitsharma@gmail.com';
    $fromEmail = defined('EMAIL_CONFIG_EMAIL') ? EMAIL_CONFIG_EMAIL : 'nlp@empoweryourdestiny.com.au';

    $amt = function($c,$cur){
        $prefix = (strtoupper($cur)==='AUD') ? 'A$' : (strtoupper($cur).' ');
        return $prefix . number_format(($c/100), 2);
    };

    $subject = 'Payment received — '.$info['product_name'].' ('.$amt($info['amount_cents'], $info['currency']).')';

    $liHtml = '';
    if (!empty($info['line_items']) && is_array($info['line_items'])) {
        foreach ($info['line_items'] as $it) {
            $liHtml .= '<tr>'.
              '<td style="padding:6px 8px;border-bottom:1px solid #eee;">'.htmlspecialchars($it['name']).'</td>'.
              '<td style="padding:6px 8px;border-bottom:1px solid #eee;">'.(int)$it['quantity'].'</td>'.
              '<td style="padding:6px 8px;border-bottom:1px solid #eee;text-align:right;">'.$amt((int)$it['amount'], $info['currency']).'</td>'.
            '</tr>';
        }
    }

    $body = '
      <div style="font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;font-size:14px;line-height:1.5;color:#111;">
        <h2 style="margin:0 0 6px 0;">New payment received</h2>
        <p style="margin:0 0 12px 0;">Product: <strong>'.htmlspecialchars($info['product_name']).'</strong></p>

        <table cellspacing="0" cellpadding="0" style="border-collapse:collapse;width:100%;max-width:640px">
          <tr><td style="padding:4px 0"><strong>Customer</strong></td><td style="padding:4px 0">'.htmlspecialchars($info['customer_name'] ?: '—').'</td></tr>
          <tr><td style="padding:4px 0"><strong>Email</strong></td><td style="padding:4px 0">'.htmlspecialchars($info['customer_email'] ?: '—').'</td></tr>
          <tr><td style="padding:4px 0"><strong>Total paid</strong></td><td style="padding:4px 0">'.$amt($info['amount_cents'], $info['currency']).'</td></tr>
          <tr><td style="padding:4px 0"><strong>Card</strong></td><td style="padding:4px 0">'.htmlspecialchars(strtoupper($info['card_brand']).' •••• '.$info['card_last4']).'</td></tr>
          <tr><td style="padding:4px 0"><strong>Date</strong></td><td style="padding:4px 0">'.htmlspecialchars($info['created_at']).'</td></tr>
          <tr><td style="padding:4px 0"><strong>Session ID</strong></td><td style="padding:4px 0"><code>'.htmlspecialchars($info['session_id']).'</code></td></tr>
          '.(!empty($info['charge_id']) ? '<tr><td style="padding:4px 0"><strong>Charge ID</strong></td><td style="padding:4px 0"><code>'.htmlspecialchars($info['charge_id']).'</code></td></tr>' : '').'
        </table>

        '.($liHtml ? '
        <h3 style="margin:16px 0 6px">Line items</h3>
        <table cellspacing="0" cellpadding="0" style="border-collapse:collapse;width:100%;max-width:640px">
          <thead>
            <tr>
              <th style="text-align:left;padding:6px 8px;border-bottom:1px solid #ddd;">Item</th>
              <th style="text-align:left;padding:6px 8px;border-bottom:1px solid #ddd;">Qty</th>
              <th style="text-align:right;padding:6px 8px;border-bottom:1px solid #ddd;">Amount</th>
            </tr>
          </thead>
          <tbody>'.$liHtml.'</tbody>
        </table>' : '' ).'

        '.(!empty($info['receipt_url']) ? ('<p style="margin:12px 0">Receipt: <a href="'.htmlspecialchars($info['receipt_url']).'" target="_blank" rel="noopener">'.htmlspecialchars($info['receipt_url']).'</a></p>') : '').'
        '.(!empty($info['charge_id']) ? ('<p style="margin:8px 0">Stripe dashboard: <a href="https://dashboard.stripe.com/'.(strpos(STRIPE_SECRET_KEY,'_test_')!==false?'test/':'').'payments/'.htmlspecialchars($info['charge_id']).'" target="_blank" rel="noopener">open payment</a></p>') : '').'
      </div>
    ';

    $this->email->from($fromEmail, 'EYD Training');
    $this->email->to($to);
    $this->email->subject($subject);
    $this->email->message($body);

    $ok = $this->email->send();
    if (!$ok && function_exists('log_message')) {
        log_message('error', '[notify_admin_payment_success] Email send failed: '. $this->email->print_debugger());
    }
    return (bool)$ok;
}



public function test_admin_mail()
{
    $ok = $this->notify_admin_payment_success([
        'product_name'   => 'Test Product',
        'customer_name'  => 'Test User',
        'customer_email' => 'info@empoweryourdestiny.com.au',
        'session_id'     => 'cs_test_123',
        'amount_cents'   => 50000,
        'currency'       => 'aud',
        'created_at'     => date('M j, Y g:i A'),
        'receipt_url'    => '',
        'card_brand'     => 'visa',
        'card_last4'     => '4242',
        'line_items'     => [['name'=>'Deposit','quantity'=>1,'amount'=>50000]],
        'charge_id'      => '',
    ]);
    echo $ok ? 'OK' : 'FAIL';
}


}
