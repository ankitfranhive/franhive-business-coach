<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('campaign/partials/campaign_styles'); ?>
<?php
$from_name = 'Barinderjeet Kaur';
$from_email = defined('EMAIL_CONFIG_EMAIL') ? EMAIL_CONFIG_EMAIL : 'nlp@empoweryourdestiny.com.au';
$to_email = trim((string)($log['TO_EMAIL'] ?? ''));
$to_name = trim((string)($log['RECIPIENT_NAME'] ?? $log['TO_NAME'] ?? ($lead['NAME'] ?? '')));
$subject = (string)($log['SUBJECT'] ?? '(No subject)');
$sent_ts = !empty($log['SEND_DATE']) ? strtotime($log['SEND_DATE']) : false;
$sent_at = $sent_ts ? date('d/m/y h:i A', $sent_ts) : (string)($log['SEND_DATE'] ?? '');
$status = ucfirst(strtolower((string)($log['STATUS'] ?? 'sent')));
$body = (string)($log['BODY'] ?? '');
$initial = strtoupper(substr($from_name !== '' ? $from_name : 'E', 0, 1));
$body_html = $body;
if ($body_html !== '' && preg_match('/<body[^>]*>(.*)<\/body>/is', $body_html, $body_match)) {
    $body_html = $body_match[1];
} elseif ($body !== '' && stripos($body, '<html') === false) {
    $body_html = $body;
}

$iframe_src = $body;
if ($iframe_src !== '' && stripos($iframe_src, '<html') === false) {
    $iframe_src = '<!DOCTYPE html><html><head><meta charset="utf-8"><style>body{font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#202124;line-height:1.55;margin:16px;}img{max-width:100%;height:auto;}</style></head><body>' . $body . '</body></html>';
}

$print_name = $to_name !== '' ? $to_name : $to_email;
$print_name = preg_replace('/[^A-Za-z0-9]+/', '_', $print_name);
$print_name = trim((string)$print_name, '_');
if ($print_name === '') {
    $print_name = 'lead';
}
$print_title = $print_name . '_sent_email_' . (int)($log['ID'] ?? 0);
$lead_id = (int)($lead['ENTITY_ID'] ?? $log['LEAD_ID'] ?? 0);
?>
<div class="mobile-menu-overlay"></div>
<div class="main-container sent-email-print-page">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="page-header">
            <div class="row">
                <div class="col-md-8 col-sm-12">
                    <div class="title">
                        <h4>Sent email</h4>
                        <small class="text-muted">This is the email as it was sent to the lead.</small>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 text-right">
                    <?php if ($lead_id > 0): ?>
                        <a class="btn btn-outline-secondary" href="<?= base_url('lead-emails/' . $lead_id); ?>">Back to email history</a>
                    <?php endif; ?>
                    <a class="btn btn-warning" href="<?= base_url('leads'); ?>">Back to leads</a>
                </div>
            </div>
        </div>

        <div class="gmail-actions">
            <span class="cm-sent-status"><?= htmlspecialchars($status !== '' ? $status : 'Sent') ?> from our side</span>
            <button type="button" class="btn btn-sm btn-warning" id="printSentEmailBtn">Print / save for screenshot</button>
        </div>

        <div class="gmail-mail" id="sentEmailCard">
            <div class="gmail-mail-top">
                <span class="cm-sent-status"><?= htmlspecialchars($status !== '' ? $status : 'Sent') ?></span>
                <div class="gmail-subject"><?= htmlspecialchars($subject) ?></div>
                <div class="gmail-meta">
                    <div class="gmail-avatar"><?= htmlspecialchars($initial) ?></div>
                    <div class="gmail-meta-text">
                        <div class="gmail-from">
                            <?= htmlspecialchars($from_name) ?>
                            <span>&lt;<?= htmlspecialchars($from_email) ?>&gt;</span>
                        </div>
                        <div class="gmail-to">
                            to <?= htmlspecialchars($to_name !== '' && strcasecmp($to_name, $to_email) !== 0 ? $to_name . ' <' . $to_email . '>' : $to_email) ?>
                        </div>
                        <div class="gmail-date"><?= htmlspecialchars($sent_at) ?></div>
                    </div>
                </div>
            </div>
            <?php if ($iframe_src !== ''): ?>
                <iframe class="gmail-body-frame" sandbox="allow-same-origin allow-popups" srcdoc="<?= htmlspecialchars($iframe_src, ENT_QUOTES, 'UTF-8') ?>"></iframe>
                <div class="gmail-body-print"><?= $body_html ?></div>
            <?php else: ?>
                <div style="padding:20px;color:#6b7280;">This sent record does not have a stored email body.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>
<script>
(function () {
    var printTitle = <?= json_encode($print_title) ?>;
    document.title = printTitle;

    var printBtn = document.getElementById('printSentEmailBtn');
    if (printBtn) {
        printBtn.addEventListener('click', function () {
            document.title = printTitle;
            window.print();
        });
    }

    window.addEventListener('beforeprint', function () {
        document.title = printTitle;
    });

    var frame = document.querySelector('.gmail-body-frame');
    if (!frame) return;
    frame.addEventListener('load', function () {
        try {
            var doc = frame.contentDocument;
            if (!doc || !doc.body) return;
            var height = Math.max(doc.body.scrollHeight, doc.documentElement.scrollHeight, 200);
            frame.style.height = (height + 24) + 'px';
        } catch (e) {}
    });
})();
</script>
</body>
</html>
