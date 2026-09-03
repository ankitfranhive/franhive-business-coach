<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">

            <div class="page-header">
                <div class="row">
                    <div class="col-md-8 col-sm-12">
                        <div class="title">
                            <h4>Attempts - <?= htmlspecialchars($test_name ?? '') ?></h4>
                            <div style="color:#666;font-size:13px;margin-top:4px;">
                                User: <strong><?= htmlspecialchars($user_name ?? '') ?></strong>
                                &nbsp; | &nbsp;
                                Test ID: <?= (int)($test_id ?? 0) ?>
                                &nbsp; | &nbsp;
                                User ID: <?= (int)($user_id ?? 0) ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12 text-right">
                        <a href="<?= base_url('all-test-reports') ?>" class="btn btn-outline-secondary btn-sm">
                            Back
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-box mb-30">
                <div class="pd-20"></div>

                <div class="pb-20">
                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            <tr>
                                <th>Attempt (Response ID)</th>
                                <th>Submit Date</th>
                                <th>Submitted</th>
                                <th>Evaluated</th>
                                <th>Total Marks</th>
                                <th>Marks Obtained</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($attempts as $a): ?>
                            <?php
                                $responseId = (int)$a->ID;
                                $isSub = (int)($a->IS_SUBMITTED ?? 0);
                                $isEval = (int)($a->IS_EVAL ?? 0);
                                $totalMarks = (int)($a->TOTAL_MARKS ?? 0);
                                $marksObt = (int)($a->MARKS_OBTAINED ?? 0);
                                $submitDate = $a->updated_at ?? ($a->UPDATED_AT ?? '');
                            ?>
                            <tr>
                                <td><?= $responseId ?></td>
                                <td><?= htmlspecialchars((string)$submitDate) ?></td>

                                <td>
                                    <?php if ($isSub === 1): ?>
                                        <span class="badge badge-success">Yes</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">No</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if ($isEval === 1): ?>
                                        <span class="badge badge-success">Yes</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">No</span>
                                    <?php endif; ?>
                                </td>

                                <td><?= $totalMarks ?></td>
                                <td><?= $marksObt ?></td>

                                <td>
                                    <?php if ($isSub !== 1): ?>
                                        <button class="btn btn-warning btn-sm" disabled>Not Submitted</button>
                                    <?php else: ?>

                                        <?php if ($isEval === 1): ?>
                                            <a href="<?= base_url('eval/' . $responseId) ?>" class="btn btn-success btn-sm">
                                                Evaluated
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= base_url('eval/' . $responseId) ?>" class="btn btn-primary btn-sm">
                                                Evaluate
                                            </a>
                                        <?php endif; ?>

                                        <!-- ✅ PDF per attempt -->
                                        <a href="<?= base_url('my-test-report-pdf/' . $responseId) ?>"
                                           class="btn btn-outline-primary btn-sm" style="margin-left:6px;">
                                            PDF
                                        </a>

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
