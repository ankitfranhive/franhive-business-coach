<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>
  <div class="mobile-menu-overlay"></div>

<?php
$userName = isset($_SESSION['user']['NAME']) && $_SESSION['user']['NAME'] !== ''
    ? $_SESSION['user']['NAME']
    : 'there';
$dayGreetings = [
    'Monday'    => 'A new week to move people forward.',
    'Tuesday'   => 'Keep the coaching momentum going.',
    'Wednesday' => 'Midweek check-in — who needs a follow-up?',
    'Thursday'  => 'Almost there. Close the loops that matter.',
    'Friday'    => 'Finish the week with signed forms and sent emails.',
    'Saturday'  => 'A good day to review what converted.',
    'Sunday'    => 'Plan next week’s calls, campaigns and agreements.',
];
$dayLine = $dayGreetings[date('l')] ?? 'Make today count.';

if (!function_exists('dash_dt')) {
    function dash_dt($value)
    {
        $value = trim((string)$value);
        if ($value === '' || strpos($value, '0000-00-00') === 0) {
            return '—';
        }
        $ts = strtotime($value);
        return $ts ? date('d/m/y h:i A', $ts) : $value;
    }
}
if (!function_exists('dash_lead_status')) {
    function dash_lead_status($status)
    {
        switch ((string)$status) {
            case '1': return 'New';
            case '2':
            case '-1': return 'Closed';
            case '3': return 'Red flag';
            default: return 'Other';
        }
    }
}
if (!function_exists('dash_task_status')) {
    function dash_task_status($status)
    {
        switch ((string)$status) {
            case '1': return 'Not started';
            case '2': return 'In progress';
            case '3': return 'Completed';
            case '-1': return 'Closed';
            default: return $status !== '' && $status !== null ? (string)$status : '—';
        }
    }
}
if (!function_exists('dash_badge')) {
    function dash_badge($status)
    {
        $s = strtolower(str_replace('_', ' ', (string)$status));
        $map = [
            'submitted' => 'success', 'sent' => 'primary', 'opened' => 'warning', 'expired' => 'danger',
            'new' => 'primary', 'closed' => 'secondary', 'red flag' => 'danger',
            'not started' => 'secondary', 'in progress' => 'primary', 'completed' => 'success',
            'draft' => 'secondary', 'setup' => 'info', 'scheduled' => 'warning', 'paused' => 'dark',
        ];
        return $map[$s] ?? 'secondary';
    }
}
?>

  <style>
    :root{
      --brand:#265ed7; --text:#1f2937; --muted:#6b7280; --card:#ffffff; --bg:#f6f8fb; --ring: rgba(38,94,215,0.16);
    }
    body{ background: var(--bg); }
    .dash-title{ color:var(--text); }
    .card-box{ background:var(--card); border:1px solid #eef1f6; border-radius:16px; box-shadow:0 6px 24px rgba(20,40,90,.06); }
    .card-header{ padding:12px 16px; border-bottom:1px solid #f0f3f7; display:flex; align-items:center; justify-content:space-between; gap:8px;}
    .card-header h5{ margin:0; color:var(--text); font-weight:700; font-size:16px;}
    .card-header a{ font-size:12px; font-weight:600; }
    .metric{ display:block; color:inherit; text-decoration:none; padding:16px; border-radius:14px; border:1px solid #eef1f6; background:#fff; transition: box-shadow .2s, transform .05s; height:100%;}
    .metric:hover{ box-shadow:0 6px 24px rgba(20,40,90,.08); transform: translateY(-1px); color:inherit; text-decoration:none;}
    .metric .icon{ width:42px; height:42px; display:grid; place-items:center; border-radius:12px; background:#f4f7ff; color:var(--brand); box-shadow: inset 0 0 0 1px var(--ring); margin-bottom:10px;}
    .metric h3{ margin:0; font-size:26px; font-weight:800; color:var(--text);}
    .metric p{ margin:4px 0 0; color:var(--text); font-size:13px; font-weight:700;}
    .metric small{ color:var(--muted); font-weight:500; display:block; margin-top:2px;}
    .img-hero{ border-radius:16px; box-shadow:0 10px 30px rgba(20,40,90,.08); }
    .dash-actions a{ margin:0 6px 8px 0; }
    .hello-card{ background:linear-gradient(180deg,#ffffff 0%, #f8fbff 100%); }
    .hello-kicker{ font-size:11px; font-weight:700; letter-spacing:.04em; text-transform:uppercase; color:#7c8aa5; margin-bottom:4px;}
    .attn-grid{ display:grid; grid-template-columns:1fr 1fr; gap:8px; }
    .attn-chip{ display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:12px; text-decoration:none; color:inherit; border:1px solid transparent; min-height:62px; transition: transform .08s, box-shadow .2s; }
    .attn-chip:hover{ transform:translateY(-1px); box-shadow:0 8px 18px rgba(20,40,90,.08); text-decoration:none; color:inherit; }
    .attn-chip .attn-count{ min-width:34px; font-size:20px; font-weight:800; line-height:1; text-align:right; }
    .attn-chip .attn-copy{ min-width:0; }
    .attn-chip .attn-group{ font-size:10px; font-weight:700; letter-spacing:.03em; text-transform:uppercase; opacity:.75; line-height:1; margin-bottom:3px; }
    .attn-chip .attn-title{ font-size:12px; font-weight:700; line-height:1.25; }
    .attn-chip.tone-warn{ background:#fff7ed; border-color:#ffedd5; color:#9a3412; }
    .attn-chip.tone-danger{ background:#fef2f2; border-color:#fee2e2; color:#991b1b; }
    .attn-chip.tone-info{ background:#eff6ff; border-color:#dbeafe; color:#1e40af; }
    .attn-chip.tone-muted{ background:#f8fafc; border-color:#e2e8f0; color:#334155; }
    .attn-empty{ background:#f0fdf4; border:1px solid #dcfce7; color:#166534; border-radius:12px; padding:14px; font-size:13px; font-weight:600; }
    .chart{ min-height:280px; }
    .table td, .table th{ vertical-align:middle; font-size:13px; }
    .empty-row{ color:var(--muted); text-align:center; padding:18px 8px; }
    .dash-equal > [class*="col-"]{ display:flex; }
    .dash-equal .card-box{ width:100%; display:flex; flex-direction:column; overflow:hidden; }
    .dash-equal .table-responsive{ flex:1; }
    .dash-equal .table{ margin-bottom:0; }
    .dash-equal .table td{ padding-top:10px; padding-bottom:10px; }
    .dash-card-foot{ margin-top:auto; padding:10px 16px; border-top:1px solid #f0f3f7; font-size:12px; font-weight:600; }
    .pa-meta{ display:flex; flex-wrap:wrap; align-items:center; gap:6px 8px; }
    .pa-meta a{ font-size:12px; font-weight:600; }
  </style>

  <div class="main-container">
    <div class="xs-pd-20-10 pd-ltr-20">

      <div class="title pb-10 d-flex flex-wrap justify-content-between align-items-center">
        <div>
          <h2 class="h3 mb-0 dash-title">Practice overview</h2>
          <p class="text-muted mb-0">Live numbers from leads, forms, agreements, campaigns and tests.</p>
        </div>
        <div class="dash-actions">
          <a class="btn btn-sm btn-outline-primary" href="<?= base_url('add-lead'); ?>">Add lead</a>
          <a class="btn btn-sm btn-outline-primary" href="<?= base_url('payment-agreement/requests'); ?>">Send agreement</a>
          <a class="btn btn-sm btn-outline-primary" href="<?= base_url('admin_eforms/send_iict_form'); ?>">Send eForm</a>
          <a class="btn btn-sm btn-primary" href="<?= base_url('add-campaign'); ?>">New campaign</a>
        </div>
      </div>

      <div class="row pb-10">
        <div class="col-lg-4 col-md-12 mb-20">
          <div class="card-box pd-20 height-100-p hello-card">
            <div class="hello-kicker">Today</div>
            <h5 class="mb-1">Hi, <?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'); ?></h5>
            <p class="text-muted mb-3" style="font-size:13px;"><?= htmlspecialchars($dayLine, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php if (!empty($attention)): ?>
              <div class="attn-grid">
                <?php foreach ($attention as $item):
                    $tone = preg_replace('/[^a-z]/', '', strtolower((string)($item['tone'] ?? 'muted'))) ?: 'muted';
                ?>
                  <a class="attn-chip tone-<?= htmlspecialchars($tone); ?>" href="<?= htmlspecialchars($item['href'] ?? '#'); ?>">
                    <span class="attn-count"><?= number_format((int)($item['count'] ?? 0)); ?></span>
                    <span class="attn-copy">
                      <div class="attn-group"><?= htmlspecialchars($item['group'] ?? ''); ?></div>
                      <div class="attn-title"><?= htmlspecialchars($item['title'] ?? ''); ?></div>
                    </span>
                  </a>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <div class="attn-empty">Nothing waiting. New submissions will show here.</div>
            <?php endif; ?>
          </div>
        </div>
        <div class="col-lg-8 col-md-12 mb-20">
          <div class="card-box pd-20">
            <div class="row">
              <div class="col-md-7 mb-10">
                <?php if (defined('SUBDOMAIN') && SUBDOMAIN === "demo"){ ?>
                  <img src="https://demo.franhive.com/uploads/1732134458_dashboard_banner_fh.jpg" alt="banner" class="img-fluid img-hero" loading="lazy">
                <?php } else { ?>
                  <img src="<?= base_url('vendors/images/banner_image.jpeg'); ?>" alt="banner" class="img-fluid img-hero" loading="lazy">
                <?php } ?>
              </div>
              <div class="col-md-5 d-flex align-items-center">
                <div>
                  <?php if (defined('SUBDOMAIN') && SUBDOMAIN === "eyd"){ ?>
                    <h5 class="mb-2">Play Big. Build Purpose.</h5>
                    <p class="mb-2">Watch signed agreements, eForms and campaign sends from one place.</p>
                    <div class="font-600">Barinderjeet Kaur</div>
                    <div class="text-muted">Human Behaviour Specialist &amp; Business Coach</div>
                  <?php } else { ?>
                    <h5 class="mb-2">Think Big. Build Purpose.</h5>
                    <p class="mb-2">Every brand can grow with clarity, strategy and purpose. Start here.</p>
                    <div class="font-600">Franhive</div>
                    <div class="text-muted">Your Partner in Growth &amp; Business Transformation</div>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row pb-10">
        <?php foreach (($kpis ?? []) as $k): ?>
        <div class="col-xl-2 col-lg-4 col-md-6 mb-20">
          <a class="metric" href="<?= htmlspecialchars($k['href'] ?? '#'); ?>">
            <div class="icon"><i class="fa-solid <?= htmlspecialchars($k['icon'] ?? 'fa-chart-line'); ?>"></i></div>
            <h3><?= number_format((int)$k['value']); ?></h3>
            <p><?= htmlspecialchars($k['label']); ?></p>
            <small><?= htmlspecialchars($k['hint'] ?? ''); ?></small>
          </a>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="row pb-10">
        <div class="col-lg-4 col-md-12 mb-20">
          <div class="card-box">
            <div class="card-header"><h5>Lead pipeline</h5><a href="<?= base_url('leads'); ?>">View leads</a></div>
            <div class="pd-20"><div id="chart-leads" class="chart"></div></div>
          </div>
        </div>
        <div class="col-lg-4 col-md-12 mb-20">
          <div class="card-box">
            <div class="card-header"><h5>Payment agreements</h5><a href="<?= base_url('payment-agreement/requests'); ?>">View all</a></div>
            <div class="pd-20"><div id="chart-pa" class="chart"></div></div>
          </div>
        </div>
        <div class="col-lg-4 col-md-12 mb-20">
          <div class="card-box">
            <div class="card-header"><h5>Task status</h5><a href="<?= base_url('tasks'); ?>">View tasks</a></div>
            <div class="pd-20"><div id="chart-tasks" class="chart"></div></div>
          </div>
        </div>
      </div>

      <div class="row pb-10 dash-equal">
        <div class="col-lg-7 col-md-12 mb-20">
          <div class="card-box">
            <div class="card-header"><h5>New eForm submissions</h5><a href="<?= base_url('admin_eforms/submissions'); ?>">All submissions</a></div>
            <div class="table-responsive">
              <table class="table table-sm table-hover mb-0">
                <thead class="thead-light"><tr><th>When</th><th>Client</th><th>Form</th><th></th></tr></thead>
                <tbody>
                <?php if (!empty($recent_eforms)): foreach ($recent_eforms as $row): ?>
                  <tr>
                    <td><?= htmlspecialchars(dash_dt($row['created_at'] ?? '')); ?></td>
                    <td>
                      <?php
                        $ef_name = trim((string)($row['client_name'] ?? ''));
                        $ef_email = trim((string)($row['client_email'] ?? ''));
                        $ef_label = $ef_name !== '' ? $ef_name : ($ef_email !== '' ? $ef_email : 'Direct link');
                      ?>
                      <div class="weight-600"><?= htmlspecialchars($ef_label); ?></div>
                      <?php if ($ef_email !== '' && $ef_email !== $ef_label): ?>
                        <small class="text-muted"><?= htmlspecialchars($ef_email); ?></small>
                      <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['template_title'] ?: (!empty($row['static_form_slug']) ? 'IICT Enrolment form' : 'eForm')); ?></td>
                    <td><a href="<?= base_url('admin_eforms/submission_view/' . (int)$row['id']); ?>">Open</a></td>
                  </tr>
                <?php endforeach; else: ?>
                  <tr><td colspan="4" class="empty-row">No eForm submissions yet.</td></tr>
                <?php endif; ?>
                </tbody>
              </table>
            </div>
            <div class="dash-card-foot">
              <a href="<?= base_url('admin_eforms/submissions'); ?>">Open eForm submissions</a>
            </div>
          </div>
        </div>
        <div class="col-lg-5 col-md-12 mb-20">
          <div class="card-box">
            <div class="card-header"><h5>New payment agreements</h5><a href="<?= base_url('payment-agreement/requests'); ?>">All requests</a></div>
            <div class="table-responsive">
              <table class="table table-sm table-hover mb-0">
                <thead class="thead-light"><tr><th>Client</th><th>Status</th><th>When</th></tr></thead>
                <tbody>
                <?php if (!empty($recent_pa)): foreach ($recent_pa as $row): ?>
                  <tr>
                    <td>
                      <div class="weight-600"><?= htmlspecialchars($row['client_name'] ?? '—'); ?></div>
                      <small class="text-muted"><?= htmlspecialchars($row['client_email'] ?? ''); ?></small>
                    </td>
                    <td>
                      <div class="pa-meta">
                        <span class="badge badge-<?= dash_badge($row['status'] ?? ''); ?>"><?= htmlspecialchars(ucfirst((string)($row['status'] ?? ''))); ?></span>
                        <?php if (isset($row['total_inc_gst']) && $row['total_inc_gst'] !== null && $row['total_inc_gst'] !== ''): ?>
                          <span class="text-muted">$<?= number_format((float)$row['total_inc_gst'], 2); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($row['agreement_id'])): ?>
                          <a href="<?= base_url('payment-agreement/preview/' . (int)$row['agreement_id']); ?>">Preview</a>
                        <?php endif; ?>
                      </div>
                    </td>
                    <td><?= htmlspecialchars(dash_dt(!empty($row['submitted_at']) ? $row['submitted_at'] : ($row['sent_at'] ?? ''))); ?></td>
                  </tr>
                <?php endforeach; else: ?>
                  <tr><td colspan="3" class="empty-row">No payment agreements yet.</td></tr>
                <?php endif; ?>
                </tbody>
              </table>
            </div>
            <div class="dash-card-foot">
              <a href="<?= base_url('payment-agreement/requests'); ?>">Open payment agreements</a>
            </div>
          </div>
        </div>
      </div>

      <div class="row pb-10">
        <div class="col-lg-6 col-md-12 mb-20">
          <div class="card-box">
            <div class="card-header"><h5>Campaigns</h5><a href="<?= base_url('campaign-dashboard'); ?>">Campaign center</a></div>
            <div class="pd-10">
              <?php
                $camp_labels = ['draft' => 'Draft', 'setup' => 'Setup', 'scheduled' => 'Triggered', 'in_progress' => 'Sending', 'sent' => 'Sent', 'paused' => 'Paused'];
                foreach ($camp_labels as $key => $label):
                  $n = (int)(($campaign_status[$key] ?? 0));
              ?>
                <span class="badge badge-<?= dash_badge($key); ?> mr-1 mb-2"><?= htmlspecialchars($label); ?> <?= $n; ?></span>
              <?php endforeach; ?>
            </div>
            <div class="table-responsive">
              <table class="table table-sm table-hover mb-0">
                <thead class="thead-light"><tr><th>Campaign</th><th>Status</th><th>Sent</th></tr></thead>
                <tbody>
                <?php if (!empty($recent_campaigns)): foreach ($recent_campaigns as $c): $meta = campaign_status_meta($c['WORKFLOW_STATUS'] ?? ''); ?>
                  <tr>
                    <td class="weight-600"><a href="<?= base_url('view-campaign/' . (int)$c['CAMPAIGN_ID']); ?>"><?= htmlspecialchars($c['TITLE'] ?? '—'); ?></a></td>
                    <td><span class="badge <?= htmlspecialchars($meta['class']); ?>"><?= htmlspecialchars($meta['label']); ?></span></td>
                    <td><?= (int)($c['SENT_COUNT'] ?? 0); ?> / <?= (int)($c['TOTAL_RECIPIENTS'] ?? 0); ?></td>
                  </tr>
                <?php endforeach; else: ?>
                  <tr><td colspan="3" class="empty-row">No campaigns yet.</td></tr>
                <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-12 mb-20">
          <div class="card-box">
            <div class="card-header"><h5>Latest test scores</h5><a href="<?= base_url('test-reports'); ?>">All reports</a></div>
            <div class="table-responsive">
              <table class="table table-sm table-hover mb-0">
                <thead class="thead-light"><tr><th>Person</th><th>Test</th><th>Score</th><th>When</th></tr></thead>
                <tbody>
                <?php if (!empty($recent_tests)): foreach ($recent_tests as $t):
                    $got = $t['MARKS_OBTAINED'] ?? null;
                    $tot = (float)($t['TOTAL_MARKS'] ?? 0);
                    if ($got === null || $got === '') {
                        $score = '—';
                    } elseif ($tot > 0) {
                        $score = round(((float)$got / $tot) * 100) . '%';
                    } else {
                        $score = number_format((float)$got, 1);
                    }
                ?>
                  <tr>
                    <td><?= htmlspecialchars($t['NAME'] ?? '—'); ?></td>
                    <td><?= htmlspecialchars($t['TEST_NAME'] ?? '—'); ?></td>
                    <td class="weight-600"><?= htmlspecialchars($score); ?></td>
                    <td><?= htmlspecialchars(dash_dt($t['SUBMITTED_AT'] ?? $t['created_at'] ?? '')); ?></td>
                  </tr>
                <?php endforeach; else: ?>
                  <tr><td colspan="4" class="empty-row">No submitted tests yet.</td></tr>
                <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="row pb-10">
        <div class="col-lg-6 col-md-12 mb-20">
          <div class="card-box">
            <div class="card-header"><h5>Recent leads</h5><a href="<?= base_url('leads'); ?>">All leads</a></div>
            <div class="table-responsive">
              <table class="table table-sm table-hover mb-0">
                <thead class="thead-light"><tr><th>Name</th><th>Source</th><th>Status</th><th>Owner</th><th>Created</th></tr></thead>
                <tbody>
                <?php if (!empty($recent_leads)): foreach ($recent_leads as $r): ?>
                  <tr>
                    <td class="weight-600"><a href="<?= base_url('leadController/viewLead/' . (int)$r['ENTITY_ID']); ?>"><?= htmlspecialchars($r['NAME'] ?? '—'); ?></a></td>
                    <td><?= htmlspecialchars($r['LEAD_SOURCE'] ?? '—'); ?></td>
                    <td><span class="badge badge-<?= dash_badge(dash_lead_status($r['LEAD_STATUS'] ?? '')); ?>"><?= htmlspecialchars(dash_lead_status($r['LEAD_STATUS'] ?? '')); ?></span></td>
                    <td><?= htmlspecialchars($r['OWNER_NAME'] ?? '—'); ?></td>
                    <td><?= htmlspecialchars(dash_dt($r['CREATED_ON'] ?? '')); ?></td>
                  </tr>
                <?php endforeach; else: ?>
                  <tr><td colspan="5" class="empty-row">No leads yet.</td></tr>
                <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-12 mb-20">
          <div class="card-box">
            <div class="card-header"><h5>Recent tasks</h5><a href="<?= base_url('tasks'); ?>">All tasks</a></div>
            <div class="table-responsive">
              <table class="table table-sm table-hover mb-0">
                <thead class="thead-light"><tr><th>Title</th><th>Assigned</th><th>Status</th><th>Due</th></tr></thead>
                <tbody>
                <?php if (!empty($recent_tasks)): foreach ($recent_tasks as $t): ?>
                  <tr>
                    <td><?= htmlspecialchars($t['SUBJECT'] ?? '—'); ?></td>
                    <td><?= htmlspecialchars($t['ASSIGNED_TO_USER'] ?? '—'); ?></td>
                    <td><span class="badge badge-<?= dash_badge(dash_task_status($t['STATUS'] ?? '')); ?>"><?= htmlspecialchars(dash_task_status($t['STATUS'] ?? '')); ?></span></td>
                    <td><?= htmlspecialchars(dash_dt($t['START_DATE'] ?? $t['CREATED_ON'] ?? '')); ?></td>
                  </tr>
                <?php endforeach; else: ?>
                  <tr><td colspan="4" class="empty-row">No tasks yet.</td></tr>
                <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <?php if (defined('SUBDOMAIN') && SUBDOMAIN === 'eyd' && !empty($welcome_note_text)): ?>
        <div class="modal fade" id="welcomeModal" tabindex="-1" role="dialog" aria-labelledby="welcomeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="welcomeModalLabel">Welcome Note-1</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body" style="font-size:15px; line-height:1.5; padding:15px; color:#333;">
                <?= $welcome_note_text; ?>
              </div>
              <div class="modal-footer"><button type="button" class="btn btn-success" data-dismiss="modal">OK</button></div>
            </div>
          </div>
        </div>
      <?php endif; ?>

    </div>
  </div>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script>
  (function () {
    if (window.jQuery) {
      jQuery(function () {
        var wm = document.getElementById('welcomeModal');
        if (wm) jQuery('#welcomeModal').modal('show');
      });
    }

    const leadPipeline = <?= json_encode($lead_pipeline ?? ['labels' => [], 'values' => []]); ?>;
    const paStatus     = <?= json_encode($pa_status ?? ['labels' => [], 'values' => []]); ?>;
    const taskStatus   = <?= json_encode($task_status ?? ['labels' => [], 'values' => []]); ?>;
    const nums = v => (Array.isArray(v) ? v : []).map(x => Number(x || 0));
    const cats = v => (Array.isArray(v) ? v : []).map(x => x == null ? '' : String(x));
    function withValues(labels, values) {
      const L = cats(labels), V = nums(values);
      const outL = [], outV = [];
      L.forEach(function (label, i) {
        if (Number(V[i] || 0) > 0) { outL.push(label); outV.push(V[i]); }
      });
      return { labels: outL.length ? outL : ['No data'], values: outV.length ? outV : [0] };
    }
    const leadChart = withValues(leadPipeline.labels, leadPipeline.values);
    const paChart = withValues(paStatus.labels, paStatus.values);
    const taskChart = withValues(taskStatus.labels, taskStatus.values);

    window.Apex = {
      chart: { foreColor: '#64748b' },
      colors: ['#265ed7', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#14b8a6'],
      grid: { borderColor: '#eef1f6' },
      legend: { fontWeight: 600 }
    };

    const elLeads = document.querySelector('#chart-leads');
    if (elLeads && window.ApexCharts) {
      new ApexCharts(elLeads, {
        chart: { type: 'bar', height: 280, toolbar: { show: false } },
        plotOptions: { bar: { horizontal: true, borderRadius: 8 } },
        series: [{ name: 'Leads', data: leadChart.values }],
        xaxis: { categories: leadChart.labels },
        dataLabels: { enabled: true }
      }).render();
    }

    function donutConfig(data) {
      return {
        chart: { type: 'donut', height: 280 },
        series: data.values,
        labels: data.labels,
        dataLabels: {
          enabled: true,
          formatter: function (val, opts) {
            const count = opts.w.config.series[opts.seriesIndex] || 0;
            return count + ' · ' + Number(val).toFixed(1) + '%';
          },
          style: { fontSize: '11px', fontWeight: 700 },
          dropShadow: { enabled: false }
        },
        legend: {
          position: 'bottom',
          formatter: function (name, opts) {
            const count = opts.w.globals.series[opts.seriesIndex] || 0;
            return name + ' (' + count + ')';
          }
        }
      };
    }

    const elPa = document.querySelector('#chart-pa');
    if (elPa && window.ApexCharts) {
      new ApexCharts(elPa, donutConfig(paChart)).render();
    }

    const elTasks = document.querySelector('#chart-tasks');
    if (elTasks && window.ApexCharts) {
      new ApexCharts(elTasks, donutConfig(taskChart)).render();
    }
  })();
  </script>
<?php $this->load->view('includes/footer'); ?>
</html>
