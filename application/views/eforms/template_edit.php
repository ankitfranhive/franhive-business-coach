<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">

        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title"><h4>Edit Template</h4></div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-warning" href="<?= base_url('admin_eforms/templates'); ?>" role="button">Templates List</a>
                </div>
            </div>
        </div>

        <div class="pd-20 card-box mb-30">
            <form method="post" action="<?= base_url('admin_eforms/template_update/' . $template['id']); ?>">

                <!-- BANNER IMAGE URL -->
                <?php $banner_url = $overrides['banner_image_url'] ?? ''; ?>
                <div class="form-group row">
                    <label class="col-form-label col-md-2">
                        Banner Image URL
                        <small class="d-block text-muted" style="font-weight:400;font-size:11px;">Shown below the EYD header bar</small>
                    </label>
                    <div class="col-md-10">
                        <input class="form-control" name="banner_image_url"
                               value="<?= html_escape($banner_url); ?>"
                               placeholder="https://example.com/banner.jpg">
                        <?php if (!empty($banner_url)): ?>
                        <div id="banner-preview" style="margin-top:10px;">
                            <img src="<?= html_escape($banner_url); ?>" alt="Banner Preview"
                                 style="max-width:100%;max-height:160px;border-radius:8px;border:1px solid #ddd;object-fit:cover;">
                        </div>
                        <?php else: ?>
                        <div id="banner-preview" style="margin-top:10px;display:none;">
                            <img src="" alt="Banner Preview"
                                 style="max-width:100%;max-height:160px;border-radius:8px;border:1px solid #ddd;object-fit:cover;">
                        </div>
                        <?php endif; ?>
                        <small class="text-muted">Paste a direct image URL. Leave blank for no banner.</small>
                    </div>
                </div>

                <hr>

                <div class="form-group row">
                    <label class="col-form-label col-md-2">Slug <span style="color:red;">*</span></label>
                    <div class="col-md-10">
                        <input class="form-control" name="slug" required value="<?= html_escape($template['slug']); ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2">Title <span style="color:red;">*</span></label>
                    <div class="col-md-10">
                        <input class="form-control" name="title" required value="<?= html_escape($template['title']); ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2">Heading</label>
                    <div class="col-md-10">
                        <input class="form-control" name="heading" value="<?= html_escape($template['heading']); ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2">Subheading</label>
                    <div class="col-md-10">
                        <input class="form-control" name="subheading" value="<?= html_escape($template['subheading']); ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2">Body HTML</label>
                    <div class="col-md-10">
                        <textarea class="form-control ckeditor" name="body_html" rows="8"><?= html_escape($template['body_html']); ?></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2">Agree Checkbox Text</label>
                    <div class="col-md-10">
                        <?php $agree = $overrides['texts']['agree_checkbox_text'] ?? 'I have read and agree.'; ?>
                        <input class="form-control" name="agree_checkbox_text" value="<?= html_escape($agree); ?>">
                    </div>
                </div>

                <hr>

                <!-- ══════════════════════════════════════════
                     SECTIONS
                     Stored in overrides_json['sections'] as
                     [ {id:'sec_0', name:'...'}, ... ]
                ═══════════════════════════════════════════ -->
                <div class="clearfix mb-2">
                    <div class="pull-left">
                        <h4 class="text-blue h4">Sections</h4>
                        <p class="text-muted" style="margin:0;">
                            Create named sections here, then assign each field to a section below.<br>
                            Fields with no section assigned will appear without a section header.
                        </p>
                    </div>
                    <div class="pull-right">
                        <button type="button" class="btn btn-info btn-sm" onclick="addSection()">+ Add Section</button>
                    </div>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-sm" id="sections_tbl">
                        <thead>
                            <tr>
                                <th style="width:10%;">ID <small class="text-muted">(auto)</small></th>
                                <th>Section Name <span style="color:red;">*</span></th>
                                <th style="width:12%;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="sections_body">
                            <?php
                            $existing_sections = $overrides['sections'] ?? [];
                            foreach ($existing_sections as $sec):
                            ?>
                            <tr>
                                <td>
                                    <input type="hidden" name="section_id[]" value="<?= html_escape($sec['id']); ?>">
                                    <code style="font-size:12px;"><?= html_escape($sec['id']); ?></code>
                                </td>
                                <td>
                                    <input class="form-control form-control-sm section-name-input"
                                           name="section_name[]"
                                           value="<?= html_escape($sec['name']); ?>"
                                           placeholder="e.g. Personal Details"
                                           required
                                           data-id="<?= html_escape($sec['id']); ?>"
                                           oninput="syncSectionOption(this)">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm"
                                            onclick="removeSection(this, '<?= html_escape($sec['id']); ?>')">Remove</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p class="text-muted" id="no_sections_msg" style="<?= !empty($existing_sections) ? 'display:none;' : '' ?>font-size:13px;">
                        No sections yet. Click <strong>+ Add Section</strong> to create one.
                    </p>
                </div>

                <hr>

                <!-- ══════════════════════════════════════════
                     FIELD LABEL OVERRIDES + SECTION ASSIGNMENT
                ═══════════════════════════════════════════ -->
                <div class="clearfix mb-2">
                    <div class="pull-left">
                        <h4 class="text-blue h4">Field Label Overrides &amp; Section Assignment</h4>
                        <p class="text-muted" style="margin:0;">Override the field label shown to the user, and assign each field to a section.</p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width:18%;">Field Name</th>
                                <th style="width:22%;">Default Label</th>
                                <th style="width:30%;">Override Label</th>
                                <th style="width:30%;">Section</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $field_sections = $overrides['field_sections'] ?? [];
                            foreach ($fields as $f):
                                $fname          = $f['name'];
                                $ftype          = $f['type'] ?? 'text';
                                $default_label  = $f['label'];
                                $override_label = $overrides['labels'][$fname] ?? $default_label;
                                $assigned_sec   = $field_sections[$fname] ?? '';

                                // Skip display-only fields from section assignment
                                $is_display = in_array($ftype, ['image','video','section']);
                            ?>
                            <tr>
                                <td>
                                    <input class="form-control form-control-sm" name="field_name[]"
                                           value="<?= html_escape($fname); ?>" readonly>
                                    <?php if ($is_display): ?>
                                        <small class="text-muted">(<?= html_escape($ftype) ?>)</small>
                                    <?php endif; ?>
                                </td>
                                <td style="vertical-align:middle;"><?= html_escape($default_label); ?></td>
                                <td>
                                    <input class="form-control form-control-sm" name="field_label_override[]"
                                           value="<?= html_escape($override_label); ?>"
                                           <?= $is_display ? 'readonly style="background:#f5f5f5;"' : '' ?>>
                                </td>
                                <td>
                                    <?php if (!$is_display): ?>
                                    <select class="form-control form-control-sm field-section-select"
                                            name="field_section[]"
                                            data-field="<?= html_escape($fname); ?>">
                                        <option value="">— No Section —</option>
                                        <?php foreach ($existing_sections as $sec): ?>
                                        <option value="<?= html_escape($sec['id']); ?>"
                                                <?= $assigned_sec === $sec['id'] ? 'selected' : '' ?>>
                                            <?= html_escape($sec['name']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php else: ?>
                                        <span class="text-muted" style="font-size:12px;">N/A (display field)</span>
                                        <input type="hidden" name="field_section[]" value="">
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-6 offset-md-2">
                        <button type="submit" class="btn btn-warning"
                                onclick="return confirm('Are you sure you want to update this template?');">
                            Update Template
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.22.1/standard-all/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Live banner preview
    const bannerInput = document.querySelector('input[name="banner_image_url"]');
    const previewDiv  = document.getElementById('banner-preview');
    const previewImg  = previewDiv ? previewDiv.querySelector('img') : null;
    if (bannerInput && previewDiv && previewImg) {
        bannerInput.addEventListener('input', function () {
            const val = this.value.trim();
            if (val) { previewImg.src = val; previewDiv.style.display = 'block'; }
            else     { previewDiv.style.display = 'none'; previewImg.src = ''; }
        });
    }

    // CKEditor
    CKEDITOR.replaceAll('ckeditor', {
        height: 320,
        extraPlugins: 'justify,colorbutton,font,showblocks,codesnippet',
        removePlugins: 'elementspath',
        toolbar: [
            { name: 'document',    items: ['Source', '-', 'Preview', '-', 'Maximize'] },
            { name: 'clipboard',   items: ['Undo', 'Redo'] },
            { name: 'styles',      items: ['Format', 'Font', 'FontSize'] },
            { name: 'basicstyles', items: ['Bold','Italic','Underline','Strike','RemoveFormat'] },
            { name: 'paragraph',   items: ['NumberedList','BulletedList','-','Outdent','Indent','-',
                                           'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'] },
            { name: 'links',       items: ['Link','Unlink'] },
            { name: 'insert',      items: ['Table','HorizontalRule'] },
            { name: 'colors',      items: ['TextColor','BGColor'] }
        ]
    });
});

