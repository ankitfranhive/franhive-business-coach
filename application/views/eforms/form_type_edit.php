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
                        <h4>Edit Form Type</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-warning" href="<?= base_url('admin_eforms/form_types'); ?>" role="button">
                        Form Types List
                    </a>
                </div>
            </div>
        </div>

        <div class="pd-20 card-box mb-30">
            <form method="post" action="<?= base_url('admin_eforms/form_type_update/' . (int)$form_type['id']); ?>">

                <div class="form-group row">
                    <label class="col-form-label col-md-2">Slug <span style="color:red;">*</span></label>
                    <div class="col-md-10">
                        <input class="form-control" name="slug" required value="<?= html_escape($form_type['slug']); ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2">Name <span style="color:red;">*</span></label>
                    <div class="col-md-10">
                        <input class="form-control" name="name" required value="<?= html_escape($form_type['name']); ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2">Description</label>
                    <div class="col-md-10">
                        <input class="form-control" name="description" value="<?= html_escape($form_type['description']); ?>">
                    </div>
                </div>

                <hr>
                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="text-blue h4">Fields</h4>
                        <p class="text-muted">
                            Use snake_case for Field Name (no spaces).<br>
                            For <strong>Image / Video</strong> types paste the URL in Options.<br>
                            For <strong>Section Header</strong> the label becomes the section title — no Options needed.
                        </p>
                    </div>
                    <div class="pull-right">
                        <button type="button" class="btn btn-info" onclick="addRow()">+ Add Field</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="fields_tbl">
                        <thead>
                            <tr>
                                <th style="width:14%;">Field Name*</th>
                                <th style="width:22%;">Label / Question Text*</th>
                                <th style="width:11%;">Type</th>
                                <th style="width:8%;">Required</th>
                                <th style="width:8%;">Position</th>
                                <th style="width:22%;">Options / URL<br><small>(comma-sep, or URL for Image/Video)</small></th>
                                <th style="width:8%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($fields)): ?>
                                <?php foreach($fields as $f):

                                    $opts = '';
                                    if (!empty($f['options_json'])) {
                                        $decoded = json_decode($f['options_json'], true);
                                        if (is_array($decoded)) $opts = implode(', ', $decoded);
                                    }
                                    $isImage   = ($f['type'] === 'image');
                                    $isVideo   = ($f['type'] === 'video');
                                    $isSection = ($f['type'] === 'section');

                                    if ($isImage)        $optPlaceholder = 'https://example.com/image.jpg';
                                    elseif ($isVideo)    $optPlaceholder = 'https://www.youtube.com/watch?v=...';
                                    elseif ($isSection)  $optPlaceholder = '(not needed)';
                                    else                 $optPlaceholder = 'Option1,Option2';
                                ?>
                                <tr>
                                    <td>
                                        <input class="form-control" name="field_name[]" required
                                               value="<?= html_escape($f['name']); ?>">
                                    </td>
                                    <td>
                                        <!-- textarea so long question text is comfortable to read/edit -->
                                        <textarea class="form-control" name="field_label[]" required
                                                  rows="2" style="resize:vertical;min-height:38px;"><?= html_escape($f['label']); ?></textarea>
                                    </td>
                                    <td>
                                        <select class="form-control field-type-select" name="field_type[]"
                                                onchange="toggleOptionsPlaceholder(this)">
                                            <option value="text"     <?= $f['type']=='text'    ?'selected':'' ?>>Text</option>
                                            <option value="email"    <?= $f['type']=='email'   ?'selected':'' ?>>Email</option>
                                            <option value="number"   <?= $f['type']=='number'  ?'selected':'' ?>>Number</option>
                                            <option value="date"     <?= $f['type']=='date'    ?'selected':'' ?>>Date</option>
                                            <option value="textarea" <?= $f['type']=='textarea'?'selected':'' ?>>Textarea</option>
                                            <option value="select"   <?= $f['type']=='select'  ?'selected':'' ?>>Select</option>
                                            <option value="radio"    <?= $f['type']=='radio'   ?'selected':'' ?>>Radio</option>
                                            <option value="checkbox" <?= $f['type']=='checkbox'?'selected':'' ?>>Checkbox</option>
                                            <option value="image"    <?= $isImage              ?'selected':'' ?>>Image (URL)</option>
                                            <option value="video"    <?= $isVideo              ?'selected':'' ?>>Video (URL)</option>
                                            <option value="section"  <?= $isSection            ?'selected':'' ?>>Section Header</option>
                                            <option value="signature"<?= $f['type']=='signature'?'selected':'' ?>>Signature</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control" name="field_required[]">
                                            <option value="0" <?= ((int)$f['is_required']===0)?'selected':'' ?>>No</option>
                                            <option value="1" <?= ((int)$f['is_required']===1)?'selected':'' ?>>Yes</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input class="form-control" type="number" name="field_order[]"
                                               value="<?= (int)$f['sort_order']; ?>">
                                        <small class="text-muted">-ve = above body</small>
                                    </td>
                                    <td>
                                        <input class="form-control field-options-input" name="field_options[]"
                                               value="<?= html_escape($opts); ?>"
                                               placeholder="<?= html_escape($optPlaceholder) ?>"
                                               <?= $isSection ? 'readonly style="background:#f5f5f5;"' : '' ?>>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm"
                                                onclick="this.closest('tr').remove()">Remove</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-2">
                        <button type="submit" class="btn btn-warning"
                                onclick="return confirm('Are you sure you want to update this Form Type?');">
                            Update Form Type
                        </button>
                    </div>
                </div>

            </form>
        </div>

    </div>
