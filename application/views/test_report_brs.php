<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<?php
// If controller doesn't send it, default to false (safe for Type 5)
$show_brs_extras = isset($show_brs_extras) ? (bool)$show_brs_extras : false;
?>

<div class="mobile-menu-overlay"></div>

<style>
    body { font-family: 'Arial', sans-serif; background-color: #eef1f7; color: #333; }
    .container { margin-top: 20px; }
    h1, h3, h4 { color: #FCB22B; text-align: center; }

    .user-details, .summary-cards, .question-table, .brs-summary, .brs-interpretation {
        text-align: center;
        margin-bottom: 30px;
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .user-photo { width: 100px; height: 100px; border-radius: 50%; border: 3px solid #FCB22B; margin-bottom: 10px; }

    .summary-card {
        display: inline-block;
        width: 28%;
        background: #fff;
        padding: 15px;
        margin: 10px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .summary-card h4 { font-size: 1.1rem; color: #555; margin-bottom: 5px; }
    .summary-card p { font-size: 1.4rem; font-weight: bold; }
    .summary-card.attempted { border-top: 4px solid #2196F3; color: #2196F3; }

    .report-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .report-table th { background-color: #FCB22B; color: #fff; padding: 12px; }
    .report-table td { padding: 10px; text-align: center; border: 1px solid #dee2e6; }
    .report-table tr:nth-child(even) { background-color: #f9f9f9; }

    .score-pill {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: bold;
        background: #f5f5f5;
        border: 1px solid #ddd;
    }

    .interp-low { color: #F44336; }
    .interp-normal { color: #2196F3; }
    .interp-high { color: #4CAF50; }
</style>

</head>
<body>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">

            <!-- Page Header -->
            <div class="page-header">
                <h4>Test Report</h4>
            </div>
            <?php $response_id = isset($response_id) ? (int)$response_id : (int)($user_data['ID'] ?? 0); ?>

<div style="display:flex; justify-content:flex-end; gap:10px; margin-bottom:15px;">
    <a class="btn btn-sm btn-outline-primary"
       href="<?= base_url('my-test-report-pdf/' . $response_id) ?>">
        Download Report PDF
    </a>

    <?php if (!empty($response_id) && (int)($user_data['IS_EVAL'] ?? 0) == 0): ?>
        <a class="btn btn-sm btn-outline-success"
           href="<?= base_url('my-test-submit-eval/' . $response_id) ?>"
           onclick="return confirm('Submit evaluation for this test? This will mark it as evaluated.');">
            Submit Evaluation
        </a>
    <?php endif; ?>

    <?php if ((int)($user_data['IS_EVAL'] ?? 0) == 1): ?>
        <button class="btn btn-sm btn-success" disabled>Evaluated</button>
    <?php endif; ?>
</div>

            <!-- User Details -->
            <div class="user-details">
                <img src="<?= $user_data['PROFILE_PICTURE'] ?: 'default-user.png' ?>" class="user-photo" alt="User Photo">
                <h3><?= htmlspecialchars($user_data['NAME'] ?? '') ?></h3>
                <p><strong>Email:</strong> <?= htmlspecialchars($user_data['EMAIL'] ?? '') ?></p>
                <p><strong>Test Name:</strong> <?= htmlspecialchars($user_data['TEST_NAME'] ?? '') ?></p>
                <p><strong>Total Questions:</strong> <?= (int)($user_data['TOTAL_QUESTIONS'] ?? 0) ?></p>
                <p><strong>Test Start Date:</strong> <?= htmlspecialchars($user_data['TEST_START_DATE'] ?? '') ?></p>
            </div>

            <!-- Summary Cards -->
            <div class="summary-cards">
                <div class="summary-card attempted">
                    <h4>Attempted</h4>
                    <p><?= (int)($attempted ?? 0) ?></p>
                </div>
            </div>

            <!-- Question Breakdown (keep existing representation) -->
            <div class="question-table">
                <h4>Question-wise Responses</h4>
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Q.No</th>
                            <th>Question</th>
                            <th>User Chosen</th>
                            <th>Marks</th>
                            <?php if ($show_brs_extras): ?>
                                <th>Reverse Scored</th>
                            <?php endif; ?>
                            <th>Time Spent (sec)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $qNo = 1; ?>
                        <?php if (!empty($question_analysis) && is_array($question_analysis)): ?>
                            <?php foreach ($question_analysis as $qa): ?>
                                <tr>
                                    <td><?= $qNo++ ?></td>
                                    <td><?= htmlspecialchars($qa['question_text'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($qa['your_answer'] ?? '') ?></td>
                                    <td><span class="score-pill"><?= (int)($qa['marks'] ?? 0) ?></span></td>

                                    <?php if ($show_brs_extras): ?>
                                        <td><?= !empty($qa['reverse_scored']) ? 'Yes' : 'No' ?></td>
                                    <?php endif; ?>

                                    <td><?= (int)($qa['time_spent'] ?? 0) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="<?= $show_brs_extras ? 6 : 5 ?>">No data available.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Total + (optional) BRS Average -->
            <div class="brs-summary">
                <h4>Summary</h4>
                <p>
                    <strong>Total Marks Gained:</strong>
                    <span class="score-pill"><?= (int)($total_marks_gained ?? 0) ?></span>
                </p>

                <?php if ($show_brs_extras): ?>
                    <p>
                        <strong>User Score (Average):</strong>
                        <span class="score-pill"><?= htmlspecialchars($user_score ?? '0.00') ?></span>
                    </p>

                    <?php
                        $interp = strtolower(trim((string)($interpretation ?? '')));
                        $interpClass = 'interp-low';
                        if (strpos($interp, 'normal') !== false) $interpClass = 'interp-normal';
                        if (strpos($interp, 'high') !== false) $interpClass = 'interp-high';
                    ?>

                    <p>
                        <strong>Interpretation:</strong>
                        <span class="score-pill <?= $interpClass ?>"><?= htmlspecialchars($interpretation ?? '') ?></span>
                    </p>
                <?php endif; ?>
            </div>

            <!-- BRS Interpretation Table (only for BRS Type 4) -->
            <?php if ($show_brs_extras): ?>
                <div class="brs-interpretation">
                    <h4>BRS Score Interpretation</h4>
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>BRS Score</th>
                                <th>Interpretation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($brs_table) && is_array($brs_table)): ?>
                                <?php foreach ($brs_table as $row): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['range'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($row['label'] ?? '') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="2">No interpretation data.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>
</body>
</html>
