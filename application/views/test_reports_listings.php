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
									<h4>Listing of all Test Reports</h4>
								</div>
								
							</div>
							<div class="col-md-6 col-sm-12 text-right">

                            <!-- <a
										class="btn btn-warning"
										href="<?= base_url('/add-test'); ?>"
										role="button"
										
									>
										Add new Test
									</a> -->
								
							</div>
						</div>
					</div>
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<!-- <h4 class="text-blue h4">Listing of all Test</h4> -->
							
						</div>
						<div class="pb-20">
							<table class="data-table table stripe hover nowrap">
							<thead>
									<tr>
										<th>Response ID</th>
										<th>Test Name</th>
										<th>User Name</th>
										<th>Attempts</th>
										<th>Total Marks</th>
										<th>Marks Obtained (Latest)</th>
										<th>Submit Date (Latest)</th>
										<th>Actions</th>
									</tr>
									</thead>
									<tbody>
<?php foreach ($tests as $t): ?>
    <?php
        $testType   = (int)$t->TEST_TYPE;
        $isEval     = (int)$t->IS_EVAL;
        $isSub      = (int)$t->IS_SUBMITTED;
        $responseId = (int)$t->RESPONSE_ID;
        $attempts   = (int)$t->ATTEMPTS;
    ?>
    <tr>
        <td><?= $responseId ?></td>
        <td><?= htmlspecialchars($t->TEST_NAME) ?></td>
        <td><?= htmlspecialchars($t->USER_NAME) ?></td>
        <td><?= $attempts ?></td>
        <td><?= (int)($t->TOTAL_MARKS ?? 0) ?></td>
        <td><?= (int)($t->MARKS_OBTAINED ?? 0) ?></td>
        <td><?= htmlspecialchars($t->SUBMIT_DATE ?? '') ?></td>

        <td>
            <?php if ($attempts === 0 || $responseId === 0): ?>
                <button class="btn btn-warning btn-sm" disabled>Not Submitted</button>

            <?php elseif ($isEval === 1): ?>
                <a href="<?= base_url('eval/' . $responseId) ?>" class="btn btn-success btn-sm">Evaluated</a>

            <?php elseif ($isEval !== 1 && $testType === 1): ?>
                <a href="<?= base_url('eval/' . $responseId) ?>" class="btn btn-info btn-sm">See Report</a>

            <?php elseif ($isEval !== 1 && $testType === 3): ?>
                <a href="<?= base_url('eval/' . $responseId) ?>" class="btn btn-info btn-sm">View Result</a>

            <?php else: ?>
                <a href="<?= base_url('eval/' . $responseId) ?>" class="btn btn-primary btn-sm">Evaluate</a>
            <?php endif; ?>

			<a href="<?= base_url('admin-test-report/' . $t->TEST_ID . '/' . $t->USER_ID) ?>" class="btn btn-outline-secondary btn-sm">
  Attempts
</a>
        </td>
    </tr>
<?php endforeach; ?>
</tbody>

							</table>
						</div>
					</div>
					<!-- Simple Datatable End -->
					
				</div>
				
			</div>
		</div>


</body>
	<?php $this->load->view('includes/footer'); ?>
</html>

