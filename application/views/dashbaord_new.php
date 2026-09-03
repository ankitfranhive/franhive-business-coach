<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('includes/header'); ?>
<body>
  <div class="mobile-menu-overlay"></div>

  <style>
    :root{
      --brand:#265ed7; --text:#1f2937; --muted:#6b7280; --card:#ffffff; --bg:#f6f8fb; --ring: rgba(38,94,215,0.16);
      --ok:#10b981; --warn:#f59e0b; --bad:#ef4444;
    }
    body{ background: var(--bg); }
    .page-title{ color:var(--text); }

    .card-box{ background:var(--card); border:1px solid #eef1f6; border-radius:16px; box-shadow:0 6px 24px rgba(20,40,90,.06); }
    .card-header{ padding:12px 16px; border-bottom:1px solid #f0f3f7; display:flex; align-items:center; justify-content:space-between;}
    .card-header h5{ margin:0; color:var(--text); font-weight:700; font-size:16px;}

    .metric{ display:flex; align-items:center; gap:14px; padding:16px; border-radius:14px; border:1px solid #eef1f6; background:#fff; transition: box-shadow .2s, transform .05s;}
    .metric:hover{ box-shadow:0 6px 24px rgba(20,40,90,.08); transform: translateY(-1px);}
    .metric .icon{ width:44px; height:44px; display:grid; place-items:center; border-radius:12px; background:#f4f7ff; color:var(--brand); box-shadow: inset 0 0 0 1px var(--ring);}
    .metric h3{ margin:0; font-size:22px; font-weight:800; color:var(--text);}
    .metric p{ margin:0; color:var(--muted); font-size:12px;}
    .metric small{ color:var(--ok); font-weight:700;}

    .timezone-chip{ display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:999px; background:#f4f7ff; color:var(--brand); border:1px solid var(--ring); font-weight:600; font-size:12px;}
    .time-led{ width:10px; height:10px; border-radius:999px; background:var(--ok); box-shadow:0 0 0 4px rgba(16,185,129,.15);}
    .tz-time{ font-size:24px; font-weight:800; letter-spacing:.5px; color:#0f172a;}
    .tz-date{ color:var(--muted); font-weight:600;}

    .img-hero{ border-radius:16px; box-shadow:0 10px 30px rgba(20,40,90,.08); }
    .list-group-item{ border:none; border-bottom:1px solid #f1f3f7;}
    .list-group-item:last-child{ border-bottom:none;}
    .badge-pill{ border-radius:999px; padding:.35rem .6rem;}

    .chart{ min-height:320px; }
    .chart-lg{ min-height:340px; }

    .fa-fw{ color: var(--brand); }
  </style>

  <div class="main-container">
    <div class="xs-pd-20-10 pd-ltr-20">

      <div class="title pb-10">
        <h2 class="h3 mb-0 page-title">Dashboard</h2>
      </div>

      <!-- Time smaller (lg-4) + Intro/Banner bigger (lg-8) -->
      <?php
  // Username
  $userName = isset($_SESSION['user']['NAME']) && $_SESSION['user']['NAME'] !== ''
      ? $_SESSION['user']['NAME']
      : 'there';

  // Day-wise greeting (like Google)
  $dayOfWeek = date('l');
  $dayGreetings = [
      'Monday'    => 'Happy Monday! Fresh week, fresh opportunities.',
      'Tuesday'   => 'It’s Tuesday – keep the momentum going.',
      'Wednesday' => 'Midweek already – you’re doing great!',
      'Thursday'  => 'Happy Thursday! Almost at the finish line.',
      'Friday'    => 'It’s Friday – finish strong!',
      'Saturday'  => 'Happy Saturday! A great day to recharge and reflect.',
      'Sunday'    => 'Easy like Sunday – plan, rest and get ready to win.'
  ];
  $dayLine = isset($dayGreetings[$dayOfWeek]) ? $dayGreetings[$dayOfWeek] : 'Make today count.';

  // Random motivational quote
  $quotes = [
      'Small consistent steps build massive results.',
      'Your future is created by what you do today, not tomorrow.',
      'Success is the sum of small efforts repeated day in and day out.',
      'Clarity + action = unstoppable growth.',
      'You don’t have to be perfect, just persistent.'
  ];
  $quoteLine = $quotes[array_rand($quotes)];
?>

<div class="row pb-10">
  <!-- Greeting + Motivation -->
  <div class="col-lg-4 col-md-12 mb-20">
    <div class="card-box pd-20">
      <div class="card-header">
        <h5 class="mb-1">
          Hi, <?php echo htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'); ?> 👋
        </h5>
        <span class="text-muted" style="font-size:13px;">
          <?php echo htmlspecialchars($dayLine, ENT_QUOTES, 'UTF-8'); ?>
        </span>
      </div>
      <div class="pd-20">
        <div style="font-size:14px; line-height:1.6;">
          “<?php echo htmlspecialchars($quoteLine, ENT_QUOTES, 'UTF-8'); ?>”
        </div>
      </div>
    </div>
  </div>

  <!-- Banner + Tenant Greeting -->
  <div class="col-lg-8 col-md-12 mb-20">
    <div class="card-box pd-20">
      <div class="row">
        <div class="col-md-7 mb-10">
          <?php if (defined('SUBDOMAIN') && SUBDOMAIN === "demo"){ ?>
            <img src="https://demo.franhive.com/uploads/1732134458_dashboard_banner_fh.jpg"
                 alt="franhive banner" class="img-fluid img-hero" loading="lazy">
          <?php } else { ?>
            <img src="<?= base_url('vendors/images/banner_image.jpeg'); ?>"
                 alt="franhive banner" class="img-fluid img-hero" loading="lazy">
          <?php } ?>
        </div>
        <div class="col-md-5 d-flex align-items-center">
          <div>
            <?php if (defined('SUBDOMAIN') && SUBDOMAIN === "eyd"){ ?>
              <h5 class="mb-2">Play Big. Build Purpose.</h5>
              <p class="mb-2">Excited to be on this journey with you—your decision already sets your direction..</p>
              <div class="font-600">Barinderjeet Kaur</div>
              <div class="text-muted">Human Behaviour Specialist & Business Coach</div>
            <?php } else { ?>
              <h5 class="mb-2">Think Big. Build Purpose.</h5>
              <p class="mb-2">Every brand can grow with clarity, strategy and purpose. Start here.</p>
              <div class="font-600">Franhive</div>
              <div class="text-muted">Your Partner in Growth & Business Transformation</div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


      <!-- KPIs -->
      <div class="row pb-10">
        <?php foreach (($kpis ?? []) as $k): ?>
        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
          <div class="metric">
            <div class="icon">
              <?php
                $map = ['ti-user'=>'fa-user-plus','ti-check-box'=>'fa-square-check','ti-briefcase'=>'fa-briefcase','ti-target'=>'fa-bullseye'];
                $fa = $map[$k['icon']] ?? 'fa-chart-line';
              ?>
              <i class="fa-solid <?= $fa ?> fa-fw"></i>
            </div>
            <div>
              <h3><?= is_float($k['value']) ? number_format($k['value'], 1) : number_format($k['value']) ?></h3>
              <p><?= htmlspecialchars($k['label']) ?> — <small><?= htmlspecialchars($k['delta']) ?></small></p>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Leads + Tasks -->
      <div class="row pb-10">
        <div class="col-lg-6 col-md-12 mb-20">
          <div class="card-box">
            <div class="card-header"><h5>Leads Funnel</h5></div>
            <div class="pd-20"><div id="chart-funnel" class="chart"></div></div>
          </div>
        </div>
        <div class="col-lg-6 col-md-12 mb-20">
          <div class="card-box">
            <div class="card-header"><h5>Tasks Status</h5></div>
            <div class="pd-20"><div id="chart-tasks" class="chart"></div></div>
          </div>
        </div>
      </div>

      <!-- Clients + Top Performers -->
      <div class="row pb-10">
        <div class="col-lg-8 col-md-12 mb-20">
          <div class="card-box">
            <div class="card-header"><h5>Clients Growth (12 months)</h5></div>
            <div class="pd-20"><div id="chart-clients" class="chart-lg"></div></div>
          </div>
        </div>
        <div class="col-lg-4 col-md-12 mb-20">
          <div class="card-box">
            <div class="card-header"><h5>Top Performers</h5></div>
            <div class="pd-10">
              <ul class="list-group">
                <?php foreach(($top_agents ?? []) as $a): ?>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>
                      <span class="weight-600"><?= htmlspecialchars($a['name']) ?></span>
                      <small class="text-muted d-block">Leads: <?= (int)$a['leads'] ?></small>
                    </span>
                    <span class="badge badge-primary badge-pill"><?= (int)$a['score'] ?>%</span>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Landing vs Conversions + Knowledge Center -->
      <!-- <div class="row pb-10">
        <div class="col-lg-6 col-md-12 mb-20">
          <div class="card-box">
            <div class="card-header"><h5>Landing Pages – Visits vs Conversions</h5></div>
            <div class="pd-20"><div id="chart-landing" class="chart"></div></div>
          </div>
        </div>
        <div class="col-lg-6 col-md-12 mb-20">
          <div class="card-box">
            <div class="card-header"><h5>Knowledge Center – Views (7d)</h5></div>
            <div class="pd-20"><div id="chart-kc" class="chart"></div></div>
          </div>
        </div>
      </div> -->

      <!-- Tests + Recent Leads -->
      <div class="row pb-10">
        <div class="col-lg-6 col-md-12 mb-20">
          <div class="card-box">
            <div class="card-header"><h5>Test Management – Avg Scores</h5></div>
            <div class="pd-20"><div id="chart-tests" class="chart"></div></div>
          </div>
        </div>
        <div class="col-lg-6 col-md-12 mb-20">
          <div class="card-box">
            <div class="card-header"><h5>Recent Leads</h5></div>
            <div class="pd-10">
              <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                  <thead class="thead-light">
                    <tr><th>Name</th><th>Source</th><th>Stage</th><th>Owner</th><th>Created</th></tr>
                  </thead>
                  <tbody>
                  <?php 
                    if (!empty($recently_leads)) {
                        $count = 0;
                        foreach ($recently_leads as $r): 
                            if ($count >= 6) break; // ✅ Stop after 6 rows
                  ?>
                      <tr>
                        <td class="weight-600"><?= htmlspecialchars($r['NAME'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($r['LEAD_SOURCE'] ?? '-') ?></td>
                        <td><span class="badge badge-pill badge-primary"><?= htmlspecialchars($r['CITY'] ?? '-') ?></span></td>
                        <td><?= htmlspecialchars($r['ENTITY_ID'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($r['STATE'] ?? '-') ?></td>
                      </tr>
                  <?php 
                            $count++;
                        endforeach;
                    } else { 
                  ?>
                      <tr><td colspan="5" class="text-center text-muted">No data</td></tr>
                  <?php } ?>
                </tbody>

                </table>
              </div>
            </div>
          </div>
        </div>


      <!-- Recent Tasks --><div class="card-box">
  <div class="card-header"><h5>Recent Tasks</h5></div>
  <div class="pd-10">
    <div class="table-responsive">
      <table class="table table-sm table-hover mb-0">
        <thead class="thead-light">
          <tr>
            <th>Title</th>
            <th>Assigned To</th>
            <th>Status</th>
            <th>Created On</th>
            <th>Due</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach (($recently_task ?? []) as $t): ?>
            <tr>
              <td><?= htmlspecialchars($t['TITLE'] ?? '-') ?></td>
              <td><?= htmlspecialchars($t['ASSIGNED_TO_USER'] ?? '-') ?></td>
              <td><span class="badge badge-pill badge-primary"><?= htmlspecialchars($t['STATUS'] ?? '-') ?></span></td>
              <td><?= htmlspecialchars($t['CREATED_ON'] ?? '-') ?></td>
              <td><?= htmlspecialchars($t['DUE_DATE'] ?? '-') ?></td>
            </tr>
          <?php endforeach; if (empty($recently_task)): ?>
            <tr><td colspan="5" class="text-center text-muted">No tasks</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>






      </div>

      <!-- Welcome Note Modal (EYD only) -->
      <?php if (defined('SUBDOMAIN') && SUBDOMAIN === 'eyd' && !empty($welcome_note_text)): ?>
        <div class="modal fade" id="welcomeModal" tabindex="-1" role="dialog" aria-labelledby="welcomeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="welcomeModalLabel">Welcome Note-1</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" style="font-size:15px; line-height:1.5; padding:15px; color:#333;">
                <?= $welcome_note_text /* sanitize if not trusted HTML */ ?>
              </div>
              <div class="modal-footer"><button type="button" class="btn btn-success" data-dismiss="modal">OK</button></div>
            </div>
          </div>
        </div>
      <?php endif; ?>

    </div>
  </div>

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- jQuery + Bootstrap (remove if header/footer already include them) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <!-- ApexCharts -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script>
  // ===== Live server clock (PHP TZ) =====
  (function(){
    const tzClock = document.getElementById('tzClock');
    const tzDate  = document.getElementById('tzDate');

    // Only run clock logic if those elements exist
    if (tzClock && tzDate) {
      const serverNow = <?= json_encode(date('Y-m-d H:i:s')) ?>;
      const tzName    = <?= json_encode(date_default_timezone_get()) ?>;
      const parts     = serverNow.match(/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/);

      let base   = new Date(parts[1], parts[2]-1, parts[3], parts[4], parts[5], parts[6]);
      let baseMs = base.getTime();
      const pad  = n => n < 10 ? '0' + n : n;

      function tick(){
        baseMs += 1000;
        const d = new Date(baseMs);
        const timeStr = `${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;
        const dateStr = d.toLocaleDateString(undefined, {
          weekday:'long', year:'numeric', month:'long', day:'numeric'
        });

        tzClock.textContent = timeStr;
        tzDate.textContent  = dateStr + ' • ' + tzName;
      }

      tick();
      setInterval(tick, 1000);
    }

    // welcome modal still works regardless
    $(function(){
      var wm = document.getElementById('welcomeModal');
      if (wm) $('#welcomeModal').modal('show');
    });
  })();

  // ===== Pull PHP → JS with robust guards =====
  const leadsFunnel   = <?= json_encode($leads_funnel   ?? null) ?> || {labels:[], values:[]};
  const tasksStatus   = <?= json_encode($tasks_status   ?? null) ?> || {labels:[], values:[]};
  const clientsGrowth = <?= json_encode($clients_growth ?? null) ?> || {months:[], active:[], new:[], churn:[]};
  const landing       = <?= json_encode($landing_pages  ?? null) ?> || {pages:[], visits:[], conversions:[]};
  const kc            = <?= json_encode($kc_views       ?? null) ?> || {days:[], views:[]};
  const tests         = <?= json_encode($tests          ?? null) ?> || {names:[], scores:[]};

  const arr  = v => Array.isArray(v) ? v : [];
  const nums = v => arr(v).map(x => Number(x ?? 0));
  const cats = v => arr(v).map(x => (x==null?'':String(x)));

  const DS = {
    funnel: { labels: cats(leadsFunnel.labels), values: nums(leadsFunnel.values) },
    tasks:  { labels: cats(tasksStatus.labels), values: nums(tasksStatus.values) },
    clients:{ months: cats(clientsGrowth.months), active: nums(clientsGrowth.active), add: nums(clientsGrowth.new), churn: nums(clientsGrowth.churn) },
    landing:{ pages: cats(landing.pages), visits: nums(landing.visits), conv: nums(landing.conversions) },
    kc:     { days: cats(kc.days), views: nums(kc.views) },
    tests:  { names: cats(tests.names), scores: nums(tests.scores) }
  };

  function fb(data, fallback){ return (data && data.length) ? data : fallback; }

  window.Apex = {
    chart: { foreColor: '#64748b' },
    colors: ['#265ed7', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#14b8a6'],
    grid: { borderColor: '#eef1f6' },
    stroke: { width: 3, curve: 'smooth' },
    legend: { fontWeight: 600 }
  };

  // Test Management — Avg Scores (radar)
  new ApexCharts(document.querySelector("#chart-tests"), {
    chart:{ type:'radar', height:320 },
    series:[ { name:'Avg Score', data: fb(DS.tests.scores, [0]) } ],
    labels: fb(DS.tests.names, ['No Data']),
    yaxis:{ max:100 },
    tooltip: { y:{ formatter:v => v + '%' } }
  }).render();

  // Leads Funnel
  new ApexCharts(document.querySelector("#chart-funnel"), {
    chart: { type:'bar', height:320 },
    plotOptions: { bar:{ horizontal:true, borderRadius:8 } },
    series: [{ name:'Leads', data: fb(DS.funnel.values, [0]) }],
    xaxis: { categories: fb(DS.funnel.labels, ['No Data']) },
    dataLabels: { enabled:true },
    tooltip: { y:{ formatter:v => v.toLocaleString() } }
  }).render();

  // Tasks Status
  new ApexCharts(document.querySelector("#chart-tasks"), {
    chart:{ type:'donut', height:320 },
    series: fb(DS.tasks.values, [1]),
    labels: fb(DS.tasks.labels, ['No Data']),
    legend:{ position:'bottom' },
    dataLabels:{ enabled:true }
  }).render();

  // Clients Growth
  new ApexCharts(document.querySelector("#chart-clients"), {
    chart:{ height:340, type:'line' },
    series:[
      { name:'Active', type:'area',   data: fb(DS.clients.active, [0]) },
      { name:'New',    type:'column', data: fb(DS.clients.add,    [0]) },
      { name:'Churn',  type:'column', data: fb(DS.clients.churn,  [0]) }
    ],
    xaxis:{ categories: fb(DS.clients.months, ['-']) },
    dataLabels:{ enabled:false },
    yaxis:[ { title:{ text:'Active'} }, { opposite:true, title:{ text:'New / Churn'} } ],
    tooltip:{ shared:true }
  }).render();

  // Landing Pages — Visits vs Conversions
  new ApexCharts(document.querySelector("#chart-landing"), {
    chart:{ type:'bar', height:320 },
    plotOptions:{ bar:{ borderRadius:8, columnWidth:'45%' } },
    series:[
      { name:'Visits',      data: fb(DS.landing.visits, [0]) },
      { name:'Conversions', data: fb(DS.landing.conv,   [0]) }
    ],
    xaxis:{ categories: fb(DS.landing.pages, ['No Data']) },
    dataLabels:{ enabled:false },
    tooltip:{ shared:true }
  }).render();

  // Knowledge Center — Views (7d)
  new ApexCharts(document.querySelector("#chart-kc"), {
    chart:{ type:'line', height:320 },
    series:[ { name:'Views', data: fb(DS.kc.views, [0]) } ],
    xaxis:{ categories: fb(DS.kc.days, ['-']) },
    markers:{ size:4 }
  }).render();
</script>


</body>
<?php $this->load->view('includes/footer'); ?>
</html>
