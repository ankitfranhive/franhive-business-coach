<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>


<script src="<?php echo base_url('vendor/tinymce/tinymce/tinymce.min.js'); ?>"></script>
<script type="text/javascript">
   $(function() {
      tinymce.init({
         selector: 'textarea',
         plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage advtemplate ai mentions tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss markdown',
         toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
         tinycomments_mode: 'embedded',
         tinycomments_author: 'Author name',
         branding: false,
         setup: function(editor) {
            editor.on('change', function() {
               tinymce.triggerSave(); // Update the textarea with TinyMCE content
            });
         }
      });
   });
</script>
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


                <textarea class="form-control" name="test_instructions" id="ckeditor" placeholder="Left content" required>
                                    <?= htmlspecialchars($testData['TEST_INSTRUCTIONS'] ) ?>
                                </textarea>

                    <!-- <textarea class="form-control" name="test_instructions" placeholder="Test Instructions" rows="3"><?= $testData['TEST_INSTRUCTIONS'] ?? '' ?></textarea> -->
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


</body>
<?php $this->load->view('includes/footer'); ?>

</html>