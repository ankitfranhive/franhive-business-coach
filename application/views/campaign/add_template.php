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
                  <h4>Add A new Template</h4>
               </div>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
               <a class="btn btn-warning" href="<?= base_url('/templates'); ?>" role="button">
                  Back To Campaign List
               </a>
            </div>
         </div>
      </div>
      <div class="pd-20 card-box mb-30">
         <form action="<?= base_url('CampaignController/createTemplate') ?>" method="post" enctype="multipart/form-data">
            <div class="form-group row">
               <label class="col-sm-12 col-md-2 col-form-label">Template Module <span style="color: red;">*</span></label>
               <div class="col-sm-12 col-md-10">
                  <select class="custom-select col-12" name="MODULE_NAME">
                     <option selected="">Choose...</option>
                     <option value="Lead">Lead</option>
                     <option value="Client">Client </option>
                     <option value="Payment Agreement">Payment Agreement</option>
                  </select>
               </div>
            </div>
            <div class="form-group row">
               <label class="col-sm-12 col-md-2 col-form-label">Template Name <span style="color: red;">*</span></label>
               <div class="col-sm-12 col-md-10">
                  <input class="form-control" type="text" name="TEMPLATE_NAME" placeholder="Template title" />
               </div>
            </div>
            <div class="form-group row">
               <label class="col-sm-12 col-md-2 col-form-label">Template Subject <span style="color: red;">*</span></label>
               <div class="col-sm-12 col-md-10">
                  <input class="form-control" name="TEMPLATE_SUBJECT" type="text" placeholder="Subject" />
               </div>
            </div>

            <div class="form-group row">
               <label class="col-sm-12 col-md-2 col-form-label">Template Content <span style="color: red;">*</span></label>
               <div class="col-sm-12 col-md-10">
                  <p class="text-muted small mb-2">
                     Placeholders (Payment Agreement emails): <code>$Name$</code> recipient,
                     <code>$Link$</code> form URL,
                     <code>$BusinessName$</code> form subject,
                     <code>$SenderName$</code> organisation.
                     Campaign emails also support <code>$Name$</code>.
                  </p>
                  <textarea name="TEMPLATE_BODY" id="templateBody" class="form-control js-richtext" required></textarea>
               </div>
            </div>

            <div class="form-group row">
               <label class="col-sm-12 col-md-2 col-form-label">Template Signature <span style="color: red;">*</span></label>
               <div class="col-sm-12 col-md-10">
                  <textarea name="TEMPLATE_SIGN" id="templateSign" class="form-control js-richtext" required></textarea>
               </div>
            </div>

            <div class="form-group row">
               <label class="col-sm-12 col-md-2 col-form-label">Attachments <span style="color: red;">*</span></label>
               <div class="col-sm-12 col-md-10">
                  <input class="form-control" id="fileInput" name="ATTACHMENTS[]" type="file" multiple />
                  <div id="previewContainer" style="margin-top: 10px;"></div>
               </div>
            </div>
            <div class="form-group row">
               <div class="col-sm-6 col-md-4">
                  <button type="submit" class="btn btn-warning">Add Template</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
</body>
<?php $this->load->view('includes/footer'); ?>

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
   $(document).ready(function() {
      $('#fileInput').on('change', function(event) {
         var files = event.target.files;
         var $previewContainer = $('#previewContainer');

         $previewContainer.empty(); // Clear existing previews

         $.each(files, function(index, file) {
            var reader = new FileReader();

            reader.onload = function(e) {
               var $preview;

               if (file.type.startsWith('image/')) {
                  // Create an image element for image files
                  $preview = $('<img>').attr('src', e.target.result)
                     .css({
                        'width': '150px',
                        'margin': '5px'
                     });
               } else if (file.type === 'application/pdf') {
                  // Create a PDF preview element for PDF files
                  $preview = $('<embed>').attr('src', e.target.result)
                     .attr('type', 'application/pdf')
                     .css({
                        'width': '150px',
                        'height': '150px',
                        'margin': '5px'
                     });
               } else {
                  // Handle other file types if necessary
                  $preview = $('<p>').text(file.name);
               }

               $previewContainer.append($preview);
            };

            reader.readAsDataURL(file); // Read file as data URL
         });
      });
   });
</script>

</html>