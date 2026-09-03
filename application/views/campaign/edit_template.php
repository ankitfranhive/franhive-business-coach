<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>

<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="page-header">
      <div class="row">
        <div class="col-md-6 col-sm-12">
          <div class="title">
            <h4>Update Template: [<?= $template_data['TEMPLATE_NAME'] ?>]</h4>
          </div>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
          <a class="btn btn-warning" href="<?= base_url('/templates'); ?>" role="button">
            Back To campaign List
          </a>
        </div>
      </div>
    </div>

    <div class="pd-20 card-box mb-30">
      <div class="clearfix">
        <div class="pull-left"></div>
      </div>

      <form action="<?= base_url('CampaignController/updateTemplate') ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="TEMPLATE_ID" value="<?= $template_data['TEMPLATE_ID'] ?>">

        <div class="form-group row">
          <label class="col-sm-12 col-md-2 col-form-label">Create Template for</label>
          <div class="col-sm-12 col-md-10">
            <select class="custom-select col-12" name="MODULE_NAME">
              <option value="">Choose...</option>
              <option value="Lead" <?= $template_data['MODULE_NAME'] == 'Lead' ? 'selected' : '' ?>>Lead</option>
              <option value="Client" <?= $template_data['MODULE_NAME'] == 'Client' ? 'selected' : '' ?>>Client</option>
              <option value="Payment Agreement" <?= $template_data['MODULE_NAME'] == 'Payment Agreement' ? 'selected' : '' ?>>Payment Agreement</option>
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-12 col-md-2 col-form-label">
            Template Name <span style="color: red;">*</span>
          </label>
          <div class="col-sm-12 col-md-10">
            <input
              class="form-control"
              type="text"
              name="TEMPLATE_NAME"
              placeholder="Template title"
              value="<?= $template_data['TEMPLATE_NAME'] ?>"
              required
            />
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-12 col-md-2 col-form-label">
            Template Subject <span style="color: red;">*</span>
          </label>
          <div class="col-sm-12 col-md-10">
            <input
              class="form-control"
              name="TEMPLATE_SUBJECT"
              type="text"
              placeholder="Subject"
              value="<?= $template_data['TEMPLATE_SUBJECT'] ?>"
              required
            />
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-12 col-md-2 col-form-label">
            Template Content <span style="color: red;">*</span>
          </label>
          <div class="col-sm-12 col-md-10">
            <p class="text-muted small mb-2">
              Placeholders: <code>$Name$</code>, <code>$Link$</code>, <code>$BusinessName$</code>, <code>$SenderName$</code>
            </p>
            <textarea name="TEMPLATE_BODY" id="ckeditor" class="form-control js-richtext" required>
<?= $template_data['TEMPLATE_BODY'] ?>
            </textarea>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-12 col-md-2 col-form-label">
            Template Signature <span style="color: red;">*</span>
          </label>
          <div class="col-sm-12 col-md-10">
            <textarea name="TEMPLATE_SIGN" id="templateSign" class="form-control js-richtext" required>
<?= $template_data['TEMPLATE_SIGN'] ?>
            </textarea>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-12 col-md-2 col-form-label">
            Attachments <span style="color: red;">*</span>
          </label>
          <div class="col-sm-12 col-md-10">
            <div id="existingFiles">
              <?php if (!empty($template_data['ATTACHMENTS'])): ?>
                <?php foreach (explode(',', $template_data['ATTACHMENTS']) as $attachment): ?>
                  <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $attachment)): ?>
                    <img src="<?= $attachment ?>" style="width: 150px; margin: 5px;" />
                  <?php elseif (preg_match('/\.pdf$/i', $attachment)): ?>
                    <embed src="<?= $attachment ?>" type="application/pdf" style="width: 150px; height: 150px; margin: 5px;" />
                  <?php else: ?>
                    <p><?= basename($attachment) ?></p>
                  <?php endif; ?>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>

            <input
              class="form-control"
              id="fileInput"
              name="ATTACHMENTS[]"
              type="file"
              multiple
            />
            <div id="previewContainer" style="margin-top: 10px;"></div>
          </div>
        </div>

        <div class="form-group row">
          <div class="col-sm-6 col-md-4">
            <button type="submit" class="btn btn-warning">Update Template</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<?php $this->load->view('includes/footer'); ?>

<!-- If your header already loads jQuery, remove this line to avoid conflicts -->

<script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>

<script>
(function () {
  function initEditors() {
    if (!window.CKEDITOR) return;

    var areas = document.querySelectorAll('textarea.js-richtext');
    areas.forEach(function (ta) {
      // Avoid double init
      if (ta.getAttribute('data-cke-initialized') === '1') return;

      CKEDITOR.replace(ta, {
        height: 280,
        // IMPORTANT: allows full HTML (use carefully; sanitize on backend)
        allowedContent: true,
        extraAllowedContent: '*(*);*{*}[*]',
        removeButtons: '',
        toolbar: [
          { name: 'document', items: ['Source','-','Preview','Print'] },
          { name: 'clipboard', items: ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo'] },
          { name: 'editing', items: ['Find','Replace','-','SelectAll'] },
          { name: 'basicstyles', items: ['Bold','Italic','Underline','Strike','RemoveFormat'] },
          { name: 'paragraph', items: ['NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','JustifyLeft','JustifyCenter','JustifyRight'] },
          { name: 'links', items: ['Link','Unlink'] },
          { name: 'insert', items: ['Image','Table','HorizontalRule','SpecialChar'] },
          { name: 'styles', items: ['Styles','Format','Font','FontSize'] },
          { name: 'colors', items: ['TextColor','BGColor'] },
          { name: 'tools', items: ['Maximize'] }
        ]
      });

      ta.setAttribute('data-cke-initialized', '1');
    });
  }

  // Init once DOM ready
  document.addEventListener('DOMContentLoaded', initEditors);

  // If you use AJAX-loaded forms / modals, call initEditors again after content loads
  window.initEditors = initEditors;

  // Ensure editor content is pushed back into textarea on submit
  document.addEventListener('submit', function () {
    if (window.CKEDITOR) {
      for (var i in CKEDITOR.instances) {
        if (CKEDITOR.instances.hasOwnProperty(i)) {
          CKEDITOR.instances[i].updateElement();
        }
      }
    }
  }, true);
})();
</script>


<script>
  $(document).ready(function () {
    $('#fileInput').on('change', function (event) {
      var files = event.target.files;
      var $previewContainer = $('#previewContainer');

      $previewContainer.empty();

      $.each(files, function (index, file) {
        var reader = new FileReader();

        reader.onload = function (e) {
          var $preview;

          if (file.type.startsWith('image/')) {
            $preview = $('<img>')
              .attr('src', e.target.result)
              .css({ width: '150px', margin: '5px' });
          } else if (file.type === 'application/pdf') {
            $preview = $('<embed>')
              .attr('src', e.target.result)
              .attr('type', 'application/pdf')
              .css({ width: '150px', height: '150px', margin: '5px' });
          } else {
            $preview = $('<p>').text(file.name);
          }

          $previewContainer.append($preview);
        };

        reader.readAsDataURL(file);
      });
    });
  });
</script>
