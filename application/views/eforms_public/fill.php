<!doctype html>
<?php
$overrides = $overrides ?? [];
$prefill         = $prefill ?? [];
$checkbox_errors = $checkbox_errors ?? [];   // passed from controller on failed validation

// Split fields into top/bottom by sort_order
$top_fields = [];
$bottom_fields = [];
foreach ($fields as $f) {
    $so = (int)($f['sort_order'] ?? 0);
    if ($so < 0) $top_fields[] = $f;
    else $bottom_fields[] = $f;
}

usort($top_fields, function($a,$b){ return ((int)($a['sort_order'] ?? 0)) <=> ((int)($b['sort_order'] ?? 0)); });
usort($bottom_fields, function($a,$b){ return ((int)($a['sort_order'] ?? 0)) <=> ((int)($b['sort_order'] ?? 0)); });

// Render input helper (Google-Forms style controls)
function render_input($f, $fname, $val, $checkbox_errors = []) {
    $type     = $f['type'] ?? 'text';
    $required = ((int)($f['is_required'] ?? 0) === 1);
    $req_attr = $required ? ' required' : '';
    $options  = [];
    if (!empty($f['options_json'])) {
        $decoded = json_decode($f['options_json'], true);
        if (is_array($decoded)) $options = $decoded;
    }

    if ($type === 'textarea') {
        echo '<textarea class="form-control" name="'.html_escape($fname).'" rows="3" placeholder="Your answer"'.$req_attr.'>'.html_escape($val).'</textarea>';
        return;
    }

    if ($type === 'select') {
        echo '<select class="form-control" name="'.html_escape($fname).'"'.$req_attr.'>';
        echo '<option value="">-- Select --</option>';
        foreach ($options as $op) {
            $sel = ((string)$val === (string)$op) ? 'selected' : '';
            echo '<option '.$sel.' value="'.html_escape($op).'">'.html_escape($op).'</option>';
        }
        echo '</select>';
        return;
    }

    if ($type === 'radio') {
        // Adding required to every radio in the group makes the browser
        // enforce "at least one selected" natively.
        echo '<div class="opt-group horizontal">';
        foreach ($options as $op) {
            $checked = ((string)$val === (string)$op) ? 'checked' : '';
            echo '<label class="opt-row">
                    <span class="opt-control radio-control">
                      <input type="radio" name="'.html_escape($fname).'" value="'.html_escape($op).'" '.$checked.$req_attr.'>
                      <span class="opt-dot"></span>
                    </span>
                    <span class="opt-text">'.html_escape($op).'</span>
                  </label>';
        }
        echo '</div>';
        return;
    }

    if ($type === 'checkbox') {
        if (!empty($options)) {
            $arrVal = is_array($val) ? $val : (strlen((string)$val) ? explode(',', (string)$val) : []);
            $arrVal = array_map('trim', $arrVal);
            // For multi-checkbox groups, mark with data-required so JS can validate
            echo '<div class="opt-group" '.($required ? 'data-required="1"' : '').'>';
            foreach ($options as $op) {
                $checked = in_array((string)$op, $arrVal) ? 'checked' : '';
                echo '<label class="opt-row">
                        <span class="opt-control checkbox-control">
                          <input type="checkbox" name="'.html_escape($fname).'[]" value="'.html_escape($op).'" '.$checked.'>
                          <span class="opt-check"></span>
                        </span>
                        <span class="opt-text">'.html_escape($op).'</span>
                     </label>';
            }
            echo '</div>';

            // Server-side error message (from controller $checkbox_errors)
            if (!empty($checkbox_errors[$fname])) {
                echo '<div class="field-error-msg" style="margin-top:6px;">'.html_escape($checkbox_errors[$fname]).'</div>';
            }

        } else {
            $checked = ($val == '1' || $val === 'on') ? 'checked' : '';
            echo '<div class="opt-group">';
            echo '<label class="opt-row">
                    <span class="opt-control checkbox-control">
                      <input type="checkbox" name="'.html_escape($fname).'" value="1" '.$checked.$req_attr.'>
                      <span class="opt-check"></span>
                    </span>
                    <span class="opt-text">Yes</span>
                 </label>';
            echo '</div>';
        }
        return;
    }

    if ($type === 'section') {
        return;
    }

    if ($type === 'image') {
        $src = '';
        if (!empty($options)) {
            $src = (string)$options[0];
        } elseif (!empty($val)) {
            $src = (string)$val;
        } elseif (!empty($f['options_json'])) {
            $src = (string)$f['options_json'];
        }
        if ($src !== '') {
            echo '<div class="image-field"><img src="'.html_escape($src).'" alt="" loading="lazy"></div>';
        }
        return;
    }

    if ($type === 'video') {
        $url = '';
        if (!empty($options)) {
            $url = (string)$options[0];
        } elseif (!empty($val)) {
            $url = (string)$val;
        }
        if ($url !== '') {
            $embed = $url;
            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/shorts\/)([a-zA-Z0-9_\-]{11})/', $url, $m)) {
              $embed = 'https://www.youtube.com/embed/' . $m[1];
          } elseif (preg_match('/vimeo\.com\/(\d+)/', $url, $m)) {
                $embed = 'https://player.vimeo.com/video/' . $m[1];
            }
            echo '<div class="video-field">
                    <iframe src="'.html_escape($embed).'"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen loading="lazy">
                    </iframe>
                  </div>';
        }
        return;
    }

    // default text/email/number/date
    $t = in_array($type, ['text','email','number','date']) ? $type : 'text';
    $placeholder = ($t === 'date') ? '' : 'Your answer';
    echo '<input class="form-control" type="'.$t.'" name="'.html_escape($fname).'" value="'.html_escape($val).'" placeholder="'.$placeholder.'"'.$req_attr.'>';
}