// ── Section counter (start after existing sections) ──
let sectionCounter = <?= count($existing_sections) ?>;

function addSection() {
    const id   = 'sec_' + sectionCounter++;
    const body = document.getElementById('sections_body');
    const tr   = document.createElement('tr');
    tr.innerHTML = `
        <td>
            <input type="hidden" name="section_id[]" value="${id}">
            <code style="font-size:12px;">${id}</code>
        </td>
        <td>
            <input class="form-control form-control-sm section-name-input"
                   name="section_name[]"
                   placeholder="e.g. Business Details"
                   required
                   data-id="${id}"
                   oninput="syncSectionOption(this)">
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm"
                    onclick="removeSection(this, '${id}')">Remove</button>
        </td>
    `;
    body.appendChild(tr);

    // Add option to every field section select
    document.querySelectorAll('.field-section-select').forEach(sel => {
        const opt = document.createElement('option');
        opt.value       = id;
        opt.textContent = '(new section)';
        opt.dataset.id  = id;
        sel.appendChild(opt);
    });

    document.getElementById('no_sections_msg').style.display = 'none';
}

function removeSection(btn, secId) {
    if (!confirm('Remove this section? Fields assigned to it will become unassigned.')) return;

    // Remove the row
    btn.closest('tr').remove();

    // Remove this option from all field-section selects and reset if selected
    document.querySelectorAll('.field-section-select').forEach(sel => {
        if (sel.value === secId) sel.value = '';
        const opt = sel.querySelector(`option[value="${secId}"]`);
        if (opt) opt.remove();
    });

    const body = document.getElementById('sections_body');
    if (body.children.length === 0) {
        document.getElementById('no_sections_msg').style.display = 'block';
    }
}

function syncSectionOption(input) {
    const id   = input.dataset.id;
    const name = input.value || '(unnamed)';
    document.querySelectorAll(`.field-section-select option[value="${id}"]`).forEach(opt => {
        opt.textContent = name;
    });
}
</script>

</body>
<?php $this->load->view('includes/footer'); ?>
</html>