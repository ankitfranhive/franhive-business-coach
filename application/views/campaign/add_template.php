<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('campaign/partials/campaign_styles'); ?>
<div class="mobile-menu-overlay"></div>
<div class="main-container">
   <div class="pd-ltr-20 xs-pd-20-10">
      <div class="page-header">
         <div class="row">
            <div class="col-md-6">
               <h4>Create email template</h4>
               <p class="text-muted mb-0">Write the email once. Insert merge tags, then fill them when the campaign sends.</p>
            </div>
            <div class="col-md-6 text-right">
               <a class="btn btn-warning" href="<?= base_url('/templates'); ?>">Back to templates</a>
            </div>
         </div>
      </div>
      <div class="pd-20 card-box mb-30">
         <form action="<?= base_url('CampaignController/createTemplate') ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
               <label>This template is for</label>
               <select class="custom-select" name="MODULE_NAME" required>
                  <option value="Lead">Leads</option>
                  <option value="Client">Clients</option>
                  <option value="Campaign">Any campaign audience</option>
               </select>
               <small class="text-muted">Payment Agreement templates are managed in that module, not here.</small>
            </div>
            <div class="form-group">
               <label>Template name</label>
               <input class="form-control" type="text" name="TEMPLATE_NAME" placeholder="Welcome email" required>
            </div>
            <div class="form-group">
               <label>Subject</label>
               <input class="form-control" name="TEMPLATE_SUBJECT" type="text" placeholder="Hi {{first_name}}, a note from EYD" required>
            </div>
            <div class="form-group">
               <label>Merge tags — click to insert into the email</label>
               <div>
                  <?php foreach (campaign_merge_tags() as $tag => $label): ?>
                     <span class="cm-chip" data-tag="<?= htmlspecialchars($tag) ?>"><?= htmlspecialchars($tag) ?> · <?= htmlspecialchars($label) ?></span>
                  <?php endforeach; ?>
               </div>
            </div>
            <div class="form-group">
               <label>Email body</label>
               <textarea name="TEMPLATE_BODY" id="templateBody" class="form-control js-richtext" required></textarea>
            </div>
            <div class="form-group">
               <label>Signature</label>
               <textarea name="TEMPLATE_SIGN" id="templateSign" class="form-control js-richtext"></textarea>
            </div>
            <div class="form-group">
               <label>Attachments (optional)</label>
               <input class="form-control" id="fileInput" name="ATTACHMENTS[]" type="file" multiple>
            </div>
            <button type="submit" class="btn btn-primary">Save template</button>
         </form>
      </div>
   </div>
</div>
<?php $this->load->view('includes/footer'); ?>
<script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
<script>
(function () {
  function initEditors() {
    if (!window.CKEDITOR) return;
    document.querySelectorAll('textarea.js-richtext').forEach(function (ta) {
      if (ta.getAttribute('data-cke-initialized') === '1') return;
      CKEDITOR.replace(ta, { height: 280, allowedContent: true });
      ta.setAttribute('data-cke-initialized', '1');
    });
  }
  document.addEventListener('DOMContentLoaded', initEditors);
  document.addEventListener('submit', function () {
    if (window.CKEDITOR) {
      for (var i in CKEDITOR.instances) {
        if (CKEDITOR.instances.hasOwnProperty(i)) CKEDITOR.instances[i].updateElement();
      }
    }
  }, true);
  document.querySelectorAll('.cm-chip').forEach(function (chip) {
    chip.addEventListener('click', function () {
      var tag = chip.getAttribute('data-tag');
      var editor = window.CKEDITOR && CKEDITOR.instances.templateBody;
      if (editor) editor.insertText(tag);
    });
  });
})();
</script>
</html>