// Dynamic heading for title/footer
$dynHeading = !empty($template['heading'])
    ? $template['heading']
    : (!empty($template['title']) ? $template['title'] : 'Form');

// Footer static parts (as requested)
$footer_year = 2023;
$footer_page = 1; // static "Page 1" per your screenshot; can be made dynamic later
?>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= html_escape($template['title'] ?? 'Form') ?> | Empower Your Destiny</title>

  <style>
    :root{
      --brand:#ffc107;
      --brand-dark:#e6a800;
      --ink:#202124;
      --muted:#5f6368;
      --line:#dadce0;
      --page-bg:#f3ecd9;     /* page background color, warm tint to match brand */
      --card-bg:#ffffff;
      --danger:#d93025;
      --radius:14px;
      --shadow:0 1px 2px rgba(60,64,67,0.08), 0 2px 8px rgba(60,64,67,0.06);
      --shadow-hover:0 1px 3px rgba(60,64,67,0.12), 0 4px 14px rgba(60,64,67,0.1);
    }

    *{box-sizing:border-box;}

    body{
      margin:0;
      font-family:'Segoe UI',Roboto,Arial,Helvetica,sans-serif;
      background:var(--page-bg);
      color:var(--ink);
      -webkit-font-smoothing:antialiased;
    }

    h3 { color: var(--brand-dark); }

    .wrap{max-width:760px;margin:0 auto;padding:24px 16px 56px;}

    /* Generic card used as the base surface for header + each question */
    .card{
      background:var(--card-bg);
      border:1px solid var(--line);
      border-radius:var(--radius);
      box-shadow:var(--shadow);
    }

    /* TOP TITLE CARD (Google Forms style: colored top bar + title block) */
    .title-card{
      overflow:hidden;
      margin-bottom:16px;
    }
    .title-card .accent-bar{
      height:10px;
      background:linear-gradient(90deg, var(--brand), var(--brand-dark));
    }
    .title-card .title-body{
      padding:24px 28px 26px;
    }

    h1{
      font-size:28px;
      font-weight:500;
      margin:0 0 8px;
      color:var(--ink);
      line-height:1.3;
    }
    .sub{color:var(--muted);margin:0;font-size:15px;line-height:1.5;}

    .content{
      background:#fafafa;
      border:1px solid var(--line);
      border-radius:10px;
      padding:16px;
      margin:18px 0 0;
      font-size:14.5px;
      line-height:1.6;
      color:#3c4043;
    }

    /* FORM */
    form{display:flex;flex-direction:column;gap:16px;}

    .q-card.has-error{
      border-left:3px solid var(--danger) !important;
    }
    .field-error-msg{
      color:var(--danger);
      font-size:12.5px;
      margin-top:6px;
    }

    /* SECTION STEP NAVIGATION */
    .step-progress-wrap{
      padding:14px 20px 12px;
      margin-bottom:4px;
      display:flex;
      flex-direction:column;
      gap:10px;
    }
    .step-progress{
      display:flex;
      align-items:center;
      gap:10px;
      flex-wrap:wrap;
    }
    .step-pips{
      display:flex;
      align-items:center;
      gap:6px;
      flex-wrap:wrap;
    }
    .step-pip{
      width:10px;height:10px;border-radius:50%;
      background:var(--line);
      transition:background .2s ease, transform .2s ease;
      flex:0 0 auto;
    }
    .step-pip.active{background:var(--brand-dark);transform:scale(1.3);}
    .step-pip.done{background:var(--brand);}
    .step-label{
      font-size:13px;color:var(--muted);
    }
    .step-label strong{color:var(--ink);}
    .step-sep{margin:0 2px;color:var(--line);}

    /* time row below pips */
    .step-time-row{
      display:flex;
      align-items:center;
      gap:12px;
      flex-wrap:wrap;
      padding-top:2px;
      border-top:1px solid var(--line);
    }
    .time-badge{
      display:inline-flex;
      align-items:center;
      background:linear-gradient(90deg,#fff8e1,#fff3cc);
      border:1px solid #ffe082;
      color:#7a5800;
      border-radius:20px;
      padding:4px 12px 4px 8px;
      font-size:12.5px;
      font-weight:600;
      gap:2px;
    }
    .time-step-badge{
      font-size:12px;
      color:var(--muted);
      margin-left:auto;
    }
    .time-step-badge strong{color:var(--ink);}

    .form-step{ display:none; }
    .form-step.active{ display:contents; }

    @media(max-width:640px){
      .step-progress-wrap{padding:12px 14px 10px;}
      .time-step-badge{margin-left:0;}
    }

    .step-nav{
      display:flex;
      gap:12px;
      align-items:center;
      padding:18px 24px;
      background:var(--card-bg);
      border:1px solid var(--line);
      border-radius:var(--radius);
      box-shadow:var(--shadow);
    }
    .step-nav .btn-primary{ flex:1; text-align:center; }
    .step-nav .btn-light  { flex:1; text-align:center; }
    .step-nav .spacer     { flex:1; }

    @media(max-width:640px){
      .step-nav{ flex-direction:column; }
      .step-nav button{ width:100%; }
    }

    /* SECTION HEADER CARD */
    .section-card{
      padding:14px 24px 12px;
      background:#fff;
      border:1px solid var(--line);
      border-left:4px solid var(--brand-dark);
      border-radius:var(--radius);
      margin-top:8px;
    }
    .section-title{
      margin:0;
      font-size:15px;
      font-weight:600;
      color:var(--brand-dark);
      letter-spacing:0.4px;
      text-transform:uppercase;
    }
    .section-title::after{
      content:'';
      display:block;
      margin-top:6px;
      width:32px;
      height:2px;
      background:var(--brand);
      border-radius:2px;
    }

    /* Each field becomes its own "question card" */
    .q-card{
      padding:20px 24px 22px;
      transition:box-shadow .15s ease, border-color .15s ease;
      border-left:3px solid transparent;
    }
    .q-card:focus-within{
      border-left:3px solid var(--brand-dark);
      box-shadow:var(--shadow-hover);
    }

    label.q-label{
      display:block;
      font-weight:500;
      font-size:15.5px;
      margin:0 0 10px;
      color:var(--ink);
    }
    label.q-label .req{color:var(--danger);margin-left:2px;}

    input.form-control,
    textarea.form-control,
    select.form-control{
      width:100%;
      padding:10px 2px;
      border:none;
      border-bottom:1.5px solid var(--line);
      border-radius:0;
      margin-top:2px;
      background:transparent;
      font-size:15px;
      font-family:inherit;
      color:var(--ink);
      transition:border-color .15s ease;
    }
    input.form-control::placeholder,
    textarea.form-control::placeholder{color:#80868b;}

    input.form-control:focus,
    textarea.form-control:focus,
    select.form-control:focus{
      outline:none;
      border-bottom:2px solid var(--brand-dark);
    }

    select.form-control{
      border:1.5px solid var(--line);
      border-radius:8px;
      padding:10px 12px;
      background:#fff;
      cursor:pointer;
    }
    select.form-control:focus{border:1.5px solid var(--brand-dark);}

    textarea.form-control{min-height:90px;resize:vertical;}

    /* Radio / checkbox option rows (Google Forms style circles/squares) */
    .opt-group{display:flex;flex-direction:column;gap:4px;margin-top:4px;}
    .opt-group.horizontal{
      flex-direction:row;
      flex-wrap:wrap;
      gap:6px 28px;
      align-items:center;
    }
    .opt-group.horizontal .opt-row{padding:6px 4px;}
    .opt-row{
      display:flex;
      align-items:center;
      gap:14px;
      padding:9px 4px;
      cursor:pointer;
      border-radius:8px;
      transition:background .12s ease;
    }
    .opt-row:hover{background:#f6f6f6;}

    .opt-control{position:relative;width:20px;height:20px;flex:0 0 auto;display:inline-flex;}
    .opt-control input{
      position:absolute;inset:0;width:20px;height:20px;margin:0;opacity:0;cursor:pointer;
    }

    .radio-control .opt-dot{
      width:20px;height:20px;border-radius:50%;border:2px solid #5f6368;display:block;position:relative;
      transition:border-color .15s ease;
    }
    .radio-control input:checked ~ .opt-dot{border-color:var(--brand-dark);}
    .radio-control input:checked ~ .opt-dot::after{
      content:"";position:absolute;inset:3px;border-radius:50%;background:var(--brand-dark);
    }

    .checkbox-control .opt-check{
      width:20px;height:20px;border-radius:4px;border:2px solid #5f6368;display:block;position:relative;
      transition:border-color .15s ease, background .15s ease;
    }
    .checkbox-control input:checked ~ .opt-check{border-color:var(--brand-dark);background:var(--brand-dark);}
    .checkbox-control input:checked ~ .opt-check::after{
      content:"";position:absolute;left:5px;top:1px;width:5px;height:10px;
      border:solid #fff;border-width:0 2px 2px 0;transform:rotate(40deg);
    }

    .opt-text{font-size:15px;color:var(--ink);}

    .image-field{margin-top:4px;}
    .image-field img{
      display:block;
      width:100%;
      max-height:360px;
      object-fit:contain;
      border-radius:10px;
      background:#fafafa;
    }

    .video-field{
      position:relative;
      width:100%;
      padding-bottom:56.25%; /* 16:9 */
      height:0;
      margin-top:4px;
      border-radius:10px;
      overflow:hidden;
      background:#000;
    }
    .video-field iframe{
      position:absolute;
      top:0;left:0;
      width:100%;height:100%;
      border:0;
      border-radius:10px;
    }

    .err{color:var(--danger);font-size:13px;margin:0 0 12px;}

    .agree{
      display:flex;gap:12px;align-items:flex-start;
      padding:18px 22px;
    }
    .agree input{width:20px;height:20px;margin-top:2px;flex:0 0 auto;cursor:pointer;}
    .agree div{font-size:14.5px;line-height:1.55;color:#3c4043;}

    .sigWrap{padding:20px 24px 22px;}
    .sigWrap > label.q-label{margin-bottom:10px;}
    canvas{
      border:1.5px solid var(--line);
      border-radius:10px;
      width:100%;
      height:170px;
      background:#fff;
      touch-action:none;
      cursor:crosshair;
    }
    canvas:hover{border-color:#b3b6bb;}

    .btns{display:flex;gap:12px;margin-top:14px;flex-wrap:wrap;align-items:center;}
    button{
      border:0;border-radius:24px;padding:11px 22px;font-weight:600;font-size:14.5px;
      cursor:pointer;transition:box-shadow .15s ease, transform .05s ease, background .15s ease;
    }
    button:active{transform:translateY(1px);}
    .btn-primary{background:var(--brand-dark);color:#1f1f1f;}
    .btn-primary:hover{background:#cf9300;box-shadow:0 2px 6px rgba(0,0,0,0.18);}
    .btn-light{background:transparent;color:var(--brand-dark);}
    .btn-light:hover{background:#fff6e0;}
    .small{font-size:12.5px;color:var(--muted);margin-top:8px;}

    /* BANNER IMAGE (same width as form, below EYD header) */
    .banner-card{
      overflow:hidden;
      padding:0;
      margin-bottom:16px;
      border-radius:var(--radius);
      line-height:0;
    }
    .banner-card img{
      width:100%;
     /* max-height:260px; */
      object-fit:cover;
      object-position:center;
      display:block;
      border-radius:var(--radius);
    }

    /* HEADER (full-width brand bar, softened with gradient) */
    .eyd-header{
      background:linear-gradient(120deg, #fff6df 0%, var(--brand) 55%, var(--brand-dark) 100%);
      border-bottom:1px solid rgba(0,0,0,0.06);
      box-shadow:0 2px 10px rgba(0,0,0,0.06);
    }
    .eyd-header-inner{
      max-width:1180px;
      margin:0 auto;
      padding:18px 18px;
      display:flex;
      align-items:center;
      gap:18px;
    }
    .eyd-logo-wrap{
      width:90px;
      height:90px;
      border-radius:999px;
      background:#1f1f1f;
      border:6px solid #fff;
      display:flex;
      align-items:center;
      justify-content:center;
      overflow:hidden;
      box-shadow:0 6px 18px rgba(0,0,0,0.18);
      flex: 0 0 auto;
    }
    .eyd-logo{
      width:92%;
      height:auto;
      display:block;
    }
    .eyd-header-text{
      color:#fff;
      line-height:1.05;
      min-width: 0;
    }
    .eyd-title{
      font-size:46px;
      font-weight:300;
      text-decoration:underline;
      text-underline-offset:10px;
      text-decoration-thickness:6px;
      margin:0;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .eyd-contact{
      margin-top:10px;
      font-size:20px;
      font-weight:600;
      opacity:0.95;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    /* FOOTER (full-width brand bar, softened with gradient, compact) */
    .eyd-footer{
      background:linear-gradient(120deg, var(--brand-dark) 0%, var(--brand) 55%, #fff6df 100%);
      color:#3a2c00;
      border-top:1px solid rgba(0,0,0,0.06);
      margin-top: 8px;
    }
    .eyd-footer-inner{
      max-width:1180px;
      margin:0 auto;
      padding:8px 14px;
      font-size:13px;
      font-weight:600;
      letter-spacing:0.2px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      text-align: center;
    }

    /* Responsive */
    @media (max-width: 900px){
      .eyd-title{font-size:34px;}
      .eyd-contact{font-size:18px;}
      .eyd-footer-inner{font-size:11.5px;white-space:normal;padding:7px 10px;}
      .eyd-logo-wrap{width:90px;height:90px;}
    }

    @media (max-width: 640px){
      .eyd-header-inner{padding:14px 14px;gap:12px;}
      .eyd-logo-wrap{width:60px;height:60px;border-width:4px;}
      .eyd-title{font-size:24px;text-underline-offset:6px;text-decoration-thickness:3px;}
      .eyd-contact{font-size:13px;margin-top:4px;white-space:normal;}
      .eyd-footer-inner{font-size:11px;padding:6px 10px;}

      .wrap{padding:16px 10px 40px;}
      .title-card .title-body{padding:18px 18px 20px;}
      h1{font-size:21px;}
      .sub{font-size:13.5px;}

      .q-card{padding:16px 16px 18px;}
      label.q-label{font-size:14.5px;}
      .opt-text, input.form-control, textarea.form-control{font-size:14.5px;}

      .sigWrap{padding:16px 16px 18px;}
      canvas{height:150px;}

      .btns{gap:10px;}
      button{padding:10px 18px;font-size:14px;width:100%;}
      .btns{flex-direction:column;}
    }
  </style>
</head>

<body>

  <!-- STATIC HEADER -->
  <div class="eyd-header">
    <div class="eyd-header-inner">
      <div class="eyd-logo-wrap">
        <img class="eyd-logo" src="https://empoweryourdestiny.com.au/wp-content/uploads/2023/09/EYD-Logo-without-tag-line.png" alt="Empower Your Destiny">
      </div>

      <div class="eyd-header-text">
        <div class="eyd-title">Empower Your Destiny</div>
        <div class="eyd-contact">Phone: 1800 MY DESTINY | Email: info@empoweryourdestiny.com.au</div>
      </div>
    </div>
  </div>

  <div class="wrap">

    <!-- BANNER IMAGE (optional, same width as form, below EYD header) -->
    <?php
      $banner_url = trim($overrides['banner_image_url'] ?? '');
      if (!empty($banner_url)):
    ?>
    <div class="card banner-card">
      <img src="<?= html_escape($banner_url) ?>" alt="Banner">
    </div>
    <?php endif; ?>

    <!-- TITLE CARD -->
    <div class="card title-card">
      <div class="accent-bar"></div>
      <div class="title-body">
        <h1><?= html_escape($dynHeading) ?></h1>
        <?php if (!empty($template['subheading'])): ?>
          <p class="sub"><?= html_escape($template['subheading']) ?></p>
        <?php endif; ?>

        <?php if (function_exists('validation_errors')): ?>
          <?= validation_errors('<div class="err">','</div>'); ?>
        <?php endif; ?>

        <?php if (!empty($template['body_html'])): ?>
          <div class="content"><?= $template['body_html'] ?></div>
        <?php endif; ?>
      </div>
    </div>

    <?php
      // ── Build section lookup ──────────────────────────────
      $sec_map            = [];
      foreach ($overrides['sections'] ?? [] as $sec) {
          $sec_map[$sec['id']] = $sec['name'];
      }
      $field_sections_map = $overrides['field_sections'] ?? [];

      // ── Group all fields into ordered steps ───────────────
      // Each step = one section (or the "ungrouped" bucket).
      // Fields with no section land in a shared bucket that
      // is placed wherever they first appear in sort order.
      $all_fields   = array_merge($top_fields, $bottom_fields);
      $steps        = [];   // [ ['sec_id'=>'', 'sec_name'=>'', 'fields'=>[...]], ... ]
      $sec_to_step  = [];   // sec_id => step index

      foreach ($all_fields as $f) {
          $ftype    = $f['type'] ?? 'text';
          $fname    = $f['name'];
          $is_display = in_array($ftype, ['image','video','section']);

          // Determine which step this field belongs to
          $this_sec = (!$is_display) ? ($field_sections_map[$fname] ?? '') : '';

          if (!isset($sec_to_step[$this_sec])) {
              $sec_to_step[$this_sec] = count($steps);
              $steps[] = [
                  'sec_id'   => $this_sec,
                  'sec_name' => ($this_sec !== '' && isset($sec_map[$this_sec]))
                                    ? $sec_map[$this_sec] : '',
                  'fields'   => []
              ];
          }
          $steps[$sec_to_step[$this_sec]]['fields'][] = $f;
      }

      $total_steps  = count($steps);
      $use_steps    = ($total_steps > 1);

      // ── Estimate time per step & total ───────────────────
      $time_weights = [
          'text'      => 12, 'email'    => 12, 'number' => 10, 'date'     => 10,
          'textarea'  => 45, 'select'   =>  8, 'radio'  =>  8, 'checkbox' => 15,
          'signature' => 25,
      ];
      $step_seconds = [];
      $total_seconds = 0;
      foreach ($steps as $si => $step) {
          $secs = 0;
          foreach ($step['fields'] as $f) {
              $secs += $time_weights[$f['type'] ?? 'text'] ?? 0;
          }
          $step_seconds[$si] = $secs;
          $total_seconds    += $secs;
      }
      // Always add signature time to total (it's outside the steps array)
      $total_seconds += 25;

      function fmt_seconds($s) {
          if ($s < 60)  return 'under 1 min';
          $m = ceil($s / 60);
          return $m . ' min' . ($m > 1 ? 's' : '');
      }
      $total_time_label = fmt_seconds($total_seconds);

      // On server-side validation failure: find the first step containing a checkbox error
      // so JS can auto-jump to it on page load.
      $error_step_idx = 0;
      if (!empty($checkbox_errors)) {
          foreach ($steps as $si => $step) {
              foreach ($step['fields'] as $f) {
                  if (!empty($checkbox_errors[$f['name']])) {
                      $error_step_idx = $si;
                      break 2;
                  }
              }
          }
      }
    ?>

    <form method="post" onsubmit="return beforeSubmit();" id="form-anchor">

      <?php if ($use_steps): ?>
      <!-- STEP PROGRESS BAR + TIME ESTIMATE -->
      <div class="step-progress-wrap card" id="step-progress-wrap">
        <div class="step-progress" id="step-progress">
          <div class="step-pips">
            <?php foreach ($steps as $si => $step): ?>
              <div class="step-pip <?= $si === 0 ? 'active' : '' ?>" id="pip-<?= $si ?>"></div>
            <?php endforeach; ?>
          </div>
          <span class="step-label">
            Step <strong id="step-cur">1</strong> of <strong><?= $total_steps ?></strong>
            <?php if (!empty($steps[0]['sec_name'])): ?>
              <span class="step-sep">&mdash;</span>
              <strong id="step-sec-name"><?= html_escape($steps[0]['sec_name']) ?></strong>
            <?php endif; ?>
          </span>
        </div>
        <div class="step-time-row">
          <span class="time-badge" id="time-badge">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-2px;margin-right:4px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <span id="time-remaining">Est. <?= html_escape($total_time_label) ?> to complete</span>
          </span>
          <span class="time-step-badge" id="time-step-badge">
            Approx. this step: <strong id="time-step-val"><?= html_escape(fmt_seconds($step_seconds[0] ?? 0)) ?></strong>
          </span>
        </div>
      </div>
      <?php endif; ?>

      <?php foreach ($steps as $si => $step):
        $active_class = (!$use_steps || $si === 0) ? 'active' : '';
      ?>
      <div class="form-step <?= $active_class ?>" id="step-<?= $si ?>"
           data-step="<?= $si ?>"
           data-sec-name="<?= html_escape($step['sec_name']) ?>"
           data-est-seconds="<?= (int)($step_seconds[$si] ?? 0) ?>">

        <?php if ($use_steps && !empty($step['sec_name'])): ?>
          <div class="card section-card">
            <h3 class="section-title"><?= html_escape($step['sec_name']) ?></h3>
          </div>
        <?php endif; ?>

        <?php foreach ($step['fields'] as $f):
          $fname    = $f['name'];
          $ftype    = $f['type'] ?? 'text';
          $label    = $overrides['labels'][$fname] ?? ($f['label'] ?? $fname);
          $default  = $prefill[$fname] ?? '';
          $required = ((int)($f['is_required'] ?? 0) === 1);

          // Checkbox: always use $prefill (may be POST-repopulated array on failed validation).
          // Other fields: use CI set_value() with $default as fallback.
          if ($ftype === 'checkbox') {
              $val = $prefill[$fname] ?? '';
          } else {
              $val = set_value($fname, $default);
          }

          // Mark card with has-error if server returned an error for this field
          $card_error_class = (!empty($checkbox_errors[$fname])) ? ' has-error' : '';
        ?>
          <?php if ($ftype === 'section'): ?>
            <div class="card section-card">
              <h3 class="section-title"><?= html_escape($label) ?></h3>
            </div>
          <?php else: ?>
            <div class="card q-card<?= $card_error_class ?>">
              <label class="q-label"><?= html_escape($label) ?><?= $required ? ' <span class="req">*</span>' : '' ?></label>
              <?php render_input($f, $fname, $val, $checkbox_errors); ?>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>

        <?php if ($use_steps): ?>
          <!-- Per-step Next / Previous -->
          <div class="step-nav">
            <?php if ($si > 0): ?>
              <button type="button" class="btn-light" onclick="gotoStep(<?= $si - 1 ?>)">&#8592; Previous</button>
            <?php else: ?>
              <div class="spacer"></div>
            <?php endif; ?>

            <?php if ($si < $total_steps - 1): ?>
              <button type="button" class="btn-primary" onclick="nextStep(<?= $si ?>)">Next &#8594;</button>
            <?php else: ?>
              <!-- Last step: show agree + signature + submit -->
              <?php if (!empty($template['agree_text'])): ?>
                <div class="card agree" style="flex:1 1 100%;order:-1;margin-bottom:0;">
                  <input type="checkbox" name="agree" value="1" <?= set_value('agree') ? 'checked' : '' ?> required>
                  <div><?= html_escape($template['agree_text']) ?></div>
                </div>
              <?php endif; ?>
              <div class="spacer"></div>
            <?php endif; ?>
          </div>
        <?php endif; ?>

      </div><!-- /.form-step -->
      <?php endforeach; ?>

      <?php if (!$use_steps && !empty($template['agree_text'])): ?>
        <div class="card agree">
          <input type="checkbox" name="agree" value="1" <?= set_value('agree') ? 'checked' : '' ?> required>
          <div><?= html_escape($template['agree_text']) ?></div>
        </div>
      <?php endif; ?>

      <!-- SIGNATURE + SUBMIT (always last; hidden until last step when paginated) -->
      <div class="card sigWrap" id="sig-block" <?= $use_steps ? 'style="display:none;"' : '' ?>>
        <label class="q-label">Signature <span class="req">*</span></label>
        <canvas id="sig"></canvas>

        <input type="hidden" name="signature_data" id="signature_data">
        <input type="hidden" name="client_timezone" id="client_timezone">
        <input type="hidden" name="client_time_iso" id="client_time_iso">
        <input type="hidden" name="screen_resolution" id="screen_resolution">

        <div class="btns">
          <?php if ($use_steps): ?>
            <button type="button" class="btn-light" onclick="gotoStep(<?= $total_steps - 2 ?>)">&#8592; Previous</button>
          <?php endif; ?>
          <button type="submit" class="btn-primary">Submit Form</button>
          <button type="button" class="btn-light" onclick="clearSig()">Clear Signature</button>
        </div>
        <div class="small">Draw your signature in the box above.</div>
      </div>

    </form>

  </div>

  <!-- FOOTER (STATIC STRING + DYNAMIC HEADING) -->
  <div class="eyd-footer">
    <div class="eyd-footer-inner">
      <?= html_escape('Empower Your Destiny I '.$dynHeading.' I '.$footer_year.' I Page '.$footer_page) ?>
    </div>
  </div>

<script>
  const canvas = document.getElementById('sig');
  const ctx = canvas.getContext('2d');

  document.getElementById('client_timezone').value =
    Intl.DateTimeFormat().resolvedOptions().timeZone || '';

  document.getElementById('client_time_iso').value =
    new Date().toISOString();

  document.getElementById('screen_resolution').value =
    (window.screen.width || '') + 'x' + (window.screen.height || '');

  function resizeCanvas(){
    const rect = canvas.getBoundingClientRect();
    const ratio = window.devicePixelRatio || 1;
    canvas.width = rect.width * ratio;
    canvas.height = rect.height * ratio;
    ctx.setTransform(1,0,0,1,0,0);
    ctx.scale(ratio, ratio);
  }

  window.addEventListener('load', resizeCanvas);
  window.addEventListener('resize', resizeCanvas);

  let drawing=false, last=null;

  function pos(e){
    const r = canvas.getBoundingClientRect();
    const x = (e.touches ? e.touches[0].clientX : e.clientX) - r.left;
    const y = (e.touches ? e.touches[0].clientY : e.clientY) - r.top;
    return {x,y};
  }

  function start(e){ drawing=true; last=pos(e); e.preventDefault(); }

  function move(e){
    if(!drawing) return;
    const p = pos(e);
    ctx.beginPath();
    ctx.moveTo(last.x,last.y);
    ctx.lineTo(p.x,p.y);
    ctx.lineWidth = 2;
    ctx.stroke();
    last = p;
    e.preventDefault();
  }

  function end(){ drawing=false; last=null; }

  canvas.addEventListener('mousedown', start);
  canvas.addEventListener('mousemove', move);
  window.addEventListener('mouseup', end);

  canvas.addEventListener('touchstart', start, {passive:false});
  canvas.addEventListener('touchmove', move, {passive:false});
  window.addEventListener('touchend', end);

  // Clear error highlight when user starts filling in a field
  document.addEventListener('input', function(e) {
    const card = e.target.closest('.q-card');
    if (card && card.classList.contains('has-error')) {
      card.classList.remove('has-error');
      const msg = card.querySelector('.field-error-msg');
      if (msg) msg.remove();
    }
  });
  document.addEventListener('change', function(e) {
    const card = e.target.closest('.q-card');
    if (card && card.classList.contains('has-error')) {
      card.classList.remove('has-error');
      const msg = card.querySelector('.field-error-msg');
      if (msg) msg.remove();
    }
  });

  function clearSig(){
    ctx.clearRect(0,0,canvas.width,canvas.height);
  }

  function beforeSubmit(){
    document.getElementById('signature_data').value = canvas.toDataURL('image/png');
    return true;
  }

  // ── Section step navigation ───────────────────────────────
  let currentStep = 0;
  const totalSteps = document.querySelectorAll('.form-step').length;

  // Pre-collect step seconds from data attributes
  const stepSeconds = [];
  document.querySelectorAll('.form-step').forEach(s => {
    stepSeconds.push(parseInt(s.dataset.estSeconds || 0, 10));
  });
  // Signature always on last step — add its 25s
  stepSeconds[stepSeconds.length - 1] = (stepSeconds[stepSeconds.length - 1] || 0) + 25;

  function fmtSecs(s) {
    if (s <= 0)  return 'under 1 min';
    if (s < 60)  return 'under 1 min';
    const m = Math.ceil(s / 60);
    return m + ' min' + (m > 1 ? 's' : '');
  }

  function updateTimeDisplay(idx) {
    // Remaining = sum of current + future steps
    const remaining = stepSeconds.slice(idx).reduce((a, b) => a + b, 0);
    const timeRemEl = document.getElementById('time-remaining');
    if (timeRemEl) {
      timeRemEl.textContent = idx === totalSteps - 1
        ? 'Almost done!'
        : 'Est. ' + fmtSecs(remaining) + ' remaining';
    }
    const timeStepEl = document.getElementById('time-step-val');
    if (timeStepEl) {
      timeStepEl.textContent = 'approx. ' + fmtSecs(stepSeconds[idx] || 0);
    }
  }

  function gotoStep(idx) {
    if (idx < 0 || idx >= totalSteps) return;

    // Hide current
    const cur = document.getElementById('step-' + currentStep);
    if (cur) cur.classList.remove('active');

    // Show new
    currentStep = idx;
    const next = document.getElementById('step-' + currentStep);
    if (next) next.classList.add('active');

    // Show sig block on last step
    const sigBlock = document.getElementById('sig-block');
    if (sigBlock) {
      sigBlock.style.display = (currentStep === totalSteps - 1) ? '' : 'none';
      if (currentStep === totalSteps - 1) resizeCanvas();
    }

    // Update progress pips
    document.querySelectorAll('.step-pip').forEach((pip, i) => {
      pip.classList.remove('active', 'done');
      if (i < currentStep)        pip.classList.add('done');
      else if (i === currentStep) pip.classList.add('active');
    });

    // Update step label
    const cur_label = document.getElementById('step-cur');
    if (cur_label) cur_label.textContent = currentStep + 1;
    const sec_name_el = document.getElementById('step-sec-name');
    if (sec_name_el && next) {
      sec_name_el.textContent = next.dataset.secName || '';
    }

    // Update time display
    updateTimeDisplay(currentStep);

    // Scroll to form questions area
    const formAnchor = document.getElementById('form-anchor');
    if (formAnchor) {
      formAnchor.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }

  function nextStep(fromIdx) {
    const step = document.getElementById('step-' + fromIdx);
    if (!step) { gotoStep(fromIdx + 1); return; }

    let firstInvalid = null;

    // 1. Native browser validation for text/email/number/date/textarea/select/radio/single-checkbox
    const nativeFields = step.querySelectorAll('input[required], select[required], textarea[required]');
    for (const el of nativeFields) {
      if (!el.checkValidity()) {
        if (!firstInvalid) firstInvalid = el;
      }
    }

    // 2. Multi-checkbox groups marked with data-required="1"
    step.querySelectorAll('.opt-group[data-required="1"]').forEach(group => {
      const checked = group.querySelectorAll('input[type="checkbox"]:checked');
      if (checked.length === 0) {
        // Mark the group's card visually
        const card = group.closest('.q-card');
        if (card && !card.classList.contains('has-error')) {
          card.classList.add('has-error');
          const msg = document.createElement('div');
          msg.className = 'field-error-msg';
          msg.textContent = 'Please select at least one option.';
          group.after(msg);
        }
        if (!firstInvalid) firstInvalid = group.querySelector('input[type="checkbox"]');
      } else {
        // Clear any existing error
        const card = group.closest('.q-card');
        if (card) {
          card.classList.remove('has-error');
          const msg = card.querySelector('.field-error-msg');
          if (msg) msg.remove();
        }
      }
    });

    // 3. If any invalid field found — scroll to it and stop
    if (firstInvalid) {
      firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
      // Trigger native browser validation bubble for native fields
      try { firstInvalid.reportValidity(); } catch(e) {}
      return;
    }

    gotoStep(fromIdx + 1);
  }

  // On server-side validation failure with steps: auto-jump to the step containing the first error
  <?php if (!empty($checkbox_errors) && $use_steps): ?>
  window.addEventListener('load', function() {
    gotoStep(<?= (int)$error_step_idx ?>);
  });
  <?php endif; ?>
</script>

</body>
</html>