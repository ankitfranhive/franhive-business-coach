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
                        <h4>Edit Question Details</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-warning" href="<?= base_url('/questions'); ?>" role="button">
                        Back To Questions List
                    </a>
                </div>
            </div>
        </div>

        <div class="pd-20 card-box mb-30">
            <form action="<?= base_url('admin/update_question') ?>" method="post">
                <input type="hidden" name="question_id" value="<?= isset($questionData['QUESTION_ID']) ? $questionData['QUESTION_ID'] : '' ?>">

                <!-- Question Title -->
                <div class="form-group row">
    <label class="col-sm-12 col-md-2 col-form-label">Question Title</label>
    <div class="col-sm-12 col-md-10">
        <textarea class="form-control js-richtext" name="question_name" id="question_name_editor" rows="6"><?= isset($questionData['QUESTION_NAME']) ? htmlspecialchars((string) $questionData['QUESTION_NAME'], ENT_QUOTES, 'UTF-8') : '' ?></textarea>
        <small class="text-muted">Formatting (bold, italic, lists, links) is saved as HTML and shown on the test.</small>
    </div>
</div>


                <!-- Marks -->
                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Marks</label>
                    <div class="col-sm-12 col-md-10">
                        <input class="form-control" value="<?= isset($questionData['QUESTION_MARKS']) ? $questionData['QUESTION_MARKS'] : '' ?>" name="question_marks" type="number" />
                    </div>
                </div>

                <!-- Question Type -->
                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Question Type</label>
                    <div class="col-sm-12 col-md-10">
                        
                        <select class="custom-select col-12" name="question_type" id="question_type">
                        <option value="-1" <?= empty($questionData['QUESTION_TYPE']) ? 'selected' : '' ?>>Choose...</option>
                            <!-- <option <?= empty($questionData['QUESTION_TYPE']) ? 'selected' : '' ?>>Choose...</option> -->
                            <option value="2" <?= isset($questionData['QUESTION_TYPE']) && $questionData['QUESTION_TYPE'] == 2 ? 'selected' : '' ?>>Subjective</option>
                            <option value="1" <?= isset($questionData['QUESTION_TYPE']) && $questionData['QUESTION_TYPE'] == 1 ? 'selected' : '' ?>>MCQ</option>
                        </select>
                    </div>
                </div>

                <!-- Options Section -->
                <div id="options_section" style="display:none;">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h5 class="m-0">Options</h5>
    <button type="button" class="btn btn-sm btn-secondary" onclick="addOption()">+ Add Option</button>
  </div>

  <div id="options-wrapper">
    <?php
      // Preload existing options (new or legacy fallback provided by controller)
      if (!empty($optionsData)) {
        $pos = 0;
        foreach ($optionsData as $opt) {
          $pos++;
          $text   = $opt['OPTION_TEXT'] ?? '';
          $marks  = $opt['OPTION_MARKS'] ?? 0;
          $isCorr = (int)($opt['IS_CORRECT'] ?? 0);
    ?>
      <div class="form-group row option-row" id="opt-<?= $pos ?>">
        <label class="col-sm-12 col-md-2 col-form-label">Option <?= $pos ?></label>

        <div class="col-sm-6 col-md-6">
          <input class="form-control option-text-input" type="text" name="option_text[]" value="<?= htmlspecialchars($text) ?>">
        </div>

        <div class="col-sm-2 col-md-2">
          <input class="form-control" type="number" name="option_marks[]" value="<?= htmlspecialchars($marks) ?>" step="0.01" min="0">
        </div>

        <div class="col-sm-2 col-md-2 d-flex align-items-center">
          <label class="m-0">
            <input type="checkbox" name="is_correct[]" value="<?= $pos ?>" <?= $isCorr ? 'checked' : '' ?>>
            Correct
          </label>
          <button type="button" class="btn btn-sm btn-danger ml-2" onclick="removeOption(<?= $pos ?>)">Remove</button>
        </div>
      </div>
    <?php
        }
      }
    ?>
  </div>

  <small class="text-muted">Min 2 options, Max 10 options.</small>
</div>


                <!-- Submit Button -->
                <div class="form-group row">
                    <div class="col-sm-6 col-md-4">
                        <button type="submit" class="btn btn-warning">Update Question</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript to Show/Hide Options -->






<script>
    (function() {
        var select = document.getElementById('question_type');
        var optionsSection = document.getElementById('options_section');
        function toggleOptions() {
            var qType = (select && select.value) ? String(select.value).trim() : '';
            optionsSection.style.display = (qType === '1') ? 'block' : 'none';
        }
        if (select && optionsSection) {
            select.addEventListener('change', toggleOptions);
            // Run immediately for first paint
            toggleOptions();
            // Also ensure it runs on DOM ready in case other scripts delay load
            document.addEventListener('DOMContentLoaded', toggleOptions);
        }
    })();
</script>


<script>
let optionCount = document.querySelectorAll('#options-wrapper .option-row').length;
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
        <input class="form-control option-text-input" type="text" name="option_text[]" value="${safeText}">
      </div>
      <div class="col-sm-2 col-md-2">
        <input class="form-control" type="number" name="option_marks[]" value="${marks}" step="0.01" min="0">
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

(function () {
  var select = document.getElementById('question_type');
  var optionsSection = document.getElementById('options_section');

  function toggleOptions() {
    var qType = (select && select.value) ? String(select.value).trim() : '';
    optionsSection.style.display = (qType === '1') ? 'block' : 'none';
    if (qType === '1' && getOptionRows().length === 0) {
      addOption();
      addOption();
    }
  }

  if (select && optionsSection) {
    select.addEventListener('change', toggleOptions);
    toggleOptions();
    reindexOptions();
  }

  var form = document.querySelector('form[action*="update_question"]');
  if (form) {
    form.addEventListener('submit', function (e) {
      if (window.CKEDITOR && CKEDITOR.instances.question_name_editor) {
        CKEDITOR.instances.question_name_editor.updateElement();
        var qHtml = CKEDITOR.instances.question_name_editor.getData();
        if (!qHtml || qHtml.replace(/<[^>]*>/g, '').replace(/&nbsp;/g, ' ').trim() === '') {
          e.preventDefault();
          alert('Please enter a question title.');
          return;
        }
      }
      if (String(select.value) !== '1') return;
      const filled = Array.from(document.querySelectorAll('#options-wrapper .option-text-input'))
        .map(function (el) { return el.value.trim(); })
        .filter(function (v) { return v !== ''; });
      if (filled.length < minOptions) {
        e.preventDefault();
        alert('Please provide at least ' + minOptions + ' options with text.');
        return;
      }
      if (!form.querySelector('#options-wrapper input[name="is_correct[]"]:checked')) {
        e.preventDefault();
        alert('Please select at least one correct option.');
      }
    });
  }
})();
</script>




<?php $this->load->view('includes/js_ckeditor_forms'); ?>
</body>
<?php $this->load->view('includes/footer'); ?>
</html>
