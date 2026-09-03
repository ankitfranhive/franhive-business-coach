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
										href="<?= base_url('/training-course-lesson'); ?>"
										role="button"
										
									>
									Back To Course Lesson List
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
                        <form action="<?= base_url('KnowldegeCenterController/createCourseLesson') ?>" method="post" enctype="multipart/form-data">
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Course Lesson Title</label>
								<div class="col-sm-12 col-md-10">
									<input
										class="form-control"
										type="text"
                                        name="TITLE"
										placeholder="Lesson title"
									/>
								</div>
							</div>

							<div class="form-group row">
    <label class="col-sm-12 col-md-2 col-form-label">Lesson For Course</label>
    <div class="col-sm-12 col-md-10">
        <select class="form-control" name="COURSE_ID">
            <option value="">Select a Course</option>
            <?php foreach ($all_course as $course): ?>
                <option value="<?= htmlspecialchars($course['COURSE_ID']) ?>">
                    <?= htmlspecialchars($course['NAME']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>


							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Lesson Content</label>
								<div class="col-sm-12 col-md-10">
									<input
										class="form-control"
										value=""
                                        name="CONTENT"
										type="text"
									/>
								</div>
							</div>
							
							
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Lesson Objective</label>
								<div class="col-sm-12 col-md-10">
									<input
										class="form-control"
										value=""
                                        name="OBJECTIVE"
										type="text"
									/>
								</div>
							</div>
							
							
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Cover Image</label>
								<div class="col-sm-12 col-md-10">
									<input
										class="form-control"
                                        name="THUMNAIL_IMAGE"
										type="file"
									/>
								</div>
							</div>

							<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Lesson Attachments</label>
							<div class="col-sm-12 col-md-10">
								<input
									class="form-control"
									name="ATTACHMENT[]"
									type="file"
									multiple
									accept="application/pdf"
								/>
							</div>
						</div>


							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Lesson Video Links</label>
								<div class="col-sm-12 col-md-10">
									<input
										class="form-control"
										value=""
                                        name="LESSON_VIDEO_LINK"
										type="text"
									/>
								</div>
							</div>

                            <div class="form-group row">
								<!-- <label class="col-sm-12 col-md-2 col-form-label">MarksL</label> -->
								<div class="col-sm-6 col-md-4">
									
                                    <button type="submit" class="btn btn-warning">Add Lesson</button>
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

