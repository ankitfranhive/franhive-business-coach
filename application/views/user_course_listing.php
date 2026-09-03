<!DOCTYPE html>
<html lang="en">
<head>
	<?php $this->load->view('includes/header'); ?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
	<div class="mobile-menu-overlay"></div>

	<div class="main-container">
		<div class="pd-ltr-20 xs-pd-20-10">
			<div class="min-height-200px">

				<div class="page-header">
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="title">
								<h4>My COURSES</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item">
										<a href="/eyd">Home</a>
									</li>
									<li class="breadcrumb-item active" aria-current="page">
										My Courses
									</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>

				<div class="product-wrap">
					<div class="product-list">
						<ul class="row list-unstyled">
							<?php if (!empty($my_courses)) : ?>
								<?php foreach ($my_courses as $key => $value) : ?>
									<li class="col-lg-4 col-md-6 col-sm-12 mb-4">
										<div class="product-box h-100">
											<div class="producct-img"></div>

											<div class="product-caption">
												<?php if (!empty($value['THUMNAIL_IMAGE'])) : ?>
													<img 
														src="<?= htmlspecialchars($value['THUMNAIL_IMAGE']); ?>" 
														alt="<?= !empty($value['NAME']) ? htmlspecialchars($value['NAME']) : 'Course Thumbnail'; ?>"
														style="width: 330px; height: 130px; object-fit: cover;"
														loading="lazy"
													>
													<br><br>
												<?php endif; ?>

												<h3>
													<?= !empty($value['NAME']) ? htmlspecialchars($value['NAME']) : 'Untitled Course'; ?>
												</h3>

												<div class="price">
													OBJECTIVE:
													<?= !empty($value['OBJECTIVE']) ? htmlspecialchars($value['OBJECTIVE']) : 'N/A'; ?>
												</div>

												<div class="d-flex justify-content-between mt-3">
													<a href="<?= base_url('my-lessons/' . $value['COURSE_ID']); ?>" class="btn btn-outline-success">
														Start Learning
													</a>

													<?php if (!empty($value['REGISTRARTION_LINK'])) : ?>
														<a href="<?= htmlspecialchars($value['REGISTRARTION_LINK']); ?>" class="btn btn-outline-primary" target="_blank" rel="noopener noreferrer">
															Register
														</a>
													<?php endif; ?>
												</div>
											</div>
										</div>
									</li>
								<?php endforeach; ?>
							<?php else : ?>
								<li class="col-12">
									<div class="alert alert-info">No courses found.</div>
								</li>
							<?php endif; ?>
						</ul>
					</div>
				</div>

			</div>
		</div>
	</div>

	<?php if (!empty($welcome_note_text_new)) : ?>
		<div class="modal fade" id="welcomeModal" tabindex="-1" role="dialog" aria-labelledby="welcomeModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				<div class="modal-content">

					<div class="modal-header">
						<h4 class="modal-title" id="welcomeModalLabel">Welcome Note</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						<?= $welcome_note_text_new; ?>

						<hr>

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

	<!-- JS -->
	<script src="<?= base_url('vendors/scripts/core.js'); ?>"></script>
	<script src="<?= base_url('vendors/scripts/script.min.js'); ?>"></script>
	<script src="<?= base_url('vendors/scripts/process.js'); ?>"></script>
	<script src="<?= base_url('vendors/scripts/layout-settings.js'); ?>"></script>

	<?php if (!empty($welcome_note_text_new)) : ?>
	<script>
		$(document).ready(function () {
			$('#welcomeModal').modal('show');
		});
	</script>
	<?php endif; ?>

	<!-- Google Tag Manager (noscript) -->
	<noscript>
		<iframe
			src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS"
			height="0"
			width="0"
			style="display:none;visibility:hidden"
		></iframe>
	</noscript>
	<!-- End Google Tag Manager (noscript) -->
</body>
</html>