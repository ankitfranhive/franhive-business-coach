<?php
// $template  = array (tpl row)
// $request   = array (request row)
// $data      = associative array: label => value (legacy fallback)
// $fields    = form type fields in template order (optional)
// $overrides = template overrides_json decoded (optional)
// $values    = ef_submission_values rows (optional)
// $signature_path = string path like "uploads/eforms/signatures/xxx.png" or null
// $meta      = optional audit/basic details array

$brand_name = "Empower Your Destiny";
$logo_url = "https://empoweryourdestiny.com.au/wp-content/uploads/2023/09/EYD-Logo-without-tag-line.png";
$logo_src = '';
$logo_ctx = stream_context_create([
  'http' => ['timeout' => 3, 'follow_location' => 1],
  'https' => ['timeout' => 3, 'follow_location' => 1],
]);
$logo_bytes = @file_get_contents($logo_url, false, $logo_ctx);
if ($logo_bytes !== false && $logo_bytes !== '') {
  $logo_src = 'data:image/png;base64,' . base64_encode($logo_bytes);
}

$heading = !empty($template['heading']) ? $template['heading'] : ($template['title'] ?? 'Form');
$subheading = $template['subheading'] ?? '';
$body_html = $template['body_html'] ?? '';
$meta = (isset($meta) && is_array($meta)) ? $meta : [];
$overrides = (isset($overrides) && is_array($overrides)) ? $overrides : [];
if (empty($overrides) && !empty($template['overrides_json'])) {
  $decoded_overrides = json_decode($template['overrides_json'], true);
  if (is_array($decoded_overrides)) {
    $overrides = $decoded_overrides;
  }
}
$fields = (isset($fields) && is_array($fields)) ? $fields : [];
$values = (isset($values) && is_array($values)) ? $values : [];
$data = (isset($data) && is_array($data)) ? $data : [];

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

if (!function_exists('pdf_rich_html')) {
  function pdf_rich_html($html) {
    $html = trim((string)$html);
    if ($html === '') {
      return '';
    }
    if ($html !== strip_tags($html)) {
      return $html;
    }
    return nl2br(htmlspecialchars($html, ENT_QUOTES, 'UTF-8'));
  }
}

if (!function_exists('pdf_field_media_src')) {
  function pdf_field_media_src($f) {
    $options = [];
    if (!empty($f['options_json'])) {
      $decoded = json_decode($f['options_json'], true);
      if (is_array($decoded)) {
        $options = $decoded;
      } elseif (is_string($f['options_json'])) {
        return trim((string)$f['options_json']);
      }
    }
    return trim((string)($options[0] ?? ''));
  }
}

// Prefer meta values, fall back to request/template
$template_title = $meta['template_title'] ?? ($template['title'] ?? $heading);
$client_name    = $meta['client_name'] ?? ($request['client_name'] ?? '');
$client_email   = $meta['client_email'] ?? ($request['client_email'] ?? '');
$submitted_raw  = $meta['submitted_at'] ?? date('Y-m-d H:i:s');
$submitted_ts   = strtotime((string)$submitted_raw);
$submitted_at   = $submitted_ts ? date('Y-m-d h:i A', $submitted_ts) : (string)$submitted_raw;
$ip_address     = $meta['ip_address'] ?? '';
$user_agent     = $meta['user_agent'] ?? '';
$ua_short       = $user_agent !== '' ? pdf_ua_summary($user_agent) : '';
$banner_url     = trim((string)($overrides['banner_image_url'] ?? ''));

$values_by_name = [];
$seen_names = [];
foreach ($values as $row) {
  $fname = (string)($row['field_name'] ?? '');
  if ($fname === '') {
    continue;
  }
  $values_by_name[$fname] = (string)($row['value_text'] ?? '');
  $seen_names[$fname] = true;
}

$field_html_before_map = is_array($overrides['field_html_before'] ?? null) ? $overrides['field_html_before'] : [];
$field_html_after_map  = is_array($overrides['field_html_after'] ?? null) ? $overrides['field_html_after'] : [];
$sec_map = [];
foreach ($overrides['sections'] ?? [] as $sec) {
  if (!empty($sec['id'])) {
    $sec_map[$sec['id']] = $sec['name'] ?? '';
  }
}
$field_sections_map = $overrides['field_sections'] ?? [];

