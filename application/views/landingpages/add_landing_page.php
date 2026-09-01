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
                        <h4>Add A Landing Page</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-warning" href="<?= base_url('/landing-pages'); ?>" role="button">
                        Back To Pages List
                    </a>
                </div>
            </div>
        </div>
        <div class="pd-20 card-box mb-30">
            <div class="clearfix">
                <div class="pull-left">
                    <!-- <h4 class="text-blue h4">Add A new Question</h4> -->
                </div>
            </div>
            <form action="<?= base_url('LandingPageController/createNewLandingPage') ?>" method="post">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Landing Page Title <span style="color: red;">*</span></label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" name="LANDING_PAGE_NAME" placeholder="Title" />
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Landing Page URL <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="LANDING_PAGE_URL" placeholder="URL" />
                            </div>
                        </div>
                    </div> -->
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4" for="BANNER_IMAGE">Banner Image<span style="color: red;">*</span></label>
                            <input class="form-control" value="" name="BANNER_IMAGE" type="file" />
                        </div>
                        <!-- <div class="form-group row">
                            <label class="col-form-label col-md-4">Banner Image <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="PHONE" placeholder="User Mobile" />
                            </div>
                        </div> -->
                    </div>
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Email <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="CC_EMAIL" placeholder="Email" />
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Email Template on Registration<span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <select name="REGISTERED_TEMPLATE" id="REGISTERED_TEMPLATE" class="form-control" onchange="">
                                    <option value="-1">Select</option>
                                    <?php foreach ($all_templates as $template) : ?>

                                        <option value="<?php echo $template['TEMPLATE_ID']; ?>"><?php echo $template['TEMPLATE_NAME']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Email Template on Payment <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <select name="PAMENT_TEMPLATE" id="PAMENT_TEMPLATE" class="form-control" onchange="">
                                    <option value="-1">Select</option>
                                    <?php foreach ($all_templates as $template) : ?>
                                        <option value="<?php echo $template['TEMPLATE_ID']; ?>"><?php echo $template['TEMPLATE_NAME']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Email Template<span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <select name="EMAIL_TEMPLATE" id="EMAIL_TEMPLATE" class="form-control" onchange="">
                                    <option value="-1">Select</option>
                                    <?php foreach ($all_templates as $template) : ?>
                                        <option value="<?php echo $template['TEMPLATE_ID']; ?>"><?php echo $template['TEMPLATE_NAME']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Landing Page Main Content <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="LANDING_PAGE_LEFT_CONTENT" placeholder="Left content" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Landing Page Side Content <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="LANDING_PAGE_RIGHT_CONTENT" placeholder="Right Content" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Landing Page VIP Experience Content (Before Link) <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="LANDING_PAGE_VIP_EXPERIENCE_CONTENT" placeholder="Landing Page VIP Experience Content" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Landing Page VIP Experience Content Steps <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="LANDING_PAGE_VIP_EXPERIENCE_CONTENT_STEPS" placeholder="Landing Page VIP Experience Content Steps" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Landing Page Thanks Content <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="LANDING_PAGE_THANKS_CONTENT" placeholder="Landing Page Thanks Content" />
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Fee <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control" type="number" name="LANDING_PAGE_FEE" placeholder="Fee" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">VIP Fee <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control" type="number" name="LANDING_PAGE_VIP_FEE" placeholder="VIP Fee" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6 offset-md-2">
                        <button type="submit" class="btn btn-warning">Add Landing Page</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- Default Basic Forms End -->
    </div>
</div>
</body>
<?php $this->load->view('includes/footer'); ?>




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


</html>