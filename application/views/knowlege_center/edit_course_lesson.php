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
                        <h4>Edit Training Course Lesson</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-warning" href="<?= base_url('/training-course-lesson'); ?>" role="button">
                        Back To Course Lesson List
                    </a>
                </div>
            </div>
        </div>
        <div class="pd-20 card-box mb-30">
            <div class="clearfix">
                <div class="pull-left">
                   
                </div>
            </div>

            <form action="<?= base_url('KnowldegeCenterController/updateCourseLesson'); ?>" method="post" enctype="multipart/form-data">
                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Course Lesson Title</label>
                    <div class="col-sm-12 col-md-10">
                    <input
                            class="form-control"
                            type="hidden"
                            name="LESSON_ID"
                            value="<?= isset($lesson['LESSON_ID']) ? $lesson['LESSON_ID'] : ''; ?>"
                           
                        />
                        <input
                            class="form-control"
                            type="text"
                            name="TITLE"
                            value="<?= isset($lesson['TITLE']) ? $lesson['TITLE'] : ''; ?>"
                            placeholder="Lesson title"
                        />
                    </div>
                </div>

                <div class="form-group row">
    <label class="col-sm-12 col-md-2 col-form-label">Lesson For Course</label>
    <div class="col-sm-12 col-md-10">
        <select class="form-control" name="LESSON_FOR_COURSE">
            <option value="">Select a Course</option>
            <?php foreach ($all_course as $course): ?>
                <option value="<?= htmlspecialchars($course['COURSE_ID']) ?>" 
                    <?= ($course['COURSE_ID'] == $lesson['COURSE_ID']) ? 'selected' : '' ?>>
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
                        name="CONTENT" 
                        type="text" 
                        value="<?= isset($lesson['CONTENT']) ? htmlspecialchars($lesson['CONTENT'], ENT_QUOTES, 'UTF-8') : ''; ?>" 
                    />
                </div>

                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Lesson Objective</label>
                    <div class="col-sm-12 col-md-10">
                        <input
                            class="form-control"
                            name="OBJECTIVE"
                            type="text"
                            value="<?= isset($lesson['OBJECTIVE']) ? $lesson['OBJECTIVE'] : ''; ?>"
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
                        <?php if (isset($lesson['THUMNAIL_IMAGE']) && !empty($lesson['THUMNAIL_IMAGE'])): ?>
                            <img src="<?= base_url('uploads/' . $lesson['THUMNAIL_IMAGE']); ?>" alt="Cover Image" style="width: 100px; height: 100px; object-fit: cover;">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group row">
    <label class="col-sm-12 col-md-2 col-form-label">Lesson Attachments</label>
    <div class="col-sm-12 col-md-10">
        <!-- Allow multiple file selection -->
        <input
            class="form-control"
            name="ATTACHMENT[]"
            type="file"
            multiple
        />

        <?php if (isset($lesson['ATTACHMENT']) && !empty($lesson['ATTACHMENT'])): 
            $attachments = json_decode($lesson['ATTACHMENT'], true); // Decode stored JSON
            if (!empty($attachments)): ?>
                <ul class="mt-2">
                    <?php foreach ($attachments as $file): ?>
                        <li>
                            <a href="<?= $file; ?>" target="_blank">Download Attachment</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>


                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Lesson Video Links</label>
                    <div class="col-sm-12 col-md-10">
                        <input
                            class="form-control"
                            name="LESSON_VIDEO_LINK"
                            type="text"
                            value="<?= isset($lesson['LESSON_VIDEO_LINK']) ? $lesson['LESSON_VIDEO_LINK'] : ''; ?>"
                        />
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-6 col-md-4">
                        <button type="submit" class="btn btn-warning">Save Lesson</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
<?php $this->load->view('includes/footer'); ?>
</html>
