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

											if ($value['IS_EVAL'] == 1 && ($value['TEST_TYPE'] == 4 || $value['TEST_TYPE'] == 5)) { 

												echo '<a href="' . base_url('/my-test/' . $value["TEST_ID"]) . '" class="btn btn-outline-success">Reattempt Test</a>';
												echo '<a href="' . base_url('/my-reports') . '" class="btn btn-outline-primary">See Result</a>';
											
											}elseif ($value['IS_EVAL'] == 1) { 
												echo '<a href="' . base_url('/my-reports') . '" class="btn btn-outline-primary">See Result</a>';
												
											}
											elseif ($value['IS_SUBMITTED'] == 1 && $value['IS_EVAL'] != 1) { 
												echo '<a href="" class="btn btn-outline-primary">Not Evaluated</a>';
											}
											
											
											elseif ($value['IS_SUBMITTED'] != 1 && $value['USER_RESPONSE'] != null) { 
												echo '<a href="' . base_url('/my-test/' . $value["TEST_ID"]) . '" class="btn btn-outline-warning">Resume</a>';
											} elseif ($current_date >= $start_date && $current_date <= $end_date) { 
												echo '<a href="' . base_url('/my-test/' . $value["TEST_ID"]) . '" class="btn btn-outline-success">Start Test</a>';
											} elseif ($current_date > $end_date) { 
												echo '<a href="javascript:void(0)" class="btn btn-outline-danger">Expired</a>';
											} else { 
												echo '<a href="javascript:void(0)" class="btn btn-outline-primary">Upcoming</a>';
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
