<?php
// $template  = array (tpl row)
// $request   = array (request row)
// $data      = associative array: label => value
// $signature_path = string path like "uploads/eforms/signatures/xxx.png" or null
// $meta      = optional audit/basic details array

$brand_name = "Empower Your Destiny";
$logo_url = "https://empoweryourdestiny.com.au/wp-content/uploads/2023/09/EYD-Logo-without-tag-line.png";

$heading = !empty($template['heading']) ? $template['heading'] : ($template['title'] ?? 'Form');
$subheading = $template['subheading'] ?? '';
$body_html = $template['body_html'] ?? '';
$meta = (isset($meta) && is_array($meta)) ? $meta : [];

if (!function_exists('h')) {
  function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
}

if (!function_exists('pdf_ua_summary')) {
  function pdf_ua_summary($ua) {
    $ua = (string)$ua;
    $os = 'Unknown OS';
    if (stripos($ua, 'Mac OS X') !== false) {
      $os = 'macOS';
      if (preg_match('/Mac OS X ([0-9_]+)/i', $ua, $m)) $os .= ' ' . str_replace('_', '.', $m[1]);
    } elseif (stripos($ua, 'Windows') !== false) {
      $os = 'Windows';
    } elseif (stripos($ua, 'Android') !== false) {
      $os = 'Android';
    } elseif (stripos($ua, 'iPhone') !== false || stripos($ua, 'iPad') !== false) {
      $os = 'iOS';
    } elseif (stripos($ua, 'Linux') !== false) {
      $os = 'Linux';
    }

    $browser = 'Browser';
    if (stripos($ua, 'Edg/') !== false && preg_match('/Edg\/([0-9\.]+)/', $ua, $m)) {
      $browser = 'Edge ' . $m[1];
    } elseif (stripos($ua, 'Chrome/') !== false && preg_match('/Chrome\/([0-9\.]+)/', $ua, $m)) {
      $browser = 'Chrome ' . $m[1];
    } elseif (stripos($ua, 'Firefox/') !== false && preg_match('/Firefox\/([0-9\.]+)/', $ua, $m)) {
      $browser = 'Firefox ' . $m[1];
    } elseif (stripos($ua, 'Safari/') !== false && stripos($ua, 'Chrome/') === false && preg_match('/Version\/([0-9\.]+)/', $ua, $m)) {
      $browser = 'Safari ' . $m[1];
    }

    return $browser . ' on ' . $os;
  }
}

// Prefer meta values, fall back to request/template
$template_title = $meta['template_title'] ?? ($template['title'] ?? $heading);
$client_name    = $meta['client_name'] ?? ($request['client_name'] ?? '');
$client_email   = $meta['client_email'] ?? ($request['client_email'] ?? '');
$submitted_at   = $meta['submitted_at'] ?? date('Y-m-d H:i:s');
$ip_address     = $meta['ip_address'] ?? '';
$user_agent     = $meta['user_agent'] ?? '';
$ua_short       = $user_agent !== '' ? pdf_ua_summary($user_agent) : '';

