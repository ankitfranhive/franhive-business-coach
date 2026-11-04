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
                        <h4>Edit Test Details</h4>
                    </div>

                </div>
                <div class="col-md-6 col-sm-12 text-right">

                    <a class="btn btn-warning" href="<?= base_url('/test'); ?>" role="button">
                        Back To Test List
                    </a>

                </div>
            </div>
        </div>
        <div class="pd-20 card-box mb-30">
            <div class="clearfix">
                <div class="pull-left">
                    <!-- <h4 class="text-fh h4">Add A new Test</h4> -->

                </div>

            </div>
            <form action="<?= base_url('admin/update_test') ?>" method="post">
                <input type="hidden" name="test_id" value="<?= $testData['TEST_ID'] ?? '' ?>">

                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Test Type</label>
                    <div class="col-sm-12 col-md-10">
                        <select class="form-control" name="test_type">
                            <option value="1" <?= isset($testData['TEST_TYPE']) && $testData['TEST_TYPE'] == '1' ? 'selected' : '' ?>>NLP MCQ</option>
                            <option value="2" <?= isset($testData['TEST_TYPE']) && $testData['TEST_TYPE'] == '2' ? 'selected' : '' ?>>Subjective</option>
                            <option value="3" <?= isset($testData['TEST_TYPE']) && $testData['TEST_TYPE'] == '3' ? 'selected' : '' ?>>Normal MCQ</option>
                            <option value="4" <?= isset($testData['TEST_TYPE']) && $testData['TEST_TYPE'] == '4' ? 'selected' : '' ?>>BRS</option>
                            <option value="5" <?= isset($testData['TEST_TYPE']) && $testData['TEST_TYPE'] == '5' ? 'selected' : '' ?>>K-10</option>
                            <option value="6" <?= isset($testData['TEST_TYPE']) && $testData['TEST_TYPE'] == '6' ? 'selected' : '' ?>>Value Test</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Student options</label>
                    <div class="col-sm-12 col-md-10">
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="resume_available" name="resume_available" value="1"
                                <?= !empty($testData['RESUME_AVAILABLE']) ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="resume_available">
                                <strong>Resume available</strong> — students can save progress and continue later until they submit finally.
                            </label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="reattempt_available" name="reattempt_available" value="1"
                                <?= !empty($testData['REATTEMPT_AVAILABLE']) ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="reattempt_available">
                                <strong>Reattempt available</strong> — after final submit and evaluation, students may take the test again (within dates).
                            </label>
                        </div>
                        <div class="custom-control custom-checkbox mt-2">
                            <input type="checkbox" class="custom-control-input" id="use_sections" name="use_sections" value="1"
                                <?= !empty($testData['USE_SECTIONS']) ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="use_sections">
                                <strong>Use sections</strong> — group questions (Value Test: section 1 = Value 2, etc.).
                            </label>
                        </div>
                        <?php if (!empty($testData['TEST_ID'])): ?>
                        <a class="btn btn-sm btn-outline-info mt-2" href="<?= base_url('test-sections/' . (int)$testData['TEST_ID']) ?>">Manage sections &amp; assignments</a>
                        <?php endif; ?>
                    </div>
                </div>



                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Test Title</label>
                    <div class="col-sm-12 col-md-10">
                        <input class="form-control" type="text" name="test_title" placeholder="Test title" value="<?= $testData['TEST_NAME'] ?? '' ?>" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Total Questions</label>
                    <div class="col-sm-12 col-md-10">
                        <input class="form-control" value="<?= $testData['TOTAL_QUESTIONS'] ?? '' ?>" name="total_questions" type="number" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Total Marks</label>
                    <div class="col-sm-12 col-md-10">
                        <input class="form-control" value="<?= $testData['TOTAL_MARKS'] ?? '' ?>" name="total_marks" type="number" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Total Duration (minutes):</label>
                    <div class="col-sm-12 col-md-10">
                        <select class="custom-select col-12" name="duration">
                            <option selected="">Choose...</option>
                            <option value="1" <?= ($testData['DURATION'] ?? '') == 1 ? 'selected' : '' ?>>30 Min</option>
                            <option value="2" <?= ($testData['DURATION'] ?? '') == 2 ? 'selected' : '' ?>>60 Min</option>
                        </select>
                    </div>
                </div>


                <div class="form-group row">
                <label class="col-sm-12 col-md-2 col-form-label">Test Instructions</label>
                <div class="col-sm-12 col-md-10">


                <textarea class="form-control js-richtext" name="test_instructions" id="test_instructions_editor" placeholder="Test instructions (HTML supported)" required><?= $testData['TEST_INSTRUCTIONS'] ?? '' ?></textarea>
                </div>
            </div>



                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Start Date</label>
                    <div class="col-sm-12 col-md-10">
                        <input class="form-control datetimepicker" name="start_date" placeholder="Choose Date and time" type="text" value="<?= $testData['TEST_START_DATE'] ?? '' ?>" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">End Date</label>
                    <div class="col-sm-12 col-md-10">
                        <input class="form-control datetimepicker" name="end_date" placeholder="Choose Date and time" type="text" value="<?= $testData['END_DATE'] ?? '' ?>" />
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-6 col-md-4">
                        <button type="submit" class="btn btn-warning">Update Test</button>
                    </div>
                </div>
            </form>

        </div>
        <!-- Default Basic Forms End -->


    </div>
</div>


<?php $this->load->view('includes/js_ckeditor_forms'); ?>
</body>
<?php $this->load->view('includes/footer'); ?>

</html>