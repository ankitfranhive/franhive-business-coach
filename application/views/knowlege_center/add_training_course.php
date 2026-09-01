<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>



<div class="mobile-menu-overlay"></div>

   <div class="main-container">
		<div class="pd-ltr-20 xs-pd-20-10">
		<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Add A new Training Course</h4>
								</div>
								
							</div>
							<div class="col-md-6 col-sm-12 text-right">

                            <a
										class="btn btn-warning"
										href="<?= base_url('/knowledge-center'); ?>"
										role="button"
										
									>
									Back To Course List
									</a>
								
							</div>
						</div>
					</div>
        <div class="pd-20 card-box mb-30">
						<div class="clearfix">
							<div class="pull-left">
								<!-- <h4 class="text-blue h4">Add A new Question</h4> -->
								
							</div>
							
						</div>
                        <form action="<?= base_url('KnowldegeCenterController/createCourse') ?>" method="post" enctype="multipart/form-data">
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Course Title</label>
								<div class="col-sm-12 col-md-10">
									<input
										class="form-control"
										type="text"
                                        name="name"
										placeholder="Course title"
									/>
								</div>
							</div>
							
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Objective</label>
								<div class="col-sm-12 col-md-10">
									<input
										class="form-control"
										value=""
                                        name="objective"
										type="text"
									/>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Registration Link</label>
								<div class="col-sm-12 col-md-10">
									<input
										class="form-control"
										value=""
                                        name="registration_link"
										type="text"
									/>
								</div>
							</div>
							
							
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Cover Image</label>
								<div class="col-sm-12 col-md-10">
									<input
										class="form-control"
										value=""
                                        name="COVER_IMAGE"
										type="file"
										accept="image/*"
									/>
								</div>
							</div>

                            <div class="form-group row">
								<!-- <label class="col-sm-12 col-md-2 col-form-label">MarksL</label> -->
								<div class="col-sm-6 col-md-4">
									
                                    <button type="submit" class="btn btn-warning">Add Course</button>
								</div>
							</div>
							
						</form>
                      
					</div>
					<!-- Default Basic Forms End -->
                    
                   
		</div>
    </div>


</body>
	<?php $this->load->view('includes/footer'); ?>
</html>

