<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$fs = isset($form_settings) ? $form_settings : [];
$req = isset($request) ? $request : [];

$heading = !empty($fs['section_payment_arrangement']) ? $fs['section_payment_arrangement'] : 'Payment arrangement';

$intro_override = isset($req['payment_arrangement_intro_override']) ? trim((string)$req['payment_arrangement_intro_override']) : '';
$settings_intro = isset($fs['payment_arrangement_intro_html']) ? trim((string)$fs['payment_arrangement_intro_html']) : '';
$intro_html = $intro_override !== ''
    ? $intro_override
    : ($settings_intro !== '' ? $fs['payment_arrangement_intro_html'] : '');

$bonus_html = !empty($fs['payment_arrangement_bonus_html']) ? $fs['payment_arrangement_bonus_html'] : '';
$footer_html = !empty($fs['payment_arrangement_plan_footer_html']) ? $fs['payment_arrangement_plan_footer_html'] : '';

$date_max_backend = !empty($fs['payment_arrangement_date_max_override']) ? trim((string)$fs['payment_arrangement_date_max_override']) : '';
$ignore_training = !empty($fs['payment_arrangement_allow_dates_after_training']) && (string)$fs['payment_arrangement_allow_dates_after_training'] === '1';

if ($intro_html === '') {
    $intro_html = '<p class="mb-2"><strong>Please select your preferred payment arrangement:</strong></p>';
}

if ($bonus_html === '') {
    $bonus_html = '<p class="mb-0"><strong>EXCLUSIVE BONUS:</strong> As a token of our appreciation, you may be eligible to receive an exclusive bonus gift. Please connect with our team for details regarding the current offering.</p>';
}

