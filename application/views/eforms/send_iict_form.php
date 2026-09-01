<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>
<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">

    <div class="page-header">
      <div class="row">
        <div class="col-md-6 col-sm-12">
          <div class="title"><h4>Send IICT Enrolment Agreement</h4></div>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
          <a class="btn btn-warning" href="<?= base_url('admin_eforms/templates'); ?>">Back to Templates</a>
        </div>
      </div>
    </div>

    <div class="pd-20 card-box mb-30">
      <p class="text-muted">Send the IICT Enrolment and Payment Agreement to a client. They will receive an email with a unique link to fill and sign the form.</p>
      <h5 class="mb-3"><?= html_escape($form_title ?? 'IICT Enrolment and Payment Agreement'); ?></h5>

      <form method="post">
        <div class="form-group row">
          <label class="col-form-label col-md-2">Client Name <span class="text-danger">*</span></label>
          <div class="col-md-10">
            <input class="form-control" name="client_name" required value="<?= html_escape($this->input->post('client_name')); ?>">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-form-label col-md-2">Client Email <span class="text-danger">*</span></label>
          <div class="col-md-10">
            <input type="email" class="form-control" name="client_email" required value="<?= html_escape($this->input->post('client_email')); ?>">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-form-label col-md-2">Link expiry (hours)</label>
          <div class="col-md-10">
            <input type="number" class="form-control" name="expiry_hours" value="24" min="1">
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-6 offset-md-2">
            <button type="submit" class="btn btn-warning">Send link to client</button>
          </div>
        </div>
      </form>

      <?php if (!empty($success) && !empty($link)): ?>
        <hr>
        <div class="alert alert-success">Link sent to client. They can also use this link:</div>
        <p><b>Client link:</b> <a target="_blank" href="<?= $link ?>"><?= $link ?></a></p>
        <p class="text-muted small">This link expires automatically after the set hours.</p>
      <?php endif; ?>
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger mt-2"><?= html_escape($error); ?></div>
      <?php endif; ?>
    </div>

  </div>
</div>

</body>
<?php $this->load->view('includes/footer'); ?>
</html>
