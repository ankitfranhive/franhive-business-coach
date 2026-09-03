<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>
<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">

    <div class="page-header">
      <div class="row">
        <div class="col-md-6 col-sm-12">
          <div class="title"><h4>Add Form Type</h4></div>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
          <a class="btn btn-warning" href="<?= base_url('admin_eforms/form_types'); ?>">Back</a>
        </div>
      </div>
    </div>

    <div class="pd-20 card-box mb-30">
      <form method="post" action="<?= base_url('admin_eforms/form_type_store'); ?>">

        <div class="form-group row">
          <label class="col-form-label col-md-2">Slug<span style="color:red;">*</span></label>
          <div class="col-md-10">
            <input class="form-control" name="slug" required placeholder="e.g. participation-consent-release">
          </div>
        </div>

        <div class="form-group row">
          <label class="col-form-label col-md-2">Name<span style="color:red;">*</span></label>
          <div class="col-md-10">
            <input class="form-control" name="name" required placeholder="e.g. Participation Consent and Release">
          </div>
        </div>

        <div class="form-group row">
          <label class="col-form-label col-md-2">Description</label>
          <div class="col-md-10">
            <input class="form-control" name="description">
          </div>
        </div>

        <hr>
        <h4 class="text-blue h4">Fields</h4>
        <p class="text-muted">For the <strong>Image</strong> type, paste the image URL into the Options column instead of an uploader.</p>

        <div class="table-responsive">
        <table class="table table-bordered" id="fields_tbl">
  <thead>
    <tr>
    <th style="width:14%;">Field Name*</th>
    <th style="width:16%;">Label*</th>
    <th style="width:12%;">Type</th>
    <th style="width:10%;">Required</th>
    <th style="width:10%;">Sort Order</th>
    <th style="width:28%;">Options / Image URL<br><small>(comma separated, or paste image URL for Image type)</small></th>
    <th style="width:10%;">Action</th>

    </tr>
  </thead>
  <tbody></tbody>
</table>

<button type="button" class="btn btn-info" onclick="addRow()">+ Add Field</button>

<script>
function toggleOptionsPlaceholder(sel) {
    const row = sel.closest('tr');
    const optInput = row.querySelector('.field-options-input');
    if (!optInput) return;
    if (sel.value === 'image') {
        optInput.placeholder = 'https://example.com/image.jpg';
    } else if (sel.value === 'video') {
        optInput.placeholder = 'https://www.youtube.com/watch?v=... or Vimeo URL';
    } else {
        optInput.placeholder = 'Yes,No OR Option1,Option2';
    }
}

function addRow(){
  const tbody = document.querySelector('#fields_tbl tbody');
  const tr = document.createElement('tr');
  tr.innerHTML = `
    <td><input class="form-control" name="field_name[]" required placeholder="bank_account_no"></td>
    <td><input class="form-control" name="field_label[]" required placeholder="Bank Account No"></td>
    <td>
      <select class="form-control field-type-select" name="field_type[]" onchange="toggleOptionsPlaceholder(this)">
        <option value="text">Text</option>
        <option value="email">Email</option>
        <option value="number">Number</option>
        <option value="date">Date</option>
        <option value="textarea">Textarea</option>
        <option value="select">Select</option>
        <option value="radio">Radio</option>
        <option value="checkbox">Checkbox</option>
        <option value="image">Image (URL)</option>
        <option value="video">Video (URL)</option>
      </select>
    </td>
    <td>
      <select class="form-control" name="field_required[]">
        <option value="0">No</option>
        <option value="1">Yes</option>
      </select>
    </td>
    <td><input class="form-control" type="number" name="field_order[]" value="0" placeholder="-1 top, 1 bottom"></td>

    <td><input class="form-control field-options-input" name="field_options[]" placeholder="Yes,No OR Option1,Option2"></td>
    <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">Remove</button></td>
  `;
  tbody.appendChild(tr);
}
document.addEventListener('DOMContentLoaded', addRow); // start with 1 row
</script>

        </div>

        <div class="form-group row">
          <div class="col-md-6 offset-md-2">
            <button type="submit" class="btn btn-warning">Save Form Type</button>
          </div>
        </div>

      </form>
    </div>

  </div>
</div>

</body>
<?php $this->load->view('includes/footer'); ?>
</html>