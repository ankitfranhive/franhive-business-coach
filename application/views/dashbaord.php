<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>
		<div class="mobile-menu-overlay"></div>

		<div class="main-container">
			<div class="xs-pd-20-10 pd-ltr-20">
				<div class="title pb-20">
					<h2 class="h3 mb-0">Time Zone </h2>
					<p><?php echo 'Current Time Zone: ' . date_default_timezone_get() . '<br>';
							echo 'Current Time: ' . date('Y-m-d H:i:s');
							?></p>
				</div>

				<div class="row pb-10">
					<div class="col-xl-3 col-lg-3 col-md-6 mb-20">
						<div class="card-box height-100-p widget-style3">
							<div class="d-flex flex-wrap">
								<div class="widget-data">
									<div class="weight-700 font-24 text-dark">75</div>
									<div class="font-14 text-secondary weight-500">
										Test Task Created
									</div>
								</div>
								<div class="widget-icon">
									<div class="icon" data-color="#00eccf">
										<i class="icon-copy dw dw-calendar1"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-lg-3 col-md-6 mb-20">
						<div class="card-box height-100-p widget-style3">
							<div class="d-flex flex-wrap">
								<div class="widget-data">
									<div class="weight-700 font-24 text-dark">124,551</div>
									<div class="font-14 text-secondary weight-500">
										Total Tasks Closed
									</div>
								</div>
								<div class="widget-icon">
									<div class="icon" data-color="#ff5b5b">
										<span class="icon-copy ti-heart"></span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-lg-3 col-md-6 mb-20">
						<div class="card-box height-100-p widget-style3">
							<div class="d-flex flex-wrap">
								<div class="widget-data">
									<div class="weight-700 font-24 text-dark">400+</div>
									<div class="font-14 text-secondary weight-500">
										Total Campaign Created
									</div>
								</div>
								<div class="widget-icon">
									<div class="icon">
										<i
											class="icon-copy fa fa-stethoscope"
											aria-hidden="true"
										></i>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-lg-3 col-md-6 mb-20">
						<div class="card-box height-100-p widget-style3">
							<div class="d-flex flex-wrap">
								<div class="widget-data">
									<div class="weight-700 font-24 text-dark">599</div>
									<div class="font-14 text-secondary weight-500">Total Leads Closed</div>
								</div>
								<div class="widget-icon">
									<div class="icon" data-color="#09cc06">
										<i class="icon-copy fa fa-money" aria-hidden="true"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- <div class="row pb-10">
					<div class="col-md-8 mb-20">
						<div class="card-box height-100-p pd-20">
							<div
								class="d-flex flex-wrap justify-content-between align-items-center pb-0 pb-md-3"
							>
								<div class="h5 mb-md-0">Users Activities</div>
								<div class="form-group mb-md-0">
									<select class="form-control form-control-sm selectpicker">
										<option value="">Last Week</option>
										<option value="">Last Month</option>
										<option value="">Last 6 Month</option>
										<option value="">Last 1 year</option>
									</select>
								</div>
							</div>
							<div id="activities-chart"></div>
						</div>
					</div>
					<div class="col-md-4 mb-20">
						<div
							class="card-box min-height-200px pd-20 mb-20"
							data-bgcolor="#455a64"
						>
							<div class="d-flex justify-content-between pb-20 text-white">
								<div class="icon h1 text-white">
									<i class="fa fa-calendar" aria-hidden="true"></i>
									 <i class="icon-copy fa fa-stethoscope" aria-hidden="true"></i> 
								</div>
								<div class="font-14 text-right">
									<div><i class="icon-copy ion-arrow-up-c"></i> 2.69%</div>
									<div class="font-12">Since last month</div>
								</div>
							</div>
							<div class="d-flex justify-content-between align-items-end">
								<div class="text-white">
									<div class="font-14">Tests</div>
									<div class="font-24 weight-500">1865</div>
								</div>
								<div class="max-width-150">
									<div id="appointment-chart"></div>
								</div>
							</div>
						</div>
						<div class="card-box min-height-200px pd-20" data-bgcolor="#265ed7">
							<div class="d-flex justify-content-between pb-20 text-white">
								<div class="icon h1 text-white">
									<i class="fa fa-stethoscope" aria-hidden="true"></i>
								</div>
								<div class="font-14 text-right">
									<div><i class="icon-copy ion-arrow-down-c"></i> 3.69%</div>
									<div class="font-12">Since last month</div>
								</div>
							</div>
							<div class="d-flex justify-content-between align-items-end">
								<div class="text-white">
									<div class="font-14">Completed</div>
									<div class="font-24 weight-500">250</div>
								</div>
								<div class="max-width-150">
									<div id="surgery-chart"></div>
								</div>
							</div>
						</div>
					</div>
				</div> -->


				<div class="row pb-10">
					<div class="col-md-8 mb-20">
						<?php if (SUBDOMAIN == "demo"){ ?>
								<img src="https://demo.franhive.com/uploads/1732134458_dashboard_banner_fh.jpg" alt="franhive banner">

						<?php }else{ ?>
							<img src="<?= base_url('vendors/images/banner_image.jpeg'); ?>" alt="" />
						<?php } ?>
						
						
						
				
				

					</div>
					<div class="col-md-4 mb-20">
					</br>
					</br>
					<h5 style="text-align:center">It’s time to play big by building an Impactful & Purposeful Business!!</h5>
					</br>
					<p style="text-align:center">I’m really humbled and excited to be on this journey with you.</p>
					<p style="text-align:center">You have embarked on a journey of growth and your decision has already set you in the direction that you are destined for.</p>
					<!-- <p>I want to acknowledge you for all the steps you’ve taken to be here and grow within yourself to be able to face the uncertainty of the business world and have a long-term perspective for your future success.

					We are so excited to have you here and we look forward to giving you a truly life-changing experience that you will cherish forever.
					Love,</p> -->
					</br>
					</br>
					<h2 style="text-align:center">Barinderjeet Kaur</h2>

					<h5 style="text-align:center">Human Behaviour Specialist & Business Coach</h5>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-4 col-md-6 mb-20">
						<div class="card-box height-100-p pd-20 min-height-200px">
							<div class="d-flex justify-content-between pb-10">
								<div class="h5 mb-0">Top Performers</div>
								<div class="dropdown">
									<a
										class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
										data-color="#1b3133"
										href="#"
										role="button"
										data-toggle="dropdown"
									>
										<i class="dw dw-more"></i>
									</a>
									<div
										class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list"
									>
										<a class="dropdown-item" href="#"
											><i class="dw dw-eye"></i> View</a
										>
										<a class="dropdown-item" href="#"
											><i class="dw dw-edit2"></i> Edit</a
										>
										<a class="dropdown-item" href="#"
											><i class="dw dw-delete-3"></i> Delete</a
										>
									</div>
								</div>
							</div>
							<div class="user-list">
								<ul>
									<li class="d-flex align-items-center justify-content-between">
										<div class="name-avatar d-flex align-items-center pr-2">
											<div class="avatar mr-2 flex-shrink-0">
												<img
													src="vendors/images/photo1.jpg"
													class="border-radius-100 box-shadow"
													width="50"
													height="50"
													alt=""
												/>
											</div>
											<div class="txt">
												<span
													class="badge badge-pill badge-sm"
													data-bgcolor="#e7ebf5"
													data-color="#265ed7"
													>4.9</span
												>
												<div class="font-14 weight-600"> Neil Wagner</div>
												<div class="font-12 weight-500" data-color="#b2b1b6">
													EYD User
												</div>
											</div>
										</div>
										<div class="cta flex-shrink-0">
											<a href="#" class="btn btn-sm btn-outline-primary"
												>Report</a
											>
										</div>
									</li>
									<li class="d-flex align-items-center justify-content-between">
										<div class="name-avatar d-flex align-items-center pr-2">
											<div class="avatar mr-2 flex-shrink-0">
												<img
													src="vendors/images/photo2.jpg"
													class="border-radius-100 box-shadow"
													width="50"
													height="50"
													alt=""
												/>
											</div>
											<div class="txt">
												<span
													class="badge badge-pill badge-sm"
													data-bgcolor="#e7ebf5"
													data-color="#265ed7"
													>4.9</span
												>
												<div class="font-14 weight-600"> Ren Delan</div>
												<div class="font-12 weight-500" data-color="#b2b1b6">
													EYD User
												</div>
											</div>
										</div>
										<div class="cta flex-shrink-0">
											<a href="#" class="btn btn-sm btn-outline-primary"
												>Report</a
											>
										</div>
									</li>
									<li class="d-flex align-items-center justify-content-between">
										<div class="name-avatar d-flex align-items-center pr-2">
											<div class="avatar mr-2 flex-shrink-0">
												<img
													src="vendors/images/photo3.jpg"
													class="border-radius-100 box-shadow"
													width="50"
													height="50"
													alt=""
												/>
											</div>
											<div class="txt">
												<span
													class="badge badge-pill badge-sm"
													data-bgcolor="#e7ebf5"
													data-color="#265ed7"
													>4.9</span
												>
												<div class="font-14 weight-600"> Garrett Kincy</div>
												<div class="font-12 weight-500" data-color="#b2b1b6">
													EYD User
												</div>
											</div>
										</div>
										<div class="cta flex-shrink-0">
											<a href="#" class="btn btn-sm btn-outline-primary"
												>Report</a
											>
										</div>
									</li>
									<li class="d-flex align-items-center justify-content-between">
										<div class="name-avatar d-flex align-items-center pr-2">
											<div class="avatar mr-2 flex-shrink-0">
												<img
													src="vendors/images/photo4.jpg"
													class="border-radius-100 box-shadow"
													width="50"
													height="50"
													alt=""
												/>
											</div>
											<div class="txt">
												<span
													class="badge badge-pill badge-sm"
													data-bgcolor="#e7ebf5"
													data-color="#265ed7"
													>4.9</span
												>
												<div class="font-14 weight-600"> Callie Reed</div>
												<div class="font-12 weight-500" data-color="#b2b1b6">
													EYD User
												</div>
											</div>
										</div>
										<div class="cta flex-shrink-0">
											<a href="#" class="btn btn-sm btn-outline-primary"
												>Report</a
											>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 mb-20">
						<div class="card-box height-100-p pd-20 min-height-200px">
							<div class="d-flex justify-content-between">
								<div class="h5 mb-0">Test Report</div>
								<div class="dropdown">
									<a
										class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
										data-color="#1b3133"
										href="#"
										role="button"
										data-toggle="dropdown"
									>
										<i class="dw dw-more"></i>
									</a>
									<div
										class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list"
									>
										<a class="dropdown-item" href="#"
											><i class="dw dw-eye"></i> View</a
										>
										<a class="dropdown-item" href="#"
											><i class="dw dw-edit2"></i> Edit</a
										>
										<a class="dropdown-item" href="#"
											><i class="dw dw-delete-3"></i> Delete</a
										>
									</div>
								</div>
							</div>

							<div id="Test-chart"></div>
						</div>
					</div>
					<div class="col-lg-4 col-md-12 mb-20">
						<div class="card-box height-100-p pd-20 min-height-200px">
							<div class="max-width-300 mx-auto">
								<img src="vendors/images/upgrade.svg" alt="" />
							</div>
							<div class="text-center">
								<div class="h5 mb-1">Today's Maked Tasks</div>
								<div
									class="font-14 weight-500 max-width-200 mx-auto pb-20"
									data-color="#a6a6a7"
								>
									There is no Task Created For Today.
								</div>
								<a href="#" class="btn btn-primary btn-lg">Create Task</a>
							</div>
						</div>
					</div>
				</div>

			

				<div class="title pb-20 pt-20">
					<h2 class="h3 mb-0">Quick Start</h2>
				</div>

				<div class="row">
					<div class="col-md-4 mb-20">
						<a href="#" class="card-box d-block mx-auto pd-20 text-secondary">
							<div class="img pb-30">
								<img src="vendors/images/medicine-bro.svg" alt="" />
							</div>
							<div class="content">
								<h3 class="h4">Services</h3>
								<p class="max-width-200">
									We provide superior health care in a compassionate maner
								</p>
							</div>
						</a>
					</div>
					<div class="col-md-4 mb-20">
						<a href="#" class="card-box d-block mx-auto pd-20 text-secondary">
							<div class="img pb-30">
								<img src="vendors/images/remedy-amico.svg" alt="" />
							</div>
							<div class="content">
								<h3 class="h4">Medications</h3>
								<p class="max-width-200">
									Look for prescription and over-the-counter drug information.
								</p>
							</div>
						</a>
					</div>
					<div class="col-md-4 mb-20">
						<a href="#" class="card-box d-block mx-auto pd-20 text-secondary">
							<div class="img pb-30">
								<img src="vendors/images/paper-map-cuate.svg" alt="" />
							</div>
							<div class="content">
								<h3 class="h4">Locations</h3>
								<p class="max-width-200">
									Convenient locations when and where you need them.
								</p>
							</div>
						</a>
					</div>
				</div>

			</div>
		</div>
		
		<!-- Font Awesome (for icons) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<!-- Bootstrap Modal -->
