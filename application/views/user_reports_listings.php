<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Your Test Reports</h4>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right">
                        <!-- Buttons if needed later -->
                    </div>
                </div>
            </div>

            <div class="card-box mb-30">
                <div class="pd-20"></div>

                <div class="pb-20">
                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Test Name</th>
                                <th>User Name</th>
                                <th>Submit Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
<?php $sr = 1; ?>
<?php foreach ($my_test_reports as $r): ?>
    <?php
        $testType = (int)($r['TEST_TYPE'] ?? 0);
        $isEval   = (int)($r['IS_EVAL'] ?? 0);
        $isSub    = (int)($r['IS_SUBMITTED'] ?? 0);

        $testId     = (int)($r['TEST_ID'] ?? 0);
        $responseId = (int)($r['RESPONSE_ID'] ?? 0);
        $attempts   = (int)($r['ATTEMPTS'] ?? 0);

        $submitDate = $r['SUBMIT_DATE'] ?? ''; // from tur_latest.updated_at
    ?>

    <tr>
        <td><?= $sr++ ?></td>

        <td>
            <?= htmlspecialchars($r['TEST_NAME'] ?? '') ?>
            <?php if ($attempts > 0): ?>
                <div style="font-size:12px;color:#666;">Attempts: <?= $attempts ?></div>
            <?php endif; ?>
        </td>

        <td><?= htmlspecialchars($r['USER_NAME'] ?? '') ?></td>

        <td><?= htmlspecialchars($submitDate) ?></td>

        <td>
            <!-- ✅ TEST TYPE 1 -->
            <?php if ($testType === 1): ?>

                <?php if ($isEval === 1): ?>
                    <button class="btn btn-warning">Connect your coach</button>
                <?php else: ?>
                    <button class="btn btn-primary">Not Evaluated</button>
                <?php endif; ?>

            <!-- ✅ TEST TYPE 4 & 5 -->
            <?php elseif (in_array($testType, [4, 5], true)): ?>

                <?php if ($attempts === 0): ?>
                    <button class="btn btn-warning">Not Submitted</button>

                <?php elseif ($isEval === 1): ?>
                    <!-- ✅ View all attempts report page -->
                    <a href="<?= base_url('my-test-report/' . $testId) ?>" class="btn btn-info">
                        See Report
                    </a>

                    <!-- ✅ Download latest attempt PDF (optional here) -->
                    <?php if ($responseId > 0): ?>
                        <a href="<?= base_url('my-test-report-pdf/' . $responseId) ?>" class="btn btn-outline-primary" style="margin-left:6px;">
                            Download PDF
                        </a>
                    <?php endif; ?>

                <?php elseif ($isEval === 0 && $isSub === 1): ?>
                    <button class="btn btn-primary">Not Evaluated</button>

                <?php else: ?>
                    <button class="btn btn-warning">Not Submitted</button>
                <?php endif; ?>

            <!-- ✅ TEST TYPE 2 & 3 -->
            <?php elseif (in_array($testType, [2, 3], true)): ?>

                <?php if ($attempts === 0): ?>
                    <button class="btn btn-warning">Not Submitted</button>
                <?php elseif ($isEval === 1): ?>
                    <!-- keep your qualified logic ONLY if these columns exist in your new query -->
                    <button class="btn btn-success">Evaluated</button>
                <?php elseif ($isEval === 0 && $isSub === 1): ?>
                    <button class="btn btn-primary">Not Evaluated</button>
                <?php else: ?>
                    <button class="btn btn-warning">Not Submitted</button>
                <?php endif; ?>

            <!-- ✅ OTHER TYPES -->
            <?php else: ?>

                <?php if ($attempts === 0): ?>
                    <button class="btn btn-warning">Not Submitted</button>
                <?php elseif ($isEval === 1): ?>
                    <button class="btn btn-success">Evaluated</button>
                <?php elseif ($isEval === 0 && $isSub === 1): ?>
                    <button class="btn btn-primary">Not Evaluated</button>
                <?php else: ?>
                    <button class="btn btn-warning">Not Submitted</button>
                <?php endif; ?>

            <?php endif; ?>
        </td>
    </tr>
<?php endforeach; ?>
</tbody>


                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
<?php $this->load->view('includes/footer'); ?>
</html>
