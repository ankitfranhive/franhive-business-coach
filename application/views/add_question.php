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
const minOptions = 2;
const maxOptions = 10;

function getOptionRows() {
  return document.querySelectorAll('#options-wrapper .option-row');
}

function reindexOptions() {
  const rows = getOptionRows();
  rows.forEach(function (row, idx) {
    const pos = idx + 1;
    row.id = 'opt-' + pos;
    const label = row.querySelector('label.col-form-label');
    if (label) label.textContent = 'Option ' + pos;
    const textInput = row.querySelector('input[name="option_text[]"]');
    if (textInput) textInput.placeholder = 'Enter option ' + pos;
    const cb = row.querySelector('input[name="is_correct[]"]');
    if (cb) cb.value = String(pos);
    const rmBtn = row.querySelector('button.btn-danger');
    if (rmBtn) rmBtn.setAttribute('onclick', 'removeOption(' + pos + ')');
  });
  optionCount = rows.length;
}

function addOption(text = '', marks = '') {
  if (getOptionRows().length >= maxOptions) {
    alert('Maximum 10 options allowed.');
    return;
  }
  const pos = getOptionRows().length + 1;
  const safeText = String(text).replace(/"/g, '&quot;');
  const html = `
    <div class="form-group row option-row" id="opt-${pos}">
      <label class="col-sm-12 col-md-2 col-form-label">Option ${pos}</label>
      <div class="col-sm-6 col-md-6">
        <input class="form-control option-text-input" type="text" name="option_text[]" placeholder="Enter option ${pos}" value="${safeText}">
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
  document.getElementById('options-wrapper').insertAdjacentHTML('beforeend', html);
  reindexOptions();
}

function removeOption(pos) {
  if (getOptionRows().length <= minOptions) {
    alert('At least ' + minOptions + ' options are required.');
    return;
  }
  const el = document.getElementById('opt-' + pos);
  if (el) el.remove();
  reindexOptions();
}

function toggleOptions() {
  const mcqOptions = document.getElementById('mcq-options');
  const mcqRadio = document.getElementById('mcq');
  if (mcqRadio.checked) {
    mcqOptions.style.display = 'block';
    if (getOptionRows().length === 0) {
      addOption();
      addOption();
    }
  } else {
    mcqOptions.style.display = 'none';
  }
}

(function () {
  const form = document.querySelector('form[action*="addQuestion"]');
  if (!form) return;
  form.addEventListener('submit', function (e) {
    const mcqRadio = document.getElementById('mcq');
    if (!mcqRadio || !mcqRadio.checked) return;
    const filled = Array.from(document.querySelectorAll('#options-wrapper .option-text-input'))
      .map(function (el) { return el.value.trim(); })
      .filter(function (v) { return v !== ''; });
    if (filled.length < minOptions) {
      e.preventDefault();
      alert('Please provide at least ' + minOptions + ' options with text.');
      return;
    }
    const anyCorrect = form.querySelector('#options-wrapper input[name="is_correct[]"]:checked');
    if (!anyCorrect) {
      e.preventDefault();
      alert('Please select at least one correct option.');
    }
  });
})();
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