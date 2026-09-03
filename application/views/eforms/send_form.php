<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>
<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">

    <div class="page-header">
      <div class="row">
        <div class="col-md-6 col-sm-12">
          <div class="title"><h4>Send Form</h4></div>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
          <a class="btn btn-warning" href="<?= base_url('admin_eforms/templates'); ?>">Back</a>
        </div>
      </div>
    </div>

    <div class="pd-20 card-box mb-30">
      <h5 class="mb-2"><?= html_escape($template['title']); ?></h5>

      <form method="post">
        <div class="form-group row">
          <label class="col-form-label col-md-2">Client Name<span style="color:red;">*</span></label>
          <div class="col-md-10">
            <input class="form-control" name="client_name" required>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-form-label col-md-2">Client Email<span style="color:red;">*</span></label>
          <div class="col-md-10">
            <input class="form-control" name="client_email" required>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-form-label col-md-2">Expiry Hours</label>
          <div class="col-md-10">
            <input class="form-control" name="expiry_hours" value="24">
          </div>
        </div>

        <div class="form-group row">
          <label class="col-form-label col-md-2">Confirmation Email Template</label>
          <div class="col-md-10">
            <select class="form-control" name="email_template_id">
              <option value="">-- Select template --</option>
              <?php foreach (($email_templates ?? []) as $email_template): ?>
                <option value="<?= (int) $email_template['TEMPLATE_ID']; ?>">
                  <?= html_escape($email_template['TEMPLATE_NAME']); ?>
                </option>
              <?php endforeach; ?>
            </select>
            <small class="text-muted">This template will be sent to the client after they successfully submit the form.</small>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-form-label col-md-2">Thank You Page Template</label>
          <div class="col-md-10">
            <?php $selected_ty = (int)($selected_thank_you_page_template_id ?? 0); ?>
            <select class="form-control" name="thank_you_page_template_id">
              <option value="">-- Default thank you page --</option>
              <?php foreach (($thank_you_page_templates ?? []) as $ty_tpl): ?>
                <option value="<?= (int)$ty_tpl['id']; ?>" <?= $selected_ty === (int)$ty_tpl['id'] ? 'selected' : ''; ?>>
                  <?= html_escape($ty_tpl['title']); ?>
                </option>
              <?php endforeach; ?>
            </select>
            <small class="text-muted">
              Optional. If left as default, the built-in thank you page is shown after submit.
              Manage templates under eForms → Thank You Page Templates.
            </small>
          </div>
        </div>

        <div class="form-group row">
          <div class="col-md-6 offset-md-2">
            <button type="submit" class="btn btn-warning">Send</button>
          </div>
        </div>
      </form>

      <?php if (!empty($link)): ?>
        <hr>
        <p><b>Client Link:</b> <a target="_blank" href="<?= $link ?>"><?= $link ?></a></p>
        <p class="text-muted">This link is emailed to the client and expires automatically.</p>
      <?php endif; ?>
    </div>

  </div>
</div>

</body>
<?php $this->load->view('includes/footer'); ?>
</html>
