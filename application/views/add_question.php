<!DOCTYPE html>
<html lang="en">


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

<body>
    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Add a New Question</h4>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right">
                        <a class="btn btn-warning" href="<?= base_url('/questions'); ?>" role="button">
                            Back to Questions List
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            <div class="pd-20 card-box mb-30">
                <form action="<?= base_url('admin/addQuestion') ?>" method="post">

                    <!-- Question Type Toggle -->
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Question Type</label>
                        <div class="col-sm-12 col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="question_type" id="subjective" value="2" required checked onclick="toggleOptions()">
                                <label class="form-check-label" for="subjective">Subjective</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="question_type" id="mcq" value="1" required onclick="toggleOptions()">
                                <label class="form-check-label" for="mcq">MCQ Type</label>
                            </div>
                        </div>
                    </div>

                    <!-- Question Title -->
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Question Title</label>
                        <div class="col-sm-12 col-md-10">
                        <textarea class="form-control" name="question_name" id="ckeditor" placeholder="Enter question title" required> </textarea>	   
                        <!-- <input class="form-control" type="text" name="question_name" placeholder="Enter question title" required /> -->
                        </div>
                    </div>

                    <!-- Question Group -->
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Question Group</label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" type="text" name="question_group" placeholder="Enter question group" required />
                        </div>
                    </div>

                    <!-- Marks -->
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Marks</label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" type="number" name="question_marks" placeholder="Enter marks" required min="0" />
                        </div>
                    </div>

                    <!-- Question Type -->
                   

                    <!-- MCQ Options (Initially Hidden) -->
                    <div id="mcq-options" style="display:none;">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h5 class="m-0">Options</h5>
    <button type="button" class="btn btn-sm btn-secondary" onclick="addOption()">+ Add Option</button>
  </div>

  <div id="options-wrapper"></div>
  <small class="text-muted">Min 2 options, Max 10 options.</small>
</div>

<script>
let optionCount = 0;
const maxOptions = 10;

function addOption(text = "", marks = "") {
  if (optionCount >= maxOptions) return alert("Maximum 10 options allowed.");

  optionCount++;
  const pos = optionCount; // 1-based position, used for checkbox value

  const html = `
    <div class="form-group row option-row" id="opt-${pos}">
      <label class="col-sm-12 col-md-2 col-form-label">Option ${pos}</label>

      <div class="col-sm-6 col-md-6">
        <input class="form-control" type="text" name="option_text[]" placeholder="Enter option ${pos}" value="${text}" required>
      </div>

      <div class="col-sm-2 col-md-2">
        <input class="form-control" type="number" name="option_marks[]" placeholder="Marks" value="${marks}" step="0.01" min="0">
      </div>

      <div class="col-sm-2 col-md-2 d-flex align-items-center">
        <label class="m-0">
          <input type="checkbox" name="is_correct[]" value="${pos}">
          Correct
        </label>
        <button type="button" class="btn btn-sm btn-danger ml-2" onclick="removeOption(${pos})">Remove</button>
      </div>
    </div>
  `;
  document.getElementById("options-wrapper").insertAdjacentHTML("beforeend", html);
}

function removeOption(pos) {
  const el = document.getElementById("opt-" + pos);
  if (el) el.remove();
}

function toggleOptions() {
  const mcqOptions = document.getElementById("mcq-options");
  const mcqRadio = document.getElementById("mcq");

  if (mcqRadio.checked) {
    mcqOptions.style.display = "block";
    if (document.querySelectorAll("#options-wrapper .option-row").length === 0) {
      addOption(); addOption(); addOption(); addOption(); // default 4
    }
  } else {
    mcqOptions.style.display = "none";
  }
}
</script>

   <!-- Submit Button -->
   <div class="form-group row">
                        <div class="col-sm-12 col-md-10 offset-md-2">
                            <button type="submit" class="btn btn-warning">Add Question</button>
                        </div>
                    </div>
                </form>
   </div>
</div>
</body>
</html>