$top_fields = [];
$bottom_fields = [];
foreach ($fields as $f) {
  $so = (int)($f['sort_order'] ?? 0);
  if ($so < 0) $top_fields[] = $f;
  else $bottom_fields[] = $f;
}
usort($top_fields, function ($a, $b) { return ((int)($a['sort_order'] ?? 0)) <=> ((int)($b['sort_order'] ?? 0)); });
usort($bottom_fields, function ($a, $b) { return ((int)($a['sort_order'] ?? 0)) <=> ((int)($b['sort_order'] ?? 0)); });
$all_fields = array_merge($top_fields, $bottom_fields);

$steps = [];
$sec_to_step = [];
foreach ($all_fields as $f) {
  $ftype = $f['type'] ?? 'text';
  $fname = $f['name'] ?? '';
  $is_display = in_array($ftype, ['image', 'video', 'section'], true);
  $this_sec = (!$is_display) ? ($field_sections_map[$fname] ?? '') : '';
  if (!isset($sec_to_step[$this_sec])) {
    $sec_to_step[$this_sec] = count($steps);
    $steps[] = [
      'sec_id'   => $this_sec,
      'sec_name' => ($this_sec !== '' && isset($sec_map[$this_sec])) ? $sec_map[$this_sec] : '',
      'fields'   => [],
    ];
  }
  $steps[$sec_to_step[$this_sec]]['fields'][] = $f;
}

$orphan_values = [];
foreach ($values as $row) {
  $fname = (string)($row['field_name'] ?? '');
  if ($fname === '') {
    continue;
  }
  $in_template = false;
  foreach ($all_fields as $f) {
    if (($f['name'] ?? '') === $fname) {
      $in_template = true;
      break;
    }
  }
  if (!$in_template) {
    $orphan_values[] = $row;
  }
}

$agree_text = trim((string)($template['agree_text'] ?? ($overrides['texts']['agree_checkbox_text'] ?? '')));
$agree_value = trim((string)($data['Agreement'] ?? ''));

