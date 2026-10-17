<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>
		<div class="mobile-menu-overlay"></div>

		<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="title">
									<h4>My Tests</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="/eyd">Home</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											My Tests
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
					<div class="product-wrap">
					<div class="product-list">
						<ul class="row">
							<?php foreach ($my_tests as $key => $value) { ?>
								<li class="col-lg-4 col-md-6 col-sm-12">
									<div class="product-box">
										<div class="producct-img">
											<!-- <img src="vendors/images/product-img1.jpg" alt="" /> -->
										</div>
										<div class="product-caption">
											<h3><?= $value['TEST_NAME'] ?></h3>
											<div class="price">Total Questions: <?= $value['TOTAL_QUESTIONS'] ?></div>
											<div class="price">Start Date: <?= $value['TEST_START_DATE'] ?></div>
											<div class="price">End Date: <?= $value['END_DATE'] ?></div>

											<?php
											$start_date = strtotime($value['TEST_START_DATE']);
											$end_date = strtotime($value['END_DATE']);
											$current_date = time();
											$in_window = ($current_date >= $start_date && $current_date <= $end_date);

											$test_type = (int)($value['TEST_TYPE'] ?? 0);
											$is_eval = (int)($value['IS_EVAL'] ?? $value['is_eval'] ?? 0);
											$is_sub = (int)($value['IS_SUBMITTED'] ?? $value['is_submitted'] ?? 0);
											$test_id = (int)$value['TEST_ID'];
											$can_reattempt = ((int)($value['ALLOW_REATTEMPT'] ?? 0) === 1)
												|| ((int)($value['HAS_REATTEMPT_GRANT'] ?? 0) === 1);

											$result_url = in_array($test_type, [4, 5], true)
												? base_url('my-test-report/' . $test_id)
												: base_url('my-reports');
											$btn = 'margin:4px;';

											if ($can_reattempt && $in_window) {
												echo '<a href="' . base_url('my-test/' . $test_id) . '" class="btn btn-outline-success" style="' . $btn . '">Reattempt Test</a>';
												if ($is_eval === 1) {
													echo '<a href="' . html_escape($result_url) . '" class="btn btn-outline-primary" style="' . $btn . '">See Result</a>';
												} elseif ($is_sub === 1) {
													echo '<span class="btn btn-outline-secondary" style="' . $btn . 'opacity:0.85;">Not Evaluated (previous attempt)</span>';
												}
											} elseif ($is_eval === 1) {
												echo '<a href="' . html_escape($result_url) . '" class="btn btn-outline-primary" style="' . $btn . '">See Result</a>';
											} elseif ($is_sub === 1) {
												echo '<span class="btn btn-outline-secondary" style="' . $btn . '">Not Evaluated</span>';
											} elseif ($value['USER_RESPONSE'] != null && $is_sub !== 1) {
												echo '<a href="' . base_url('my-test/' . $test_id) . '" class="btn btn-outline-warning" style="' . $btn . '">Resume</a>';
											} elseif ($in_window) {
												echo '<a href="' . base_url('my-test/' . $test_id) . '" class="btn btn-outline-success" style="' . $btn . '">Take the Test</a>';
											} elseif ($current_date > $end_date) {
												echo '<span class="btn btn-outline-danger" style="' . $btn . '">Expired</span>';
											} else {
												echo '<span class="btn btn-outline-primary" style="' . $btn . '">Upcoming</span>';
											}
											?>

										</div>
									</div>
								</li>
							<?php } ?>
						</ul>
					</div>

						
					</div>
				</div>
				
			</div>
		</div>
		
		<!-- js -->
		<script src="vendors/scripts/core.js"></script>
		<script src="vendors/scripts/script.min.js"></script>
		<script src="vendors/scripts/process.js"></script>
		<script src="vendors/scripts/layout-settings.js"></script>
		<!-- Google Tag Manager (noscript) -->
		<noscript
			><iframe
				src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS"
				height="0"
				width="0"
				style="display: none; visibility: hidden"
			></iframe
		></noscript>
		<!-- End Google Tag Manager (noscript) -->
	</body>
</html>
