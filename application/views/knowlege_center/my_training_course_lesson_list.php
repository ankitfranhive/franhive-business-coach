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
					<!-- <a class="btn btn-warning" href=" <?= $lesson['REGISTRARTION_LINK'] ?>" role="button">
							Registration Link
						</a> -->

						<div class="title">
                            <h4>Listing of Your Lessons</h4>
                        </div>

					</div>
					<div class="col-md-6 col-sm-12 text-right">

						<a class="btn btn-warning" href="<?= base_url('/user-test'); ?>" role="button">
							Go To MY Task
						</a>

					</div>
				</div>

                <!-- <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Listing of Your Lessons</h4>
                        </div>
                    </div>
                </div> -->
            </div>
            
            <div class="row d-flex flex-wrap justify-content-center">
                <?php foreach ($all_course_lesson as $lesson): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="lesson-card p-3 shadow-sm rounded d-flex flex-column align-items-center text-center">
                            <div class="lesson-img-container mb-3">
                                <img src="<?= $lesson['THUMBNAIL_IMAGE'] ?>" class="img-fluid rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                            </div>
                            <h5 class="lesson-title"> <?= $lesson['TITLE'] ?> </h5>
                            <p class="lesson-desc text-muted"> <?= substr($lesson['OBJECTIVE'], 0, 80) . '...' ?> </p>
                            <a href="<?= base_url('knowledge-center/view-my-course-lesson/' . $lesson['LESSON_ID']) ?>" class="btn btn-sm btn-outline-primary mt-2">View Lesson</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .lesson-card {
        background: #fff;
        border-radius: 10px;
        transition: transform 0.3s ease-in-out;
    }
    .lesson-card:hover {
        transform: translateY(-5px);
    }
    .lesson-title {
        font-weight: bold;
        color: #333;
    }
    .lesson-desc {
        font-size: 14px;
    }
</style>

</body>
<?php $this->load->view('includes/footer'); ?>
</html>
