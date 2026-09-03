<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<?php
$attempt_reports = isset($attempt_reports) && is_array($attempt_reports) ? $attempt_reports : [];

// Interpretation badge class helper
function interpClass($interpretation) {
    $interp = strtolower(trim((string)$interpretation));
    $cls = 'interp-low';
    if (strpos($interp, 'normal') !== false) $cls = 'interp-normal';
    if (strpos($interp, 'high') !== false)   $cls = 'interp-high';
    return $cls;
}
?>

<div class="mobile-menu-overlay"></div>

<style>
    body { font-family: 'Poppins', Arial, sans-serif; background: #eef1f7; color: #2c2c2c; }
    .page-title { font-size: 18px; font-weight: 700; color: #FCB22B; margin: 0; }
    .top-actions { display:flex; justify-content:flex-end; flex-wrap:wrap; gap:10px; margin-bottom:15px; }
    .card-wrap { text-align:left; margin-bottom:18px; background:#fff; padding:18px; border-radius:14px; box-shadow:0 8px 18px rgba(0,0,0,0.06); }
    .user-card { display:flex; align-items:center; gap:16px; flex-wrap:wrap; }
    .user-photo { width:84px; height:84px; border-radius:50%; border:3px solid #FCB22B; object-fit:cover; background:#f5f5f5; }
    .user-meta h3 { margin:0; font-size:18px; font-weight:700; color:#222; }
    .user-meta .muted { color:#666; font-size:13px; margin-top:2px; }

    .meta-grid { margin-top:12px; display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:10px 14px; }
    .meta-item { background:#fafafa; border:1px solid #f0f0f0; border-radius:12px; padding:10px 12px; font-size:13px; }
    .meta-item strong { color:#222; }

    .summary-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:12px; }
    .summary-box { border-radius:14px; padding:14px; background:#fff; box-shadow:0 8px 18px rgba(0,0,0,0.06); border-top:4px solid #2196F3; text-align:center; }
    .summary-box h4 { margin:0; font-size:13px; color:#666; font-weight:600; }
    .summary-box p { margin:6px 0 0; font-size:22px; font-weight:800; color:#2196F3; }
    .summary-box.total { border-top-color:#FCB22B; }
    .summary-box.total p { color:#FCB22B; }
    .summary-box.avg { border-top-color:#8e44ad; }
    .summary-box.avg p { color:#8e44ad; }

    .score-pill { display:inline-block; padding:6px 14px; border-radius:999px; font-weight:700; background:#f7f7f7; border:1px solid #e6e6e6; font-size:13px; }
    .interp-low { color:#F44336; border-color:rgba(244,67,54,0.25); background:rgba(244,67,54,0.06); }
    .interp-normal { color:#2196F3; border-color:rgba(33,150,243,0.25); background:rgba(33,150,243,0.06); }
    .interp-high { color:#4CAF50; border-color:rgba(76,175,80,0.25); background:rgba(76,175,80,0.06); }

    .attempt-title { display:flex; align-items:center; justify-content:space-between; gap:10px; flex-wrap:wrap; margin:0 0 12px; }
    .attempt-title h5 { margin:0; font-size:15px; font-weight:800; color:#222; }

    @media (max-width:768px){
        .meta-grid { grid-template-columns:1fr; }
        .summary-grid { grid-template-columns:1fr; }
        .top-actions { justify-content:flex-start; }
    }
</style>

</head>
<body>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">

            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:10px;">
                    <h4 class="page-title">Test Report</h4>
                </div>
            </div>

            <!-- ✅ User/Test Details (once) -->
            <div class="card-wrap">
                <div class="user-card">
                    <img
                        src="<?= !empty($profile_picture) ? htmlspecialchars($profile_picture) : 'default-user.png' ?>"
                        class="user-photo"
                        alt="User Photo"
                    />

                    <div class="user-meta">
                        <h3><?= htmlspecialchars($user_name ?? '') ?></h3>
                        <div class="muted"><?= htmlspecialchars($user_email ?? '') ?></div>
                    </div>
                </div>

                <div class="meta-grid">
                    <div class="meta-item"><strong>Test Name:</strong> <?= htmlspecialchars($test_name ?? '') ?></div>
                    <div class="meta-item"><strong>Total Questions:</strong> <?= (int)($total_questions ?? 0) ?></div>
                    <div class="meta-item"><strong>Test Start Date:</strong> <?= htmlspecialchars($test_start_date ?? '') ?></div>
                    <div class="meta-item"><strong>Total Attempts:</strong> <?= count($attempt_reports) ?></div>
                </div>
            </div>

            <!-- ✅ Each Attempt -->
            <?php if (!empty($attempt_reports)): ?>
                <?php $attemptNo = 1; ?>
                <?php foreach ($attempt_reports as $rep): ?>

                    <?php
                    $show_brs_extras = isset($rep['show_brs_extras']) ? (bool)$rep['show_brs_extras'] : false;
                    $user_data = $rep['user_data'] ?? [];

                    // ✅ response_id should come from builder; fallback to user_data ID
                    $response_id = (int)($rep['response_id'] ?? ($user_data['ID'] ?? 0));

                    $attempted = (int)($rep['attempted'] ?? 0);
                    $total_marks_gained = (int)($rep['total_marks_gained'] ?? 0);
                    $user_score = $rep['user_score'] ?? '0.00';
                    $interpretation = $rep['interpretation'] ?? '';
                    $interpClass = interpClass($interpretation);
                    ?>

                    <div class="card-wrap">
                        <div class="attempt-title">
                            <h5>Attempt #<?= $attemptNo++ ?></h5>

                            <div class="top-actions" style="margin-bottom:0;">
                                <?php if (!empty($response_id)): ?>
                                    <a class="btn btn-sm btn-outline-primary"
                                       href="<?= base_url('my-test-report-pdf/' . $response_id) ?>">
                                        Download Report PDF
                                    </a>
                                <?php endif; ?>

                                <?php if (!empty($response_id) && (int)($user_data['IS_EVAL'] ?? 0) == 0): ?>
                                    <a class="btn btn-sm btn-outline-success"
                                       href="<?= base_url('my-test-submit-eval/' . $response_id) ?>"
                                       onclick="return confirm('Submit evaluation for this attempt? This will mark it as evaluated.');">
                                        Submit Evaluation
                                    </a>
                                <?php endif; ?>

                                <?php if ((int)($user_data['IS_EVAL'] ?? 0) == 1): ?>
                                    <button class="btn btn-sm btn-success" disabled>Evaluated</button>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Attempt summary -->
                        <div class="summary-grid">
                            <div class="summary-box">
                                <h4>Attempted</h4>
                                <p><?= $attempted ?></p>
                            </div>

                            <div class="summary-box total">
                                <h4>Total Marks Gained</h4>
                                <p><?= $total_marks_gained ?></p>
                            </div>

                            <?php if ($show_brs_extras): ?>
                                <div class="summary-box avg">
                                    <h4>User Score (Average)</h4>
                                    <p><?= htmlspecialchars($user_score) ?></p>
                                    <div style="margin-top:8px;">
                                        <span class="score-pill <?= $interpClass ?>">
                                            <?= htmlspecialchars($interpretation) ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- If later you want question table per attempt, use $rep['question_analysis'] here -->

                    </div>

                <?php endforeach; ?>
            <?php else: ?>
                <div class="card-wrap">No attempts found.</div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>
</body>
</html>
