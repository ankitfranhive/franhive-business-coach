<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<script src="<?php echo base_url('vendor/tinymce/tinymce/tinymce.min.js'); ?>"></script>
<script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function () {
    // Initialize TinyMCE only for the welcome note textarea
    tinymce.init({
      selector: 'textarea#WELCOME_NOTE_TEXT',
      plugins: 'code anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage advtemplate ai mentions tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss markdown',
      toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
      tinycomments_mode: 'embedded',
      tinycomments_author: 'Author name',
      branding: false,
      setup: function (editor) {
        editor.on('change', function () {
          tinymce.triggerSave(); // Sync textarea with TinyMCE
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
            <h4>Edit Welcome Note Text</h4>
          </div>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
          <a class="btn btn-warning" href="<?= base_url('/welcome-note-text-list'); ?>" role="button">
            Back To List
          </a>
        </div>
      </div>
    </div>

    <div class="pd-20 card-box mb-30">
      <form action="<?= base_url('edit-welcome-note-text/' . $welcome_note_data['ID']) ?>" method="post">
        <input type="hidden" name="ID" value="<?= $welcome_note_data['ID'] ?>">

        <div class="form-group">
          <label for="WELCOME_NOTE_TEXT" class="col-form-label">Welcome Note Text <span style="color: red;">*</span></label>
          <textarea id="WELCOME_NOTE_TEXT" class="form-control tinymce" name="WELCOME_NOTE_TEXT" rows="8" placeholder="Edit Welcome Note Text"><?= htmlspecialchars($welcome_note_data['WELCOME_NOTE_TEXT']) ?></textarea>
        </div>

        <div class="form-group text-right">
          <button type="submit" class="btn btn-warning">Update Welcome Note Text</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php $this->load->view('includes/footer'); ?>
</html>
