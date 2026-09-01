<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>
<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">

    <div class="page-header">
      <div class="row">
        <div class="col-md-6 col-sm-12">
          <div class="title"><h4>Edit IICT Enrolment Agreement</h4></div>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
          <a class="btn btn-secondary" href="<?= base_url('admin_eforms/templates'); ?>">Back to Templates</a>
          <a class="btn btn-warning" href="<?= base_url('admin_eforms/send_iict_form'); ?>">Send to client</a>
        </div>
      </div>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
      <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
      <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
    <?php endif; ?>




    <div class="pd-20 card-box mb-30">
      <p class="text-muted">Edit the form title, intro text, agreement checkbox text, and the list of fields. Saved values are stored in the database and override the config file.</p>

      <form method="post" action="<?= base_url('admin_eforms/iict_form_update'); ?>">
        <h5 class="mb-3">Form content</h5>
        <div class="form-group row">
          <label class="col-form-label col-md-2">Title <span class="text-danger">*</span></label>
          <div class="col-md-10">
            <input class="form-control" name="title" required value="<?= html_escape($template['title'] ?? ''); ?>">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-form-label col-md-2">Heading</label>
          <div class="col-md-10">
            <input class="form-control" name="heading" value="<?= html_escape($template['heading'] ?? ''); ?>">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-form-label col-md-2">Subheading</label>
          <div class="col-md-10">
            <input class="form-control" name="subheading" value="<?= html_escape($template['subheading'] ?? ''); ?>">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-form-label col-md-2">Body / intro (HTML)</label>
          <div class="col-md-10">
            <textarea class="form-control" name="body_html" rows="4"><?= html_escape($template['body_html'] ?? ''); ?></textarea>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-form-label col-md-2">Agreement checkbox text</label>
          <div class="col-md-10">
            <textarea class="form-control" name="agree_text" rows="2"><?= html_escape($template['agree_text'] ?? ''); ?></textarea>
          </div>
        </div>

        <hr class="my-4">
        <h5 class="mb-3">Fields</h5>
        <p class="text-muted small">Field name: use letters, numbers, underscore (e.g. full_name). Type: text, email, number, date, textarea.</p>

        <div class="table-responsive">
          <table class="table table-bordered" id="fields-table">
            <thead>
              <tr>
                <th>Field name</th>
                <th>Label</th>
                <th>Type</th>
                <th>Required</th>
                <th>Order</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($fields as $i => $f): ?>
              <tr>
                <td><input type="text" class="form-control form-control-sm" name="field_name[]" value="<?= html_escape($f['name'] ?? ''); ?>" placeholder="e.g. full_name"></td>
                <td><input type="text" class="form-control form-control-sm" name="field_label[]" value="<?= html_escape($f['label'] ?? ''); ?>"></td>
                <td>
                  <select class="form-control form-control-sm" name="field_type[]">
                    <?php foreach (['text'=>'Text','email'=>'Email','number'=>'Number','date'=>'Date','textarea'=>'Textarea'] as $v => $l): ?>
                      <option value="<?= $v ?>" <?= (($f['type'] ?? '') === $v) ? 'selected' : '' ?>><?= $l ?></option>
                    <?php endforeach; ?>
                  </select>
                </td>
                <td><input type="checkbox" name="field_required[]" value="1" <?= !empty($f['is_required']) ? 'checked' : '' ?>></td>
                <td><input type="number" class="form-control form-control-sm" name="field_order[]" value="<?= (int)($f['sort_order'] ?? $i); ?>" style="width:70px"></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <p class="small text-muted">To add more rows, use "Add field" then save. To remove a field, clear its name and save.</p>
        <button type="button" class="btn btn-outline-secondary btn-sm mb-2" id="add-field-row">Add field</button>

        <div class="form-group mt-4">
          <button type="submit" class="btn btn-warning">Save changes</button>
        </div>
      </form>
    </div>

  </div>
</div>

<script>
document.getElementById('add-field-row').addEventListener('click', function() {
  var tbody = document.querySelector('#fields-table tbody');
  var row = tbody.insertRow(-1);
  row.innerHTML = '<td><input type="text" class="form-control form-control-sm" name="field_name[]" placeholder="e.g. full_name"></td>' +
    '<td><input type="text" class="form-control form-control-sm" name="field_label[]"></td>' +
    '<td><select class="form-control form-control-sm" name="field_type[]"><option value="text">Text</option><option value="email">Email</option><option value="number">Number</option><option value="date">Date</option><option value="textarea">Textarea</option></select></td>' +
    '<td><input type="checkbox" name="field_required[]" value="1"></td>' +
    '<td><input type="number" class="form-control form-control-sm" name="field_order[]" value="0" style="width:70px"></td>';
});
</script>
</body>
<?php $this->load->view('includes/footer'); ?>
</html>
