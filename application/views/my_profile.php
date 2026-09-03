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
							<h4>Profile</h4>
						</div>
						<nav aria-label="breadcrumb" role="navigation">
							<ol class="breadcrumb">
								<li class="breadcrumb-item">
									<a href="<?= base_url(); ?>">Home</a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">
									Profile
								</li>
							</ol>
						</nav>
					</div>
				</div>
			</div>

			<div class="row">

				<!-- LEFT: Profile Card -->
				<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
					<div class="pd-20 card-box height-100-p">
						<div class="profile-photo text-center">
							<img
								class="avatar-photo"
								src="<?= !empty($user_data['PROFILE_PICTURE']) ? $user_data['PROFILE_PICTURE'] : base_url('assets/images/default-user.png'); ?>"
								alt="Profile Picture"
							/>
						</div>

						<h5 class="text-center h5 mb-0"><?= htmlspecialchars($user_data['NAME'] ?? '') ?></h5>

						<div class="profile-info mt-4">
							<h5 class="mb-20 h5 text-blue">Contact Information</h5>
							<ul>
								<li>
									<span>Email Address:</span>
									<?= htmlspecialchars($user_data['EMAIL'] ?? '') ?>
								</li>
								<li>
									<span>Phone Number:</span>
									<?= htmlspecialchars($user_data['MOBILE'] ?? '') ?>
								</li>
								<li>
									<span>Country:</span>
									<?= htmlspecialchars($user_data['CITY'] ?? '') ?>
								</li>
								<li>
									<span>Address:</span>
									<?= htmlspecialchars($user_data['ADDRESS_LINE_1'] ?? '') ?>
								</li>
							</ul>
						</div>
					</div>
				</div>

				<!-- RIGHT: Tabs -->
				<div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
					<div class="card-box height-100-p overflow-hidden">
						<div class="profile-tab height-100-p">
							<div class="tab height-100-p">

								<ul class="nav nav-tabs customtab" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" data-toggle="tab" href="#timeline" role="tab">Timeline</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tasks" role="tab">Tasks</a>
									</li>
									<li class="nav-item">
										<!-- <a class="nav-link" data-toggle="tab" href="#setting" role="tab">Settings</a> -->
									</li>
								</ul>

								<div class="tab-content">

									<!-- Timeline Tab -->
									<div class="tab-pane fade show active" id="timeline" role="tabpanel">
										<div class="pd-20">
											<div class="profile-timeline"></div>
										</div>
									</div>

									<!-- Tasks Tab -->
									<div class="tab-pane fade" id="tasks" role="tabpanel">
										<div class="pd-20 profile-task-wrap">
											<div class="container pd-0">

												<div class="modal fade customscroll" id="task-add" tabindex="-1" role="dialog">
													<div class="modal-dialog modal-dialog-centered" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title">Tasks Add</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" data-placement="bottom" title="Close Modal">
																	<span aria-hidden="true">&times;</span>
																</button>
															</div>

															<div class="modal-body pd-0">
																<div class="task-list-form">
																	<ul>
																		<li>
																			<form>
																				<div class="form-group row">
																					<label class="col-md-4">Task Type</label>
																					<div class="col-md-8">
																						<input type="text" class="form-control" />
																					</div>
																				</div>

																				<div class="form-group row">
																					<label class="col-md-4">Task Message</label>
																					<div class="col-md-8">
																						<textarea class="form-control"></textarea>
																					</div>
																				</div>

																				<div class="form-group row">
																					<label class="col-md-4">Assigned to</label>
																					<div class="col-md-8">
																						<select class="selectpicker form-control" data-style="btn-outline-primary" title="Not Chosen" multiple="" data-selected-text-format="count" data-count-selected-text="{0} people selected">
																							<option>Ferdinand M.</option>
																							<option>Don H. Rabon</option>
																							<option>Ann P. Harris</option>
																							<option>Katie D. Verdin</option>
																							<option>Christopher S. Fulghum</option>
																							<option>Matthew C. Porter</option>
																						</select>
																					</div>
																				</div>

																				<div class="form-group row mb-0">
																					<label class="col-md-4">Due Date</label>
																					<div class="col-md-8">
																						<input type="text" class="form-control date-picker" />
																					</div>
																				</div>
																			</form>
																		</li>
																	</ul>
																</div>

																<div class="add-more-task">
																	<a href="#" data-toggle="tooltip" data-placement="bottom" title="Add Task">
																		<i class="ion-plus-circled"></i> Add More Task
																	</a>
																</div>
															</div>

															<div class="modal-footer">
																<button type="button" class="btn btn-primary">Add</button>
																<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
															</div>

														</div>
													</div>
												</div>

											</div>
										</div>
									</div>

									<!-- Settings Tab -->
									<div class="tab-pane fade height-100-p" id="setting" role="tabpanel">
										<div class="profile-setting">
											<form>
												<ul class="profile-edit-list row">
													<li class="weight-500 col-md-6">
														<h4 class="text-blue h5 mb-20">Edit Your Personal Setting</h4>

														<div class="form-group">
															<label>Full Name</label>
															<input class="form-control form-control-lg" type="text" value="<?= htmlspecialchars($user_data['NAME'] ?? '') ?>" />
														</div>

														<div class="form-group">
															<label>Email</label>
															<input class="form-control form-control-lg" type="email" value="<?= htmlspecialchars($user_data['EMAIL'] ?? '') ?>" />
														</div>

														<div class="form-group">
															<label>Date of birth</label>
															<input class="form-control form-control-lg date-picker" type="text" />
														</div>

														<div class="form-group">
															<label>Gender</label>
															<div class="d-flex">
																<div class="custom-control custom-radio mb-5 mr-20">
																	<input type="radio" id="genderMale" name="gender" class="custom-control-input" />
																	<label class="custom-control-label weight-400" for="genderMale">Male</label>
																</div>
																<div class="custom-control custom-radio mb-5">
																	<input type="radio" id="genderFemale" name="gender" class="custom-control-input" />
																	<label class="custom-control-label weight-400" for="genderFemale">Female</label>
																</div>
															</div>
														</div>

														<div class="form-group">
															<label>Country</label>
															<select class="selectpicker form-control form-control-lg" data-style="btn-outline-secondary btn-lg" title="Not Chosen">
																<option>United States</option>
																<option>India</option>
																<option>United Kingdom</option>
															</select>
														</div>

														<div class="form-group">
															<label>State/Province/Region</label>
															<input class="form-control form-control-lg" type="text" />
														</div>

														<div class="form-group">
															<label>Postal Code</label>
															<input class="form-control form-control-lg" type="text" />
														</div>

														<div class="form-group">
															<label>Phone Number</label>
															<input class="form-control form-control-lg" type="text" />
														</div>

														<div class="form-group">
															<label>Address</label>
															<textarea class="form-control"></textarea>
														</div>

														<div class="form-group">
															<div class="custom-control custom-checkbox mb-5">
																<input type="checkbox" class="custom-control-input" id="customCheck1-1" />
																<label class="custom-control-label weight-400" for="customCheck1-1">
																	I agree to receive notification emails
																</label>
															</div>
														</div>

														<div class="form-group mb-0">
															<input type="submit" class="btn btn-primary" value="Update Information" />
														</div>
													</li>
												</ul>
											</form>
										</div>
									</div>

								</div><!-- tab-content -->
							</div><!-- tab -->
						</div><!-- profile-tab -->
					</div><!-- card-box -->
				</div><!-- col -->
			</div><!-- row -->
		</div><!-- min-height -->
	</div><!-- pd -->
</div><!-- main-container -->

</body>
<?php $this->load->view('includes/footer'); ?>
</html>
