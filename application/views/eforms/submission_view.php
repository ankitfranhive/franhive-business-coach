<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<?php
function ua_summary($ua) {
  $ua = (string)$ua;

  // OS
  $os = 'Unknown OS';
  if (stripos($ua, 'Mac OS X') !== false) {
    $os = 'macOS';
    if (preg_match('/Mac OS X ([0-9_]+)/i', $ua, $m)) $os .= ' '.str_replace('_','.', $m[1]);
  } elseif (stripos($ua, 'Windows') !== false) {
    $os = 'Windows';
  } elseif (stripos($ua, 'Android') !== false) {
    $os = 'Android';
  } elseif (stripos($ua, 'iPhone') !== false || stripos($ua, 'iPad') !== false) {
    $os = 'iOS';
  } elseif (stripos($ua, 'Linux') !== false) {
    $os = 'Linux';
  }

  // Browser
  $browser = 'Browser';
  if (stripos($ua, 'Edg/') !== false && preg_match('/Edg\/([0-9\.]+)/', $ua, $m)) {
    $browser = 'Edge '.$m[1];
  } elseif (stripos($ua, 'Chrome/') !== false && preg_match('/Chrome\/([0-9\.]+)/', $ua, $m)) {
    $browser = 'Chrome '.$m[1];
  } elseif (stripos($ua, 'Firefox/') !== false && preg_match('/Firefox\/([0-9\.]+)/', $ua, $m)) {
    $browser = 'Firefox '.$m[1];
  } elseif (stripos($ua, 'Safari/') !== false && stripos($ua, 'Chrome/') === false && preg_match('/Version\/([0-9\.]+)/', $ua, $m)) {
    $browser = 'Safari '.$m[1];
  }

  return $browser.' on '.$os;
}
?>


<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">

        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title"><h4>Submission #<?= (int)$submission['id'] ?></h4></div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-warning" href="<?= base_url('admin_eforms/submissions'); ?>">Back</a>
                </div>
            </div>
        </div>

        <div class="pd-20 card-box mb-30">
            <h5 class="mb-2">Basic</h5>
            <p><b>Template:</b> <?= html_escape($submission['template_title'] ?? '') ?></p>
            <p><b>Client:</b> <?= html_escape($submission['client_name'] ?? '') ?> (<?= html_escape($submission['client_email'] ?? '') ?>)</p>
            <p><b>Submitted At:</b> <?= html_escape($submission['created_at'] ?? '') ?></p>

            <hr>
            <h5 class="mb-2">Audit (Proof)</h5>
            <p><b>IP Address:</b> <?= html_escape($submission['ip_address'] ?? '') ?></p>
            <p><b>User Agent (short):</b> <?= html_escape(ua_summary($submission['user_agent'] ?? '')) ?></p>
            <p><b>User Agent (full):</b> <span style="word-break:break-word;"><?= html_escape($submission['user_agent'] ?? '') ?></span></p>


            <?php if(!empty($submission['signature_path'])): ?>
                <p><b>Signature:</b><br>
                    <img src="<?= base_url($submission['signature_path']); ?>" style="max-width:400px;border:1px solid #ddd;padding:8px;border-radius:6px;">
                </p>
            <?php endif; ?>

            <p><b>PDF:</b>
                <a class="btn btn-sm btn-success" target="_blank" href="<?= base_url('admin_eforms/download_pdf/' . (int)$submission['id']); ?>">Download PDF</a>
                <small class="text-muted d-block mt-1">PDF includes Basic + Audit details and filled values.</small>
            </p>

            <hr>
            <h5 class="mb-2">Filled Values</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead><tr><th>Field</th><th>Value</th></tr></thead>
                    <tbody>
                    <?php if(!empty($values)): ?>
                        <?php foreach($values as $v): ?>
                            <tr>
                                <td><?= html_escape($v['field_label'] ?? $v['field_name']) ?></td>
                                <td style="white-space:pre-wrap;"><?= html_escape($v['value_text'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="2">No values found.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</div>
</body>
<?php $this->load->view('includes/footer'); ?>
</html>