// DomPDF often fails on local absolute paths ("Image not found or type unknown").
// Embed the signature as a base64 data URI when the file exists.
$signature_src = null;
if (!empty($signature_path)) {
    $candidates = [];
    $raw = trim((string)$signature_path);

    // Absolute path already
    if ($raw !== '' && (strpos($raw, '/') === 0 || preg_match('/^[A-Za-z]:[\\\\\\/]/', $raw))) {
        $candidates[] = $raw;
    }

    // Relative to FCPATH / DOCUMENT_ROOT
    $rel = ltrim(str_replace('\\', '/', $raw), '/');
    $candidates[] = rtrim(FCPATH, '/\\') . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $rel);
    if (!empty($_SERVER['DOCUMENT_ROOT'])) {
        $candidates[] = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $rel);
    }

    foreach ($candidates as $candidate) {
        if (is_file($candidate) && is_readable($candidate)) {
            $bytes = @file_get_contents($candidate);
            if ($bytes !== false && $bytes !== '') {
                $mime = 'image/png';
                if (function_exists('finfo_open')) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    if ($finfo) {
                        $detected = finfo_file($finfo, $candidate);
                        finfo_close($finfo);
                        if (!empty($detected)) $mime = $detected;
                    }
                } else {
                    $ext = strtolower(pathinfo($candidate, PATHINFO_EXTENSION));
                    if ($ext === 'jpg' || $ext === 'jpeg') $mime = 'image/jpeg';
                    elseif ($ext === 'gif') $mime = 'image/gif';
                    elseif ($ext === 'webp') $mime = 'image/webp';
                }
                $signature_src = 'data:' . $mime . ';base64,' . base64_encode($bytes);
            }
            break;
        }
    }

    // Last resort: public URL (requires DomPDF remote enabled)
    if ($signature_src === null && function_exists('base_url')) {
        $signature_src = base_url($rel);
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; color:#111; }
    .top { width:100%; border-bottom:1px solid #ddd; padding-bottom:10px; margin-bottom:12px; }
    .brand { display:flex; align-items:center; gap:12px; }
    .logo { height:45px; }
    .brand-name { font-size:16px; font-weight:bold; }
    h1 { font-size:16px; margin:10px 0 4px; }
    h2 { font-size:13px; margin:16px 0 8px; color:#1b2a4e; border-bottom:1px solid #eee; padding-bottom:4px; }
    .sub { color:#555; margin:0 0 10px; }
    .box { border:1px solid #e5e5e5; padding:10px; border-radius:6px; margin:10px 0; }
    table { width:100%; border-collapse:collapse; margin-top:8px; }
    th, td { border:1px solid #ddd; padding:8px; vertical-align:top; }
    th { background:#f3f3f3; text-align:left; width:35%; }
    .muted { color:#666; font-size:11px; }
    .sig { margin-top:14px; }
    .sig img { border:1px solid #ddd; padding:6px; height:80px; }
    .footer { margin-top:18px; border-top:1px solid #ddd; padding-top:8px; font-size:10px; color:#666; }
    .break-all { word-break: break-all; }
  </style>
</head>
<body>

  <div class="top">
    <div class="brand">
      <img class="logo" src="<?= h($logo_url) ?>" alt="<?= h($brand_name) ?>">
      <div>
        <div class="brand-name"><?= h($brand_name) ?></div>
        <div class="muted">Generated on: <?= h(date('Y-m-d H:i:s')) ?></div>
      </div>
    </div>
  </div>

  <h1><?= h($heading) ?></h1>
  <?php if (!empty($subheading)): ?>
    <p class="sub"><?= h($subheading) ?></p>
  <?php endif; ?>

  <?php if (!empty($body_html)): ?>
    <div class="box">
      <?= $body_html /* trusted HTML saved by admin */ ?>
    </div>
  <?php endif; ?>

  <h2>Basic</h2>
  <table>
    <tbody>
      <tr>
        <th>Template</th>
        <td><?= h($template_title) ?></td>
      </tr>
      <tr>
        <th>Client</th>
        <td>
          <?= h($client_name !== '' ? $client_name : '-') ?>
          <?php if ($client_email !== ''): ?>
            (<?= h($client_email) ?>)
          <?php endif; ?>
        </td>
      </tr>
      <tr>
        <th>Submitted At</th>
        <td><?= h($submitted_at) ?></td>
      </tr>
    </tbody>
  </table>

  <h2>Audit (Proof)</h2>
  <table>
    <tbody>
      <tr>
        <th>IP Address</th>
        <td class="break-all"><?= h($ip_address !== '' ? $ip_address : '-') ?></td>
      </tr>
      <tr>
        <th>User Agent (short)</th>
        <td><?= h($ua_short !== '' ? $ua_short : '-') ?></td>
      </tr>
      <tr>
        <th>User Agent (full)</th>
        <td class="break-all"><?= h($user_agent !== '' ? $user_agent : '-') ?></td>
      </tr>
    </tbody>
  </table>

  <h2>Filled Values</h2>
  <table>
    <tbody>
      <?php if (!empty($data) && is_array($data)): ?>
        <?php foreach ($data as $label => $value): ?>
          <tr>
            <th><?= h($label) ?></th>
            <td><?= nl2br(h($value)) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="2">No data found</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <?php if (!empty($signature_src)): ?>
    <div class="sig">
      <div style="font-weight:bold; margin-bottom:6px;">Signature</div>
      <img src="<?= h($signature_src) ?>" alt="Signature">
    </div>
  <?php elseif (!empty($signature_path)): ?>
    <div class="sig">
      <div style="font-weight:bold; margin-bottom:6px;">Signature</div>
      <div class="muted">Signature file could not be embedded into PDF. Path: <?= h($signature_path) ?></div>
    </div>
  <?php endif; ?>

  <div class="footer">
    <?= h($brand_name) ?> • This document was generated electronically.
  </div>

</body>
</html>