if ($footer_html === '') {
    $footer_html = '<p class="mb-2">Payment reminders may not be issued. It is the participant\'s responsibility to ensure payments are made on time.</p>';
}
?>
<div class="payment-arrangement-section mb-4" id="payment-arrangement-section"
     data-ignore-training="<?= $ignore_training ? '1' : '0'; ?>"
     data-backend-max="<?= html_escape($date_max_backend); ?>">
    <h4 class="section-title"><?= html_escape($heading); ?></h4>

    <div class="payment-arrangement-intro mb-3 consent-rich-body"><?= $intro_html; ?></div>

    <div class="form-group">
        <label class="d-block font-weight-bold mb-2">Preferred arrangement <span class="text-danger">*</span></label>
        <div class="custom-control custom-radio mb-2">
            <input type="radio" class="custom-control-input" id="pa_type_full" name="payment_arrangement_type" value="pay_full" required
                <?= set_radio('payment_arrangement_type', 'pay_full'); ?>>
            <label class="custom-control-label" for="pa_type_full"><strong>Pay in Full</strong></label>
        </div>
        <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" id="pa_type_plan" name="payment_arrangement_type" value="pay_plan"
                <?= set_radio('payment_arrangement_type', 'pay_plan'); ?>>
            <label class="custom-control-label" for="pa_type_plan"><strong>Payment Plan (as agreed with the team – interest waived)</strong></label>
        </div>
    </div>

    <div id="pa-block-full" class="border rounded p-3 mb-3 bg-white" style="display:none;">
        <p class="mb-2">I agree to pay the remaining balance of <strong>$<span id="pa-balance-mirror">0.00</span></strong> in one instalment.</p>
        <p class="text-muted small mb-2">(Amount reflects <strong>Balance payable $</strong> above: Total (inc GST) less Deposit Paid $.)</p>
        <div class="form-group mb-2">
            <label>I commit to completing full payment on or before <span class="text-danger">*</span></label>
            <input type="date" name="pay_full_commit_date" id="pay_full_commit_date" class="form-control pa-full-required" value="<?= set_value('pay_full_commit_date'); ?>">
        </div>
        <div class="consent-rich-body small"><?= $bonus_html; ?></div>
    </div>

    <div id="pa-block-plan" class="border rounded p-3 mb-3 bg-white" style="display:none;">
        <p class="mb-2">I confirm that I have discussed and agreed to a payment plan with a team member. As part of this agreement, Empower Your Destiny has waived any applicable interest.</p>
        <h6 class="mt-3 mb-2">Payment Plan Details</h6>
        <div class="row">
            <div class="col-md-4 form-group mb-2">
                <label>Total amount payable ($) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="pay_plan_total" id="pay_plan_total" class="form-control bg-light pa-plan-required" value="<?= set_value('pay_plan_total'); ?>" readonly tabindex="-1">
                <small class="text-muted">Matches Balance payable $ (remaining after deposit).</small>
            </div>
            <div class="col-md-4 form-group mb-2">
                <label>Number of instalments <span class="text-danger">*</span></label>
                <input type="number" name="pay_plan_num_instalments" id="pay_plan_num_instalments" class="form-control pa-plan-required" min="1" step="1" value="<?= set_value('pay_plan_num_instalments'); ?>">
            </div>
            <div class="col-md-4 form-group mb-2">
                <label>Instalment amount ($) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="pay_plan_instalment_amount" id="pay_plan_instalment_amount" class="form-control bg-light pa-plan-required" value="<?= set_value('pay_plan_instalment_amount'); ?>" readonly tabindex="-1">
                <small class="text-muted">Total payable ÷ number of instalments.</small>
            </div>
            <div class="col-md-4 form-group mb-2">
                <label>Final payment date <span class="text-danger">*</span></label>
                <input type="date" name="pay_plan_final_date" id="pay_plan_final_date" class="form-control pa-capped-date pa-plan-required" value="<?= set_value('pay_plan_final_date'); ?>">
            </div>
            <div class="col-md-8 form-group mb-2">
                <label>Notes <span class="text-danger">*</span></label>
                <textarea name="pay_plan_notes" class="form-control pa-plan-required" rows="2"><?= set_value('pay_plan_notes'); ?></textarea>
            </div>
            <div class="col-md-6 form-group mb-2">
                <label>Name / initial (acknowledgement) <span class="text-danger">*</span></label>
                <input type="text" name="pay_plan_ack_name" class="form-control pa-plan-required" value="<?= set_value('pay_plan_ack_name'); ?>" placeholder="Name or initials">
            </div>
        </div>
        <p class="small mb-2"><span id="pa-ack-name-display">…</span> acknowledge and agree that:</p>
        <ul class="small mb-3">
            <li>All payments must be completed by the agreed final payment date.</li>
            <li>The full program fee remains payable regardless of attendance or participation.</li>
            <li>Late or missed payments may result in additional fees or interest being applied.</li>
        </ul>
        <div class="form-group mb-2">
            <label>I commit to completing full payment on or before <span class="text-danger">*</span></label>
            <input type="date" name="pay_plan_commit_date" id="pay_plan_commit_date" class="form-control pa-plan-required" value="<?= set_value('pay_plan_commit_date'); ?>">
        </div>
        <div class="consent-rich-body small"><?= $footer_html; ?></div>
    </div>
</div>