<div class="modal fade" id="welcomeModal" tabindex="-1" role="dialog" aria-labelledby="welcomeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="welcomeModalLabel">Welcome Note</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span> <!-- Cross button -->
        </button>
      </div>
	  <?php if (!empty($welcome_note_text)): ?>
<div class="modal fade" id="welcomeModal" tabindex="-1" role="dialog" aria-labelledby="welcomeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-body">
        <?= $welcome_note_text; ?>
        <p>
          <a href="mailto:nlp@empoweryourdestiny.com.au" target="_blank" rel="noopener noreferrer">
            <i class="fas fa-envelope"></i> Email us at: nlp@empoweryourdestiny.com.au
          </a>
        </p>
        <p>
          <a href="http://m.me/empoweryourdestiny" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-facebook-messenger"></i> Chat with us on Messenger
          </a>
        </p>
        <p>
          <a href="https://chat.whatsapp.com/Lx5xjiM1m2F4caEhAqlXPr?fbclid=IwZXh0bgNhZW0CMTEAAR7SNBajR3ml7YIGetMP5DsXKPNygY5WolXqWBeTq-PEtP3XGlIJs414Ij0ZFw_aem_P9N74qpaTumgU4dInFoVmQ" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-whatsapp"></i> Chat with us on WhatsApp
          </a>
        </p>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
      </div>

    </div>
  </div>
</div>
<?php endif; ?>



<!-- jQuery and Bootstrap JS (if not already included) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Show the Modal on Page Load -->
<script>
$(document).ready(function() {
    $('#welcomeModal').modal('show');
});
</script>

		
		
	</body>
	<?php $this->load->view('includes/footer'); ?>
</html>