// DomPDF often fails on local absolute paths ("Image not found or type unknown").
// Embed the signature as a base64 data URI when the file exists.
$signature_src = null;
if (!empty($signature_path)) {
    $candidates = [];
    $raw = trim((string)$signature_path);

    if ($raw !== '' && (strpos($raw, '/') === 0 || preg_match('/^[A-Za-z]:[\\\\\\/]/', $raw))) {
        $candidates[] = $raw;
    }

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
    .banner { margin: 0 0 12px; }
    .banner img { max-width: 100%; height: auto; }
    .form-body { margin: 10px 0 16px; line-height: 1.5; }
    .form-body p { margin: 0 0 8px; }
    .form-body ul, .form-body ol { margin: 0 0 8px 18px; }
    .form-body img { max-width: 100%; height: auto; }
    .section-title { font-size: 14px; color: #1b2a4e; border-bottom: 2px solid #ffc107; padding-bottom: 4px; margin: 18px 0 10px; }
    .field-html { margin: 10px 0; line-height: 1.5; }
    .field-html p { margin: 0 0 8px; }
    .field-html img { max-width: 100%; height: auto; }
    .q-card { border: 1px solid #e5e5e5; padding: 10px 12px; margin: 8px 0; page-break-inside: avoid; }
    .q-label { font-weight: bold; color: #202124; margin-bottom: 5px; }
    .q-value { border: 1px solid #dadce0; background: #fafafa; padding: 7px 9px; min-height: 16px; }
    .media-field img { max-width: 100%; height: auto; }
    .agree-row { margin: 12px 0; padding: 10px; border: 1px solid #e5e5e5; }
  </style>
</head>
<body>

  <div class="top">
    <div class="brand">
      <?php if ($logo_src !== ''): ?>
      <img class="logo" src="<?= h($logo_src) ?>" alt="<?= h($brand_name) ?>">
      <?php endif; ?>
      <div>
        <div class="brand-name"><?= h($brand_name) ?></div>
        <!-- <div class="muted">Generated on: <?= h(date('Y-m-d H:i:s')) ?></div> -->
      </div>
    </div>
  </div>

  <?php if ($banner_url !== ''): ?>
    <div class="banner"><img src="<?= h($banner_url) ?>" alt="Banner"></div>
  <?php endif; ?>

  <h1><?= h($heading) ?></h1>
  <?php if (!empty($subheading)): ?>
    <p class="sub"><?= h($subheading) ?></p>
  <?php endif; ?>

  <?php if (trim((string)$body_html) !== ''): ?>
    <div class="form-body"><?= pdf_rich_html($body_html) ?></div>
  <?php endif; ?>

  <?php if (!empty($steps)): ?>
    <?php foreach ($steps as $step): ?>
      <?php if (!empty($step['sec_name'])): ?>
        <div class="section-title"><?= h($step['sec_name']) ?></div>
      <?php endif; ?>

      <?php foreach ($step['fields'] as $f):
        $fname = (string)($f['name'] ?? '');
        $ftype = $f['type'] ?? 'text';
        $label = $overrides['labels'][$fname] ?? ($f['label'] ?? $fname);
        $html_before = $field_html_before_map[$fname] ?? '';
        $html_after  = $field_html_after_map[$fname] ?? '';
        $filled = $values_by_name[$fname] ?? '';
      ?>
        <?php if (trim((string)$html_before) !== ''): ?>
          <div class="field-html"><?= pdf_rich_html($html_before) ?></div>
        <?php endif; ?>

        <?php if ($ftype === 'section'): ?>
          <div class="section-title"><?= h($label) ?></div>
        <?php elseif ($ftype === 'image'):
          $src = pdf_field_media_src($f);
        ?>
          <?php if ($src !== ''): ?>
            <div class="media-field"><img src="<?= h($src) ?>" alt="<?= h($label) ?>"></div>
          <?php endif; ?>
        <?php elseif ($ftype === 'video'):
          $src = pdf_field_media_src($f);
        ?>
          <?php if ($src !== ''): ?>
            <div class="q-card">
              <div class="q-label"><?= h($label) ?></div>
              <div class="q-value"><a href="<?= h($src) ?>"><?= h($src) ?></a></div>
            </div>
          <?php endif; ?>
        <?php else: ?>
          <div class="q-card">
            <div class="q-label"><?= h($label) ?></div>
            <div class="q-value"><?= $filled !== '' ? nl2br(h($filled)) : '&nbsp;' ?></div>
          </div>
        <?php endif; ?>

        <?php if (trim((string)$html_after) !== ''): ?>
          <div class="field-html"><?= pdf_rich_html($html_after) ?></div>
        <?php endif; ?>
      <?php endforeach; ?>
    <?php endforeach; ?>

    <?php foreach ($orphan_values as $row): ?>
      <div class="q-card">
        <div class="q-label"><?= h($row['field_label'] ?? $row['field_name'] ?? 'Field') ?></div>
        <div class="q-value"><?= nl2br(h((string)($row['value_text'] ?? ''))) ?></div>
      </div>
    <?php endforeach; ?>

  <?php else: ?>
    <h2>Filled Values</h2>
    <table>
      <tbody>
        <?php if (!empty($data)): ?>
          <?php foreach ($data as $label => $value): ?>
            <?php if ((string)$label === 'Agreement') continue; ?>
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
  <?php endif; ?>

  <?php if ($agree_text !== ''): ?>
    <div class="agree-row">
      <strong><?= $agree_value !== '' ? 'Accepted' : 'Agreement' ?>:</strong>
      <?= h($agree_text) ?>
    </div>
  <?php endif; ?>

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

  <?php
    $include_audit = !empty($include_audit);
  ?>
  <?php if ($include_audit): ?>
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
  <?php endif; ?>

  <div class="footer">
    <?= h($brand_name) ?> • This document was generated electronically.
  </div>

</body>
</html>