<script>
(function () {
    var root = document.getElementById('payment-arrangement-section');
    if (!root) return;

    var totalInput = document.getElementById('total_inc_gst_input') || document.querySelector('input[name="total_inc_gst"]');
    var depositInput = document.getElementById('deposit_amount_input') || document.querySelector('input[name="deposit_amount"]');
    var balanceInput = document.querySelector('input[name="balance_amount"]');
    var mirror = document.getElementById('pa-balance-mirror');
    var planTotal = document.getElementById('pay_plan_total');
    var numInst = document.getElementById('pay_plan_num_instalments');
    var instAmt = document.getElementById('pay_plan_instalment_amount');
    var raFull = document.getElementById('pa_type_full');
    var raPlan = document.getElementById('pa_type_plan');
    var blockFull = document.getElementById('pa-block-full');
    var blockPlan = document.getElementById('pa-block-plan');
    var ackInput = document.querySelector('input[name="pay_plan_ack_name"]');
    var ackDisp = document.getElementById('pa-ack-name-display');

    function fmtMoney(v) {
        var n = parseFloat(v);
        if (isNaN(n)) return '0.00';
        return n.toFixed(2);
    }

    function parseMoney(v) {
        var n = parseFloat(v);
        return isNaN(n) ? 0 : n;
    }

    /** Remaining balance: Total (inc GST) − Deposit Paid (not below 0). */
    function computeBalancePayable() {
        var total = parseMoney(totalInput ? totalInput.value : '0');
        var dep = Math.max(0, parseMoney(depositInput ? depositInput.value : '0'));
        if (dep > total) {
            dep = total;
        }
        return Math.round(Math.max(0, total - dep) * 100) / 100;
    }

    function recalcBalanceAndPlan() {
        var bal = computeBalancePayable();
        if (balanceInput) {
            balanceInput.value = fmtMoney(bal);
        }
        if (mirror) {
            mirror.textContent = fmtMoney(bal);
        }
        if (planTotal) {
            planTotal.value = fmtMoney(bal);
        }
        syncInstalment();
    }

    function syncInstalment() {
        if (!instAmt || !numInst) return;
        var totalPay = parseMoney(planTotal ? planTotal.value : '0');
        var n = parseInt(numInst.value, 10);
        if (!n || n < 1) {
            n = 1;
        }
        var each = n > 0 ? Math.round((totalPay / n) * 100) / 100 : 0;
        instAmt.value = fmtMoney(each);
    }

    if (totalInput) {
        totalInput.addEventListener('input', recalcBalanceAndPlan);
        totalInput.addEventListener('change', recalcBalanceAndPlan);
    }
    if (depositInput) {
        depositInput.addEventListener('input', recalcBalanceAndPlan);
        depositInput.addEventListener('change', recalcBalanceAndPlan);
    }
    if (numInst) {
        numInst.addEventListener('input', syncInstalment);
        numInst.addEventListener('change', syncInstalment);
    }
    recalcBalanceAndPlan();

    function setRequired(nodes, on) {
        nodes.forEach(function (el) {
            if (!el) return;
            if (on) el.setAttribute('required', 'required');
            else el.removeAttribute('required');
        });
    }

    function toggles() {
        var full = raFull && raFull.checked;
        var plan = raPlan && raPlan.checked;
        if (blockFull) blockFull.style.display = full ? 'block' : 'none';
        if (blockPlan) blockPlan.style.display = plan ? 'block' : 'none';
        setRequired(document.querySelectorAll('.pa-full-required'), full);
        setRequired(document.querySelectorAll('.pa-plan-required'), plan);
    }

    function syncAck() {
        if (ackDisp && ackInput) {
            var t = (ackInput.value || '').trim();
            ackDisp.textContent = t || '…';
        }
    }

    if (raFull) raFull.addEventListener('change', toggles);
    if (raPlan) raPlan.addEventListener('change', toggles);
    if (ackInput) {
        ackInput.addEventListener('input', syncAck);
        ackInput.addEventListener('change', syncAck);
        syncAck();
    }
    toggles();

    function parseISODate(s) {
        if (!s || typeof s !== 'string') return null;
        var d = new Date(s + 'T12:00:00');
        return isNaN(d.getTime()) ? null : d;
    }

    function getTrainingMaxDate() {
        var inputs = document.querySelectorAll('input[name^="course_dates"]');
        var max = null;
        inputs.forEach(function (inp) {
            if (!inp.value) return;
            var d = parseISODate(inp.value);
            if (!d) return;
            if (!max || d > max) max = d;
        });
        return max;
    }

    function applyDateCaps() {
        var ignore = root.getAttribute('data-ignore-training') === '1';
        var backend = (root.getAttribute('data-backend-max') || '').trim();
        var trainMax = getTrainingMaxDate();
        var capDate = null;

        if (ignore) {
            if (backend) {
                capDate = parseISODate(backend);
            }
        } else {
            if (trainMax) {
                capDate = trainMax;
            }
            if (backend) {
                var bd = parseISODate(backend);
                if (bd && (!capDate || bd < capDate)) {
                    capDate = bd;
                }
            }
        }

        var capStr = capDate ? capDate.toISOString().slice(0, 10) : '';
        document.querySelectorAll('.pa-capped-date').forEach(function (el) {
            el.removeAttribute('max');
            if (capStr) {
                el.setAttribute('max', capStr);
            }
        });
    }

    document.querySelectorAll('input[name^="course_dates"]').forEach(function (inp) {
        inp.addEventListener('change', applyDateCaps);
        inp.addEventListener('input', applyDateCaps);
    });
    applyDateCaps();
})();
</script>