</div>

<script>
function toggleOptionsPlaceholder(sel) {
    const row      = sel.closest('tr');
    const optInput = row.querySelector('.field-options-input');
    if (!optInput) return;

    if (sel.value === 'image') {
        optInput.placeholder = 'https://example.com/image.jpg';
        optInput.readOnly    = false;
        optInput.style.background = '';
    } else if (sel.value === 'video') {
        optInput.placeholder = 'https://www.youtube.com/watch?v=... or Vimeo URL';
        optInput.readOnly    = false;
        optInput.style.background = '';
    } else if (sel.value === 'section') {
        optInput.placeholder = '(not needed for Section Header)';
        optInput.value       = '';
        optInput.readOnly    = true;
        optInput.style.background = '#f5f5f5';
    } else {
        optInput.placeholder = 'Option1,Option2';
        optInput.readOnly    = false;
        optInput.style.background = '';
    }
}

function addRow() {
    const tbody = document.querySelector('#fields_tbl tbody');
    const tr    = document.createElement('tr');
    tr.innerHTML = `
        <td>
            <input class="form-control" name="field_name[]" required placeholder="field_key">
        </td>
        <td>
            <textarea class="form-control" name="field_label[]" required
                      rows="2" style="resize:vertical;min-height:38px;"
                      placeholder="Question text or label"></textarea>
        </td>
        <td>
            <select class="form-control field-type-select" name="field_type[]"
                    onchange="toggleOptionsPlaceholder(this)">
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
                <option value="section">Section Header</option>
                <option value="signature">Signature</option>
            </select>
        </td>
        <td>
            <select class="form-control" name="field_required[]">
                <option value="0" selected>No</option>
                <option value="1">Yes</option>
            </select>
        </td>
        <td>
            <input class="form-control" type="number" name="field_order[]" value="0">
            <small class="text-muted">-ve = above body</small>
        </td>
        <td>
            <input class="form-control field-options-input" name="field_options[]"
                   placeholder="Option1,Option2">
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm"
                    onclick="this.closest('tr').remove()">Remove</button>
        </td>
    `;
    tbody.appendChild(tr);
}
</script>

</body>
<?php $this->load->view('includes/footer'); ?>
</html>